<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_prompts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manage_session_id')->constrained('manage_sessions')->cascadeOnDelete();
            $table->string('title');
            $table->longText('body');
            $table->boolean('is_active')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_prompts');
    }
};
