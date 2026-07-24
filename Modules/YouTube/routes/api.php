<?php

use Illuminate\Support\Facades\Route;
use Modules\YouTube\Http\Controllers\YouTubeController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('youtubes', YouTubeController::class)->names('youtube');
});
