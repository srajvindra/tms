<?php

use Illuminate\Support\Facades\Route;
use Modules\DataAnalyser\Http\Controllers\DataAnalyserController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::post('data-analyser/classify', [DataAnalyserController::class, 'classifyApi'])
        ->name('dataanalyser.classify');
});
