<?php

namespace App\Services;

use App\Models\Dashboard\Plan;
use App\Models\Dashboard\Property;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Handles subscription expiry and automatic downgrade to basic plan.
 * The basic plan is identified by is_default=true (trial plan).
 */
class SubscriptionService
{
    /**
     * Ensure the user's plan is active. If subscription has expired, downgrade to default plan.
     * Call this before any plan-based checks (e.g. property limits).
     *
     * @param  User  $user
     * @return User The updated user (refreshed if downgraded)
     */
    public function ensureActivePlan(User $user): User
    {
        $endsAt = $user->subscription_ends_at ? Carbon::parse($user->subscription_ends_at) : null;

        if (!$endsAt || !$endsAt->isPast()) {
            return $user;
        }

        $defaultPlan = $this->getBasicPlan();
        if (!$defaultPlan) {
            return $user;
        }

        DB::transaction(function () use ($user, $defaultPlan) {
            $user->update([
                'plan_id' => $defaultPlan->id,
                'last_plan_id' => $user->plan_id,
                'subscription_started_at' => null,
                'subscription_ends_at' => null,
            ]);
        });

        return $user->refresh();
    }

    /**
     * Ensure user's subscription state is up to date.
     * If expired, downgrade to basic plan. Returns the user (possibly updated).
     *
     * @param  User|null  $user
     * @return array{user: User, was_expired: bool, was_downgraded: bool, basic_plan: Plan|null}
     */
    public function ensureSubscriptionValid(?User $user = null): array
    {
        $user = $user ?? Auth::user();
        $wasExpired = false;
        $wasDowngraded = false;
        $basicPlan = $this->getBasicPlan();

        if (!$user) {
            return [
                'user' => $user,
                'was_expired' => false,
                'was_downgraded' => false,
                'basic_plan' => $basicPlan,
            ];
        }

        $plan = $user->plan;
        if (!$plan) {
            // No plan - assign basic if available (legacy users)
            if ($basicPlan) {
                DB::transaction(function () use ($user, $basicPlan) {
                    $user->update([
                        'plan_id' => $basicPlan->id,
                        'subscription_started_at' => null,
                        'subscription_ends_at' => null,
                        'last_plan_id' => null,
                    ]);
                });
                $user->refresh();
                $wasDowngraded = true;
            }
            return [
                'user' => $user,
                'was_expired' => false,
                'was_downgraded' => $wasDowngraded,
                'basic_plan' => $basicPlan,
            ];
        }

        // Check if this is the basic/default plan - never expires
        if ($this->isBasicPlan($plan)) {
            return [
                'user' => $user,
                'was_expired' => false,
                'was_downgraded' => false,
                'basic_plan' => $basicPlan,
            ];
        }

        // Paid plan - check expiry via ensureActivePlan
        $endsAt = $user->subscription_ends_at ? Carbon::parse($user->subscription_ends_at) : null;
        $previousPlanId = $user->plan_id;

        // No subscription_ends_at: legacy user on paid plan - treat as expired to be safe
        if (!$endsAt) {
            $wasExpired = true;
            $wasDowngraded = $this->downgradeToBasic($user);
            if ($wasDowngraded) {
                $user->refresh();
            }
            return [
                'user' => $user,
                'was_expired' => $wasExpired,
                'was_downgraded' => $wasDowngraded,
                'basic_plan' => $basicPlan,
            ];
        }

        if ($endsAt->isPast()) {
            $user = $this->ensureActivePlan($user);
            $wasExpired = true;
            $wasDowngraded = ($user->plan_id !== $previousPlanId);
        }

        return [
            'user' => $user,
            'was_expired' => $wasExpired,
            'was_downgraded' => $wasDowngraded,
            'basic_plan' => $basicPlan,
        ];
    }

    /**
     * Check if user's subscription is expired (without performing downgrade).
     */
    public function isExpired(User $user): bool
    {
        $plan = $user->plan;
        if (!$plan) {
            return true;
        }
        if ($this->isBasicPlan($plan)) {
            return false;
        }
        $endsAt = $user->subscription_ends_at ? Carbon::parse($user->subscription_ends_at) : null;
        if (!$endsAt) {
            return true; // legacy paid plan without dates
        }
        return $endsAt->isPast();
    }

