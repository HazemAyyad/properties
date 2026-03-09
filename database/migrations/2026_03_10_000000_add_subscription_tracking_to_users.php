<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add subscription tracking fields for expiry and basic plan fallback.
     * - subscription_started_at: when current paid plan started
     * - subscription_ends_at: when it expires (null = permanent/basic plan)
     * - last_plan_id: previous plan before downgrade (audit)
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'subscription_started_at')) {
                $table->timestamp('subscription_started_at')->nullable()->after('plan_id');
            }
            if (!Schema::hasColumn('users', 'subscription_ends_at')) {
                $table->timestamp('subscription_ends_at')->nullable()->after('subscription_started_at');
            }
            if (!Schema::hasColumn('users', 'last_plan_id')) {
                $table->unsignedInteger('last_plan_id')->nullable()->after('subscription_ends_at');
            }
        });

        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'is_default')) {
                $table->boolean('is_default')->default(false)->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['subscription_started_at', 'subscription_ends_at', 'last_plan_id']);
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('is_default');
        });
    }
};
