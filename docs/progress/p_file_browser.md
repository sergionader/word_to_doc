# File Browser Enhancements (2026-03-09)

> **Last Updated:** 2026-03-09 13:06 EDT

## Summary

Added file/folder dates (created/modified) with sortable columns, localhost auto-login middleware, and iteratively fixed the list view layout from flex to a proper HTML table with fixed column widths.

## Status: 🚧 In Progress

Column right-alignment still needs visual verification after the latest `table-fixed` + `<colgroup>` fix.

## Key Decisions

- Used `stat()` (`ctime`/`mtime`) for file timestamps rather than `filemtime()`/`filectime()` for consistency
- Directories always sort first regardless of sort column
- Switched from flex-based list layout to `<table>` with `table-fixed` and `<colgroup>` for reliable column sizing
- Used Unicode triangle characters for sort indicators instead of SVGs (Blade `@if` inside `<svg>` broke rendering)
- AutoLoginLocalhost middleware auto-creates a user if none exists (Local User / local@desktop.app)
- Date format: `d M y H:i` (e.g., "25 Jan 26 17:51") to keep columns compact
- NativePHP route detection uses `config('nativephp-internal.running')` (user updated)

## Changes Made

| File | Change |
|------|--------|
| `app/Services/FileSystemService.php` | Added `created_at`, `modified_at` (via `stat()`) and `size: 0` for directories |
| `app/Livewire/FileBrowser.php` | Added `sortBy`/`sortDirection` properties, `sortItems()` and `applySorting()` methods |
| `resources/views/livewire/file-browser.blade.php` | Replaced flex list view with `<table>` layout, sortable column headers, date columns |
| `app/Http/Middleware/AutoLoginLocalhost.php` | Created - auto-logs in first user (or creates one) when on localhost |
| `bootstrap/app.php` | Registered `AutoLoginLocalhost` middleware in web stack |
| `routes/web.php` | User added NativePHP route detection for landing page |

## Technical Details

- **Sorting**: `applySorting()` runs after `loadDirectory()` and on `sortItems()` click. Uses `usort` with direction multiplier. Directories always grouped first.
- **Localhost detection**: Checks `$request->getHost()` and `$request->ip()` against `localhost`, `127.0.0.1`, `::1`
- **Table layout**: `table-fixed` with `<colgroup>`: Name (auto), Size (100px), Created (140px), Modified (140px). `whitespace-nowrap` on data cells.

## Issues Resolved

### Giant SVG sort arrow rendering

- **Problem**: Blade `@if` directives inside `<svg>` tags broke SVG rendering, causing a page-sized arrow
- **Solution**: Replaced SVG arrows with Unicode triangle characters (`\u{25B2}` / `\u{25BC}`)

### Columns running together (flex layout)

- **Problem**: Flex-based column widths (`w-28`, `w-16`) were not respected, causing Size/Created/Modified to overlap
- **Solution**: Switched to `<table>` with `table-fixed` and explicit `<colgroup>` column widths

### Convert button in MD preview crashing (prior session)

- **Problem**: `convertPreviewFile()` called `closePreview()` first, nulling `previewFilePath` before `convertFile()` received it
- **Solution**: Saved path to local variable before calling `closePreview()`

## Outstanding Tasks

- [ ] Verify right-alignment of Size/Created/Modified columns after `table-fixed` + `<colgroup>` fix
- [ ] Consider hiding date columns on small screens (responsive)
- [ ] Grid view does not show dates (only list view)

---

## Session Log

### 2026-03-09 13:06 EDT

- Added `created_at` and `modified_at` timestamps to `FileSystemService::listDirectory()` using `stat()`
- Added `sortBy`/`sortDirection` to `FileBrowser` Livewire component with `sortItems()` and `applySorting()`
- Created `AutoLoginLocalhost` middleware for localhost auto-authentication
- Registered middleware in `bootstrap/app.php`
- Initial list view used flex layout with SVG sort arrows - SVGs rendered giant due to Blade `@if` inside `<svg>`
- Switched to Unicode triangles for sort indicators
- Flex column widths (`w-28`, `w-16`) didn't hold - columns ran together
- Switched to `<table>` layout which correctly separated columns
- Added `table-fixed` with `<colgroup>` (Size: 100px, Created: 140px, Modified: 140px) for right-aligned fixed columns
- Added `w-full text-right` to header buttons for alignment
