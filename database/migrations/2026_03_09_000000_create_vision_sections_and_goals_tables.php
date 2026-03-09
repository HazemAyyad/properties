<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vision_sections', function (Blueprint $table) {
            $table->id();
            $table->json('vision_title');
            $table->json('vision_description');
            $table->json('goals_title')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('vision_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vision_section_id')
                ->constrained('vision_sections')
                ->onDelete('cascade');
            $table->string('icon')->nullable();
            $table->json('title');
            $table->json('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $exists = DB::table('settings')->where('key', 'vision_goals')->exists();
        if (!$exists) {
            DB::table('settings')->insert([
                'key' => 'vision_goals',
                'value' => '1',
                'value_ar' => '1',
                'name' => 'Vision & Goals section',
                'name_ar' => 'قسم الرؤية والأهداف',
                'page' => 'sections',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('vision_sections')->insert([
            'vision_title' => json_encode(['en' => 'Our Vision', 'ar' => 'رؤيتنا'], JSON_UNESCAPED_UNICODE),
            'vision_description' => json_encode(['en' => 'Your vision description here.', 'ar' => 'وصف الرؤية هنا.'], JSON_UNESCAPED_UNICODE),
            'goals_title' => json_encode(['en' => 'Our Goals', 'ar' => 'أهدافنا'], JSON_UNESCAPED_UNICODE),
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('vision_goals');
        Schema::dropIfExists('vision_sections');
        DB::table('settings')->where('key', 'vision_goals')->delete();
    }
};
