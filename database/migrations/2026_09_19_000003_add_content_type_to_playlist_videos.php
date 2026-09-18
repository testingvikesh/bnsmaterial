<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('playlist_videos', function (Blueprint $table) {
            $table->string('content_type', 20)->default('Video')->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('playlist_videos', function (Blueprint $table) {
            $table->dropColumn('content_type');
        });
    }
};
