<?php

use App\Services\ConversionService;

it('converts a .docx file to .md successfully', function () {
    $service = new ConversionService();
    $docxPath = base_path('tests/fixtures/sample.docx');

    // Make a copy so we don't pollute fixtures
    $tempDir = sys_get_temp_dir() . '/word_to_doc_test_' . uniqid();
    mkdir($tempDir);
    $tempDocx = $tempDir . '/sample.docx';
    copy($docxPath, $tempDocx);

    $outputPath = $service->convert($tempDocx);

    expect($outputPath)->toEndWith('.md');
    expect(file_exists($outputPath))->toBeTrue();
    expect(dirname($outputPath))->toBe($tempDir);

    // Clean up
    @unlink($outputPath);
    @unlink($tempDocx);
    @rmdir($tempDir);
});

it('throws exception for missing file', function () {
    $service = new ConversionService();

    $service->convert('/nonexistent/path/file.docx');
})->throws(RuntimeException::class, 'Source file not found');

it('saves output file in same directory as source', function () {
    $service = new ConversionService();
    $docxPath = base_path('tests/fixtures/sample.docx');

    $tempDir = sys_get_temp_dir() . '/word_to_doc_test_' . uniqid();
    mkdir($tempDir);
    $tempDocx = $tempDir . '/test_document.docx';
    copy($docxPath, $tempDocx);

    $outputPath = $service->convert($tempDocx);

    expect($outputPath)->toBe($tempDir . '/test_document.md');
    expect(file_exists($outputPath))->toBeTrue();

    // Clean up
    @unlink($outputPath);
    @unlink($tempDocx);
    @rmdir($tempDir);
});
