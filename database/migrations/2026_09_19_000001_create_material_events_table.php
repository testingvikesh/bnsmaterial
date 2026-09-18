<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_file_id')->constrained('material_files')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('manage_session_id')->constrained('manage_sessions')->cascadeOnDelete();
            $table->foreignId('viewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type', 16);
            $table->string('ip', 45)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'manage_session_id', 'type']);
            $table->index(['material_file_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_events');
    }
};
