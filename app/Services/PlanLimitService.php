<?php

namespace App\Services;

use App\Models\Dashboard\Plan;
use App\Services\SubscriptionService;
use App\Models\Dashboard\Property;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Service for checking and enforcing plan-based property limits.
 * Counts non-soft-deleted properties. -1 = unlimited.
 */
class PlanLimitService
{
    /**
     * Check property limit. Ensures subscription is valid (downgrades if expired) before calculating limits.
     * Call this before any plan-based checks.
     *
     * @param  User|null  $user  Defaults to authenticated user
     * @return array{allowed: bool, limit: int|null, used: int, remaining: int|null, message: string, plan: Plan|null, subscription_expired: bool}
     */
    public function checkPropertyLimit(?User $user = null): array
    {
        $user = $user ?? Auth::user();
        if ($user) {
            $user = app(SubscriptionService::class)->ensureActivePlan($user);
        }
        return $this->canCreateProperty($user);
    }

    /**
     * Check if the given user can create a new property.
     * Uses ensureSubscriptionValid for full expiry handling (including legacy users).
     *
     * @param  User|null  $user  Defaults to authenticated user
     * @return array{allowed: bool, limit: int|null, used: int, remaining: int|null, message: string, plan: Plan|null, subscription_expired: bool}
     */
    public function canCreateProperty(?User $user = null): array
    {
        $user = $user ?? Auth::user();
        if (!$user) {
            return [
                'allowed' => false,
                'limit' => null,
                'used' => 0,
                'remaining' => null,
                'message' => __('You need an active subscription plan before adding properties.'),
                'plan' => null,
                'subscription_expired' => false,
            ];
        }

        $subscriptionService = app(SubscriptionService::class);
        $result = $subscriptionService->ensureSubscriptionValid($user);
        $user = $result['user'];
        $subscriptionExpired = $result['was_expired'];

        $plan = $user->plan;

        // When subscription expired, enforce basic plan limits (even if downgrade failed)
        $basicPlan = null;
        if ($subscriptionExpired) {
            $basicPlan = $subscriptionService->getBasicPlan();
            $plan = $basicPlan ?? $plan;
        }

        if (!$plan) {
            return [
                'allowed' => false,
                'limit' => null,
                'used' => $this->countUserProperties($user),
                'remaining' => null,
                'message' => __('You need an active plan. Upgrade your account to add properties.'),
                'plan' => null,
                'subscription_expired' => $subscriptionExpired,
            ];
        }

        // Subscription expired but no basic plan in DB - enforce safe limit of 1
        if ($subscriptionExpired && !$basicPlan) {
            $used = $this->countUserProperties($user);
            return [
                'allowed' => $used < 1,
                'limit' => 1,
                'used' => $used,
                'remaining' => max(0, 1 - $used),
                'message' => __('Your paid subscription has expired and your account has been moved to the Basic plan. You cannot add new properties until you upgrade or free up slots.'),
                'plan' => $user->plan,
                'subscription_expired' => true,
            ];
        }

        $limit = $plan->number_of_properties;
        $used = $this->countUserProperties($user);

        // -1 = unlimited
        if ($limit === Plan::UNLIMITED_PROPERTIES || $limit === -1) {
            return [
                'allowed' => true,
                'limit' => -1,
                'used' => $used,
                'remaining' => null,
                'message' => '',
                'plan' => $plan,
                'subscription_expired' => $subscriptionExpired,
            ];
        }

        // null or invalid: treat as 0 (deny creation)
        $limit = (int) $limit;
        if ($limit < 1) {
            $planName = $plan->title ?? __('Plan');
            $message = $subscriptionExpired
                ? __('Your paid subscription has expired and your account has been moved to the Basic plan. You cannot add new properties until you upgrade or free up slots.')
                : __('Your plan :plan has reached its limit. Upgrade your account to add more properties.', ['plan' => $planName]);
            return [
                'allowed' => false,
                'limit' => $limit,
                'used' => $used,
                'remaining' => 0,
                'message' => $message,
                'plan' => $plan,
                'subscription_expired' => $subscriptionExpired,
            ];
        }

        $remaining = max(0, $limit - $used);
        $allowed = $remaining > 0;
        $planName = $plan->title ?? __('Plan');
        $message = $allowed
            ? ''
            : ($subscriptionExpired
                ? __('Your paid subscription has expired and your account has been moved to the Basic plan. You cannot add new properties until you upgrade or free up slots.')
                : __('Your plan :plan has reached its limit. Upgrade your account to add more properties.', ['plan' => $planName]));

        return [
            'allowed' => $allowed,
            'limit' => $limit,
            'used' => $used,
            'remaining' => $remaining,
            'message' => $message,
            'plan' => $plan,
            'subscription_expired' => $subscriptionExpired,
        ];
    }

    /**
     * Count non-soft-deleted properties for the user.
     * Uses Property model from Dashboard namespace (same table as user properties).
     */
    public function countUserProperties(User $user): int
    {
        return Property::where('user_id', $user->id)->count();
    }
}
