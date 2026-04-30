<?php

namespace Modules\Asana\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Asana\Services\AsanaService;

class AsanaController extends Controller
{
    public function __construct(private AsanaService $asana) {}

    /**
     * Return a list of Asana workspaces for the authenticated token.
     */
    public function workspaces(): JsonResponse
    {
        $workspaces = $this->asana->getWorkspaces();

        return response()->json([
            'data' => $workspaces,
        ]);
    }

    /**
     * Return all projects in the given workspace.
     */
    public function projects(string $workspaceGid): JsonResponse
    {
        $projects = $this->asana->getProjectsByWorkspace($workspaceGid);

        return response()->json([
            'data' => $projects,
        ]);
    }

    /**
     * Return all tasks in the given project.
     */
    public function tasks(string $projectGid): JsonResponse
    {
        $tasks = $this->asana->getTasksByProject($projectGid);

        return response()->json([
            'data' => $tasks,
        ]);
    }
}
