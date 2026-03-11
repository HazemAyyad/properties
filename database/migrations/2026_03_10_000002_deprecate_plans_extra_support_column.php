<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Plans.extra_support is deprecated: plan features are the single source of truth (plan_features table).
 * This migration intentionally does NOT drop the column to keep the transition safe and data-preserving.
 *
 * Future cleanup (run only after all app logic and any external consumers no longer use extra_support):
 *   Schema::table('plans', fn (Blueprint $table) => $table->dropColumn('extra_support'));
 */
return new class extends Migration
{
    public function up(): void
    {
        // No-op: column kept for backward compatibility. Drop in a future migration when safe.
    }

    public function down(): void
    {
        // No-op
    }
};
