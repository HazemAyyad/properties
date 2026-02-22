<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('settings')->where('key', 'featured_3d_tour_price')->exists();
        if (!$exists) {
            DB::table('settings')->insert([
                'key' => 'featured_3d_tour_price',
                'value' => '30',
                'value_ar' => '30',
                'name' => 'Featured 3D Tour Monthly Price',
                'name_ar' => 'سعر جولة 3D المميزة الشهرية',
                'page' => 'settings',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'featured_3d_tour_price')->delete();
    }
};
