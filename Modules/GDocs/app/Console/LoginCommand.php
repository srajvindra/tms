<?php

namespace Modules\GDocs\Console;

use Illuminate\Console\Command;
use Modules\GDocs\Services\GDocsDownloader;

class LoginCommand extends Command
{
    protected $signature = 'gdocs:login';

    protected $description = 'Open a normal Chrome window with the GDocs profile so you can sign in to Google once. Cookies persist for later downloads.';

    public function handle(GDocsDownloader $downloader): int
    {
        $downloader->openSignInWindow(fn (string $message) => $this->line($message));

        $this->newLine();
        $this->info('Chrome closed. Now run: php artisan gdocs:download');

        return self::SUCCESS;
    }
}
