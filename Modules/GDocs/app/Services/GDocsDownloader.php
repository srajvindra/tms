<?php

namespace Modules\GDocs\Services;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use RuntimeException;
use Symfony\Component\Process\Process;

class GDocsDownloader
{
    private string $profileDir;

    private string $downloadDir;

    private string $linksFile;

    private string $chromeDriverUrl = 'http://localhost:9515';

    private int $downloadTimeoutSeconds = 120;

    private ?Process $driverProcess = null;

    public function __construct()
    {
        $this->profileDir = storage_path('app/gdocs/chrome-profile');
        $this->downloadDir = storage_path('app/gdocs/downloads');
        $this->linksFile = module_path('GDocs', 'resources/links.txt');
    }

    /**
     * @return array{status: string, downloaded: array<int, array{url: string, file: string}>, failed: array<int, array{url: string, reason: string}>, message?: string}
     */
    public function download(?callable $onProgress = null): array
    {
        $this->ensureDirectories();
        $links = $this->readLinks();

        if ($links === []) {
            return [
                'status' => 'no_links',
                'downloaded' => [],
                'failed' => [],
                'message' => 'No links found in '.$this->linksFile,
            ];
        }

        $this->ensureChromeDriverRunning();

        $driver = $this->makeDriver();
        $downloaded = [];
        $failed = [];

        try {
            $this->ensureLoggedIn($driver);

            foreach ($links as $url) {
                $docId = $this->extractDocId($url);

                if ($docId === null) {
                    $failed[] = ['url' => $url, 'reason' => 'invalid_google_doc_url'];
                    $this->report($onProgress, "skip (invalid URL): {$url}");

                    continue;
                }

                $this->report($onProgress, "downloading: {$url}");

                $before = $this->snapshotDownloadDir();
                $exportUrl = "https://docs.google.com/document/d/{$docId}/export?format=docx";
                $driver->get($exportUrl);

                $newFile = $this->waitForNewDownload($before);

                if ($newFile === null) {
                    $failed[] = ['url' => $url, 'reason' => 'download_timeout'];
                    $this->report($onProgress, "failed (timeout): {$url}");
                } else {
                    $downloaded[] = ['url' => $url, 'file' => $newFile];
                    $this->report($onProgress, "saved: {$newFile}");
                }
            }
        } finally {
            $driver->quit();
            $this->stopChromeDriver();
        }

        return [
            'status' => 'ok',
            'downloaded' => $downloaded,
            'failed' => $failed,
        ];
    }

    private function ensureDirectories(): void
    {
        foreach ([$this->profileDir, $this->downloadDir] as $dir) {
            if (! is_dir($dir) && ! mkdir($dir, 0775, true) && ! is_dir($dir)) {
                throw new RuntimeException("Could not create directory: {$dir}");
            }
        }
    }