    /**
     * Downgrade user to basic plan. Returns true if downgrade was performed.
     */
    public function downgradeToBasic(User $user): bool
    {
        $basicPlan = $this->getBasicPlan();
        if (!$basicPlan) {
            return false;
        }

        $previousPlanId = $user->plan_id;

        DB::transaction(function () use ($user, $basicPlan, $previousPlanId) {
            $user->update([
                'plan_id' => $basicPlan->id,
                'last_plan_id' => $previousPlanId,
                'subscription_started_at' => null,
                'subscription_ends_at' => null,
            ]);
        });

        return true;
    }

    /**
     * Get the default basic plan (trial). Used for fallback when subscription expires.
     */
    public function getBasicPlan(): ?Plan
    {
        if (Schema::hasColumn('plans', 'is_default')) {
            $plan = Plan::where('is_default', true)->where('status', 1)->first();
            if ($plan) {
                return $plan;
            }
        }
        return Plan::where('slug', 'trial')->where('status', 1)->first();
    }

    /**
     * Check if given plan is the basic/default plan.
     */
    public function isBasicPlan(Plan $plan): bool
    {
        if (Schema::hasColumn('plans', 'is_default')) {
            return (bool) $plan->is_default;
        }
        return $plan->slug === 'trial';
    }

    /**
     * Status constants for admin dashboard.
     */
    public const STATUS_ACTIVE_PAID = 'active_paid';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_ON_BASIC = 'on_basic';
    public const STATUS_EXPIRING_SOON = 'expiring_soon';

    public const EXPIRING_SOON_DAYS = 7;

    /**
     * Get subscription status for a user (for admin dashboard).
     *
     * @return array{status: string, days_remaining: int|null, subscription_started_at: Carbon|null, subscription_ends_at: Carbon|null, plan: Plan|null, last_plan: Plan|null, property_count: int}
     */
    public function getSubscriptionStatus(User $user): array
    {
        $plan = $user->plan;
        $lastPlan = $user->last_plan_id ? Plan::find($user->last_plan_id) : null;
        $propertyCount = Property::where('user_id', $user->id)->count();

        $startedAt = $user->subscription_started_at ? Carbon::parse($user->subscription_started_at) : null;
        $endsAt = $user->subscription_ends_at ? Carbon::parse($user->subscription_ends_at) : null;

        $isBasic = $plan && $this->isBasicPlan($plan);
        $daysRemaining = null;
        $status = self::STATUS_ON_BASIC;

        if ($isBasic) {
            $status = self::STATUS_ON_BASIC;
        } elseif (!$endsAt) {
            $status = self::STATUS_EXPIRED;
        } elseif ($endsAt->isPast()) {
            $status = self::STATUS_EXPIRED;
        } else {
            $daysRemaining = (int) max(0, now()->diffInDays($endsAt, false));
            if ($daysRemaining <= self::EXPIRING_SOON_DAYS) {
                $status = self::STATUS_EXPIRING_SOON;
            } else {
                $status = self::STATUS_ACTIVE_PAID;
            }
        }

        return [
            'status' => $status,
            'days_remaining' => $daysRemaining,
            'subscription_started_at' => $startedAt,
            'subscription_ends_at' => $endsAt,
            'plan' => $plan,
            'last_plan' => $lastPlan,
            'property_count' => $propertyCount,
        ];
    }

    /**
     * Get subscription info for display (user-facing).
     *
     * @return array{expires_at: Carbon|null, is_expired: bool, is_basic: bool, days_remaining: int|null}
     */
    public function getSubscriptionInfo(User $user): array
    {
        $plan = $user->plan;
        $isBasic = $plan && $this->isBasicPlan($plan);
        $endsAt = $user->subscription_ends_at ? Carbon::parse($user->subscription_ends_at) : null;
        $isExpired = $endsAt && $endsAt->isPast();
        $daysRemaining = $endsAt && !$isExpired ? (int) max(0, now()->diffInDays($endsAt, false)) : null;

        return [
            'expires_at' => $endsAt,
            'is_expired' => $isExpired,
            'is_basic' => $isBasic,
            'days_remaining' => $daysRemaining,
        ];
    }
}
