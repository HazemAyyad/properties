<?php

namespace Database\Seeders;

use App\Models\Dashboard\Plan;
use App\Models\Dashboard\PlanFeature;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        // حذف ميزات الخطط القديمة أولاً (مرتبطة بـ plan_id)
        PlanFeature::query()->delete();

        // فصل المستخدمين عن الخطط قبل حذفها
        User::query()->update(['plan_id' => null]);

        // حذف كل الخطط القديمة
        Plan::query()->delete();

        // بداية الـ id من 1
        DB::statement('ALTER TABLE plan_features AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE plans AUTO_INCREMENT = 1');

        // Former extra_support content: now seeded as plan_features (single source of truth).
        $supportFeatureBySlug = [
            'trial' => ['en' => 'none', 'ar' => 'لا يوجد'],
            'standard-subscription' => ['en' => 'limited support', 'ar' => 'دعم محدود'],
            'extra' => ['en' => 'limited support', 'ar' => 'دعم محدود'],
            'light' => ['en' => 'limited support', 'ar' => 'دعم محدود'],
            'unique-client' => [
                'en' => 'Advertising management + marketing support or periodic consultations',
                'ar' => 'إدارة إعلانية + دعم تسويقي أو استشارات دورية',
            ],
            'featured-listing' => [
                'en' => 'Advertising management + marketing support or periodic consultations',
                'ar' => 'إدارة إعلانية + دعم تسويقي أو استشارات دورية',
            ],
            'featuring-client' => [
                'en' => 'Advertising management + marketing support or periodic consultations',
                'ar' => 'إدارة إعلانية + دعم تسويقي أو استشارات دورية',
            ],
        ];

        $plans = [
            [
                'slug' => 'trial',
                'title' => ['en' => 'Free month', 'ar' => 'شهر مجاني'],
                'description' => ['en' => 'Free of charge to post one property per month', 'ar' => 'مجاني لنشر عقار واحد شهرياً'],
                'duration_months' => 1,
                'number_of_properties' => 1,
                'price_monthly' => 0,
                'price_yearly' => 0,
                'status' => 1,
                'is_default' => true,
            ],
            [
                'slug' => 'standard-subscription',
                'is_default' => false,
                'title' => ['en' => 'Standard subscription', 'ar' => 'باقة أساسية'],
                'description' => ['en' => 'Three months period, one property', 'ar' => 'فترة ثلاثة أشهر، عقار واحد'],
                'duration_months' => 3,
                'number_of_properties' => 1,
                'price_monthly' => 30,
                'price_yearly' => 90,
                'status' => 1,
            ],
            [
                'slug' => 'extra',
                'is_default' => false,
                'title' => ['en' => 'Extra', 'ar' => 'باقة متميزة'],
                'description' => ['en' => 'Six months period, three properties', 'ar' => 'فترة ستة أشهر، ثلاثة عقارات'],
                'duration_months' => 6,
                'number_of_properties' => 3,
                'price_monthly' => 50,
                'price_yearly' => 300,
                'status' => 1,
            ],
            [
                'slug' => 'light',
                'is_default' => false,
                'title' => ['en' => 'Light', 'ar' => 'باقة فضية'],
                'description' => ['en' => '12 months period, 6 properties', 'ar' => 'فترة 12 شهراً، 6 عقارات'],
                'duration_months' => 12,
                'number_of_properties' => 6,
                'price_monthly' => 80,
                'price_yearly' => 960,
                'status' => 1,
            ],
            [
                'slug' => 'unique-client',
                'is_default' => false,
                'title' => ['en' => 'Unique client', 'ar' => 'باقة ذهبية'],
                'description' => ['en' => '12 months period, unlimited properties', 'ar' => 'فترة 12 شهراً، عقارات غير محدودة'],
                'duration_months' => 12,
                'number_of_properties' => Plan::UNLIMITED_PROPERTIES,
                'price_monthly' => 100,
                'price_yearly' => 1200,
                'status' => 1,
            ],
            [
                'slug' => 'featured-listing',
                'is_default' => false,
                'title' => ['en' => 'Featured listing', 'ar' => 'عقار مميز'],
                'description' => ['en' => 'Unique property', 'ar' => 'عقار مميز'],
                'duration_months' => null,
                'number_of_properties' => 1,
                'price_monthly' => 20,
                'price_yearly' => 20,
                'status' => 1,
            ],
            [
                'slug' => 'featuring-client',
                'is_default' => false,
                'title' => ['en' => 'Featuring client (VIP)', 'ar' => 'عميل مميز (للمكاتب العقارية أو شركات المقاولة)'],
                'description' => ['en' => 'For companies – unlimited', 'ar' => 'للشركات – غير محدود'],
                'duration_months' => 12,
                'number_of_properties' => Plan::UNLIMITED_PROPERTIES,
                'price_monthly' => 500,
                'price_yearly' => 6000,
                'status' => 1,
            ],
        ];

        foreach ($plans as $data) {
            $isDefault = $data['is_default'] ?? false;
            unset($data['is_default']);
            Plan::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, Schema::hasColumn('plans', 'is_default') ? ['is_default' => $isDefault] : [])
            );
        }

        // Seed one plan_feature per plan from former extra_support content (preserves same meaning).
        foreach ($supportFeatureBySlug as $slug => $title) {
            $plan = Plan::where('slug', $slug)->first();
            if ($plan) {
                PlanFeature::query()->create([
                    'plan_id' => $plan->id,
                    'title' => $title,
                    'status' => 1,
                ]);
            }
        }

        $trial = Plan::where('slug', 'trial')->first();
        if ($trial) {
            User::whereNull('plan_id')->update(['plan_id' => $trial->id]);
        }
    }
}
