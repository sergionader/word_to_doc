<?php

namespace App\Services;

use Pandoc\Pandoc;
use RuntimeException;

class ConversionService
{
    public function convert(string $sourcePath): string
    {
        if (!file_exists($sourcePath)) {
            throw new RuntimeException("Source file not found: {$sourcePath}");
        }

        $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));

        return match ($extension) {
            'docx' => $this->convertDocxToMarkdown($sourcePath),
            'md' => $this->convertMarkdownToDocx($sourcePath),
            default => throw new RuntimeException("Unsupported file type: .{$extension}"),
        };
    }

    protected function convertDocxToMarkdown(string $docxPath): string
    {
        $pathInfo = pathinfo($docxPath);
        $outputPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.md';

        try {
            $pandoc = new Pandoc();
            $pandoc->inputFile($docxPath)
                ->from('docx')
                ->to('markdown')
                ->output($outputPath)
                ->run();
        } catch (\Exception $e) {
            throw new RuntimeException("Conversion failed: {$e->getMessage()}", 0, $e);
        }

        if (!file_exists($outputPath)) {
            throw new RuntimeException("Conversion produced no output file.");
        }

        return $outputPath;
    }

    protected function convertMarkdownToDocx(string $mdPath): string
    {
        $pathInfo = pathinfo($mdPath);
        $outputPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.docx';

        try {
            $pandoc = new Pandoc();
            $pandoc->inputFile($mdPath)
                ->from('markdown')
                ->to('docx')
                ->output($outputPath)
                ->run();
        } catch (\Exception $e) {
            throw new RuntimeException("Conversion failed: {$e->getMessage()}", 0, $e);
        }

        if (!file_exists($outputPath)) {
            throw new RuntimeException("Conversion produced no output file.");
        }

        return $outputPath;
    }
}
