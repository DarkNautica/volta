<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id')->constrained()->cascadeOnDelete();
            $table->string('external_user_id');
            $table->integer('credit_balance')->default(0);
            $table->timestamps();

            $table->unique(['app_id', 'external_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_users');
    }
};
