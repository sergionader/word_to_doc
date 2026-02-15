<?php

use App\Services\FileSystemService;

beforeEach(function () {
    // Create a temp directory structure for testing
    $this->testDir = sys_get_temp_dir() . '/word_to_doc_fs_test_' . uniqid();
    mkdir($this->testDir);
    mkdir($this->testDir . '/subfolder');
    file_put_contents($this->testDir . '/document.docx', 'fake docx');
    file_put_contents($this->testDir . '/readme.txt', 'text file');
    file_put_contents($this->testDir . '/.hidden', 'hidden file');

    config(['filesystems.browse_root' => $this->testDir]);
    $this->service = new FileSystemService();
});

afterEach(function () {
    // Clean up
    @unlink($this->testDir . '/document.docx');
    @unlink($this->testDir . '/readme.txt');
    @unlink($this->testDir . '/.hidden');
    @rmdir($this->testDir . '/subfolder');
    @rmdir($this->testDir);
});

it('lists directory contents correctly', function () {
    $items = $this->service->listDirectory($this->testDir);

    // Should have subfolder and document.docx, but not readme.txt or .hidden
    expect($items)->toHaveCount(2);

    $names = array_column($items, 'name');
    expect($names)->toContain('subfolder');
    expect($names)->toContain('document.docx');
    expect($names)->not->toContain('readme.txt');
    expect($names)->not->toContain('.hidden');
});

it('returns directories before files', function () {
    $items = $this->service->listDirectory($this->testDir);

    expect($items[0]['type'])->toBe('directory');
    expect($items[1]['type'])->toBe('file');
});

it('blocks directory traversal attacks', function () {
    $result = $this->service->isValidPath('/etc/passwd');
    expect($result)->toBeFalse();

    $result = $this->service->isValidPath($this->testDir . '/../../etc/passwd');
    expect($result)->toBeFalse();
});

it('allows valid paths within root', function () {
    $result = $this->service->isValidPath($this->testDir);
    expect($result)->toBeTrue();

    $result = $this->service->isValidPath($this->testDir . '/subfolder');
    expect($result)->toBeTrue();
});

it('returns only folders and .docx files', function () {
    $items = $this->service->listDirectory($this->testDir);

    foreach ($items as $item) {
        if ($item['type'] === 'file') {
            expect($item['name'])->toEndWith('.docx');
        }
    }
});

it('returns parent directory', function () {
    $parent = $this->service->getParentDirectory($this->testDir . '/subfolder');
    expect($parent)->toBe($this->testDir);
});

it('returns empty array for non-existent directory', function () {
    $items = $this->service->listDirectory('/nonexistent/path');
    expect($items)->toBeEmpty();
});
