<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('member_profiles')) {
            return;
        }

        Schema::create('member_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('whatsapp', 20)->nullable();
            $table->string('member_id', 50)->nullable();
            $table->string('business_name')->nullable();
            $table->string('business_category')->nullable();
            $table->string('business_location')->nullable();
            $table->text('business_address')->nullable();
            $table->text('business_description')->nullable();
            $table->text('main_products_services')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Keep existing imported member_profiles data.
    }
};
