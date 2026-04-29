<?php

use Illuminate\Support\Facades\Route;
use Modules\SchemaAnalyser\Http\Controllers\SchemaAnalyserController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('schema-analyser', [SchemaAnalyserController::class, 'index'])
        ->name('schemaanalyser.index');
});
