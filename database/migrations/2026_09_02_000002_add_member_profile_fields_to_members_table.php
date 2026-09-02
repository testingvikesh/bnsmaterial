<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('whatsapp', 20)->nullable()->after('phone');
            $table->string('state', 100)->nullable()->after('city');
            $table->string('batch_name', 150)->nullable()->after('state');
            $table->unsignedTinyInteger('age')->nullable()->after('batch_name');
            $table->string('gender', 30)->nullable()->after('age');
            $table->string('education_qualification')->nullable()->after('gender');
            $table->string('current_status', 50)->nullable()->after('education_qualification');
            $table->string('current_status_other')->nullable()->after('current_status');

            $table->string('business_category')->nullable()->after('business_name');
            $table->string('business_location')->nullable()->after('business_category');
            $table->text('business_address')->nullable()->after('business_location');
            $table->text('business_description')->nullable()->after('business_address');
            $table->text('main_products_services')->nullable()->after('business_description');
            $table->text('main_services')->nullable()->after('main_products_services');
            $table->string('business_type', 50)->nullable()->after('main_services');
            $table->string('business_type_other')->nullable()->after('business_type');
            $table->string('business_stage', 50)->nullable()->after('business_type_other');
            $table->string('operating_area', 50)->nullable()->after('business_stage');
            $table->string('gstin', 30)->nullable()->after('operating_area');
            $table->string('pan_number', 20)->nullable()->after('gstin');
            $table->string('gst_legal_name')->nullable()->after('pan_number');

            $table->string('planning_business_name')->nullable()->after('gst_legal_name');
            $table->string('planning_category')->nullable()->after('planning_business_name');
            $table->string('planning_location')->nullable()->after('planning_category');
            $table->text('planning_idea')->nullable()->after('planning_location');
            $table->string('planning_timeline', 100)->nullable()->after('planning_idea');
            $table->text('planning_notes')->nullable()->after('planning_timeline');
            $table->text('idea_why')->nullable()->after('planning_notes');
            $table->string('idea_customer')->nullable()->after('idea_why');
            $table->text('idea_opportunity')->nullable()->after('idea_customer');
            $table->text('idea_existing_skill')->nullable()->after('idea_opportunity');
            $table->text('idea_want_to_learn')->nullable()->after('idea_existing_skill');

            $table->string('website_url')->nullable()->after('idea_want_to_learn');
            $table->string('instagram')->nullable()->after('website_url');
            $table->string('facebook')->nullable()->after('instagram');
            $table->string('linkedin')->nullable()->after('facebook');
            $table->string('youtube')->nullable()->after('linkedin');
            $table->string('google_business')->nullable()->after('youtube');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn([
                'whatsapp', 'state', 'batch_name', 'age', 'gender', 'education_qualification',
                'current_status', 'current_status_other', 'business_category', 'business_location',
                'business_address', 'business_description', 'main_products_services', 'main_services',
                'business_type', 'business_type_other', 'business_stage', 'operating_area',
                'gstin', 'pan_number', 'gst_legal_name', 'planning_business_name', 'planning_category',
                'planning_location', 'planning_idea', 'planning_timeline', 'planning_notes',
                'idea_why', 'idea_customer', 'idea_opportunity', 'idea_existing_skill', 'idea_want_to_learn',
                'website_url', 'instagram', 'facebook', 'linkedin', 'youtube', 'google_business',
            ]);
        });
    }
};
