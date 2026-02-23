# Plan: Read Markdown Files

## Context
The app lets users browse files and convert between .docx and .md, but there's no way to actually **view** Markdown file contents. This adds a click-to-preview feature that renders .md files as formatted HTML in a modal overlay.

## Current State
- `FileBrowser` Livewire component (`app/Livewire/FileBrowser.php`) handles directory browsing and file conversion
- Files are listed in grid/list views; right-click context menu only offers "Convert"
- `.md` files have no click handler — only directories respond to left-click
- `FileSystemService` (`app/Services/FileSystemService.php`) provides path validation via `isValidPath()`
- `league/commonmark` is already installed (Laravel dependency) — available via `Str::markdown()`
- Existing `<x-modal>` component (`resources/views/components/modal.blade.php`) supports up to `2xl` width

## Implementation Plan

### Phase 1: Install Tailwind Typography Plugin
- `npm install -D @tailwindcss/typography`
- Add `typography` to plugins in `tailwind.config.js`

### Phase 2: Backend — Add `readFile()` to FileBrowser Component
In `app/Livewire/FileBrowser.php`:
- Add properties: `$showMarkdownPreview` (bool), `$markdownHtml` (?string), `$previewFileName` (?string)
- Add `readFile(string $filePath)` method:
  - Validate path with `$this->fileSystemService->isValidPath()`
  - Check `.md` extension, readability, and size (max 512 KB)
  - Parse with `Str::markdown($content, ['html_input' => 'strip', 'allow_unsafe_links' => false])`
  - Set `$showMarkdownPreview = true`
- Add `closePreview()` method to reset state

### Phase 3: Frontend — Update File Browser View
In `resources/views/livewire/file-browser.blade.php`:
- Add `wire:click="readFile('...')"` on `.md` files in both grid and list views
- Add "Read" button to context menu (shown only for `.md` files, above "Convert")
- Add `wire:loading` spinner overlay while `readFile` processes
- Add Markdown preview modal at bottom of view:
  - Header with filename + close button
  - Scrollable body (`max-h-[70vh]`) with `prose dark:prose-invert` styling
  - Escape key + backdrop click to close

### Phase 4: Expand Modal Component
In `resources/views/components/modal.blade.php`:
- Add `'4xl' => 'sm:max-w-4xl'` to `$maxWidth` map

## Files to Modify

| File | Change |
|------|--------|
| `tailwind.config.js` | Add `@tailwindcss/typography` plugin |
| `app/Livewire/FileBrowser.php` | Add `readFile()`, `closePreview()`, preview state properties |
| `resources/views/livewire/file-browser.blade.php` | Click handlers, context menu "Read", loading spinner, preview modal |
| `resources/views/components/modal.blade.php` | Add `4xl` to width map |

No new files. No new Composer packages.

## Verification
1. `npm run build` — assets compile with typography plugin
2. Browse to a directory with `.md` files
3. Single-click a `.md` file → modal opens with rendered Markdown
4. Right-click a `.md` file → context menu shows "Read" and "Convert to Word"
5. Right-click a `.docx` file → context menu only shows "Convert to Markdown"
6. Toggle dark mode with modal open — prose inverts correctly
7. Escape key and backdrop click close the modal
8. File >512 KB shows error toast instead of opening
