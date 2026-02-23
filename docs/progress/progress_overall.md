# Word-to-Markdown Laravel Application (2026-02-14)

> **Last Updated:** 2026-02-22 17:18 EST

## Summary

Built a full Laravel 12 application from scratch that converts MS Word (.docx) files to Markdown via a file browser with right-click context menu and a drag-and-drop uploader. Now also reads and renders Markdown files directly in the browser. Includes authentication (Breeze), admin panel (Filament v4), SQLite database, dark/light theme, and comprehensive test suites (Pest + Playwright).

## Status: 🚧 In Progress

Core app complete. UX refinements ongoing — theme persistence, font cleanup, home page updates, README refresh.

## Key Decisions

- Used Filament v4 (latest) instead of v3 as originally planned — v4 was the version resolved by composer
- Used Pest v3 (not v4) due to PHPUnit 11 compatibility with Laravel 12
- Moved ConversionService and FileSystemService tests from Unit to Feature directory since they need Laravel's application container (`base_path()`, `config()`)
- Auth tests use Livewire Volt components (Breeze's Livewire stack) rather than direct HTTP POST
- File upload tests mock `ConversionService` since Livewire's test helper doesn't support real file uploads easily
- Dashboard route redirects to `/browse` instead of rendering its own view
- Default file browser view set to list (not grid) for better usability
- Replaced Laravel branding entirely — custom nav logo, app-specific welcome/landing page
- Dark mode is the default theme; persisted via localStorage
- App name driven by `APP_NAME` env var (`config('app.name')`) — no hardcoded strings in views
- Removed serif font (Cormorant) from all UI; everything uses sans-serif (Outfit) for consistency
- Logo/app name in nav is a navigation link (to browse), not a theme toggle

## Changes Made

| File | Change |
|------|--------|
| `.env` | Configured database, browse root `/`, pandoc binary |
| `config/filesystems.php` | Added `browse_root` config key |
| `database/migrations/*_add_fields_to_users_table.php` | Added `last_used_folder` (nullable string) and `is_admin` (boolean) to users |
| `database/migrations/*_create_conversions_table.php` | Created conversions table with user_id, source/output paths, status enum, error_message |
| `database/seeders/AdminSeeder.php` | Seeds default admin user (admin@example.com) |
| `database/seeders/DatabaseSeeder.php` | Updated to call AdminSeeder |
| `app/Models/User.php` | Added `is_admin`, `last_used_folder` to fillable; `conversions()` relationship; `FilamentUser` interface with `canAccessPanel()` |
| `app/Models/Conversion.php` | New model with user relationship |
| `app/Services/ConversionService.php` | Pandoc-based .docx to .md conversion service |
| `app/Services/FileSystemService.php` | Directory listing, path validation (prevents traversal), parent directory navigation |
| `app/Livewire/FileBrowser.php` | File browser with navigation, breadcrumbs, right-click convert, last-folder memory, grid/list view toggle, quick nav (iCloud Drive, Desktop, Documents, Downloads) |
| `app/Livewire/FileUploader.php` | Drag-and-drop upload with Livewire file uploads, conversion, download link |
| `app/Livewire/ConversionHistory.php` | Paginated conversion history table |
| `app/Http/Controllers/ConversionController.php` | Download endpoint with ownership check |
| `resources/js/app.js` | Added `livewire:navigated` listener to re-apply theme after SPA navigation |
| `resources/views/livewire/file-browser.blade.php` | Grid/list view toggle, folder/file icons, Alpine.js context menu, breadcrumbs, quick nav buttons; removed `font-serif` |
| `resources/views/livewire/file-uploader.blade.php` | Drop zone UI with working drag-and-drop via `@this.upload()`, progress indicator, download button; removed `font-serif` |
| `resources/views/livewire/conversion-history.blade.php` | Table with status badges, download links, pagination; removed `font-serif` |
| `resources/views/livewire/layout/navigation.blade.php` | Custom nav: document icon + app name from config, Browse/Upload/History links; removed `font-serif` |
| `resources/views/welcome.blade.php` | Landing page: updated hero ("Word & Markdown, back and forth"), feature cards (Read Markdown, Right-Click Convert, File Browser), app name from config; removed `font-serif` |
| `resources/views/layouts/guest.blade.php` | App name from `config('app.name')`; removed `font-serif` |
| `resources/views/layouts/app.blade.php` | FOUC prevention script for dark mode |
| `resources/views/profile.blade.php` | Removed `font-serif` |
| `README.md` | Full rewrite: features list, MD reading, email config section, updated tech stack with versions |
| `routes/web.php` | App routes: browse, convert, history, download; dashboard redirects to browse |
| `app/Filament/Resources/UserResource.php` | Admin CRUD for users |
| `app/Filament/Resources/ConversionResource.php` | Admin view for all conversions |
| `app/Filament/Widgets/StatsOverview.php` | Dashboard stats: users, conversions, success/fail counts |
| `app/Filament/Widgets/RecentConversions.php` | Dashboard table of recent conversions |
| `app/Providers/Filament/AdminPanelProvider.php` | Configured with custom widgets |
| `tests/Feature/ConversionServiceTest.php` | 3 tests: successful conversion, missing file, output path |
| `tests/Feature/FileSystemServiceTest.php` | 7 tests: listing, sorting, traversal blocking, path validation |
| `tests/Feature/AuthTest.php` | 7 tests: register, login, logout, redirects (using Volt) |
| `tests/Feature/FileBrowserTest.php` | 7 tests: render, list, navigate, remember folder, convert, reject invalid |
| `tests/Feature/ConversionControllerTest.php` | 5 tests: upload+convert, reject non-docx, db record, download, ownership |
| `tests/Feature/AdminTest.php` | 3 tests: admin access, non-admin blocked, guest redirect |
| `tests/Feature/Auth/AuthenticationTest.php` | Updated navigation test for dashboard→browse redirect |
| `tests/fixtures/sample.docx` | Sample Word doc created via pandoc for test fixtures |
| `tests/e2e/auth.spec.ts` | E2E: login, register, logout, redirect flows |
| `tests/e2e/file-upload.spec.ts` | E2E: upload page render, .docx upload and conversion |
| `tests/e2e/file-browser.spec.ts` | E2E: browser render, breadcrumbs, folder navigation, up button |
| `tests/e2e/admin.spec.ts` | E2E: admin login, dashboard access, non-admin 403 |
| `playwright.config.ts` | Playwright config with auto-starting `php artisan serve` |

## Technical Details

- **Stack**: Laravel 12, Livewire 3 + Volt, Alpine.js, Tailwind CSS 3, Vite 7, Filament v4, SQLite
- **Conversion**: Uses `ueberdosis/pandoc` package wrapping the Pandoc CLI (`pandoc` must be in PATH)
- **File browser security**: `FileSystemService.isValidPath()` uses `realpath()` to prevent directory traversal outside `BROWSE_ROOT_PATH`
- **Admin access**: `User` model implements `FilamentUser` interface; `canAccessPanel()` checks `is_admin` column
- **Filament v4 differences**: `form()` method signature uses `Schema` instead of `Form`; `$navigationIcon` type is `string | BackedEnum | null`
- **Theme system**: Dark mode default; `localStorage` key `theme` with values `dark`/`light`; FOUC prevention via inline script in `<head>`; `livewire:navigated` event re-applies theme after SPA navigation
- **Fonts**: Sans-serif only (Outfit); Cormorant serif font still loaded but no longer used in any view
- **Email**: Default mailer is `log` (writes to `storage/logs`); switch to SMTP via `.env` for real delivery

## Issues Resolved

### npm cache ownership
- **Problem**: npm cache contained root-owned files, blocking `npm install`
- **Solution**: Used `--cache /tmp/.npm-cache` as workaround. Permanent fix requires `sudo chown -R $(id -u):$(id -g) ~/.npm`

### Filament v4 type mismatch
- **Problem**: `$navigationIcon` type changed from `?string` to `string | BackedEnum | null` in Filament v4
- **Solution**: Updated property declarations in both resource files

### Filament v4 form() signature change
- **Problem**: `form(Form $form): Form` incompatible with Filament v4's `form(Schema $schema): Schema`
- **Solution**: Updated both resources to use `Filament\Schemas\Schema`

### Pest version conflict
- **Problem**: Pest v4 requires PHPUnit 12, but Laravel 12 ships with PHPUnit 11
- **Solution**: Installed Pest v3 using `-W` flag to allow PHPUnit downgrade to 11.5.50

### Browse root `/` path trimming
- **Problem**: `rtrim('/', '/')` produces empty string, causing `realpath('')` to fail and all paths to be rejected as invalid
- **Solution**: Added special case: `$root === '/' ? '/' : rtrim($root, '/')`

### Breeze Livewire auth tests
- **Problem**: Auth tests using `$this->post('/login', ...)` don't work with Breeze's Livewire/Volt auth components
- **Solution**: Rewrote tests to use `Volt::test('pages.auth.login')` matching Breeze's pattern

### Drag-and-drop upload not working
- **Problem**: Drop zone had `x-on:drop.prevent` that toggled `isDragging` visual state but never passed the dropped file to Livewire — file was silently discarded
- **Solution**: Added `@this.upload('file', $event.dataTransfer.files[0])` in the drop handler to use Livewire's upload mechanism directly from Alpine.js

### Breadcrumbs showing merged path segments (no separators)
- **Problem**: `getBreadcrumbs()` used `str_replace($root, '', $path)` — when root is `/`, this removed ALL `/` characters from the entire path, producing one merged string like "UserssergionLibrary..."
- **Solution**: Replaced with `substr($path, strlen(rtrim($root, '/')))` to only strip the root prefix

### Theme lost after login (dark → light)
- **Problem**: Livewire's `navigate: true` redirect after login performs SPA-style DOM morphing. The inline FOUC prevention `<script>` in `<head>` doesn't re-execute, so the `dark` class on `<html>` is lost
- **Solution**: Added `livewire:navigated` event listener in `app.js` that re-applies the theme from `localStorage`

## Outstanding Tasks

- [ ] Fix npm cache ownership permanently: `sudo chown -R $(id -u):$(id -g) ~/.npm`
- [ ] Run existing tests to verify no regressions from UX changes
- [ ] Remove unused Cormorant serif font from font imports (no longer referenced in views)

---

## Session Log

### 2026-02-14 ~21:30-22:30 UTC

- Created Laravel project, installed all dependencies (Breeze, Filament, Pandoc, Pest, Playwright)
- Configured PostgreSQL, ran migrations, seeded admin user
- Built ConversionService and FileSystemService
- Created all 3 Livewire components (FileBrowser, FileUploader, ConversionHistory) with Blade views
- Set up routes and navigation
- Built Filament admin panel with UserResource, ConversionResource, and dashboard widgets
- Fixed multiple Filament v4 compatibility issues
- Wrote 56 Pest tests (all passing) and 14 Playwright E2E tests (all passing)
- Fixed FileSystemService root path trimming bug that prevented browsing from `/`

### 2026-02-14 Session 2

- **Fixed drag-and-drop upload**: Drop zone was missing the actual file handoff to Livewire. Added `@this.upload('file', ...)` in the Alpine.js drop handler
- **Added list view to file browser**: New `viewMode` property (`grid`/`list`) with toggle button in toolbar. List view shows compact rows with file sizes
- **Added quick nav shortcuts**: Toolbar buttons for iCloud Drive, Desktop, Documents, Downloads — auto-detected from `$HOME`, only shown if the directory exists and is within `BROWSE_ROOT_PATH`
- `FileBrowser.php`: Added `toggleViewMode()`, `getQuickNavItems()` methods and `$viewMode` public property
- `file-browser.blade.php`: Restructured to support both grid and list views; added quick nav buttons with distinct icons per location
- `file-uploader.blade.php`: Fixed drop handler to call `@this.upload()` instead of just toggling visual state

### 2026-02-14 Session 3

- **Fixed breadcrumbs path merging bug**: `str_replace('/', '', $path)` was stripping all slashes when root is `/`. Changed to `substr()` prefix removal
- **Default view → list**: Changed `$viewMode` default from `'grid'` to `'list'`
- **Removed Laravel logo from nav**: Replaced `<x-application-logo>` with document icon + "Word to MD" text linking to `/browse`
- **Replaced default Laravel welcome page**: New landing page with hero section ("Convert Word docs to Markdown"), three feature cards (File Browser, Right-Click Convert, Drag & Drop), and login/register CTAs
- `FileBrowser.php`: Fixed `getBreadcrumbs()` to use `substr()` instead of `str_replace()`; changed default `$viewMode` to `'list'`
- `navigation.blade.php`: Custom branding replacing Laravel logo
- `welcome.blade.php`: Full rewrite — app-specific landing page

### 2026-02-22 17:18 EST

- **Fixed theme lost after login**: Added `livewire:navigated` event listener in `app.js` to re-apply dark/light theme from `localStorage` after Livewire SPA navigation. The inline FOUC script in `<head>` doesn't re-execute during `wire:navigate` transitions.
- **Updated home page for MD reading**: Hero changed to "Word & Markdown, back and forth". Feature cards reordered: Read Markdown (new), Right-Click Convert, File Browser (consolidated drag & drop).
- **Dynamic app name**: Replaced all hardcoded "Word to MD" / "Word to Markdown" strings with `config('app.name', 'Word to MD')` across welcome, guest layout, and navigation views.
- **Removed serif font**: Stripped `font-serif` class from all 11 blade templates (welcome, guest layout, navigation, file browser, file uploader, conversion history, profile). All text now uses Outfit sans-serif.
- **Reverted logo to navigation link**: Logo in nav bar restored to `<a>` linking to browse route (was accidentally made a theme toggle button).
- **Updated README**: Full rewrite for public portfolio — added features list, Markdown reading, email configuration section, updated tech stack with accurate versions, MIT license.
- **Set `.env` APP_NAME**: Changed from "Word to Markdown & Vice Versa" to "Word to MD".
