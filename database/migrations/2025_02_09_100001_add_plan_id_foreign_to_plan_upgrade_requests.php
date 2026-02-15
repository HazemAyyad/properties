<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // plans.id type may differ per environment; skip FK to avoid incompatibility
    }

    public function down(): void
    {
    }
};
