<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('material_files', function (Blueprint $table) {
            $table->unsignedInteger('duration_ms')->nullable()->after('generated_at');
        });
    }

    public function down(): void
    {
        Schema::table('material_files', function (Blueprint $table) {
            $table->dropColumn('duration_ms');
        });
    }
};
