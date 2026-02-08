<?php

namespace Database\Seeders;

use App\Models\Dashboard\Category;
use Illuminate\Database\Seeder;

class PropertyCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * أنواع العقارات: شقة، فيلا/منزل مستقل، مزرعة، مكتب، عقارات تجارية، أرض
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => ['en' => 'Apartment', 'ar' => 'شقة'],
                'description' => ['en' => 'Apartment', 'ar' => 'شقة'],
                'slug' => ['en' => 'apartment', 'ar' => 'شقة'],
            ],
            [
                'name' => ['en' => 'Villa', 'ar' => 'فيلا/ منزل مستقل'],
                'description' => ['en' => 'Villa / Detached House', 'ar' => 'فيلا/ منزل مستقل'],
                'slug' => ['en' => 'villa', 'ar' => 'فيلا'],
            ],
            [
                'name' => ['en' => 'Farm', 'ar' => 'مزرعة'],
                'description' => ['en' => 'Farm', 'ar' => 'مزرعة'],
                'slug' => ['en' => 'farm', 'ar' => 'مزرعة'],
            ],
            [
                'name' => ['en' => 'Office', 'ar' => 'مكتب'],
                'description' => ['en' => 'Office', 'ar' => 'مكتب'],
                'slug' => ['en' => 'office', 'ar' => 'مكتب'],
            ],
            [
                'name' => ['en' => 'Commercial', 'ar' => 'عقارات تجارية'],
                'description' => ['en' => 'Warehouse / Factory / Companies', 'ar' => 'مخزن/ مصنع/ شركات'],
                'slug' => ['en' => 'commercial', 'ar' => 'عقارات-تجارية'],
            ],
            [
                'name' => ['en' => 'Land', 'ar' => 'أرض'],
                'description' => ['en' => 'Land', 'ar' => 'أرض'],
                'slug' => ['en' => 'land', 'ar' => 'أرض'],
            ],
        ];

        foreach ($categories as $item) {
            $enSlug = $item['slug']['en'];
            $existing = Category::whereRaw("JSON_UNQUOTE(JSON_EXTRACT(slug, '$.en')) = ?", [$enSlug])->first();
            if ($existing) {
                $existing->update([
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'slug' => $item['slug'],
                    'status' => 1,
                ]);
            } else {
                Category::create([
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'slug' => $item['slug'],
                    'status' => 1,
                ]);
            }
        }
    }
}
