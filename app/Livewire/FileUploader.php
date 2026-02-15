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

    public $file;
    public bool $converting = false;
    public ?string $result = null;
    public ?string $error = null;
    public ?string $downloadUrl = null;
    public ?int $conversionId = null;

    protected $rules = [
        'file' => 'required|file|mimes:docx|max:51200', // 50MB max
    ];

    public function convert(): void
    {
        $this->validate();

        $this->converting = true;
        $this->result = null;
        $this->error = null;
        $this->downloadUrl = null;

        try {
            // Store uploaded file to temp directory
            $originalName = $this->file->getClientOriginalName();
            $tempDir = storage_path('app/private/uploads');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $storedPath = $this->file->storeAs('uploads', $originalName, 'local');
            $fullPath = storage_path('app/private/' . $storedPath);

            $conversionService = app(ConversionService::class);
            $outputPath = $conversionService->convert($fullPath);

            $conversion = Conversion::create([
                'user_id' => Auth::id(),
                'source_path' => $fullPath,
                'output_path' => $outputPath,
                'status' => 'completed',
            ]);

            $this->conversionId = $conversion->id;
            $this->downloadUrl = route('conversion.download', $conversion);
            $this->result = 'Converted successfully: ' . pathinfo($originalName, PATHINFO_FILENAME) . '.md';
        } catch (\Exception $e) {
            $this->error = 'Conversion failed: ' . $e->getMessage();

            if (isset($fullPath)) {
                Conversion::create([
                    'user_id' => Auth::id(),
                    'source_path' => $fullPath ?? '',
                    'output_path' => '',
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }
        }

        $this->converting = false;
        $this->file = null;
    }

    public function render()
    {
        return view('livewire.file-uploader')->layout('layouts.app');
    }
}
