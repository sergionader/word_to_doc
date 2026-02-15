<?php

use App\Livewire\FileBrowser;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->testDir = sys_get_temp_dir() . '/word_to_doc_browse_' . uniqid();
    mkdir($this->testDir);
    mkdir($this->testDir . '/subfolder');

    $docxSource = base_path('tests/fixtures/sample.docx');
    copy($docxSource, $this->testDir . '/test.docx');

    config(['filesystems.browse_root' => $this->testDir]);

    $this->user = User::factory()->create();
});

afterEach(function () {
    @unlink($this->testDir . '/test.docx');
    @unlink($this->testDir . '/test.md');
    @rmdir($this->testDir . '/subfolder');
    @rmdir($this->testDir);
});

it('renders file browser component', function () {
    $this->actingAs($this->user)
        ->get('/browse')
        ->assertStatus(200)
        ->assertSeeLivewire(FileBrowser::class);
});

it('lists directories and docx files', function () {
    Livewire::actingAs($this->user)
        ->test(FileBrowser::class)
        ->assertSee('subfolder')
        ->assertSee('test.docx');
});

it('navigates into folders', function () {
    Livewire::actingAs($this->user)
        ->test(FileBrowser::class)
        ->call('navigateTo', $this->testDir . '/subfolder')
        ->assertSet('currentPath', $this->testDir . '/subfolder');
});

it('navigates up', function () {
    $this->user->update(['last_used_folder' => $this->testDir . '/subfolder']);

    Livewire::actingAs($this->user)
        ->test(FileBrowser::class)
        ->assertSet('currentPath', $this->testDir . '/subfolder')
        ->call('navigateUp')
        ->assertSet('currentPath', $this->testDir);
});

it('remembers last used folder', function () {
    Livewire::actingAs($this->user)
        ->test(FileBrowser::class)
        ->call('navigateTo', $this->testDir . '/subfolder');

    expect($this->user->fresh()->last_used_folder)->toBe($this->testDir . '/subfolder');
});

it('converts a docx file via right-click action', function () {
    $component = Livewire::actingAs($this->user)
        ->test(FileBrowser::class)
        ->call('convertFile', $this->testDir . '/test.docx')
        ->assertSet('conversionError', null);

    expect($component->get('conversionResult'))->not->toBeNull();
    expect(file_exists($this->testDir . '/test.md'))->toBeTrue();
});

it('rejects invalid paths', function () {
    Livewire::actingAs($this->user)
        ->test(FileBrowser::class)
        ->call('convertFile', '/etc/passwd')
        ->assertSet('conversionError', 'Invalid file path.');
});
