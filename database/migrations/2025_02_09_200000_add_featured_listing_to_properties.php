<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'featured_listing_receipt')) {
                $table->string('featured_listing_receipt', 500)->nullable()->after('registration_document');
            }
            if (!Schema::hasColumn('properties', 'featured_listing_until')) {
                $table->date('featured_listing_until')->nullable()->after('featured_listing_receipt');
            }
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['featured_listing_receipt', 'featured_listing_until']);
        });
    }
};
