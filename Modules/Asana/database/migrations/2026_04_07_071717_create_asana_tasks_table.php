<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asana_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('gid')->unique();
            $table->string('resource_type')->default('task');
            $table->string('resource_subtype')->nullable();

            // Content
            $table->string('name');
            $table->text('notes')->nullable();
            $table->text('html_notes')->nullable();

            // Status
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->string('completed_by')->nullable();
            $table->string('approval_status')->nullable();

            // Dates
            $table->date('due_on')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->date('start_on')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('asana_created_at')->nullable();
            $table->timestamp('asana_modified_at')->nullable();

            // Assignee
            $table->string('assignee_gid')->nullable();
            $table->string('assignee_name')->nullable();
            $table->string('assignee_email')->nullable();
            $table->string('assignee_status')->nullable();

            // Hierarchy
            $table->string('parent_gid')->nullable();
            $table->string('parent_name')->nullable();
            $table->integer('num_subtasks')->default(0);

            // Project / section (primary membership)
            $table->string('project_gid')->index();
            $table->string('project_name')->nullable();
            $table->string('section_name')->nullable();

            // Workspace
            $table->string('workspace_gid')->nullable();
            $table->string('workspace_name')->nullable();

            // Engagement
            $table->boolean('liked')->default(false);
            $table->integer('num_likes')->default(0);

            // Misc
            $table->string('permalink_url')->nullable();
            $table->string('external_id')->nullable();

            // Arrays stored as JSON
            $table->json('followers')->nullable();
            $table->json('tags')->nullable();
            $table->json('projects')->nullable();
            $table->json('memberships')->nullable();
            $table->json('custom_fields')->nullable();
            $table->json('dependencies')->nullable();
            $table->json('dependents')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asana_tasks');
    }
};
