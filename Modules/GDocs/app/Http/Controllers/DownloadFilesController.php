<?php

namespace Modules\GDocs\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\GDocs\Services\GDocsDownloader;

class DownloadFilesController extends Controller
{
    public function __invoke(GDocsDownloader $downloader): JsonResponse
    {
        $result = $downloader->download();

        return response()->json($result);
    }
}
