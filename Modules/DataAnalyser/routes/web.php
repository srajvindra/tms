<?php

use Illuminate\Support\Facades\Route;
use Modules\DataAnalyser\Http\Controllers\DataAnalyserController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('data-analyser', [DataAnalyserController::class, 'index'])
        ->name('dataanalyser.index');
    Route::post('data-analyser/classify', [DataAnalyserController::class, 'classify'])
        ->name('dataanalyser.classify');
});
