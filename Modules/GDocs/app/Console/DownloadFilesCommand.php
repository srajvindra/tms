<?php

namespace Modules\GDocs\Console;

use Illuminate\Console\Command;
use Modules\GDocs\Services\GDocsDownloader;

class DownloadFilesCommand extends Command
{
    protected $signature = 'gdocs:download';

    protected $description = 'Download every Google Doc in Modules/GDocs/resources/links.txt as DOCX via a persistent Chrome session.';

    public function handle(GDocsDownloader $downloader): int
    {
        $result = $downloader->download(fn (string $message) => $this->line($message));

        if ($result['status'] === 'no_links') {
            $this->warn($result['message'] ?? 'No links to process.');

            return self::SUCCESS;
        }

        $this->newLine();
        $this->info('Downloaded: '.count($result['downloaded']));
        $this->info('Failed:     '.count($result['failed']));

        foreach ($result['failed'] as $entry) {
            $this->warn("  - {$entry['url']} ({$entry['reason']})");
        }

        return $result['failed'] === [] ? self::SUCCESS : self::FAILURE;
    }
}
