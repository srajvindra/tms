<?php

use Illuminate\Support\Facades\Route;
use Modules\Asana\Http\Controllers\AsanaController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('asana/workspaces', [AsanaController::class, 'workspaces'])->name('asana.workspaces');
    Route::get('asana/workspaces/{workspaceGid}/projects', [AsanaController::class, 'projects'])->name('asana.projects');
    Route::get('asana/projects/{projectGid}/tasks', [AsanaController::class, 'tasks'])->name('asana.tasks');
});
