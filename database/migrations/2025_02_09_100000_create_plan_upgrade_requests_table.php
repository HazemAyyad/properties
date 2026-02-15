<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_upgrade_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('plan_id'); // plans.id is int (signed)
            $table->string('transfer_receipt')->comment('path to uploaded image');
            $table->string('status', 20)->default('pending'); // pending, accepted, rejected
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            // plan_id: no FK to avoid type mismatch with plans.id (int vs bigint) across environments
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_upgrade_requests');
    }
};
