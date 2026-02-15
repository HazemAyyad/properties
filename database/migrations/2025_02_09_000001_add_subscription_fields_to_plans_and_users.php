<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'slug')) {
                $table->string('slug', 80)->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('plans', 'duration_months')) {
                $table->unsignedSmallInteger('duration_months')->nullable()->after('description')->comment('Plan period in months');
            }
            if (!Schema::hasColumn('plans', 'number_of_properties')) {
                $table->integer('number_of_properties')->default(1)->after('duration_months')->comment('-1 = unlimited');
            }
            if (!Schema::hasColumn('plans', 'extra_support')) {
                $table->json('extra_support')->nullable()->after('price_yearly')->comment('Translatable extra support description');
            }
        });

        if (!Schema::hasColumn('users', 'plan_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedInteger('plan_id')->nullable()->after('id');
                $table->foreign('plan_id')->references('id')->on('plans')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn('plan_id');
        });
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['slug', 'duration_months', 'number_of_properties', 'extra_support']);
        });
    }
};
