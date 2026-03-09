# Word to Markdown - Master Documentation

**Last Updated:** 2026-03-09 | Branch: `master` | Commit: `1287c64`

---

## Version History

| Version | Date | Author | Branch / Commit | Summary |
|---------|------|--------|-----------------|---------|
| 1.5 | 2026-03-09 | Claude | `master` @ `1287c64` | Added file browser sorting/dates, pinned folders, settings page, auto-login middleware, NativePHP desktop app support. |
| 1.4 | 2026-02-24 | Claude | `master` @ `c69d8d2` | Added SVG favicon, added "Powered by TimeSaver Systems" footer to all layouts with link to adaptai.chat. |
| 1.3.1 | 2026-02-22 20:30 | Claude | `master` @ `66a2a2c` | Added @tailwindcss/typography to tech stack, documented published pagination views, modal component 4xl option, and README screenshots reference. Fixed commit hash for v1.3. |
| 1.3 | 2026-02-22 17:50 | Claude | `master` @ `66a2a2c` | Added Markdown reader to file browser, fixed theme persistence after login, switched to sans-serif font, dynamic app name via config, email config docs. |
| 1.2 | 2026-02-16 12:35 | Claude | `master` @ `9a364f1` | Changed admin seeder to generic credentials (admin@example.com), added admin seeding section to Operations. |
| 1.1.1 | 2026-02-16 17:30 | Claude | `master` @ `9a364f1` | Documented ConversionService internal methods, FileSystemService readability checks, upload result label inversion, landing page bidirectional messaging. |
| 1.1 | 2026-02-16 12:00 | Claude | `master` @ `9a364f1` | Added bidirectional conversion (MD to DOCX), updated ConversionService, FileUploader, FileBrowser, and landing page. |
| 1.0 | 2026-02-16 12:00 | Claude | `master` @ `9a364f1` | Initial master documentation covering full application architecture. |

---

## Table of Contents

