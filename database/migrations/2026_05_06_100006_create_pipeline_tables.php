<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'b2b_schema';

    public function up(): void
    {
        Schema::create('pipelines', function (Blueprint $table) {
            $table->id();
            $table->string('pid', 20)->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_current')->default(true);
            $table->string('pipeline_name', 150);
            $table->enum('type', ['owner', 'lease', 'main'])->default('owner');
            $table->string('overview_title')->nullable();
            $table->text('overview_content')->nullable();
            $table->string('singular_name')->nullable();
            $table->boolean('status')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['pid', 'version']);
            $table->index(['pid', 'is_current']);
        });

        Schema::create('pipeline_stages', function (Blueprint $table) {
            $table->id();
            $table->string('psid', 20)->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_current')->default(true);
            $table->foreignId('pipeline_id')->nullable()->constrained('pipelines')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('type', 50)->default('owner');
            $table->string('status', 50)->default('active');
            $table->integer('stage_order')->default(0);
            $table->string('color', 30)->nullable();
            $table->string('default_color', 30)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['psid', 'version']);
            $table->index(['psid', 'is_current']);
        });

        Schema::create('pipeline_stage_workflow_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pipeline_stage_id');
            $table->foreign('pipeline_stage_id', 'psws_stage_fk')->references('id')->on('pipeline_stages')->cascadeOnDelete();
            $table->string('from_day')->nullable();
            $table->string('to_day', 50)->nullable();
            $table->string('from_time', 100)->nullable();
            $table->string('to_time', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pipeline_stage_steps', function (Blueprint $table) {
            $table->id();
            $table->string('step_uid', 20)->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_current')->default(true);
            $table->unsignedBigInteger('pipeline_stage_id');
            $table->unsignedBigInteger('pipeline_stage_step_master_id')->nullable();
            $table->string('name');
            $table->string('step_type', 50)->nullable();
            $table->string('timing', 100)->nullable();
            $table->unsignedInteger('delay')->nullable();
            $table->string('unit', 50)->nullable();
            $table->unsignedInteger('day')->nullable();
            $table->unsignedBigInteger('target_stage_id')->nullable();
            $table->unsignedBigInteger('target_pipeline_id')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['step_uid', 'version']);
            $table->index(['step_uid', 'is_current']);
            $table->foreign('pipeline_stage_id', 'pss_stage_fk')->references('id')->on('pipeline_stages')->cascadeOnDelete();
            $table->foreign('pipeline_stage_step_master_id', 'pss_master_fk')->references('id')->on('global_steps')->nullOnDelete();
            $table->foreign('target_stage_id', 'pss_target_stage_fk')->references('id')->on('pipeline_stages')->nullOnDelete();
            $table->foreign('target_pipeline_id', 'pss_target_pipeline_fk')->references('id')->on('pipelines')->nullOnDelete();
        });

        Schema::create('pipeline_stage_step_assign_staff', function (Blueprint $table) {
            $table->id();
            $table->string('assign_uid', 20)->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_current')->default(true);
            $table->unsignedBigInteger('pipeline_stage_step_id');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            $table->unique(['assign_uid', 'version'], 'pssas_uid_ver_unique');
            $table->foreign('pipeline_stage_step_id', 'pssas_step_fk')->references('id')->on('pipeline_stage_steps')->cascadeOnDelete();
        });

        Schema::create('pipeline_stage_step_connect_params', function (Blueprint $table) {
            $table->id();
            $table->string('param_uid', 20)->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_current')->default(true);
            $table->unsignedBigInteger('pipeline_stage_step_id');
            $table->unsignedBigInteger('global_step_param_id');
            $table->timestamps();
            $table->unique(['param_uid', 'version'], 'psscp_uid_ver_unique');
            $table->foreign('pipeline_stage_step_id', 'psscp_step_fk')->references('id')->on('pipeline_stage_steps')->cascadeOnDelete();
            $table->foreign('global_step_param_id', 'psscp_param_fk')->references('id')->on('global_step_params')->cascadeOnDelete();
        });

        Schema::create('pipeline_stage_step_display_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('cond_uid', 20)->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_current')->default(true);
            $table->unsignedBigInteger('pipeline_stage_step_id');
            $table->string('display_condition')->nullable();
            $table->string('field_type')->nullable();
            $table->enum('operator', ['is', 'isnot', 'contain', 'doesnotcontain'])->nullable();
            $table->string('value')->nullable();
            $table->timestamps();
            $table->unique(['cond_uid', 'version'], 'pssdc_uid_ver_unique');
            $table->foreign('pipeline_stage_step_id', 'pssdc_step_fk')->references('id')->on('pipeline_stage_steps')->cascadeOnDelete();
        });

        Schema::create('pipeline_stage_step_escalation_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_uid', 20)->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_current')->default(true);
            $table->string('escalatable_type', 100)->nullable();
            $table->unsignedBigInteger('escalatable_id')->nullable();
            $table->unsignedBigInteger('pipeline_stage_step_id')->nullable();
            $table->string('type', 100)->nullable();
            $table->unsignedInteger('time')->nullable();
            $table->string('time_unit', 50)->nullable();
            $table->unsignedBigInteger('risk_id')->nullable();
            $table->boolean('immediately')->default(false);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['rule_uid', 'version'], 'psser_uid_ver_unique');
            $table->index(['escalatable_type', 'escalatable_id'], 'psser_escalatable_idx');
            $table->foreign('pipeline_stage_step_id', 'psser_step_fk')->references('id')->on('pipeline_stage_steps')->nullOnDelete();
            $table->foreign('risk_id', 'psser_risk_fk')->references('id')->on('risk')->nullOnDelete();
        });

        Schema::create('pipeline_stage_step_escalation_user', function (Blueprint $table) {
            $table->id();
            $table->string('euser_uid', 20)->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_current')->default(true);
            $table->unsignedBigInteger('pipeline_stage_step_escalation_rules_id');
            $table->unsignedBigInteger('pipeline_stage_step_id')->nullable();
            $table->string('type', 50)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->timestamps();
            $table->unique(['euser_uid', 'version'], 'psseu_uid_ver_unique');
            $table->foreign('pipeline_stage_step_escalation_rules_id', 'psseu_rule_fk')->references('id')->on('pipeline_stage_step_escalation_rules')->cascadeOnDelete();
            $table->foreign('pipeline_stage_step_id', 'psseu_step_fk')->references('id')->on('pipeline_stage_steps')->nullOnDelete();
            $table->foreign('user_id', 'psseu_user_fk')->references('id')->on('users')->nullOnDelete();
            $table->foreign('role_id', 'psseu_role_fk')->references('id')->on('roles')->nullOnDelete();
        });

        Schema::create('pipeline_stage_step_instructions', function (Blueprint $table) {
            $table->id();
            $table->string('instr_uid', 20)->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_current')->default(true);
            $table->unsignedBigInteger('pipeline_stage_step_id');
            $table->text('instruction_message')->nullable();
            $table->timestamps();
            $table->unique(['instr_uid', 'version'], 'pssin_uid_ver_unique');
            $table->foreign('pipeline_stage_step_id', 'pssin_step_fk')->references('id')->on('pipeline_stage_steps')->cascadeOnDelete();
        });

        Schema::create('pipeline_stage_step_template_links', function (Blueprint $table) {
            $table->id();
            $table->string('tlink_uid', 20)->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_current')->default(true);
            $table->unsignedBigInteger('pipeline_stage_step_id');
            $table->unsignedBigInteger('template_id');
            $table->timestamps();
            $table->unique(['tlink_uid', 'version'], 'psstl_uid_ver_unique');
            $table->foreign('pipeline_stage_step_id', 'psstl_step_fk')->references('id')->on('pipeline_stage_steps')->cascadeOnDelete();
            $table->foreign('template_id', 'psstl_tpl_fk')->references('id')->on('templates')->cascadeOnDelete();
        });

        Schema::create('pipeline_stage_step_time_limits', function (Blueprint $table) {
            $table->id();
            $table->string('tlimit_uid', 20)->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_current')->default(true);
            $table->unsignedBigInteger('pipeline_stage_step_id');
            $table->unsignedBigInteger('time')->nullable();
            $table->timestamps();
            $table->unique(['tlimit_uid', 'version'], 'psstl2_uid_ver_unique');
            $table->foreign('pipeline_stage_step_id', 'psstl2_step_fk')->references('id')->on('pipeline_stage_steps')->cascadeOnDelete();
        });

        Schema::create('pipeline_api_connections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_response_id');
            $table->foreignId('pipeline_id')->constrained('pipelines')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pipeline_api_connections');
        Schema::dropIfExists('pipeline_stage_step_time_limits');
        Schema::dropIfExists('pipeline_stage_step_template_links');
        Schema::dropIfExists('pipeline_stage_step_instructions');
        Schema::dropIfExists('pipeline_stage_step_escalation_user');
        Schema::dropIfExists('pipeline_stage_step_escalation_rules');
        Schema::dropIfExists('pipeline_stage_step_display_conditions');
        Schema::dropIfExists('pipeline_stage_step_connect_params');
        Schema::dropIfExists('pipeline_stage_step_assign_staff');
        Schema::dropIfExists('pipeline_stage_steps');
        Schema::dropIfExists('pipeline_stage_workflow_settings');
        Schema::dropIfExists('pipeline_stages');
        Schema::dropIfExists('pipelines');
    }
};
