<?php

namespace App\Livewire;

use App\Models\Conversion;
use App\Services\ConversionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class FileUploader extends Component
{
    use WithFileUploads;

    public $files = [];
    public bool $converting = false;
    public array $results = [];
    public ?string $error = null;

    protected $rules = [
        'files' => 'required|array|min:1|max:5',
        'files.*' => 'file|max:51200', // 50MB max per file
    ];

    protected $messages = [
        'files.max' => 'You can upload a maximum of 5 files at once.',
        'files.*.max' => 'Each file must be less than 50MB.',
    ];

    public function removeFile(int $index): void
    {
        $files = collect($this->files)->values()->all();
        array_splice($files, $index, 1);
        $this->files = $files;
    }

    public function convert(): void
    {
        $this->validate();

        foreach ($this->files as $file) {
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['docx', 'md'])) {
                $this->addError('files', 'Only .docx and .md files are allowed.');
                return;
            }
        }

        $this->converting = true;
        $this->results = [];
        $this->error = null;

        $conversionService = app(ConversionService::class);

        foreach ($this->files as $file) {
            $originalName = $file->getClientOriginalName();

            try {
                $tempDir = storage_path('app/private/uploads');
                if (!is_dir($tempDir)) {
                    mkdir($tempDir, 0755, true);
                }

                $storedPath = $file->storeAs('uploads', $originalName, 'local');
                $fullPath = storage_path('app/private/' . $storedPath);

                $outputPath = $conversionService->convert($fullPath);

                $conversion = Conversion::create([
                    'user_id' => Auth::id(),
                    'source_path' => $fullPath,
                    'output_path' => $outputPath,
                    'status' => 'completed',
                ]);

                $this->results[] = [
                    'name' => $originalName,
                    'status' => 'completed',
                    'message' => basename($outputPath),
                    'downloadUrl' => route('conversion.download', $conversion),
                ];
            } catch (\Exception $e) {
                Conversion::create([
                    'user_id' => Auth::id(),
                    'source_path' => $fullPath ?? '',
                    'output_path' => '',
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                $this->results[] = [
                    'name' => $originalName,
                    'status' => 'failed',
                    'message' => $e->getMessage(),
                    'downloadUrl' => null,
                ];
            }
        }

        $this->converting = false;
        $this->files = [];
    }

    public function render()
    {
        return view('livewire.file-uploader')->layout('layouts.app');
    }
}