- [Part I: System Overview](#part-i-system-overview)
  - [1. Introduction](#1-introduction)
  - [2. Tech Stack](#2-tech-stack)
  - [3. Architecture](#3-architecture)
- [Part II: Database Layer](#part-ii-database-layer)
  - [4. Schema](#4-schema)
  - [5. Models & Relationships](#5-models--relationships)
- [Part III: User-Facing Application](#part-iii-user-facing-application)
  - [6. Authentication](#6-authentication)
  - [7. File Browser](#7-file-browser)
  - [8. File Upload & Conversion](#8-file-upload--conversion)
  - [9. Conversion History](#9-conversion-history)
  - [10. Profile Management](#10-profile-management)
  - [11. Settings](#11-settings)
- [Part IV: Admin Panel](#part-iv-admin-panel)
  - [12. Dashboard & Widgets](#12-dashboard--widgets)
  - [13. User Management](#13-user-management)
  - [14. Conversion Management](#14-conversion-management)
- [Part V: Services](#part-v-services)
  - [15. ConversionService](#15-conversionservice)
  - [16. FileSystemService](#16-filesystemservice)
- [Part VI: Routes](#part-vi-routes)
- [Part VII: Frontend & Design](#part-vii-frontend--design)
  - [17. Theme System](#17-theme-system)
  - [18. Layout & Navigation](#18-layout--navigation)
  - [19. Landing Page](#19-landing-page)
- [Part VIII: Operations](#part-viii-operations)
  - [20. Development Setup](#20-development-setup)
  - [21. NativePHP Desktop App](#21-nativephp-desktop-app)
  - [22. Email Configuration](#22-email-configuration)
  - [23. External Dependencies](#23-external-dependencies)

---

# Part I: System Overview

## 1. Introduction

**Word to MD** is a Laravel 12 web application for working with Word and Markdown files. It reads rendered Markdown directly in the browser and provides bidirectional conversion between `.docx` and `.md` formats. Three main workflows:

1. **File Browser** - Navigate the server's file system, click `.md` files to read them rendered, and right-click any `.docx` or `.md` file to convert.
2. **Upload & Convert** - Drag-and-drop or upload up to 5 files (.docx or .md) at once for batch conversion with download links.
3. **Markdown Reader** - Click any `.md` file in the file browser to view it rendered with full formatting in a modal overlay.

All conversions are tracked in a database with a full history view. An admin panel (Filament v4) provides user management and conversion analytics. The app also runs as a standalone macOS desktop application via NativePHP (Electron).

Screenshots of each workflow are included in the project README (`docs/screenshot/`).

## 2. Tech Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| Framework | Laravel | 12.x |
| PHP | PHP | 8.2+ |
| Frontend Reactivity | Livewire | 3.6+ |
| Livewire Components | Volt | 1.7+ |
| JS Framework | Alpine.js | (ships with Livewire) |
| Admin Panel | Filament | 4.7+ |
| Document Conversion | Pandoc (via `ueberdosis/pandoc`) | 0.9+ |
| CSS Framework | Tailwind CSS | 3.x |
| Tailwind Plugin | @tailwindcss/typography | (prose styling for Markdown reader) |
| Build Tool | Vite | 7.x |
| Auth Scaffolding | Laravel Breeze | 2.3+ (dev) |
| Testing | Pest | 3.8+ |
| E2E Testing | Playwright | 1.58+ |
| Desktop App | NativePHP (Electron) | 2.1+ |
| Database | SQLite | (default) |

## 3. Architecture

```
app/
├── Filament/                  # Admin panel resources & widgets
│   ├── Resources/
│   │   ├── ConversionResource.php
│   │   └── UserResource.php
│   └── Widgets/
│       ├── StatsOverview.php
│       └── RecentConversions.php
├── Http/
│   ├── Controllers/
│   │   └── ConversionController.php   # Download endpoint
│   └── Middleware/
│       └── AutoLoginLocalhost.php     # Auto-login on localhost requests
├── Livewire/
│   ├── FileBrowser.php            # File system navigation + conversion
│   ├── FileUploader.php           # Drag-and-drop upload + batch conversion
│   ├── ConversionHistory.php      # Paginated history table
│   └── Settings.php               # User settings (default folder, pinned folders)
├── Models/
│   ├── User.php
│   └── Conversion.php
├── Providers/
│   └── NativeAppServiceProvider.php   # NativePHP desktop app config
├── Services/
│   ├── ConversionService.php      # Pandoc conversion logic
│   └── FileSystemService.php      # File system browsing with path validation
└── View/Components/
    ├── AppLayout.php
    └── GuestLayout.php
```

The application follows a Livewire-first architecture. Full-page Livewire components handle routing for the three main pages (Browse, Upload, History). The Filament admin panel runs at `/admin` with its own authentication gate.

---

# Part II: Database Layer

## 4. Schema

### `users` Table

| Column | Type | Constraints | Default | Description |
|--------|------|-------------|---------|-------------|
| `id` | bigint | PK, auto-increment | - | Primary key |
| `name` | string(255) | required | - | User's display name |
| `email` | string(255) | required, unique | - | User's email address |
| `email_verified_at` | timestamp | nullable | NULL | Email verification timestamp |
| `password` | string(255) | required | - | Hashed password |
| `last_used_folder` | string(255) | nullable | NULL | Last browsed directory path |
| `default_folder` | string(255) | nullable | NULL | Default folder to open on login |
| `pinned_folders` | json | nullable | NULL | Array of pinned folder objects (`[{name, path}]`) |
| `is_admin` | boolean | - | false | Admin panel access flag |
| `remember_token` | string(100) | nullable | NULL | Remember me token |
| `created_at` | timestamp | - | - | Creation timestamp |
| `updated_at` | timestamp | - | - | Last update timestamp |

### `conversions` Table

| Column | Type | Constraints | Default | Description |
|--------|------|-------------|---------|-------------|
| `id` | bigint | PK, auto-increment | - | Primary key |
| `user_id` | bigint | FK → users.id, cascade delete | - | User who initiated the conversion |
| `source_path` | string(255) | required | - | Absolute path to the source file |
| `output_path` | string(255) | required | - | Absolute path to the converted file |
| `status` | enum | pending, processing, completed, failed | pending | Conversion status |
| `error_message` | text | nullable | NULL | Error details if conversion failed |
| `created_at` | timestamp | - | - | Conversion timestamp |
| `updated_at` | timestamp | - | - | Last update timestamp |

## 5. Models & Relationships

### User Model (`App\Models\User`)

- Implements `FilamentUser` for admin panel access control
- Uses `HasFactory`, `Notifiable` traits
- **Relationships:**
  - `conversions()` → HasMany → `Conversion`
- **Access Control:**
  - `canAccessPanel(Panel $panel): bool` → returns `$this->is_admin`
- **Fillable:** `name`, `email`, `password`, `last_used_folder`, `default_folder`, `pinned_folders`, `is_admin`
- **Casts:**
  - `email_verified_at` → datetime
  - `password` → hashed
  - `is_admin` → boolean
  - `pinned_folders` → array

### Conversion Model (`App\Models\Conversion`)

- **Fillable:** `user_id`, `source_path`, `output_path`, `status`, `error_message`
- **Relationships:**
  - `user()` → BelongsTo → `User`

---

# Part III: User-Facing Application

## 6. Authentication

Authentication is provided by **Laravel Breeze** (Livewire/Volt stack) with the following pages:

- Login (`/login`)
- Register (`/register`)
- Forgot Password (`/forgot-password`)
- Reset Password (`/reset-password/{token}`)
- Email Verification (`/verify-email`)
- Confirm Password (`/confirm-password`)

The dashboard route (`/dashboard`) redirects to `/browse` (the file browser).

### AutoLoginLocalhost Middleware

**Class:** `App\Http\Middleware\AutoLoginLocalhost`

Registered globally on all web routes via `bootstrap/app.php`. On localhost requests (`127.0.0.1`, `::1`, `localhost`), automatically logs in the first user in the database. If no user exists, creates a default one:

| Field | Value |
|-------|-------|
| Name | `Local User` |
| Email | `local@desktop.app` |
| Password | Random 32-char string |
| Admin | `true` |
| Email Verified | Yes |

This enables the NativePHP desktop app to work without a login screen, and also provides a convenient auto-login for local development.

## 7. File Browser

**Component:** `App\Livewire\FileBrowser`
**Route:** `GET /browse` (auth required)
**View:** `livewire/file-browser.blade.php`

Features:
- Navigates the server file system within the configured `browse_root`
- Shows directories, `.docx`, and `.md` files (hidden files are excluded)
- Directories listed first, then files, both alphabetically sorted
- **Column sorting** - Click column headers (Name, Size, Modified) to sort; directories always stay on top
- **File metadata** - Each item includes `size`, `created_at`, and `modified_at` timestamps from `stat()`
- **Markdown Reader** - Click any `.md` file to read it rendered in a modal overlay
- **Quick Navigation** shortcuts: iCloud Drive, Desktop, Documents, Downloads
- **Pinned Folders** - Pin frequently used directories for quick access; stored per-user in `pinned_folders` JSON column
- **Default Folder** - Set a folder as the default starting location (used when no `last_used_folder` exists)
- **Breadcrumb** navigation with clickable path segments
- **Grid/List** view toggle
- **Right-click context menu** on files/directories with dynamic actions:
  - `.md` files → "Read" (preview) and "Convert to Word"
  - `.docx` files → "Convert to Markdown"
  - Directories → "Pin Folder" / "Unpin Folder", "Set as Default"
- Remembers the user's last visited folder (`last_used_folder` on User model)
- Falls back to `default_folder`, then `browse_root` on mount
- Converted files appear alongside source files in the listing

### Markdown Reader

The `readFile()` method renders `.md` files in a modal preview:

- **Max file size:** 512 KB
- **Rendering:** Uses `Str::markdown()` (CommonMark) with `html_input: strip` and `allow_unsafe_links: false`
- **Styling:** Tailwind Typography (`prose dark:prose-invert`)
- **UI:** Modal overlay with filename header, scrollable content (max 70vh), close button, Escape key dismiss
- **Loading:** Spinner overlay shown via `wire:loading` during file read

### Path Validation

All paths are validated via `FileSystemService::isValidPath()` to ensure they are within the configured `browse_root`. This prevents directory traversal attacks.

## 8. File Upload & Conversion

**Component:** `App\Livewire\FileUploader`
**Route:** `GET /convert` (auth required)
**View:** `livewire/file-uploader.blade.php`

Features:
- Drag-and-drop upload zone
- Accepts up to **5 files** per batch
- File types: `.docx` and `.md`
- Max file size: **50 MB** per file
- Individual file removal before conversion
- Shows per-file success/failure results with download links:
  - **Success:** Output filename in bold (e.g., **file.docx**), subtitle "Created from file.md"
  - **Failure:** Source filename as title, error message as subtitle
- Files stored temporarily at `storage/app/private/uploads/`
- Automatic format detection: `.docx` → Markdown, `.md` → Word

### Validation Rules

| Rule | Value |
|------|-------|
| `files` | required, array, min:1, max:5 |
| `files.*` | file, max:51200 |
| Extension check | .docx or .md only (validated in `convert()` method) |

## 9. Conversion History

**Component:** `App\Livewire\ConversionHistory`
**Route:** `GET /history` (auth required)
**View:** `livewire/conversion-history.blade.php`

Displays a paginated table (20 per page) of the authenticated user's conversions with:
- Source file name
- Date (formatted as `M d, Y H:i`)
- Status badge (Completed / Failed / Processing / Pending)
- Download link (for completed conversions where the output file exists)

## 10. Profile Management

**Route:** `GET /profile` (auth required)
**View:** `profile.blade.php`

Standard Breeze profile management with:
- Update profile information (name, email)
- Update password
- Delete account

## 11. Settings

**Component:** `App\Livewire\Settings`
**Route:** `GET /settings` (auth required)
**View:** `livewire/settings.blade.php`

User preferences page with:
- **Default Folder** - Set the folder that opens when no recent folder exists. Validates the path exists and is within `browse_root` before saving.
- **Pinned Folders** - View and unpin folders from the pinned list. Folders are pinned via the file browser context menu.
- `clearDefault()` method resets the default folder to null.

---

# Part IV: Admin Panel

The admin panel is powered by **Filament v4** and accessible at `/admin`.

**Access Control:** Only users with `is_admin = true` can access the panel. This is enforced by the `canAccessPanel()` method on the User model.

**Theme:** Primary color set to Amber.

## 12. Dashboard & Widgets

### StatsOverview Widget
Displays four stat cards:
- Total Users
- Total Conversions
- Successful Conversions
- Failed Conversions

### RecentConversions Widget
A full-width table showing the 10 most recent conversions with:
- User name
- File name (basename of source_path)
- Status badge (color-coded)
- Creation date

## 13. User Management

**Resource:** `App\Filament\Resources\UserResource`

**Table Columns:** Name (searchable), Email (searchable), Admin (icon), Conversions Count, Created At

**Actions:** Edit, Delete, Bulk Delete

**Form Fields:** Name, Email, Password (optional on edit), Admin toggle

## 14. Conversion Management

**Resource:** `App\Filament\Resources\ConversionResource`

**Table Columns:** User Name (searchable), Source File (searchable, shows basename), Status (color-coded badge), Created At

**Filters:** Status (pending/processing/completed/failed)

**Actions:** View only (no edit/delete)

---

# Part V: Services

## 15. ConversionService

**Class:** `App\Services\ConversionService`

Handles bidirectional document conversion using Pandoc.

### `convert(string $sourcePath): string`

Public entry point. Detects the file extension and dispatches to the appropriate protected method.

- **Input:** Absolute path to a `.docx` or `.md` file
- **Output:** Absolute path to the converted file (same directory, same filename, opposite extension)
- **Routing:** Uses a `match` expression on the file extension to dispatch to internal methods
- **Errors:** Throws `RuntimeException` if source not found, unsupported file type, conversion fails, or no output produced

### `convertDocxToMarkdown(string $docxPath): string` (protected)

Converts a Word document to Markdown using Pandoc (`--from docx --to markdown`).

### `convertMarkdownToDocx(string $mdPath): string` (protected)

Converts a Markdown file to Word using Pandoc (`--from markdown --to docx`).

## 16. FileSystemService

**Class:** `App\Services\FileSystemService`

Provides secure file system browsing within a configurable root directory.

**Configuration:** `filesystems.browse_root` (defaults to `/`)

### Methods

| Method | Signature | Description |
|--------|-----------|-------------|
| `listDirectory` | `(string $path): array` | Lists directories, `.docx`, and `.md` files in the given path, sorted (dirs first, then files, alphabetically). Skips hidden files. Each item includes `name`, `path`, `type`, `size`, `created_at`, and `modified_at` (unix timestamps via `stat()`). Checks `is_readable()` before scanning and wraps `scandir()` in a try/catch to gracefully handle permission errors. |
| `isValidPath` | `(string $path): bool` | Validates that the resolved path is within the browse root. Prevents directory traversal. |
| `getParentDirectory` | `(string $path): string` | Returns the parent directory, or the root if the parent is outside bounds. |

---

# Part VI: Routes

## Web Routes

| Method | URI | Handler | Name | Middleware |
|--------|-----|---------|------|------------|
| GET | `/` | welcome view (or redirect → `/browse` in NativePHP) | - | - |
| GET | `/dashboard` | redirect → `/browse` | dashboard | auth, verified |
| GET | `/browse` | `FileBrowser` (Livewire) | browse | auth, verified |
| GET | `/convert` | `FileUploader` (Livewire) | convert | auth, verified |
| GET | `/history` | `ConversionHistory` (Livewire) | history | auth, verified |
| GET | `/download/{conversion}` | `ConversionController@download` | conversion.download | auth, verified |
| GET | `/profile` | profile view | profile | auth |
| GET | `/settings` | `Settings` (Livewire) | settings | auth |

## Admin Routes

| URI | Description |
|-----|-------------|
| `/admin` | Filament admin dashboard |
| `/admin/users` | User management |
| `/admin/conversions` | Conversion management |

## Download Endpoint

**Controller:** `App\Http\Controllers\ConversionController`

**Authorization:** Verifies that the authenticated user owns the conversion record. Returns 403 if not.

**Validation:** Returns 404 if the conversion is not completed or the output file doesn't exist on disk.

---

# Part VII: Frontend & Design

## 17. Theme System

The application supports **dark and light themes** with the following implementation:

- Theme preference stored in `localStorage` under key `theme`
- Default theme: **dark**
- FOUC prevention: inline script in `<head>` reads theme before page render
- SPA navigation persistence: `livewire:navigated` event listener in `app.js` re-applies theme from `localStorage` (the inline FOUC script doesn't re-execute during Livewire `wire:navigate` transitions)
- Toggle component: `<x-theme-toggle />` with sun/moon icons (Alpine.js)
- Available on the landing page header, app navigation bar (desktop + mobile), and guest layout (auth pages)

### Design Tokens

- **Primary accent:** Amber (amber-600 light / amber-400 dark)
- **Neutral palette:** neutral-50 through neutral-950
- **Font:** Outfit (sans-serif) for all text
- **Border radius:** rounded-md (buttons), rounded-lg (cards)

## 18. Layout & Navigation

### Favicon

All layouts include an SVG favicon (`public/favicon.svg`) — an amber document icon on a rounded square, matching the app's design tokens.

### App Layout (`layouts/app.blade.php`)
- Top navigation bar with logo (links to browse), page links, theme toggle, user dropdown
- App name pulled from `config('app.name')` — set via `APP_NAME` in `.env`
- Navigation links: Browse, Upload, History, Settings
- Responsive hamburger menu for mobile
- User dropdown: Profile, Log Out
- Footer: "Powered by TimeSaver Systems" linking to `https://adaptai.chat/en/about`
- Uses `flex flex-col` with `flex-1` on main to push footer to the bottom

### Guest Layout (`layouts/guest.blade.php`)
- Minimal centered layout for auth pages
- Logo with app name from `config('app.name')` and theme toggle
- Footer: "Powered by TimeSaver Systems" with `mt-auto` positioning

### Modal Component (`components/modal.blade.php`)
- Breeze modal component extended with `4xl` max-width option
- Supported sizes: `sm`, `md`, `lg`, `xl`, `2xl`, `4xl`
- Note: The Markdown preview in FileBrowser uses its own custom modal (not this component)

### Pagination Views
Published and customized Laravel pagination views at `resources/views/vendor/pagination/` with dark mode support and amber accent colors matching the design system. Includes Tailwind, Bootstrap 4/5, Semantic UI, and default variants.

## 19. Landing Page

The landing page (`welcome.blade.php`) features:
- Header with app name from `config('app.name')`, theme toggle, login/register links
- Hero section: "Word & Markdown, back and forth"
- Three feature cards: Read Markdown, Right-Click Convert, File Browser
- "Get Started" CTA button (links to login, or "Open File Browser" if authenticated)
- Footer: "Powered by TimeSaver Systems" linking to `https://adaptai.chat/en/about`

---

# Part VIII: Operations

## 20. Development Setup

### Quick Start

```bash
composer setup    # install deps, generate key, run migrations, build assets
composer dev      # start all services concurrently
```

### Dev Services (via `composer dev`)

| Service | Command | Color |
|---------|---------|-------|
| Server | `php artisan serve` | Blue |
| Queue | `php artisan queue:listen --tries=1 --timeout=0` | Purple |
| Logs | `php artisan pail --timeout=0` | Pink |
| Vite | `npm run dev` | Orange |

### Admin Seeding

```bash
php artisan db:seed
```

Seeds a default admin user via `AdminSeeder`:

| Field | Value |
|-------|-------|
| Email | `admin@example.com` |
| Password | `test1234##` |

Uses `updateOrCreate` so it can be re-run safely.

### Testing

```bash
composer test     # clears config cache + runs pest tests
```

## 21. NativePHP Desktop App

The application can run as a standalone macOS desktop app using **NativePHP** (Electron).

**Package:** `nativephp/desktop` (^2.1)
**Provider:** `App\Providers\NativeAppServiceProvider`
**Config:** `config/nativephp.php`

### Running in Development

```bash
composer native:dev
```

Launches the app in an Electron window with Vite hot-reloading. Runs concurrently: `php artisan native:run` + `npm run dev`.

### Building for Distribution

```bash
php artisan native:build --no-interaction
```

Produces `.dmg` installers in `nativephp/electron/dist/` for both Apple Silicon (arm64) and Intel (x64).

> The built app is unsigned. On macOS, right-click → Open, or System Settings → Privacy & Security → Open Anyway.

### NativeAppServiceProvider Configuration

- **Window:** 1200x800, standard title bar
- **Menu:** App, File, Edit, View, Window (native macOS menus)
- **PHP ini:** `memory_limit` 256M, `upload_max_filesize` 50M, `post_max_size` 55M

### Desktop App Behavior

- Root route (`/`) redirects to `/browse` when `config('nativephp-internal.running')` is true
- `AutoLoginLocalhost` middleware auto-creates and logs in a local user (no login screen)
- Vite `npm run build` runs as a prebuild hook so assets are always compiled before building
- App ID: `com.timesaversystems.wordtomd`
- Requires **Pandoc** installed on the host system (not bundled)

## 22. Email Configuration

By default, the mailer is set to `log`, meaning all emails (password resets, verification, etc.) are written to `storage/logs/` instead of being sent.

To enable real email delivery, set the following in `.env`:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

Any SMTP provider works: Mailgun, Postmark, AWS SES, Mailtrap (for testing), etc. See the [Laravel Mail docs](https://laravel.com/docs/12.x/mail) for all supported drivers.

## 23. External Dependencies

### Pandoc

The application requires **Pandoc** installed on the server for document conversion.

- **PHP wrapper:** `ueberdosis/pandoc` (^0.9.0)
- **System requirement:** `pandoc` binary must be available in PATH
- **Conversion pipelines:**
  - DOCX → Markdown (`--from docx --to markdown`)
  - Markdown → DOCX (`--from markdown --to docx`)

### Filesystem Configuration

The file browser root is configured via `filesystems.browse_root` in the Laravel config. This determines the top-level directory users can browse. Defaults to `/`.
