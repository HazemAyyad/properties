<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_informations', function (Blueprint $table) {
            $table->string('building_age', 50)->nullable()->after('year_built');
            $table->string('floor', 100)->nullable()->after('floors');
            $table->unsignedTinyInteger('furnished')->nullable()->after('floor')->comment('0=unfurnished, 1=furnished');
            $table->decimal('size_max', 12, 2)->nullable()->after('size');
            $table->decimal('land_area_min', 12, 2)->nullable()->after('land_area');
            $table->decimal('land_area_max', 12, 2)->nullable()->after('land_area_min');
            $table->string('zoning', 100)->nullable()->after('land_area_max')->comment('Land zoning: سكني أ, تجاري, etc.');
            $table->string('land_type', 100)->nullable()->after('zoning')->comment('صخرية, تربة حمراء, etc.');
            $table->string('services', 255)->nullable()->after('land_type')->comment('Land services');
            $table->decimal('price_min', 14, 2)->nullable()->after('services');
            $table->decimal('price_max', 14, 2)->nullable()->after('price_min');
            $table->json('extra_features')->nullable()->after('price_max')->comment('Category-specific amenities keys');
        });
    }

    public function down(): void
    {
        Schema::table('property_informations', function (Blueprint $table) {
            $table->dropColumn([
                'building_age', 'floor', 'furnished', 'size_max',
                'land_area_min', 'land_area_max', 'zoning', 'land_type',
                'services', 'price_min', 'price_max', 'extra_features',
            ]);
        });
    }
};
