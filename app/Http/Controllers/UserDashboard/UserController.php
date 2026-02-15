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
        $user = Auth::user()->load(['plan.features']);
        $pendingRequest = PlanUpgradeRequest::where('user_id', $user->id)
            ->where('status', PlanUpgradeRequest::STATUS_PENDING)
            ->with('plan')
            ->latest()
            ->first();
        return view('user_dashboard.profile.index', compact('user', 'pendingRequest'));
    }

    public function upgradeForm()
    {
        $user = Auth::user()->load('plan');
        $pendingRequest = PlanUpgradeRequest::where('user_id', $user->id)
            ->where('status', PlanUpgradeRequest::STATUS_PENDING)
            ->with('plan')
            ->latest()
            ->first();
        $plans = Plan::where('status', 1)->with('features')->orderBy('price_monthly')->get();
        return view('user_dashboard.profile.upgrade', compact('user', 'plans', 'pendingRequest'));
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

        $transferReceiptUrl = url($upgradeRequest->transfer_receipt);

        $plan = $upgradeRequest->plan;
        $planDetails = $plan ? (
            $plan->title . "\n"
            . '— ' . $plan->description . "\n"
            . '— ' . __('Cost') . ': ' . $plan->price_monthly . ' JOD'
            . ($plan->duration_months ? ' / ' . $plan->duration_months . ' ' . __('months') : '') . "\n"
            . '— ' . __('Properties') . ': ' . ($plan->number_of_properties == -1 ? __('Unlimited') : $plan->number_of_properties) . "\n"
            . ($plan->extra_support ? '— ' . __('Extra support') . ': ' . $plan->extra_support . "\n" : '')
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

    public function update(Request $request){
        $user = User::find(Auth::id());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'company' => 'required',
            'position' => 'required',
            'office_no' => 'required',
            'office_address' => 'required',
            'job' => 'required',
            'location' => 'required',
            'facebook' => 'required|url',  // Validate as URL
            'twitter' => 'required|url',   // Validate as URL
            'linkedin' => 'required|url',  // Validate as URL
             'mobile' => 'required|unique:users,mobile,'.Auth::id(),
             'email' => 'required|unique:users,email,'.Auth::id(),
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation for photo
            'agent_poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation for agent poster
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
            return response()->json(['success'=>"The process has successfully"]);
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
//        return $request->all();
        $messages = [
            'password_confirmation.same' => __('Password does not match'),
            'password.required' => __('Please enter the password'),
        ];

        $validator = Validator::make($request->all(), [
            'password' => 'required|same:password|min:6',
            'password_confirmation' => 'required|same:password|min:6',
        ], $messages);
        $user = User::query()->where('id', Auth::id())->first();
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input = $request->all();
            return response(["responseJSON" => $errors, "input" => $input, "message" => __('Verify that the data is correct, fill in all fields')], 422);
        }

        if ($validator->passes()) {

            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json(['success' => __('The process has successfully')]);


        }

    }




 }
