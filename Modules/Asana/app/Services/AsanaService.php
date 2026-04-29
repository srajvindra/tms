<?php

namespace Modules\Asana\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class AsanaService
{
    private string $baseUrl;
    private string $token;

    public function __construct()
    {
        $this->baseUrl = config('asana.base_url');
        $this->token = config('asana.token');
    }

    public function getWorkspaces(): array
    {
        $response = $this->get('/workspaces');

        return $response->json('data', []);
    }

    public function getProjectsByWorkspace(string $workspaceGid): array
    {
        $response = $this->get("/workspaces/{$workspaceGid}/projects");

        return $response->json('data', []);
    }

    public function getTasksByProject(string $projectGid): array
    {
        $fields = implode(',', [
            'gid',
            'resource_type',
            'resource_subtype',
            'name',
            'notes',
            'html_notes',
            'completed',
            'completed_at',
            'completed_by',
            'created_at',
            'modified_at',
            'due_on',
            'due_at',
            'start_on',
            'start_at',
            'assignee',
            'assignee.name',
            'assignee.email',
            'assignee_status',
            'followers',
            'followers.name',
            'parent',
            'parent.name',
            'projects',
            'projects.name',
            'memberships',
            'memberships.project.name',
            'memberships.section.name',
            'tags',
            'tags.name',
            'custom_fields',
            'custom_fields.name',
            'custom_fields.display_value',
            'custom_fields.type',
            'dependencies',
            'dependents',
            'num_subtasks',
            'num_likes',
            'liked',
            'likes',
            'permalink_url',
            'workspace',
            'workspace.name',
            'approval_status',
            'external',
        ]);

        $tasks = [];
        $query = ['opt_fields' => $fields, 'limit' => 100];

        do {
            $response = $this->get("/projects/{$projectGid}/tasks", $query);
            $tasks = array_merge($tasks, $response->json('data', []));
            $offset = $response->json('next_page.offset');
            $query['offset'] = $offset;

            sleep(10);
            
        } while ($offset !== null);

        return $tasks;
    }

    private function get(string $endpoint, array $query = []): Response
    {
        return Http::withToken($this->token)
            ->acceptJson()
            ->get($this->baseUrl . $endpoint, $query);
    }
}
