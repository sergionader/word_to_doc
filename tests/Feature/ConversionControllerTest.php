<?php

use App\Livewire\FileUploader;
use App\Models\Conversion;
use App\Models\User;
use App\Services\ConversionService;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('uploads and converts a docx file', function () {
    // Mock ConversionService to avoid needing a real .docx
    $mock = Mockery::mock(ConversionService::class);
    $mock->shouldReceive('convert')
        ->once()
        ->andReturnUsing(function ($path) {
            $mdPath = preg_replace('/\.docx$/', '.md', $path);
            file_put_contents($mdPath, '# Converted');
            return $mdPath;
        });
    app()->instance(ConversionService::class, $mock);

    $fakeFile = UploadedFile::fake()->createWithContent(
        'sample.docx',
        file_get_contents(base_path('tests/fixtures/sample.docx'))
    );

    Livewire::actingAs($this->user)
        ->test(FileUploader::class)
        ->set('file', $fakeFile)
        ->call('convert')
        ->assertSet('error', null);

    expect(Conversion::where('user_id', $this->user->id)->count())->toBe(1);
    expect(Conversion::first()->status)->toBe('completed');
});

it('rejects non-docx files', function () {
    $fakeFile = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

    Livewire::actingAs($this->user)
        ->test(FileUploader::class)
        ->set('file', $fakeFile)
        ->call('convert')
        ->assertHasErrors(['file']);
});

it('creates a conversion record in database', function () {
    $mock = Mockery::mock(ConversionService::class);
    $mock->shouldReceive('convert')
        ->once()
        ->andReturnUsing(function ($path) {
            $mdPath = preg_replace('/\.docx$/', '.md', $path);
            file_put_contents($mdPath, '# Converted');
            return $mdPath;
        });
    app()->instance(ConversionService::class, $mock);

    $fakeFile = UploadedFile::fake()->createWithContent(
        'sample.docx',
        file_get_contents(base_path('tests/fixtures/sample.docx'))
    );

    Livewire::actingAs($this->user)
        ->test(FileUploader::class)
        ->set('file', $fakeFile)
        ->call('convert');

    $conversion = Conversion::where('user_id', $this->user->id)->first();
    expect($conversion)->not->toBeNull();
    expect($conversion->source_path)->toContain('sample.docx');
    expect($conversion->output_path)->toEndWith('.md');
    expect($conversion->status)->toBe('completed');
});

it('allows downloading completed conversion', function () {
    $tempDir = sys_get_temp_dir() . '/word_to_doc_dl_' . uniqid();
    mkdir($tempDir);
    $mdPath = $tempDir . '/test.md';
    file_put_contents($mdPath, '# Test');

    $conversion = Conversion::create([
        'user_id' => $this->user->id,
        'source_path' => $tempDir . '/test.docx',
        'output_path' => $mdPath,
        'status' => 'completed',
    ]);

    $this->actingAs($this->user)
        ->get(route('conversion.download', $conversion))
        ->assertStatus(200)
        ->assertDownload('test.md');

    @unlink($mdPath);
    @rmdir($tempDir);
});

it('prevents downloading other users conversions', function () {
    $otherUser = User::factory()->create();

    $conversion = Conversion::create([
        'user_id' => $otherUser->id,
        'source_path' => '/tmp/test.docx',
        'output_path' => '/tmp/test.md',
        'status' => 'completed',
    ]);

    $this->actingAs($this->user)
        ->get(route('conversion.download', $conversion))
        ->assertStatus(403);
});
