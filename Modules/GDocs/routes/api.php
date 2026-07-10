<?php

use Illuminate\Support\Facades\Route;
use Modules\GDocs\Http\Controllers\GDocsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('gdocs', GDocsController::class)->names('gdocs');
});
