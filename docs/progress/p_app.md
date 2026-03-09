# NativePHP Desktop App (2026-03-09)

> **Last Updated:** 2026-03-09 13:27 EDT

## Summary

Converted the Word to MD Laravel web application into a native macOS desktop app using NativePHP (Electron). The app builds as a standalone `.dmg` installer with auto-login (no authentication required) and direct file browser access on launch.

## Status: ✅ Complete

App builds and runs successfully as both a dev server (`composer native:dev`) and production DMG (`php artisan native:build`).

## Key Decisions

- Used **NativePHP Desktop v2.1.1** (Electron-based) instead of rewriting to a JS framework — preserves the entire Laravel/Livewire codebase
- Auto-login via `AutoLoginLocalhost` middleware instead of removing auth entirely — `auth()->user()` is used extensively throughout the app, so creating a default local user is cleaner than stripping auth
- Default local user is auto-created on first request if no user exists (`Local User` / `local@desktop.app`)
- Root route (`/`) redirects to `/browse` when running inside NativePHP, skipping the landing page
- Removed `titleBarHidden()` from window config as it caused EPIPE errors without a custom draggable element
- App name changed to "mdword" for cleaner DMG naming
- Vite `npm run build` added as a prebuild hook so assets are always compiled before building

## Changes Made

| File | Change |
|------|--------|
| `composer.json` | Added `nativephp/desktop` dependency, `native:dev` script alias, post-update hook |
| `app/Providers/NativeAppServiceProvider.php` | Created — window config (1200x800), native app menu, PHP ini settings for uploads |
| `config/nativephp.php` | Created — app ID `com.timesaversystems.wordtomd`, author, description, prebuild hook |
| `app/Http/Middleware/AutoLoginLocalhost.php` | Updated — auto-creates default user if none exists |
| `routes/web.php` | Updated — `/` redirects to `/browse` when NativePHP is running |
| `.env` | Changed `APP_NAME` to `mdword` |

## Technical Details

### NativePHP Setup

- **Package:** `nativephp/desktop` v2.1.1 (Composer) + Electron v38.7.2 (npm)
- **Requirements met:** PHP 8.4 (needs 8.3+), Node 24 (needs 22+)
- **Install commands:** `composer require nativephp/desktop` → `php artisan native:install --no-interaction`
- **NPM deps installed manually** in `vendor/nativephp/desktop/resources/electron/` due to TTY issue in sandboxed environment

### NativeAppServiceProvider Config

- Window: 1200x800, standard title bar
- Menu: App, File, Edit, View, Window (native macOS menus)
- PHP ini: 256M memory, 50M upload, 55M post size

### Build Output

| File | Arch | Size |
|------|------|------|
| `mdword-1.0.0-arm64.dmg` | Apple Silicon | 147 MB |
| `mdword-1.0.0-x64.dmg` | Intel | 151 MB |

Build artifacts location: `nativephp/electron/dist/`

### Running the App

- **Dev mode:** `composer native:dev` (or `php artisan native:run`)
- **Build:** `php artisan native:build --no-interaction`
- **Note:** Built app is unsigned — requires right-click → Open or System Settings → Privacy → Open Anyway on macOS

### NativePHP Database

- NativePHP creates its own SQLite database (`database/nativephp.sqlite`) at runtime
- The `nativephp` database connection is only available inside the Electron runtime
- Migrations run automatically on app start

## Issues Resolved

### TTY Error During Install

- **Problem:** `php artisan native:install` failed with "TTY mode requires /dev/tty to be read/writable" in sandboxed environment
- **Solution:** Ran with `--no-interaction` flag, then manually installed npm deps in the Electron directory

### EPIPE Error on Launch

- **Problem:** Electron crashed with `Error: write EPIPE` when using `titleBarHidden()` in window config
- **Solution:** Removed `titleBarHidden()` — it requires a custom draggable HTML element which wasn't implemented

### Authentication in Desktop App

- **Problem:** Can't log in within the Electron app (no user exists in NativePHP's database)
- **Solution:** Updated `AutoLoginLocalhost` middleware to auto-create a default user if none exists, then auto-login on localhost requests

## Outstanding Tasks

- [ ] Add a custom app icon (currently uses default Electron icon)
- [ ] Code-sign the app for distribution (requires Apple Developer account)
- [ ] Consider bundling Pandoc with the app (currently requires system install)
- [ ] Hide/remove navigation elements not needed in desktop mode (login/register links, etc.)
- [ ] Update MASTER_DOC.md with NativePHP documentation

---

## Session Log

### 2026-03-09 13:27 EDT

- Discussed feasibility of converting Laravel app to Electron — recommended NativePHP as the best path
- Confirmed NativePHP is free/open-source (MIT license)
- Installed `nativephp/desktop` v2.1.1 via Composer
- Ran `php artisan native:install --no-interaction` (worked around TTY sandbox issue)
- Manually installed npm/Electron deps in vendor electron directory
- Created `NativeAppServiceProvider` with window and menu config
- Configured `config/nativephp.php` with app metadata
- Hit EPIPE error — simplified window config by removing `titleBarHidden()`
- Verified Laravel app boots fine via `php artisan serve`
- Built Vite assets and successfully launched NativePHP dev server
- Updated `AutoLoginLocalhost` middleware to auto-create users for desktop mode
- Added NativePHP detection in routes to skip landing page
- Built production DMGs (arm64 + x64) successfully
- Renamed app to "mdword" and rebuilt — final output: `mdword-1.0.0-arm64.dmg` (147 MB)
