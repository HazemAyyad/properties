<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Mark trial plan as default (basic fallback) for existing installations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('plans', 'is_default')) {
            DB::table('plans')->where('slug', 'trial')->update(['is_default' => true]);
            DB::table('plans')->where('slug', '!=', 'trial')->update(['is_default' => false]);
        }
    }

    public function down(): void
    {
        DB::table('plans')->update(['is_default' => false]);
    }
};
