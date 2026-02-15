<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('settings')->where('key', 'featured_listing_price')->exists();
        if (!$exists) {
            DB::table('settings')->insert([
                'key' => 'featured_listing_price',
                'value' => '50',
                'value_ar' => '50',
                'name' => 'Featured Listing Monthly Price',
                'name_ar' => 'سعر الاشتراك الشهري للإعلان المميز',
                'page' => 'settings',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'featured_listing_price')->delete();
    }
};
