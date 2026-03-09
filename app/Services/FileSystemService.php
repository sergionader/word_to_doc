<?php

namespace App\Services;

class FileSystemService
{
    protected string $rootPath;

    public function __construct()
    {
        $root = config('filesystems.browse_root', '/');
        $this->rootPath = $root === '/' ? '/' : rtrim($root, '/');
    }

    public function listDirectory(string $path): array
    {
        if (!$this->isValidPath($path)) {
            return [];
        }

        if (!is_dir($path) || !is_readable($path)) {
            return [];
        }

        $items = [];

        try {
            $entries = scandir($path);
        } catch (\ErrorException $e) {
            return [];
        }

        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            // Skip hidden files/folders
            if (str_starts_with($entry, '.')) {
                continue;
            }

            $fullPath = $path . '/' . $entry;

            if (is_dir($fullPath)) {
                $stat = stat($fullPath);
                $items[] = [
                    'name' => $entry,
                    'path' => $fullPath,
                    'type' => 'directory',
                    'size' => 0,
                    'created_at' => $stat ? $stat['ctime'] : 0,
                    'modified_at' => $stat ? $stat['mtime'] : 0,
                ];
            } elseif (str_ends_with(strtolower($entry), '.docx') || str_ends_with(strtolower($entry), '.md')) {
                $stat = stat($fullPath);
                $items[] = [
                    'name' => $entry,
                    'path' => $fullPath,
                    'type' => 'file',
                    'size' => filesize($fullPath),
                    'created_at' => $stat ? $stat['ctime'] : 0,
                    'modified_at' => $stat ? $stat['mtime'] : 0,
                ];
            }
        }

        // Sort: directories first, then files, both alphabetically
        usort($items, function ($a, $b) {
            if ($a['type'] !== $b['type']) {
                return $a['type'] === 'directory' ? -1 : 1;
            }
            return strcasecmp($a['name'], $b['name']);
        });

        return $items;
    }

    public function isValidPath(string $path): bool
    {
        $realPath = realpath($path);

        if ($realPath === false) {
            return false;
        }

        // Must be within the browse root
        return str_starts_with($realPath, realpath($this->rootPath));
    }

    public function getParentDirectory(string $path): string
    {
        $parent = dirname($path);

        if (!$this->isValidPath($parent)) {
            return $this->rootPath;
        }

        return $parent;
    }
}
