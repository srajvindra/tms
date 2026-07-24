<?php

namespace Modules\Tasks\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class JsonReaderController extends Controller
{
    /**
     * Display the JSON reader interface
     */
    public function index(): View
    {
        return view('tasks::json-reader.index');
    }

    /**
     * Read and display JSON file contents
     */
    public function read(Request $request): JsonResponse
    {
        $request->validate([
            'file_path' => 'required|string|max:255',
        ]);

        $filePath = $request->input('file_path');
        
        try {
            // Check file size first
            $actualPath = $this->findActualPath($filePath);
            $fileSize = filesize($actualPath);
            
            // If file is extremely large, provide a warning
            if ($fileSize > 500 * 1024 * 1024) { // 500MB
                return response()->json([
                    'success' => false,
                    'message' => "File is too large ({$this->formatBytes($fileSize)}). Maximum supported size is 500MB.",
                    'file_path' => $filePath
                ], 400);
            }
            
            // For large files, increase memory and time limits
            if ($fileSize > 50 * 1024 * 1024) { // 50MB
                ini_set('memory_limit', '2048M'); // 2GB for very large files
                set_time_limit(600); // 10 minutes
            }
            
            $data = $this->readJsonFile($filePath);
            
            // Automatically save the formatted JSON to a new file
            $savedFilePath = $this->saveFormattedJson($data, $filePath);
            
            // Limit the pretty JSON output for very large datasets
            $dataCount = is_array($data) ? count($data) : 1;
            $prettyJson = $dataCount > 100 ? 
                json_encode(array_slice($data, 0, 100), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n... (showing first 100 items of {$dataCount})" :
                json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            
            return response()->json([
                'success' => true,
                'message' => "JSON file read successfully. Contains {$dataCount} item(s). Formatted JSON saved to: {$savedFilePath}",
                'data' => is_array($data) && count($data) > 50 ? array_slice($data, 0, 50) : $data, // Limit data for frontend
                'file_path' => $filePath,
                'saved_file_path' => $savedFilePath,
                'file_size' => $this->formatBytes($fileSize),
                'item_count' => $dataCount,
                'pretty_json' => $prettyJson
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'file_path' => $filePath
            ], 400);
        }
    }

    /**
     * List available JSON files in storage
     */
    public function listFiles(): JsonResponse
    {
        try {
            $files = collect(Storage::disk('local')->allFiles())
                ->filter(fn($file) => str_ends_with(strtolower($file), '.json'))
                ->values()
                ->toArray();

            // Also check for JSON files in the project root
            $rootFiles = collect(glob(base_path('*.json')))
                ->map(fn($file) => str_replace(base_path('/'), '', $file))
                ->values()
                ->toArray();

            $allFiles = array_merge($files, $rootFiles);

            return response()->json([
                'success' => true,
                'files' => $allFiles
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to list JSON files: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Find the actual path of the file
     */
    private function findActualPath(string $filePath): string
    {
        $possiblePaths = [
            $filePath,
            storage_path('app/' . $filePath),
            base_path($filePath),
            public_path($filePath)
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path) && is_readable($path)) {
                return $path;
            }
        }

        throw new \Exception("File not found or not readable: {$filePath}");
    }

    /**
     * Format file size in human readable format
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Save formatted JSON data to a new file
     * Uses streaming for large datasets to manage memory efficiently
     */
    private function saveFormattedJson(array $data, string $originalFilePath): string
    {
        // Generate a new filename based on the original
        $pathInfo = pathinfo($originalFilePath);
        $basename = $pathInfo['filename'] ?? 'formatted';
        $timestamp = date('Y-m-d_H-i-s');
        $newFileName = "{$basename}_formatted_{$timestamp}.json";
        
        // Save to storage/app directory
        $savePath = storage_path('app/' . $newFileName);
        
        $dataCount = count($data);
        
        // For very large datasets, write streaming JSON to manage memory
        if ($dataCount > 10000) {
            return $this->saveFormattedJsonStreaming($data, $savePath, $newFileName);
        }
        
        // For smaller datasets, use regular JSON encoding
        $formattedJson = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        if ($formattedJson === false) {
            throw new \Exception("Failed to encode data as JSON: " . json_last_error_msg());
        }
        
        // Write to file
        $result = file_put_contents($savePath, $formattedJson);
        
        if ($result === false) {
            throw new \Exception("Failed to save formatted JSON to: {$savePath}");
        }
        
        return $newFileName;
    }
    
    /**
     * Save large JSON arrays using streaming to manage memory
     */
    private function saveFormattedJsonStreaming(array $data, string $savePath, string $fileName): string
    {
        $handle = fopen($savePath, 'w');
        if ($handle === false) {
            throw new \Exception("Cannot open file for writing: {$savePath}");
        }
        
        try {
            // Write opening bracket and first item
            fwrite($handle, "[\n");
            
            $totalItems = count($data);
            foreach ($data as $index => $item) {
                $jsonItem = json_encode($item, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                
                if ($jsonItem === false) {
                    throw new \Exception("Failed to encode item {$index} as JSON: " . json_last_error_msg());
                }
                
                // Indent the JSON item
                $indentedItem = "  " . str_replace("\n", "\n  ", $jsonItem);
                fwrite($handle, $indentedItem);
                
                // Add comma if not the last item
                if ($index < $totalItems - 1) {
                    fwrite($handle, ",");
                }
                fwrite($handle, "\n");
                
                // Free memory periodically
                if ($index % 1000 === 0) {
                    unset($data[$index]);
                }
            }
            
            // Write closing bracket
            fwrite($handle, "]");
            
        } finally {
            fclose($handle);
        }
        
        return $fileName;
    }

    /**
     * Read JSON file and return parsed data
     */
    private function readJsonFile(string $filePath): array
    {
        $actualPath = $this->findActualPath($filePath);

        // Read file contents
        $jsonContent = file_get_contents($actualPath);
        
        if ($jsonContent === false) {
            throw new \Exception("Failed to read file: {$filePath}");
        }

        // Try to decode as regular JSON first
        $data = json_decode($jsonContent, true);

        // If regular JSON fails, try NDJSON (newline-delimited JSON)
        if (json_last_error() !== JSON_ERROR_NONE) {
            // For very large files, process line by line to save memory
            if (strlen($jsonContent) > 50 * 1024 * 1024) { // 50MB
                return $this->parseNDJSONFromFile($actualPath);
            }
            
            $lines = explode("\n", trim($jsonContent));
            $data = [];
            $lineNumber = 0;
            
            foreach ($lines as $line) {
                $lineNumber++;
                $line = trim($line);
                if (empty($line)) continue;
                
                $lineData = json_decode($line, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $preview = strlen($line) > 100 ? substr($line, 0, 100) . '...' : $line;
                    throw new \Exception("JSON decode error on line {$lineNumber}: " . json_last_error_msg() . ". Line content: " . $preview);
                }
                $data[] = $lineData;
            }
            
            // If we successfully parsed NDJSON, reset the JSON error
            json_decode('{}'); // Reset json_last_error
        }

        return $data;
    }

    /**
     * Parse NDJSON from file line by line (memory efficient for large files)
     * Processes the entire file in batches to handle very large datasets
     */
    private function parseNDJSONFromFile(string $filePath): array
    {
        $data = [];
        $lineNumber = 0;
        $batchSize = 1000; // Process in batches of 1000 records
        $batchCount = 0;
        
        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            throw new \Exception("Cannot open file for reading: {$filePath}");
        }
        
        try {
            while (($line = fgets($handle)) !== false) {
                $lineNumber++;
                $line = trim($line);
                if (empty($line)) continue;
                
                $lineData = json_decode($line, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $preview = strlen($line) > 100 ? substr($line, 0, 100) . '...' : $line;
                    throw new \Exception("JSON decode error on line {$lineNumber}: " . json_last_error_msg() . ". Line content: " . $preview);
                }
                $data[] = $lineData;
                
                // Every batch, free up some memory and give status update
                if ($lineNumber % $batchSize === 0) {
                    $batchCount++;
                    // Force garbage collection periodically to manage memory
                    if ($batchCount % 10 === 0) {
                        gc_collect_cycles();
                    }
                }
            }
        } finally {
            fclose($handle);
        }
        
        return $data;
    }

    /**
     * Upload and read JSON file
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'json_file' => 'required|file|mimes:json,txt|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('json_file');
            $path = $file->store('json-uploads');
            
            $data = $this->readJsonFile(storage_path('app/' . $path));
            
            return response()->json([
                'success' => true,
                'message' => 'JSON file uploaded and read successfully',
                'data' => $data,
                'file_path' => $path,
                'pretty_json' => json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process uploaded file: ' . $e->getMessage()
            ], 400);
        }
    }
}