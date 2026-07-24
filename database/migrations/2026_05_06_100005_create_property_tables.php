<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'b2b_schema';

    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('llc_id')->nullable()->constrained('properties_category')->nullOnDelete();
            $table->foreignId('property_type_id')->nullable()->constrained('property_types')->nullOnDelete();
            $table->string('property_name');
            $table->text('address');
            $table->string('city')->nullable();
            $table->string('state', 100)->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->text('description')->nullable();
            $table->string('tax_authority')->nullable();
            $table->integer('year_build')->nullable();
            $table->date('management_start_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'under_termination', 'hidden', 'under_retention', 'occupied', 'vacant'])->default('active')->nullable();
            $table->boolean('is_pma')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('property_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->string('unit_number', 50);
            $table->enum('status', ['Occupied', 'Vacant'])->default('Vacant');
            $table->integer('bedrooms')->nullable();
            $table->decimal('bathrooms', 4, 1)->nullable();
            $table->decimal('square_footage', 10, 2)->nullable();
            $table->decimal('monthly_rent', 10, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('property_unit_amenities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('property_units')->cascadeOnDelete();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->foreignId('amenity_id')->constrained('amenities')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['unit_id', 'amenity_id']);
        });

        Schema::create('property_unit_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('property_units')->cascadeOnDelete();
            $table->foreignId('master_document_id')->constrained('master_documents')->cascadeOnDelete();
            $table->string('document_name')->nullable();
            $table->string('document_type', 100)->nullable();
            $table->string('file_type', 50)->nullable();
            $table->string('upload_status', 50)->default('uploaded');
            $table->timestamps();
        });

        Schema::create('property_unit_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('property_units')->cascadeOnDelete();
            $table->decimal('nsf_fees', 10, 2)->nullable();
            $table->string('fees_type', 50)->nullable();
            $table->decimal('fees', 10, 2)->nullable();
            $table->decimal('min_fees', 10, 2)->nullable();
            $table->decimal('max_fees', 10, 2)->nullable();
            $table->string('late_fee_type', 50)->nullable();
            $table->decimal('base_late_fee', 10, 2)->nullable();
            $table->integer('grace_period')->nullable();
            $table->timestamps();
        });

        Schema::create('property_unit_lease_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('property_units')->cascadeOnDelete();
            $table->string('lease_template')->nullable();
            $table->string('default_method', 100)->nullable();
            $table->string('lease_fee_type', 50)->nullable();
            $table->decimal('lease_fee_percentage', 8, 4)->nullable();
            $table->string('renewal_fee_type', 50)->nullable();
            $table->decimal('renewal_fee_percentage', 8, 4)->nullable();
            $table->timestamps();
        });

        Schema::create('property_unit_maintenance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('property_units')->cascadeOnDelete();
            $table->boolean('covered_by_warranty')->default(false);
            $table->boolean('entry_pre_authorized')->default(false);
            $table->text('notes')->nullable();
            $table->text('instructions')->nullable();
            $table->timestamps();
        });

        Schema::create('property_unit_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('property_units')->cascadeOnDelete();
            $table->boolean('waive_fees_when_vacant')->default(false);
            $table->timestamps();
        });

        Schema::create('property_assigners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->string('title', 20)->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number', 30)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('property_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->string('gl_code', 20)->nullable();
            $table->string('gl_level', 100)->nullable();
            $table->string('account_level', 150)->nullable();
            $table->string('bank_account_name', 200)->nullable();
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->timestamps();
        });

        Schema::create('property_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->string('image_type', 50)->nullable();
            $table->foreignId('master_document_id')->nullable()->constrained('master_documents')->nullOnDelete();
            $table->string('type', 50)->nullable();
            $table->string('upload_status', 50)->default('pending');
            $table->timestamps();
        });

        Schema::create('property_groups_included', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->foreignId('property_group_id')->constrained('property_groups')->cascadeOnDelete();
            $table->unique(['property_id', 'property_group_id']);
        });

        Schema::create('owner_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->dateTime('generated_at');
            $table->timestamps();
        });

        Schema::create('tenant_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained('property_units')->cascadeOnDelete();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->date('lease_start')->nullable();
            $table->date('lease_end')->nullable();
            $table->decimal('monthly_rent', 10, 2)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->enum('status', ['active', 'past', 'future', 'eviction'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_units');
        Schema::dropIfExists('owner_properties');
        Schema::dropIfExists('property_groups_included');
        Schema::dropIfExists('property_documents');
        Schema::dropIfExists('property_bank_accounts');
        Schema::dropIfExists('property_assigners');
        Schema::dropIfExists('property_unit_settings');
        Schema::dropIfExists('property_unit_maintenance');
        Schema::dropIfExists('property_unit_lease_settings');
        Schema::dropIfExists('property_unit_fees');
        Schema::dropIfExists('property_unit_documents');
        Schema::dropIfExists('property_unit_amenities');
        Schema::dropIfExists('property_units');
        Schema::dropIfExists('properties');
    }
};
