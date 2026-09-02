<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('manage_session_id')->constrained('manage_sessions')->cascadeOnDelete();
            $table->foreignId('session_prompt_id')->constrained('session_prompts')->cascadeOnDelete();
            $table->string('file_path');
            $table->timestamp('generated_at');
            $table->timestamps();

            $table->unique(['user_id', 'manage_session_id', 'session_prompt_id'], 'material_files_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_files');
    }
};
