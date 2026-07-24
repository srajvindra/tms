<?php

use Illuminate\Support\Facades\Route;
use Modules\WorldMap\Http\Controllers\WorldMapController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('worldmaps', WorldMapController::class)->names('worldmap');
});
