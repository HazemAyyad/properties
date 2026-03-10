<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Dashboard\Blog;
use App\Models\Dashboard\Faqs;
use App\Models\Dashboard\Policy;
use App\Models\Dashboard\Property;
use App\Models\Dashboard\Service;
use App\Models\Dashboard\VisionGoal;
use App\Models\Dashboard\VisionSection;
use App\Models\PlanUpgradeRequest;
use App\Models\State;
use App\Models\User;
use App\Services\SubscriptionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class MainController extends Controller
{
    public function index()
    {
        $subscriptionService = app(SubscriptionService::class);
        $basicPlan = $subscriptionService->getBasicPlan();
        $basicPlanId = $basicPlan?->id;

        // ---- Summary cards ----
        $cards = [
            'total_users' => User::count(),
            'total_properties' => Property::count(),
            'approved_properties' => Property::where('moderation_status', 1)->count(),
            'pending_properties' => Property::where('moderation_status', 0)->count(),
            'rejected_properties' => Property::where('moderation_status', 2)->count(),
            'featured_listings' => Property::where('is_featured', 1)
                ->whereNotNull('featured_listing_until')
                ->where('featured_listing_until', '>=', now()->toDateString())
                ->count(),
            'featured_3d_tours' => Property::where('is_3d_tour_featured', 1)
                ->whereNotNull('featured_3d_tour_until')
                ->where('featured_3d_tour_until', '>=', now()->toDateString())
                ->count(),
            'active_paid_subscriptions' => User::whereNotNull('subscription_ends_at')
                ->where('subscription_ends_at', '>', now())
                ->when($basicPlanId, fn ($q) => $q->where('plan_id', '!=', $basicPlanId))
                ->count(),
            'expired_subscriptions' => User::whereNotNull('subscription_ends_at')
                ->where('subscription_ends_at', '<=', now())
                ->count(),
            'pending_plan_upgrades' => PlanUpgradeRequest::where('status', PlanUpgradeRequest::STATUS_PENDING)->count(),
        ];

        // ---- Subscription overview ----
        $subscriptionOverview = [
            'active_paid' => $cards['active_paid_subscriptions'],
            'expiring_soon' => User::whereNotNull('subscription_ends_at')
                ->where('subscription_ends_at', '>', now())
                ->where('subscription_ends_at', '<=', now()->addDays(SubscriptionService::EXPIRING_SOON_DAYS))
                ->when($basicPlanId, fn ($q) => $q->where('plan_id', '!=', $basicPlanId))
                ->count(),
            'expired' => $cards['expired_subscriptions'],
            'on_basic' => $basicPlanId ? User::where('plan_id', $basicPlanId)->count() : 0,
            'pending_upgrades' => $cards['pending_plan_upgrades'],
        ];

        // ---- Subscriptions expiring soon (table) ----
        $expiringSoonUsers = User::query()
            ->with('plan')
            ->whereNotNull('subscription_ends_at')
            ->where('subscription_ends_at', '>', now())
            ->where('subscription_ends_at', '<=', now()->addDays(SubscriptionService::EXPIRING_SOON_DAYS))
            ->when($basicPlanId, fn ($q) => $q->where('plan_id', '!=', $basicPlanId))
            ->orderBy('subscription_ends_at')
            ->limit(10)
            ->get()
            ->map(function ($user) use ($subscriptionService) {
                $info = $subscriptionService->getSubscriptionStatus($user);
                return (object)[
                    'user' => $user,
                    'plan' => $user->plan,
                    'ends_at' => $user->subscription_ends_at ? Carbon::parse($user->subscription_ends_at) : null,
                    'days_left' => $info['days_remaining'],
                    'status' => $info['status'],
                ];
            });

        // ---- Recent plan upgrade requests ----
        $recentUpgradeRequests = PlanUpgradeRequest::with(['user', 'plan'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ---- Pending featured listing/3D (receipt uploaded, awaiting admin) ----
        $pendingFeaturedListing = Property::where('user_id', '!=', 0)
            ->whereNotNull('featured_listing_receipt')
            ->whereNull('featured_listing_until')
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        $pendingFeatured3d = Property::where('user_id', '!=', 0)
            ->whereNotNull('featured_3d_tour_receipt')
            ->whereNull('featured_3d_tour_until')
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $recentPaymentRequests = collect();
        foreach ($recentUpgradeRequests as $req) {
            $recentPaymentRequests->push((object)[
                'type' => 'subscription',
                'user' => $req->user,
                'related' => $req->plan ? $req->plan->title : '—',
                'status' => $req->status,
                'date' => $req->created_at,
                'id' => $req->id,
                'url' => route('admin.plan-upgrade-requests.show', $req->id),
            ]);
        }
        foreach ($pendingFeaturedListing as $p) {
            $recentPaymentRequests->push((object)[
                'type' => 'featured_listing',
                'user' => $p->user,
                'related' => $p->title,
                'status' => 'pending',
                'date' => $p->updated_at,
                'id' => $p->id,
                'url' => route('admin.properties.featured-listings'),
            ]);
        }
        foreach ($pendingFeatured3d as $p) {
            $recentPaymentRequests->push((object)[
                'type' => 'featured_3d_tour',
                'user' => $p->user,
                'related' => $p->title,
                'status' => 'pending',
                'date' => $p->updated_at,
                'id' => $p->id,
                'url' => route('admin.properties.featured-3d-tours'),
            ]);
        }
        $recentPaymentRequests = $recentPaymentRequests->sortByDesc('date')->take(10)->values();

        // ---- Latest pending properties ----
        $latestPendingProperties = Property::where('moderation_status', 0)
            ->with(['user', 'category'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ---- Users overview ----
        $usersOverview = [
            'total' => $cards['total_users'],
            'new_this_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
            'with_active_paid' => $cards['active_paid_subscriptions'],
            'on_basic' => $subscriptionOverview['on_basic'],
        ];
        $latestUsers = User::with('plan')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ---- Alerts (admin attention) ----
        $alerts = [
            'pending_properties' => $cards['pending_properties'],
            'pending_plan_upgrades' => $cards['pending_plan_upgrades'],
            'expired_subscriptions' => $cards['expired_subscriptions'],
            'pending_featured_listing' => $pendingFeaturedListing->count(),
            'pending_featured_3d' => $pendingFeatured3d->count(),
        ];

        // ---- CMS / content health ----
        $cmsHealth = [
            'blogs_count' => Blog::count(),
            'faqs_count' => Faqs::count(),
            'services_count' => Service::count(),
            'policies_count' => Policy::count(),
            'vision_goals_count' => VisionGoal::count(),
            'vision_section_exists' => VisionSection::count() > 0,
        ];

        // Chart-like data (for simple display)
        $chartPropertiesByStatus = [
            'approved' => $cards['approved_properties'],
            'pending' => $cards['pending_properties'],
            'rejected' => $cards['rejected_properties'],
        ];
        $chartNewUsersLast30 = User::where('created_at', '>=', now()->subDays(30))->count();
        $chartUpgradeRequestsLast30 = PlanUpgradeRequest::where('created_at', '>=', now()->subDays(30))->count();

        return view('dashboard.index', compact(
            'cards',
            'subscriptionOverview',
            'expiringSoonUsers',
            'recentPaymentRequests',
            'latestPendingProperties',
            'usersOverview',
            'latestUsers',
            'alerts',
            'cmsHealth',
            'chartPropertiesByStatus',
            'chartNewUsersLast30',
            'chartUpgradeRequestsLast30'
        ));
    }
    public function get_users(Request $request)
    {

//        $query = $request->get('query');
//        $users = User::where('name', 'LIKE', '%' . $query . '%')->get();
//        return response()->json($users);
        $query = $request->get('query');
        $users = User::where('name', 'LIKE', '%' . $query . '%')->get();
        $formattedUsers = [];

        foreach ($users as $user) {
            $formattedUsers[] = ['id' => $user->id, 'text' => $user->name];
        }

        return response()->json($formattedUsers);
    }
    public function checkSlug(Request $request)
    {
        $slug = $request->query('slug');
        $exists = Property::where('slug', $slug)->exists();
        return response()->json(['exists' => $exists]);
    }
    public function getStates($country_id)
    {
        $states = State::where('country_id', $country_id)->get();
        return response()->json($states);
    }

    public function getCities($state_id)
    {
        $cities = City::where('state_id', $state_id)->get();
        return response()->json($cities);
    }
    function upload($file)
    {

        $image_name ='/public/uploads/photos/' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(env('PATH_FILE_URL').'/uploads/photos/', $image_name);
        return $image_name;

} 
    public function saveProjectImages(Request $request)
    {

        $file = $request->file('dzfile');
         $filename = $this->upload($file);
         return response()->json([
            'name' => $filename,
            'original_name' => $file->getClientOriginalName(),
        ]);

    }
}
