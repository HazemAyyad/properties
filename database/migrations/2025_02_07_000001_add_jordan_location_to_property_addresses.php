<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('property_addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('property_addresses', 'hod_id')) {
                $table->unsignedInteger('hod_id')->nullable()->after('village_id');
            }
            if (!Schema::hasColumn('property_addresses', 'hay_id')) {
                $table->unsignedInteger('hay_id')->nullable()->after('hod_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_addresses', function (Blueprint $table) {
            if (Schema::hasColumn('property_addresses', 'hod_id')) {
                $table->dropColumn('hod_id');
            }
            if (Schema::hasColumn('property_addresses', 'hay_id')) {
                $table->dropColumn('hay_id');
            }
        });
    }
};
