<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $visionSection = DB::table('vision_sections')->first();
        if (!$visionSection) {
            return;
        }

        DB::table('vision_sections')->where('id', $visionSection->id)->update([
            'vision_title' => json_encode([
                'en' => 'Our Vision',
                'ar' => 'رؤيتنا',
            ], JSON_UNESCAPED_UNICODE),
            'vision_description' => json_encode([
                'en' => 'To be the leading real estate platform that connects people with their dream properties. We strive to provide trusted, transparent, and exceptional service while building lasting relationships with our clients and partners. Our vision is to make finding the perfect home a smooth, stress-free experience for everyone.',
                'ar' => 'أن نكون المنصة العقارية الرائدة التي تربط الناس بعقارات أحلامهم. نسعى لتقديم خدمة موثوقة وشفافة ومتميزة مع بناء علاقات دائمة مع عملائنا وشركائنا. رؤيتنا هي جعل البحث عن المنزل المثالي تجربة سلسة وخالية من التوتر للجميع.',
            ], JSON_UNESCAPED_UNICODE),
            'goals_title' => json_encode([
                'en' => 'Our Goals',
                'ar' => 'أهدافنا',
            ], JSON_UNESCAPED_UNICODE),
            'updated_at' => now(),
        ]);

        $goalsCount = DB::table('vision_goals')->where('vision_section_id', $visionSection->id)->count();
        if ($goalsCount > 0) {
            return;
        }

        $goals = [
            [
                'title' => ['en' => 'Customer Satisfaction', 'ar' => 'رضا العملاء'],
                'description' => ['en' => 'Deliver exceptional service and exceed client expectations in every transaction.', 'ar' => 'تقديم خدمة استثنائية وتجاوز توقعات العملاء في كل عملية.'],
                'sort_order' => 1,
            ],
            [
                'title' => ['en' => 'Quality Listings', 'ar' => 'عروض ذات جودة'],
                'description' => ['en' => 'Provide verified, accurate property listings that help clients make informed decisions.', 'ar' => 'تقديم عروض عقارية موثقة ودقيقة تساعد العملاء على اتخاذ قرارات مستنيرة.'],
                'sort_order' => 2,
            ],
            [
                'title' => ['en' => 'Trusted Partnerships', 'ar' => 'شراكات موثوقة'],
                'description' => ['en' => 'Build strong relationships with developers, owners, and agents to expand our network.', 'ar' => 'بناء علاقات قوية مع المطورين والملاك والوكلاء لتوسيع شبكتنا.'],
                'sort_order' => 3,
            ],
            [
                'title' => ['en' => 'Market Leadership', 'ar' => 'الريادة في السوق'],
                'description' => ['en' => 'Stay at the forefront of real estate trends and technology to serve our clients better.', 'ar' => 'البقاء في طليعة اتجاهات العقارات والتكنولوجيا لخدمة عملائنا بشكل أفضل.'],
                'sort_order' => 4,
            ],
        ];

        foreach ($goals as $goal) {
            DB::table('vision_goals')->insert([
                'vision_section_id' => $visionSection->id,
                'icon' => null,
                'title' => json_encode($goal['title'], JSON_UNESCAPED_UNICODE),
                'description' => json_encode($goal['description'], JSON_UNESCAPED_UNICODE),
                'sort_order' => $goal['sort_order'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        $visionSection = DB::table('vision_sections')->first();
        if ($visionSection) {
            DB::table('vision_goals')->where('vision_section_id', $visionSection->id)->delete();
            DB::table('vision_sections')->where('id', $visionSection->id)->update([
                'vision_description' => json_encode(['en' => 'Your vision description here.', 'ar' => 'وصف الرؤية هنا.'], JSON_UNESCAPED_UNICODE),
                'updated_at' => now(),
            ]);
        }
    }
};
