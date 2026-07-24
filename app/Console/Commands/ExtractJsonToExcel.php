<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;

class ExtractJsonToExcel extends Command
{
    protected $signature = 'extract:json-to-excel {file} {--output=}';
    
    protected $description = 'Extract specific columns from JSON file to Excel';

    public function handle(): void
    {
        $filePath = $this->argument('file');
        $outputPath = $this->option('output') ?: 'extracted_tickets.xlsx';
        
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return;
        }
        
        $this->info('Processing JSON file...');
        
        $extractedData = $this->extractDataFromJson($filePath);
        
        $this->info('Creating Excel file...');
        
        Excel::store(new TicketExport($extractedData), $outputPath);
        
        $this->info("Excel file created: {$outputPath}");
        $this->info("Total records processed: " . count($extractedData));
    }
    
    private function extractDataFromJson(string $filePath): array
    {
        $extractedData = [];
        $file = fopen($filePath, 'r');
        
        if (!$file) {
            throw new \Exception("Unable to open file: {$filePath}");
        }
        
        while (($line = fgets($file)) !== false) {
            $ticket = json_decode(trim($line), true);
            
            if (!$ticket) {
                continue;
            }
            
            // Get submitter info
            $submitter = $ticket['submitter'] ?? null;
            $submitterName = $submitter['name'] ?? '';
            $submitterEmail = $submitter['email'] ?? '';
            
            // Get assignee info
            $assignee = $ticket['assignee'] ?? null;
            $assigneeName = $assignee['name'] ?? '';
            $assigneeEmail = $assignee['email'] ?? '';
            $assigneeCreatedAt = $assignee['created_at'] ?? '';
            $assigneeUpdatedAt = $assignee['updated_at'] ?? '';
            $assigneePhotoUrl = $assignee['photo']['content_url'] ?? '';
            
            // Get comments
            $comments = $ticket['comments'] ?? [];
            $maxComments = max(count($comments), 1);
            
            // Base row data
            $rowData = [
                'created_at' => $ticket['created_at'] ?? '',
                'updated_at' => $ticket['updated_at'] ?? '',
                'subject' => $ticket['subject'] ?? '',
                'description' => $ticket['description'] ?? '',
                'status' => $ticket['status'] ?? '',
                'submitter_name' => $submitterName,
                'submitter_email' => $submitterEmail,
                'assignee_name' => $assigneeName,
                'assignee_email' => $assigneeEmail,
                'assignee_created_at' => $assigneeCreatedAt,
                'assignee_updated_at' => $assigneeUpdatedAt,
                'assignee_photo_url' => $assigneePhotoUrl,
            ];
            
            // Add comment data dynamically
            foreach ($comments as $index => $comment) {
                $commentNumber = $index + 1;
                $rowData["comment_{$commentNumber}_body"] = $comment['body'] ?? '';
                $rowData["comment_{$commentNumber}_created_at"] = $comment['created_at'] ?? '';
            }
            
            $extractedData[] = $rowData;
        }
        
        fclose($file);
        
        return $extractedData;
    }
}

class TicketExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    private array $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function collection(): Collection
    {
        return collect($this->data);
    }
    
    public function headings(): array
    {
        if (empty($this->data)) {
            return [];
        }
        
        // Get all possible column names from all rows
        $allKeys = [];
        foreach ($this->data as $row) {
            $allKeys = array_merge($allKeys, array_keys($row));
        }
        
        $uniqueKeys = array_unique($allKeys);
        
        // Sort to ensure consistent column order
        $baseColumns = [
            'created_at',
            'updated_at', 
            'subject',
            'description',
            'status',
            'submitter_name',
            'submitter_email',
            'assignee_name',
            'assignee_email',
            'assignee_created_at',
            'assignee_updated_at',
            'assignee_photo_url'
        ];
        
        $commentColumns = array_filter($uniqueKeys, function($key) {
            return strpos($key, 'comment_') === 0;
        });
        
        // Sort comment columns naturally (comment_1_body, comment_2_body, etc.)
        natsort($commentColumns);
        
        return array_merge($baseColumns, array_values($commentColumns));
    }
}