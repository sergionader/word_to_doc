<?php

namespace App\Livewire;

use App\Models\Conversion;
use App\Services\ConversionService;
use App\Services\FileSystemService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FileBrowser extends Component
{
    public string $currentPath = '';
    public string $viewMode = 'list';
    public array $items = [];
    public ?string $convertingFile = null;
    public ?string $conversionResult = null;
    public ?string $conversionError = null;

    protected FileSystemService $fileSystemService;

    public function boot(FileSystemService $fileSystemService): void
    {
        $this->fileSystemService = $fileSystemService;
    }

    public function mount(): void
    {
        $user = Auth::user();
        $lastFolder = $user->last_used_folder;

        if ($lastFolder && $this->fileSystemService->isValidPath($lastFolder) && is_dir($lastFolder)) {
            $this->currentPath = $lastFolder;
        } else {
            $this->currentPath = config('filesystems.browse_root', '/');
        }

        $this->loadDirectory();
    }

    public function navigateTo(string $path): void
    {
        if (!$this->fileSystemService->isValidPath($path)) {
            return;
        }

        $this->currentPath = $path;
        $this->loadDirectory();

        $user = Auth::user();
        $user->update(['last_used_folder' => $path]);
    }

    public function navigateUp(): void
    {
        $parent = $this->fileSystemService->getParentDirectory($this->currentPath);
        $this->navigateTo($parent);
    }

    public function toggleViewMode(): void
    {
        $this->viewMode = $this->viewMode === 'grid' ? 'list' : 'grid';
    }

    public function getQuickNavItems(): array
    {
        $items = [];
        $home = getenv('HOME') ?: '/Users/' . get_current_user();

        $icloudPath = $home . '/Library/Mobile Documents/com~apple~CloudDocs';
        if (is_dir($icloudPath) && $this->fileSystemService->isValidPath($icloudPath)) {
            $items[] = ['name' => 'iCloud Drive', 'path' => $icloudPath, 'icon' => 'cloud'];
        }

        $desktopPath = $home . '/Desktop';
        if (is_dir($desktopPath) && $this->fileSystemService->isValidPath($desktopPath)) {
            $items[] = ['name' => 'Desktop', 'path' => $desktopPath, 'icon' => 'desktop'];
        }

        $documentsPath = $home . '/Documents';
        if (is_dir($documentsPath) && $this->fileSystemService->isValidPath($documentsPath)) {
            $items[] = ['name' => 'Documents', 'path' => $documentsPath, 'icon' => 'folder'];
        }

        $downloadsPath = $home . '/Downloads';
        if (is_dir($downloadsPath) && $this->fileSystemService->isValidPath($downloadsPath)) {
            $items[] = ['name' => 'Downloads', 'path' => $downloadsPath, 'icon' => 'download'];
        }

        return $items;
    }

    public function convertFile(string $filePath): void
    {
        $this->conversionResult = null;
        $this->conversionError = null;

        if (!$this->fileSystemService->isValidPath($filePath)) {
            $this->conversionError = 'Invalid file path.';
            return;
        }

        $lowerPath = strtolower($filePath);
        if (!str_ends_with($lowerPath, '.docx') && !str_ends_with($lowerPath, '.md')) {
            $this->conversionError = 'Only .docx and .md files can be converted.';
            return;
        }

        $this->convertingFile = basename($filePath);
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $outputExtension = $extension === 'docx' ? '.md' : '.docx';

        try {
            $conversionService = app(ConversionService::class);
            $outputPath = $conversionService->convert($filePath);

            Conversion::create([
                'user_id' => Auth::id(),
                'source_path' => $filePath,
                'output_path' => $outputPath,
                'status' => 'completed',
            ]);

            $this->conversionResult = 'Converted successfully: ' . basename($outputPath);
            $this->loadDirectory();
        } catch (\Exception $e) {
            Conversion::create([
                'user_id' => Auth::id(),
                'source_path' => $filePath,
                'output_path' => pathinfo($filePath, PATHINFO_DIRNAME) . '/' . pathinfo($filePath, PATHINFO_FILENAME) . $outputExtension,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            $this->conversionError = 'Conversion failed: ' . $e->getMessage();
        }

        $this->convertingFile = null;
    }

    public function getBreadcrumbs(): array
    {
        $root = config('filesystems.browse_root', '/');
        $rootPrefix = rtrim($root, '/');
        $relativePath = substr($this->currentPath, strlen($rootPrefix));
        $parts = array_filter(explode('/', $relativePath));

        $breadcrumbs = [['name' => 'Root', 'path' => $root]];
        $currentBuildPath = $root;

        foreach ($parts as $part) {
            $currentBuildPath = rtrim($currentBuildPath, '/') . '/' . $part;
            $breadcrumbs[] = ['name' => $part, 'path' => $currentBuildPath];
        }

        return $breadcrumbs;
    }

    protected function loadDirectory(): void
    {
        $this->items = $this->fileSystemService->listDirectory($this->currentPath);
    }

    public function render()
    {
        return view('livewire.file-browser', [
            'breadcrumbs' => $this->getBreadcrumbs(),
            'quickNavItems' => $this->getQuickNavItems(),
        ])->layout('layouts.app');
    }
}
