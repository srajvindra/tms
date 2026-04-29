<?php

use Illuminate\Support\Facades\Route;
use Modules\Asana\Http\Controllers\AsanaController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('asanas', AsanaController::class)->names('asana');
});
