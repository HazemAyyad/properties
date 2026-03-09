<?php

namespace App\Services;

use App\Models\Dashboard\Plan;
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

        // Paid plan - check expiry
        $endsAt = $user->subscription_ends_at ? Carbon::parse($user->subscription_ends_at) : null;

        // No subscription_ends_at: legacy user on paid plan - treat as expired to be safe
        // (we don't know when they started, so we downgrade)
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
            $wasExpired = true;
            $wasDowngraded = $this->downgradeToBasic($user);
            if ($wasDowngraded) {
                $user->refresh();
            }
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
     * Get subscription info for display.
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
