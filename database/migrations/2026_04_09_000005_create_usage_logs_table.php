<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id')->constrained()->cascadeOnDelete();
            $table->foreignId('app_user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('app_model_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('credits_charged');
            $table->integer('tokens_used')->nullable();
            $table->string('endpoint')->nullable();
            $table->boolean('success')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usage_logs');
    }
};
