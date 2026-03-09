<?php

namespace App\Livewire;

use App\Models\Conversion;
use App\Services\ConversionService;
use App\Services\FileSystemService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class FileBrowser extends Component
{
    public string $currentPath = '';
    public string $viewMode = 'list';
    public array $items = [];
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';
    public ?string $convertingFile = null;
    public ?string $conversionResult = null;
    public ?string $conversionError = null;
    public bool $showMarkdownPreview = false;
    public ?string $markdownHtml = null;
    public ?string $previewFileName = null;
    public ?string $previewFilePath = null;

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
        } elseif ($user->default_folder && $this->fileSystemService->isValidPath($user->default_folder) && is_dir($user->default_folder)) {
            $this->currentPath = $user->default_folder;
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

    public function refreshDirectory(): void
    {
        $this->loadDirectory();
    }

    public function toggleViewMode(): void
    {
        $this->viewMode = $this->viewMode === 'grid' ? 'list' : 'grid';
    }

    public function sortItems(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }

        $this->applySorting();
    }

    protected function applySorting(): void
    {
        usort($this->items, function ($a, $b) {
            // Directories always first
            if ($a['type'] !== $b['type']) {
                return $a['type'] === 'directory' ? -1 : 1;
            }

            $field = $this->sortBy;
            $direction = $this->sortDirection === 'asc' ? 1 : -1;

            if ($field === 'name') {
                return $direction * strcasecmp($a['name'], $b['name']);
            }

            return $direction * (($a[$field] ?? 0) <=> ($b[$field] ?? 0));
        });
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

    public function getPinnedFolders(): array
    {
        $user = Auth::user();
        $pinned = $user->pinned_folders ?? [];

        return array_filter($pinned, function ($folder) {
            return is_dir($folder['path']) && $this->fileSystemService->isValidPath($folder['path']);
        });
    }

    public function pinFolder(string $path): void
    {
        if (!$this->fileSystemService->isValidPath($path) || !is_dir($path)) {
            return;
        }

        $user = Auth::user();
        $pinned = $user->pinned_folders ?? [];

        foreach ($pinned as $folder) {
            if ($folder['path'] === $path) {
                return;
            }
        }

        $pinned[] = ['name' => basename($path), 'path' => $path];
        $user->update(['pinned_folders' => $pinned]);
    }

    public function unpinFolder(string $path): void
    {
        $user = Auth::user();
        $pinned = $user->pinned_folders ?? [];

        $pinned = array_values(array_filter($pinned, fn($f) => $f['path'] !== $path));
        $user->update(['pinned_folders' => $pinned]);
    }

    public function isFolderPinned(string $path): bool
    {
        $user = Auth::user();
        $pinned = $user->pinned_folders ?? [];

        foreach ($pinned as $folder) {
            if ($folder['path'] === $path) {
                return true;
            }
        }
        return false;
    }

    public function setAsDefault(string $path): void
    {
        if (!$this->fileSystemService->isValidPath($path) || !is_dir($path)) {
            return;
        }

        $user = Auth::user();
        $user->update(['default_folder' => $path]);
        $this->conversionResult = 'Default folder set to: ' . basename($path);
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

    public function readFile(string $filePath): void
    {
        $this->conversionResult = null;
        $this->conversionError = null;

        if (!$this->fileSystemService->isValidPath($filePath)) {
            $this->conversionError = 'Invalid file path.';
            return;
        }

        if (strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) !== 'md') {
            $this->conversionError = 'Only Markdown files can be previewed.';
            return;
        }

        if (!is_readable($filePath)) {
            $this->conversionError = 'File is not readable.';
            return;
        }

        $fileSize = filesize($filePath);
        if ($fileSize > 512 * 1024) {
            $this->conversionError = 'File is too large to preview (max 512 KB).';
            return;
        }

        $content = file_get_contents($filePath);
        $this->markdownHtml = Str::markdown($content, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
        $this->previewFileName = basename($filePath);
        $this->previewFilePath = $filePath;
        $this->showMarkdownPreview = true;
    }

    public function refreshPreview(): void
    {
        if ($this->previewFilePath) {
            $this->readFile($this->previewFilePath);
        }
    }

    public function convertPreviewFile(): void
    {
        if ($this->previewFilePath) {
            $filePath = $this->previewFilePath;
            $this->closePreview();
            $this->convertFile($filePath);
        }
    }

    public function closePreview(): void
    {
        $this->showMarkdownPreview = false;
        $this->markdownHtml = null;
        $this->previewFileName = null;
        $this->previewFilePath = null;
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
        $this->applySorting();
    }

    public function render()
    {
        return view('livewire.file-browser', [
            'breadcrumbs' => $this->getBreadcrumbs(),
            'quickNavItems' => $this->getQuickNavItems(),
            'pinnedFolders' => $this->getPinnedFolders(),
        ])->layout('layouts.app');
    }
}
