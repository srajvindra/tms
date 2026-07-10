<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'b2b_schema';

    public function up(): void
    {
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('amenity_name', 100)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('properties_category', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('property_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('lead_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->timestamps();
        });

        Schema::create('event_legends', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('color', 20)->default('#000000');
            $table->timestamps();
        });

        Schema::create('event_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('event_reminders', function (Blueprint $table) {
            $table->id();
            $table->integer('minutes_before');
            $table->enum('method', ['email', 'popup'])->default('popup');
            $table->timestamps();
        });

        Schema::create('risk', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('zip_codes', function (Blueprint $table) {
            $table->id();
            $table->string('zipcode', 5);
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('state_abbr', 2)->nullable();
            $table->string('county_area', 50)->nullable();
            $table->string('code', 3)->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->tinyInteger('some_field')->nullable();
            $table->timestamps();
        });

        Schema::create('property_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('global_steps', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('icon', 100)->nullable();
            $table->string('color', 30)->nullable();
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('global_step_params', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('icon', 100);
            $table->string('title');
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('otp', 6);
            $table->timestamp('expires_at');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->boolean('is_used')->default(false);
            $table->timestamps();
        });

        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('tokenable_type');
            $table->unsignedBigInteger('tokenable_id');
            $table->text('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->index(['tokenable_type', 'tokenable_id']);
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id')->nullable();
            $table->string('type');
            $table->string('title');
            $table->string('come_from');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['email', 'sms'])->default('email');
            $table->text('to_addresses')->nullable();
            $table->text('cc_addresses')->nullable();
            $table->text('bcc_addresses')->nullable();
            $table->string('subject')->nullable();
            $table->longText('message');
            $table->boolean('is_global')->default(true);
            $table->string('scope_type')->nullable();
            $table->unsignedBigInteger('scope_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['scope_type', 'scope_id']);
        });

        Schema::create('master_documents', function (Blueprint $table) {
            $table->id();
            $table->string('original_name');
            $table->string('file_name');
            $table->string('file_path', 1000);
            $table->string('file_type', 100)->nullable();
            $table->integer('file_size_bytes')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('required_documents', function (Blueprint $table) {
            $table->id();
            $table->string('did', 20)->nullable()->unique();
            $table->string('document_name', 200)->nullable();
            $table->text('document_description')->nullable();
            $table->string('document_type', 100)->nullable();
            $table->string('type', 100)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('status', 50)->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('business_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_phone', 50)->nullable();
            $table->string('website', 500)->nullable();
            $table->string('street_address', 500)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->text('business_description')->nullable();
            $table->foreignId('logo_id')->nullable()->constrained('master_documents')->nullOnDelete();
            $table->string('uploaded_status')->nullable();
            $table->timestamps();
        });

        Schema::create('marketing_details', function (Blueprint $table) {
            $table->id();
            $table->string('mkt_uid', 20)->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_current')->default(true);
            $table->string('scope_type', 100);
            $table->unsignedBigInteger('scope_id');
            $table->enum('posted_to_website', ['YES', 'NO'])->default('NO');
            $table->enum('posted_to_internet', ['YES', 'NO'])->default('NO');
            $table->boolean('premium_listing')->default(false);
            $table->string('available_on', 500)->nullable();
            $table->dateTime('marketing_time')->nullable();
            $table->text('marketing_description')->nullable();
            $table->string('youtube_url', 500)->nullable();
            $table->boolean('removed_from_vacancies')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['scope_type', 'scope_id']);
            $table->unique(['mkt_uid', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_details');
        Schema::dropIfExists('business_settings');
        Schema::dropIfExists('required_documents');
        Schema::dropIfExists('master_documents');
        Schema::dropIfExists('templates');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('otps');
        Schema::dropIfExists('global_step_params');
        Schema::dropIfExists('global_steps');
        Schema::dropIfExists('property_groups');
        Schema::dropIfExists('zip_codes');
        Schema::dropIfExists('risk');
        Schema::dropIfExists('event_reminders');
        Schema::dropIfExists('event_types');
        Schema::dropIfExists('event_legends');
        Schema::dropIfExists('lead_sources');
        Schema::dropIfExists('property_types');
        Schema::dropIfExists('properties_category');
        Schema::dropIfExists('amenities');
    }
};
