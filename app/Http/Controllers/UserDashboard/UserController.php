<?php

namespace App\Http\Controllers\UserDashboard;

use App\Events\NotificationEvent;
use App\Http\Controllers\Controller;
use App\Mail\PlanUpgradeRequestNotification;
use App\Models\Dashboard\Admin;
use App\Models\Dashboard\Plan;
use App\Models\Dashboard\Setting;
use App\Models\PlanUpgradeRequest;
use App\Models\User;
use App\Services\PlanLimitService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $subscriptionResult = app(SubscriptionService::class)->ensureSubscriptionValid($user);
        $user = $subscriptionResult['user']->load(['plan.features']);
        $subscriptionInfo = app(SubscriptionService::class)->getSubscriptionInfo($user);
        $subscriptionStatus = app(SubscriptionService::class)->getSubscriptionStatus($user);
        $planLimit = app(PlanLimitService::class)->canCreateProperty($user);

        $pendingRequest = PlanUpgradeRequest::where('user_id', $user->id)
            ->where('status', PlanUpgradeRequest::STATUS_PENDING)
            ->with('plan')
            ->latest()
            ->first();

        $upgradeHistory = PlanUpgradeRequest::where('user_id', $user->id)
            ->with('plan')
            ->latest()
            ->limit(20)
            ->get();

        $latestProcessedRequest = PlanUpgradeRequest::where('user_id', $user->id)
            ->whereIn('status', [PlanUpgradeRequest::STATUS_ACCEPTED, PlanUpgradeRequest::STATUS_REJECTED])
            ->with('plan')
            ->latest()
            ->first();

        $showExpiredPlanAlert = ($subscriptionResult['was_downgraded'] ?? false)
            || (($subscriptionInfo['is_basic'] ?? false) && $user->last_plan_id);

        return view('user_dashboard.profile.index', compact(
            'user', 'pendingRequest', 'subscriptionInfo', 'subscriptionResult',
            'subscriptionStatus', 'planLimit', 'upgradeHistory', 'latestProcessedRequest',
            'showExpiredPlanAlert'
        ));
    }

    /**
     * Full upgrade request history page.
     */
    public function upgradeHistory()
    {
        $user = Auth::user();
        $user->load(['plan.features']);
        $subscriptionInfo = app(SubscriptionService::class)->getSubscriptionInfo($user);

        $upgradeHistory = PlanUpgradeRequest::where('user_id', $user->id)
            ->with('plan')
            ->latest()
            ->paginate(15);

        $latestProcessedRequest = PlanUpgradeRequest::where('user_id', $user->id)
            ->whereIn('status', [PlanUpgradeRequest::STATUS_ACCEPTED, PlanUpgradeRequest::STATUS_REJECTED])
            ->with('plan')
            ->latest()
            ->first();

        return view('user_dashboard.profile.upgrade_history', compact(
            'user', 'subscriptionInfo', 'upgradeHistory', 'latestProcessedRequest'
        ));
    }

    public function upgradeForm()
    {
        $user = Auth::user();
        $subscriptionResult = app(SubscriptionService::class)->ensureSubscriptionValid($user);
        $user = $subscriptionResult['user']->load('plan');
        $subscriptionInfo = app(SubscriptionService::class)->getSubscriptionInfo($user);
        $pendingRequest = PlanUpgradeRequest::where('user_id', $user->id)
            ->where('status', PlanUpgradeRequest::STATUS_PENDING)
            ->with('plan')
            ->latest()
            ->first();
        $latestProcessedRequest = PlanUpgradeRequest::where('user_id', $user->id)
            ->whereIn('status', [PlanUpgradeRequest::STATUS_ACCEPTED, PlanUpgradeRequest::STATUS_REJECTED])
            ->with('plan')
            ->latest()
            ->first();
        $plans = Plan::where('status', 1)->with('features')->orderBy('price_monthly')->get();
        return view('user_dashboard.profile.upgrade', compact('user', 'plans', 'pendingRequest', 'subscriptionInfo', 'subscriptionResult', 'latestProcessedRequest'));
    }

    public function storeUpgradeRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id',
            'transfer_receipt' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response([
                'responseJSON' => $errors,
                'message' => __('Verify that the data is correct, fill in all fields'),
            ], 422);
        }

        $user = Auth::user();
        $pendingRequest = PlanUpgradeRequest::where('user_id', $user->id)
            ->where('status', PlanUpgradeRequest::STATUS_PENDING)
            ->exists();
        if ($pendingRequest) {
            return response([
                'responseJSON' => ['plan_id' => [__('You have a pending upgrade request. Please wait for approval or rejection.')]],
                'message' => __('Please wait'),
            ], 422);
        }
        if ($user->plan_id == $request->plan_id) {
            return response([
                'responseJSON' => ['plan_id' => [__('You are already on this plan')]],
                'message' => __('Invalid plan'),
            ], 422);
        }

        $uploadPath = public_path('uploads/transfer_receipts');
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        $file = $request->file('transfer_receipt');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move($uploadPath, $filename);
        $path = '/public/uploads/transfer_receipts/' . $filename;

        $upgradeRequest = PlanUpgradeRequest::create([
            'user_id' => $user->id,
            'plan_id' => $request->plan_id,
            'transfer_receipt' => $path,
            'status' => PlanUpgradeRequest::STATUS_PENDING,
        ]);

        $upgradeRequest->load(['user', 'plan']);
        $requestUrl = url(route('admin.plan-upgrade-requests.show', $upgradeRequest->id, false));

        $adminEmail = Setting::where('key', 'email')->value('value') ?: config('mail.from.address');
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new PlanUpgradeRequestNotification($upgradeRequest, $requestUrl));
            } catch (\Throwable $e) {
                Log::warning('Plan upgrade notification email failed: ' . $e->getMessage());
            }
        }

        if (config('services.telegram_notify.enabled') && config('services.telegram_notify.bot_token') && config('services.telegram_notify.chat_id')) {
            $this->sendTelegramUpgradeNotification($upgradeRequest, $requestUrl);
        }

        $firstAdmin = Admin::first();
        if ($firstAdmin) {
            event(new NotificationEvent([
                'id' => uniqid(),
                'user_id' => $user->id,
                'notifiable_id' => $firstAdmin->id,
                'author' => $user->name,
                'url' => $requestUrl,
                'title' => __('Plan upgrade request from') . ' ' . $user->name,
                'timestamp' => now()->toDateTimeString(),
                'time' => now()->toDateTimeString(),
            ]));
        }

        return response()->json([
            'success' => __('Request sent successfully. We will review it shortly.'),
        ]);
    }

    protected function sendTelegramUpgradeNotification(PlanUpgradeRequest $upgradeRequest, string $requestUrl): void
    {
        $token = config('services.telegram_notify.bot_token');
        $chatId = config('services.telegram_notify.chat_id');

        // Encode URL so Telegram doesn't truncate at spaces (e.g. filenames like "ChatGPT Image Mar 11, 2026.png")
        $transferReceiptUrl = $this->encodeUrlForTelegram(url($upgradeRequest->transfer_receipt));

        $plan = $upgradeRequest->plan ? $upgradeRequest->plan->load('features') : null;
        $featureLines = $plan ? $plan->features->where('status', 1)->map(fn ($f) => '— ' . $f->title)->implode("\n") : '';
        $planDetails = $plan ? (
            $plan->title . "\n"
            . '— ' . $plan->description . "\n"
            . '— ' . __('Cost') . ': ' . $plan->price_monthly . ' JOD'
            . ($plan->duration_months ? ' / ' . $plan->duration_months . ' ' . __('months') : '') . "\n"
            . '— ' . __('Properties') . ': ' . ($plan->number_of_properties == -1 ? __('Unlimited') : $plan->number_of_properties) . "\n"
            . ($featureLines !== '' ? $featureLines . "\n" : '')
        ) : '';

        $message = __('New plan upgrade request') . "\n\n"
            . __('User') . ': ' . ($upgradeRequest->user->name ?? '') . ' (' . ($upgradeRequest->user->email ?? '') . ")\n\n"
            . __('New plan details') . ":\n" . $planDetails . "\n"
            . __('Transfer receipt') . ": " . $transferReceiptUrl . "\n\n"
            . __('Request details') . ": " . $requestUrl;

        $apiUrl = 'https://api.telegram.org/bot' . $token . '/sendMessage';
        try {
            Http::timeout(10)->asForm()->post($apiUrl, [
                'chat_id' => $chatId,
                'text' => $message,
                'disable_web_page_preview' => false,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Telegram upgrade notification failed: ' . $e->getMessage());
        }
    }

    /**
     * Encode URL path segments so links with spaces/special chars work in Telegram.
     */
    protected function encodeUrlForTelegram(string $url): string
    {
        $parsed = parse_url($url);
        if (!isset($parsed['path']) || $parsed['path'] === '') {
            return $url;
        }
        $segments = explode('/', trim($parsed['path'], '/'));
        $encodedPath = implode('/', array_map('rawurlencode', $segments));
        $scheme = $parsed['scheme'] ?? 'https';
        $host = $parsed['host'] ?? '';
        $result = $scheme . '://' . $host . '/' . $encodedPath;
        if (!empty($parsed['query'])) {
            $result .= '?' . $parsed['query'];
        }
        return $result;
    }

    public function update(Request $request){
        $user = User::find(Auth::id());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'office_no' => 'nullable|string|max:50',
            'office_address' => 'nullable|string|max:255',
            'job' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'facebook' => 'nullable|url|max:500',
            'twitter' => 'nullable|url|max:500',
            'linkedin' => 'nullable|url|max:500',
            'mobile' => 'required|unique:users,mobile,'.Auth::id(),
            'email' => 'required|email|unique:users,email,'.Auth::id(),
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'agent_poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input=$request->all();
            return response(["responseJSON" => $errors,"input"=>$input, "message" => 'Verify that the data is correct, fill in all fields'], 422);
        }
        $data = $request->all();
        unset($data['_token']);

        if ($validator->passes()) {
            // Handling file uploads
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('uploads/avatars'), $photoName);
                $data['photo'] = '/public/uploads/avatars/' . $photoName;
            }

            if ($request->hasFile('agent_poster')) {
                $agentPoster = $request->file('agent_poster');
                $posterName = time() . '_' . $agentPoster->getClientOriginalName();
                $agentPoster->move(public_path('uploads/posters'), $posterName);
                $data['agent_poster'] = '/public/uploads/posters/' . $posterName;
            }

            $user->update($data);
            return response()->json(['success' => __('Profile updated successfully.')]);
        }
    }
    public function delete_account($id)
    {
        $user =User::find($id);
        $arr = array('msg' => 'There are some errors, try again', 'status' => false);
        if($user){
            $user->delete();
            $arr = array('msg' => "operation accomplished successfully", 'status' => true);
        }
        return Response()->json($arr);

    }

    public function update_password(Request $request)
    {
        $messages = [
            'current_password.required' => __('Current password is required.'),
            'current_password.current_password' => __('The current password is incorrect.'),
            'password.required' => __('Please enter the new password.'),
            'password.min' => __('Password must be at least :min characters.'),
            'password.confirmed' => __('New password confirmation does not match.'),
        ];

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|current_password:web',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|same:password',
        ], $messages);

        $user = User::query()->where('id', Auth::id())->first();
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response([
                'responseJSON' => $errors,
                'input' => $request->all(),
                'message' => __('Verify that the data is correct, fill in all fields'),
            ], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['success' => __('Password updated successfully.')]);
    }




 }