    /**
     * @return array<int, string>
     */
    private function readLinks(): array
    {
        if (! is_file($this->linksFile)) {
            return [];
        }

        $lines = file($this->linksFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

        return collect($lines)
            ->map(fn ($line) => trim($line))
            ->reject(fn ($line) => $line === '' || str_starts_with($line, '#'))
            ->values()
            ->all();
    }

    private function ensureChromeDriverRunning(): void
    {
        if ($this->canConnectToDriver()) {
            return;
        }

        $binary = $this->resolveChromeDriverBinary();

        $this->driverProcess = new Process([$binary, '--port=9515']);
        $this->driverProcess->setTimeout(null);
        $this->driverProcess->start();

        $deadline = time() + 10;
        while (time() < $deadline) {
            if ($this->canConnectToDriver()) {
                return;
            }
            usleep(250_000);
        }

        throw new RuntimeException('ChromeDriver did not become reachable on port 9515 within 10 seconds.');
    }

    private function canConnectToDriver(): bool
    {
        $socket = @fsockopen('127.0.0.1', 9515, $errno, $errstr, 1);

        if ($socket === false) {
            return false;
        }

        fclose($socket);

        return true;
    }

    private function resolveChromeDriverBinary(): string
    {
        $candidates = [
            base_path('vendor/laravel/dusk/bin/chromedriver-linux'),
            base_path('vendor/laravel/dusk/bin/chromedriver-linux64'),
        ];

        foreach ($candidates as $path) {
            if (is_file($path)) {
                if (! is_executable($path)) {
                    @chmod($path, 0755);
                }

                return $path;
            }
        }

        throw new RuntimeException(
            'No chromedriver binary found in vendor/laravel/dusk/bin. Run: php artisan dusk:chrome-driver'
        );
    }

    private function stopChromeDriver(): void
    {
        if ($this->driverProcess instanceof Process && $this->driverProcess->isRunning()) {
            $this->driverProcess->stop(5);
        }
    }

    private function makeDriver(): RemoteWebDriver
    {
        $options = new ChromeOptions;
        $options->addArguments([
            '--user-data-dir='.$this->profileDir,
            '--password-store=basic',
            '--no-first-run',
            '--no-default-browser-check',
            '--disable-extensions',
            '--disable-gpu',
            '--window-size=1280,900',
            '--disable-blink-features=AutomationControlled',
        ]);
        $options->setExperimentalOption('prefs', [
            'download.default_directory' => $this->downloadDir,
            'download.prompt_for_download' => false,
            'download.directory_upgrade' => true,
            'safebrowsing.enabled' => true,
            'profile.default_content_settings.popups' => 0,
        ]);
        $options->setExperimentalOption('excludeSwitches', ['enable-automation']);
        $options->setExperimentalOption('useAutomationExtension', false);

        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        return RemoteWebDriver::create($this->chromeDriverUrl, $capabilities, 30_000, 30_000);
    }

    private function ensureLoggedIn(RemoteWebDriver $driver): void
    {
        $driver->get('https://docs.google.com/document/u/0/');
        sleep(2);

        $currentUrl = $driver->getCurrentURL();

        if (str_contains($currentUrl, 'docs.google.com/document')) {
            return;
        }

        throw new RuntimeException(
            'Not signed in to Google. Ended up at: '.$currentUrl
            .' — Run "php artisan gdocs:login" first (after wiping storage/app/gdocs/chrome-profile if needed).'
        );
    }

    public function openSignInWindow(?callable $onProgress = null): int
    {
        $this->ensureDirectories();

        $chrome = $this->resolveChromeBinary();

        $this->report($onProgress, "Opening Chrome with profile: {$this->profileDir}");
        $this->report($onProgress, 'Log in to Google, then close the Chrome window to continue.');

        $process = new Process([
            $chrome,
            '--user-data-dir='.$this->profileDir,
            '--password-store=basic',
            '--no-first-run',
            '--no-default-browser-check',
            'https://accounts.google.com/',
        ]);
        $process->setTimeout(null);
        $process->run();

        return $process->getExitCode() ?? 0;
    }

    private function resolveChromeBinary(): string
    {
        foreach (['google-chrome', 'google-chrome-stable', 'chromium', 'chromium-browser'] as $name) {
            $path = trim((string) shell_exec('command -v '.escapeshellarg($name).' 2>/dev/null'));
            if ($path !== '' && is_executable($path)) {
                return $path;
            }
        }

        throw new RuntimeException('Could not find Chrome on PATH (looked for google-chrome, chromium, etc.).');
    }

    private function extractDocId(string $url): ?string
    {
        if (preg_match('#/document/d/([a-zA-Z0-9_-]+)#', $url, $matches) === 1) {
            return $matches[1];
        }

        return null;
    }

    /**
     * @return array<string, true>
     */
    private function snapshotDownloadDir(): array
    {
        $files = glob($this->downloadDir.'/*') ?: [];

        return array_fill_keys($files, true);
    }

    /**
     * @param  array<string, true>  $before
     */
    private function waitForNewDownload(array $before): ?string
    {
        $deadline = time() + $this->downloadTimeoutSeconds;

        while (time() < $deadline) {
            sleep(1);

            $current = glob($this->downloadDir.'/*') ?: [];

            foreach ($current as $path) {
                if (isset($before[$path])) {
                    continue;
                }

                if (str_ends_with($path, '.crdownload')) {
                    continue;
                }

                if (is_file($path)) {
                    return basename($path);
                }
            }
        }

        return null;
    }

    private function report(?callable $onProgress, string $message): void
    {
        if ($onProgress !== null) {
            $onProgress($message);
        }
    }
}
