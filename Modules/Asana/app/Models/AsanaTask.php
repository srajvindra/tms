<?php

namespace Modules\Asana\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsanaTask extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'gid',
        'resource_type',
        'resource_subtype',
        'name',
        'notes',
        'html_notes',
        'completed',
        'completed_at',
        'completed_by',
        'approval_status',
        'due_on',
        'due_at',
        'start_on',
        'start_at',
        'asana_created_at',
        'asana_modified_at',
        'assignee_gid',
        'assignee_name',
        'assignee_email',
        'assignee_status',
        'parent_gid',
        'parent_name',
        'num_subtasks',
        'project_gid',
        'project_name',
        'section_name',
        'workspace_gid',
        'workspace_name',
        'liked',
        'num_likes',
        'permalink_url',
        'external_id',
        'followers',
        'tags',
        'projects',
        'memberships',
        'custom_fields',
        'dependencies',
        'dependents',
    ];

    protected function casts(): array
    {
        return [
            'completed'          => 'boolean',
            'liked'              => 'boolean',
            'completed_at'       => 'datetime',
            'due_at'             => 'datetime',
            'start_at'           => 'datetime',
            'asana_created_at'   => 'datetime',
            'asana_modified_at'  => 'datetime',
            'due_on'             => 'date',
            'start_on'           => 'date',
            'num_subtasks'       => 'integer',
            'num_likes'          => 'integer',
            'followers'          => 'array',
            'tags'               => 'array',
            'projects'           => 'array',
            'memberships'        => 'array',
            'custom_fields'      => 'array',
            'dependencies'       => 'array',
            'dependents'         => 'array',
        ];
    }
}
