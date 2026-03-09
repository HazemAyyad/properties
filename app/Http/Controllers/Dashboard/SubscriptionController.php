<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PlanLimitService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $subscriptionService = app(SubscriptionService::class);
        $planLimitService = app(PlanLimitService::class);

        $query = User::query()
            ->with(['plan', 'properties'])
            ->whereHas('plan')
            ->orderBy('subscription_ends_at', 'desc');

        $filter = $request->get('filter', 'all');
        $search = $request->get('search');
        $planId = $request->get('plan_id');
        $sort = $request->get('sort', 'expiry');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($planId) {
            $query->where('plan_id', $planId);
        }

        $allUsers = clone $query;
        $users = clone $query;

        switch ($filter) {
            case 'active_paid':
                $users->whereNotNull('subscription_ends_at')
                    ->where('subscription_ends_at', '>', now());
                break;
            case 'expired':
                $basicPlan = $subscriptionService->getBasicPlan();
                $users->where(function ($q) use ($basicPlan) {
                    $q->where('subscription_ends_at', '<=', now());
                    if ($basicPlan) {
                        $q->orWhere(function ($q2) use ($basicPlan) {
                            $q2->where('plan_id', $basicPlan->id)->whereNotNull('last_plan_id');
                        });
                    }
                });
                break;
            case 'on_basic':
                $basicPlan = $subscriptionService->getBasicPlan();
                if ($basicPlan) {
                    $users->where('plan_id', $basicPlan->id);
                }
                break;
            case 'expiring_soon':
                $users->whereNotNull('subscription_ends_at')
                    ->where('subscription_ends_at', '>', now())
                    ->where('subscription_ends_at', '<=', now()->addDays(SubscriptionService::EXPIRING_SOON_DAYS));
                break;
        }

        if ($sort === 'expiry') {
            $users->orderByRaw('CASE WHEN subscription_ends_at IS NULL THEN 1 ELSE 0 END')
                ->orderBy('subscription_ends_at', 'asc');
        } elseif ($sort === 'name') {
            $users->orderBy('name');
        }

        $usersPaginator = $users->paginate(20)->withQueryString();

        $usersWithStatus = $usersPaginator->getCollection()->map(function ($user) use ($subscriptionService) {
            $status = $subscriptionService->getSubscriptionStatus($user);
            $user->subscription_status = $status;
            return $user;
        });
        $usersPaginator->setCollection($usersWithStatus);

        $summary = $this->getSummary($subscriptionService);

        $plans = \App\Models\Dashboard\Plan::where('status', 1)->orderBy('price_monthly')->get();

        return view('dashboard.subscriptions.index', compact('usersPaginator', 'summary', 'plans', 'filter', 'search', 'planId', 'sort'));
    }

    protected function getSummary(SubscriptionService $subscriptionService): array
    {
        $basicPlan = $subscriptionService->getBasicPlan();
        $basicPlanId = $basicPlan?->id;

        $activePaid = User::whereNotNull('subscription_ends_at')
            ->where('subscription_ends_at', '>', now())
            ->where('plan_id', '!=', $basicPlanId)
            ->count();

        $expired = User::whereNotNull('subscription_ends_at')
            ->where('subscription_ends_at', '<=', now())
            ->count();

        $onBasic = $basicPlanId ? User::where('plan_id', $basicPlanId)->count() : 0;

        $expiringSoon = User::whereNotNull('subscription_ends_at')
            ->where('subscription_ends_at', '>', now())
            ->where('subscription_ends_at', '<=', now()->addDays(SubscriptionService::EXPIRING_SOON_DAYS))
            ->where('plan_id', '!=', $basicPlanId)
            ->count();

        return [
            'active_paid' => $activePaid,
            'expired' => $expired,
            'on_basic' => $onBasic,
            'expiring_soon' => $expiringSoon,
        ];
    }
}
