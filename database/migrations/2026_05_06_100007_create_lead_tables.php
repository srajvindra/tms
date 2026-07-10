<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'b2b_schema';

    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('lid', 20)->nullable();
            $table->foreignId('pipeline_id')->nullable()->constrained('pipelines')->nullOnDelete();
            $table->foreignId('stage_id')->nullable()->constrained('pipeline_stages')->nullOnDelete();
            $table->unsignedBigInteger('pipeline_version_ref_id')->nullable();
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->unsignedBigInteger('contacts_id')->nullable();
            $table->string('customer_name', 200)->nullable();
            $table->boolean('email_send')->default(true);
            $table->string('email_id', 150)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('unit', 100)->nullable();
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('source', 100)->nullable();
            $table->dateTime('last_touch')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('lead_assign_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('lead_document', function (Blueprint $table) {
            $table->id();
            $table->string('ldid', 20)->nullable();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->unsignedBigInteger('document_id')->nullable();
            $table->string('document_name', 200)->nullable();
            $table->dateTime('uploadedDate')->nullable();
            $table->string('document_type', 100)->nullable();
            $table->text('comments')->nullable();
            $table->unsignedBigInteger('master_images_id')->nullable();
            $table->string('uploaded_status')->nullable();
            $table->string('file_type')->nullable();
            $table->foreignId('uploadedby')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assignedstaff')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 50)->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('lead_notes', function (Blueprint $table) {
            $table->id();
            $table->string('lnid', 20)->nullable();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('lead_properties', function (Blueprint $table) {
            $table->id();
            $table->string('lprid', 20)->nullable();
            $table->foreignId('property_id')->nullable()->constrained('properties')->nullOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('lead_stage', function (Blueprint $table) {
            $table->id();
            $table->string('lsid', 20)->nullable();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->foreignId('stage_id')->nullable()->constrained('pipeline_stages')->nullOnDelete();
            $table->unsignedBigInteger('pipeline_stages_id')->nullable();
            $table->string('color', 30)->nullable();
            $table->string('name', 150)->nullable();
            $table->text('description')->nullable();
            $table->string('type', 50)->nullable();
            $table->string('status', 50)->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('lead_task', function (Blueprint $table) {
            $table->id();
            $table->string('ltid', 20)->nullable();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->foreignId('source_step_id')->nullable()->constrained('pipeline_stage_steps')->nullOnDelete();
            $table->unsignedBigInteger('workflow_id')->nullable();
            $table->string('tasktype', 50)->nullable();
            $table->string('task_name', 200)->nullable();
            $table->dateTime('due_date')->nullable();
            $table->string('priority', 50)->nullable();
            $table->string('status', 50)->default('active');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('completed_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('activity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->string('activity_name', 150)->nullable();
            $table->dateTime('activity_date')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('ping')->default(true);
            $table->text('description')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('activity_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action_type', 100)->nullable();
            $table->string('entity', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('source', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_log');
        Schema::dropIfExists('activity');
        Schema::dropIfExists('lead_task');
        Schema::dropIfExists('lead_stage');
        Schema::dropIfExists('lead_properties');
        Schema::dropIfExists('lead_notes');
        Schema::dropIfExists('lead_document');
        Schema::dropIfExists('lead_assign_user');
        Schema::dropIfExists('leads');
    }
};
