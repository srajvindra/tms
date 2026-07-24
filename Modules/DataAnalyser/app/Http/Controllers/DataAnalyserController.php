<?php

namespace Modules\DataAnalyser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Modules\DataAnalyser\Http\Requests\ClassifyUrlsRequest;
use Modules\DataAnalyser\Services\UrlClassifier;

class DataAnalyserController extends Controller
{
    public function index(UrlClassifier $classifier): View
    {
        return view('dataanalyser::index', [
            'categories' => $classifier->categories(),
            'result' => null,
        ]);
    }

    public function classify(ClassifyUrlsRequest $request, UrlClassifier $classifier): View
    {
        return view('dataanalyser::index', [
            'categories' => $classifier->categories(),
            'result' => $classifier->classifyTsv($request->tsvContent()),
        ]);
    }

    public function classifyApi(ClassifyUrlsRequest $request, UrlClassifier $classifier): JsonResponse
    {
        return response()->json($classifier->classifyTsv($request->tsvContent()));
    }
}
