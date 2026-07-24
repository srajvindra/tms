<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'b2b_schema';

    public function up(): void
    {
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('label_name', 200);
            $table->enum('field_type', ['text', 'number', 'currency', 'percentage', 'multiple_choice', 'date', 'url_website', 'checkbox'])->default('text');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_required')->default(false);
            $table->string('default_value', 500)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('custom_field_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_field_id')->constrained('custom_fields')->cascadeOnDelete();
            $table->string('option_value', 200);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('custom_field_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('original_custom_field_id')->constrained('custom_fields')->cascadeOnDelete();
            $table->string('label_name', 200);
            $table->string('field_type', 50);
            $table->boolean('is_required')->default(false);
            $table->string('default_value', 500)->nullable();
            $table->string('entity_category', 100)->nullable();
            $table->string('entity_name', 100)->nullable();
            $table->string('section_name', 200)->nullable();
            $table->timestamps();
            $table->unique(['original_custom_field_id', 'entity_category', 'entity_name', 'section_name'], 'cfv_orig_entity_section_unique');
        });

        Schema::create('custom_field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_field_clone_id')->constrained('custom_field_versions')->cascadeOnDelete();
            $table->string('entity_category', 100)->nullable();
            $table->string('entity_name', 100)->nullable();
            $table->string('section_name', 200)->nullable();
            $table->unsignedBigInteger('record_id');
            $table->text('value')->nullable();
            $table->timestamps();
            $table->unique(['custom_field_clone_id', 'record_id']);
        });

        Schema::create('custom_field_version_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_field_clone_id')->constrained('custom_field_versions')->cascadeOnDelete();
            $table->string('option_value', 200);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('custom_field_version_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_field_clone_id')->constrained('custom_field_versions')->cascadeOnDelete();
            $table->enum('scope', ['pipeline', 'process']);
            $table->unsignedBigInteger('pipeline_id')->nullable();
            $table->unsignedBigInteger('process_id')->nullable();
            $table->timestamps();
        });

        Schema::create('custom_field_process_map', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_field_id')->constrained('custom_fields')->cascadeOnDelete();
            $table->enum('scope', ['pipeline', 'process']);
            $table->foreignId('pipeline_id')->nullable()->constrained('pipelines')->nullOnDelete();
            $table->unsignedBigInteger('process_id')->nullable();
            $table->timestamps();
        });

        Schema::create('entity_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_field_id')->constrained('custom_fields')->cascadeOnDelete();
            $table->string('entity_category', 100)->nullable();
            $table->string('entity_name', 100)->nullable();
            $table->string('section_name', 200)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entity_types');
        Schema::dropIfExists('custom_field_process_map');
        Schema::dropIfExists('custom_field_version_processes');
        Schema::dropIfExists('custom_field_version_options');
        Schema::dropIfExists('custom_field_values');
        Schema::dropIfExists('custom_field_versions');
        Schema::dropIfExists('custom_field_options');
        Schema::dropIfExists('custom_fields');
    }
};
