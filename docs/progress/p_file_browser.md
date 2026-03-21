# File Browser Enhancements (2026-03-09)

> **Last Updated:** 2026-03-21 14:41 EDT

## Summary

Added file/folder dates (created/modified) with sortable columns, localhost auto-login middleware, and iteratively fixed the list view layout from flex to a proper HTML table with fixed column widths. Added persistent state across refresh/restart for sort order, view mode, and session lifetime.

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
- All browser state (sort order, view mode, split screen) persisted to DB rather than session — survives server restarts
- Session lifetime set to 1 year (525600 min) in `.env` for localhost use — sessions effectively never expire

## Changes Made

| File | Change |
|------|--------|
| `app/Services/FileSystemService.php` | Added `created_at`, `modified_at` (via `stat()`) and `size: 0` for directories |
| `app/Livewire/FileBrowser.php` | Added `sortBy`/`sortDirection` properties, `sortItems()` and `applySorting()` methods |
| `resources/views/livewire/file-browser.blade.php` | Replaced flex list view with `<table>` layout, sortable column headers, date columns |
| `app/Http/Middleware/AutoLoginLocalhost.php` | Created - auto-logs in first user (or creates one) when on localhost |
| `bootstrap/app.php` | Registered `AutoLoginLocalhost` middleware in web stack |
| `routes/web.php` | User added NativePHP route detection for landing page |
| `database/migrations/2026_03_21_..._add_sort_and_view_mode_to_users_table.php` | Added `view_mode`, `sort_by`, `sort_direction`, `right_sort_by`, `right_sort_direction` columns |
| `app/Models/User.php` | Added new columns to `$fillable` |
| `app/Livewire/FileBrowser.php` | Restore sort/view state in `mount()`, persist on change in `sortItems()`, `rightSortItems()`, `toggleViewMode()` |
| `.env` | `SESSION_LIFETIME` changed from 120 to 525600 (1 year) |

## Technical Details

- **Sorting**: `applySorting()` runs after `loadDirectory()` and on `sortItems()` click. Uses `usort` with direction multiplier. Directories always grouped first.
- **Localhost detection**: Checks `$request->getHost()` and `$request->ip()` against `localhost`, `127.0.0.1`, `::1`
- **Table layout**: `table-fixed` with `<colgroup>`: Name (auto), Size (100px), Created (140px), Modified (140px). `whitespace-nowrap` on data cells.
- **State persistence**: All UI state stored in `users` table columns. Restored in `mount()`, persisted on every user action (sort click, view toggle). No reliance on session for UI state.

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

### Session config error with app()->environment()

- **Problem**: Attempted to use `app()->environment('local')` in `config/session.php` to conditionally set session lifetime — caused "Target class [env] does not exist" error because the container isn't booted during config loading
- **Solution**: Set `SESSION_LIFETIME=525600` directly in `.env` instead

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

### 2026-03-21 14:41 EDT

- Added persistent state for sort order (`sort_by`, `sort_direction`, `right_sort_by`, `right_sort_direction`) and view mode (`view_mode`) via new DB migration on `users` table
- Updated `FileBrowser::mount()` to restore all persisted state from DB
- Updated `sortItems()`, `rightSortItems()`, `toggleViewMode()` to persist changes to DB on every action
- Updated `User` model `$fillable` with 5 new columns
- Set `SESSION_LIFETIME=525600` (1 year) in `.env` so sessions never expire on localhost
- Attempted `app()->environment()` in config file — failed because container not booted during config loading; used `.env` value instead
- Already persisted and unchanged: `last_used_folder` (current folder), `pinned_folders`, `split_screen_enabled`, `split_screen_path`
