<?php

namespace App\Services;

use Pandoc\Pandoc;
use RuntimeException;

class ConversionService
{
    public function convert(string $docxPath): string
    {
        if (!file_exists($docxPath)) {
            throw new RuntimeException("Source file not found: {$docxPath}");
        }

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
}
