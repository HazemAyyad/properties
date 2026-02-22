<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'is_3d_tour_featured')) {
                $table->boolean('is_3d_tour_featured')->default(0)->after('featured_listing_until');
            }
            if (!Schema::hasColumn('properties', 'featured_3d_tour_receipt')) {
                $table->string('featured_3d_tour_receipt', 500)->nullable()->after('is_3d_tour_featured');
            }
            if (!Schema::hasColumn('properties', 'featured_3d_tour_until')) {
                $table->date('featured_3d_tour_until')->nullable()->after('featured_3d_tour_receipt');
            }
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['is_3d_tour_featured', 'featured_3d_tour_receipt', 'featured_3d_tour_until']);
        });
    }
};
