<?php

namespace App\Services;

use App\Models\Dashboard\Plan;
use App\Models\Dashboard\Property;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Service for checking and enforcing plan-based property limits.
 * Counts non-soft-deleted properties. -1 = unlimited.
 * Structure allows future subscription expiry integration.
 */
class PlanLimitService
{
    /**
     * Check if the given user can create a new property.
     *
     * @param  User|null  $user  Defaults to authenticated user
     * @return array{allowed: bool, limit: int|null, used: int, remaining: int|null, message: string, plan: Plan|null}
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
            ];
        }

        $plan = $user->plan;
        if (!$plan) {
            return [
                'allowed' => false,
                'limit' => null,
                'used' => $this->countUserProperties($user),
                'remaining' => null,
                'message' => __('You need an active plan. Upgrade your account to add properties.'),
                'plan' => null,
            ];
        }

        // Future: integrate subscription expiry check here
        // if ($this->isPlanExpired($user)) { return [...]; }

        $limit = $plan->number_of_properties;
        $used = $this->countUserProperties($user);

        // -1 = unlimited
        if ($limit === Plan::UNLIMITED_PROPERTIES || $limit === -1) {
            return [
                'allowed' => true,
                'limit' => -1,
                'used' => $used,
                'remaining' => null, // unlimited
                'message' => '',
                'plan' => $plan,
            ];
        }

        // null or invalid: treat as 0 (deny creation)
        $limit = (int) $limit;
        if ($limit < 1) {
            $planName = $plan->title ?? __('Plan');
            return [
                'allowed' => false,
                'limit' => $limit,
                'used' => $used,
                'remaining' => 0,
                'message' => __('Your plan :plan has reached its limit. Upgrade your account to add more properties.', ['plan' => $planName]),
                'plan' => $plan,
            ];
        }

        $remaining = max(0, $limit - $used);
        $allowed = $remaining > 0;
        $planName = $plan->title ?? __('Plan');

        return [
            'allowed' => $allowed,
            'limit' => $limit,
            'used' => $used,
            'remaining' => $remaining,
            'message' => $allowed
                ? ''
                : __('Your plan :plan has reached its limit. Upgrade your account to add more properties.', ['plan' => $planName]),
            'plan' => $plan,
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
