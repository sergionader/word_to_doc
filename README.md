# Word to Markdown

A Laravel 12 web application for bidirectional conversion between Microsoft Word (.docx) and Markdown (.md) files. Browse your file system, right-click to convert, or drag and drop files for batch conversion.

## Prerequisites

- **PHP** 8.2+
- **Composer**
- **Node.js** & **npm**
- **Pandoc** (the `pandoc` binary must be available in your PATH)

### Installing Pandoc

```bash
# macOS
brew install pandoc

# Ubuntu/Debian
sudo apt-get install pandoc

# Windows (via Chocolatey)
choco install pandoc
```

## Setup

```bash
composer setup
```

This runs the following automatically:
1. Installs PHP dependencies
2. Copies `.env.example` to `.env` (if needed)
3. Generates the application key
4. Runs database migrations
5. Installs Node dependencies
6. Builds frontend assets

## Running the App

```bash
composer dev
```

This starts all services concurrently:

| Service | Description |
|---------|-------------|
| Server | `php artisan serve` (http://localhost:8000) |
| Queue | `php artisan queue:listen` |
| Logs | `php artisan pail` (real-time log viewer) |
| Vite | `npm run dev` (hot module replacement) |

## Running Tests

```bash
composer test
```

## Configuration

### File Browser Root

By default, the file browser can navigate the entire filesystem. To restrict it, set `BROWSE_ROOT` in your `.env`:

```
BROWSE_ROOT=/Users/yourname/Documents
```

### Admin Access

Run the seeder to create the default admin user:

```bash
php artisan db:seed
```

| Field | Value |
|-------|-------|
| Email | `admin@example.com` |
| Password | `test1234##` |

The admin panel is available at `/admin`.

## Tech Stack

- **Laravel 12** - PHP framework
- **Livewire 3** - Reactive frontend components
- **Filament 4** - Admin panel
- **Pandoc** - Document conversion engine
- **Tailwind CSS** - Styling
- **SQLite** - Database (default)

## Documentation

See [docs/MASTER_DOC.md](docs/MASTER_DOC.md) for full project documentation.
