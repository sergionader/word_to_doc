<?php

namespace App\Livewire;

use App\Services\FileSystemService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Settings extends Component
{
    public string $defaultFolder = '';
    public bool $saved = false;

    protected FileSystemService $fileSystemService;

    public function boot(FileSystemService $fileSystemService): void
    {
        $this->fileSystemService = $fileSystemService;
    }

    public function mount(): void
    {
        $user = Auth::user();
        $this->defaultFolder = $user->default_folder ?? '';
    }

    public function save(): void
    {
        $this->saved = false;

        if ($this->defaultFolder !== '') {
            if (!$this->fileSystemService->isValidPath($this->defaultFolder) || !is_dir($this->defaultFolder)) {
                $this->addError('defaultFolder', 'The specified directory does not exist or is not accessible.');
                return;
            }
        }

        $user = Auth::user();
        $user->update(['default_folder' => $this->defaultFolder ?: null]);
        $this->saved = true;
    }

    public function clearDefault(): void
    {
        $this->defaultFolder = '';
        $this->save();
    }

    public function unpinFolder(string $path): void
    {
        $user = Auth::user();
        $pinned = $user->pinned_folders ?? [];
        $pinned = array_values(array_filter($pinned, fn($f) => $f['path'] !== $path));
        $user->update(['pinned_folders' => $pinned]);
    }

    public function render()
    {
        return view('livewire.settings')->layout('layouts.app');
    }
}
