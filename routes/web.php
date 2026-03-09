<?php

use App\Http\Controllers\ConversionController;
use App\Livewire\ConversionHistory;
use App\Livewire\FileBrowser;
use App\Livewire\FileUploader;
use App\Livewire\Settings;
use Illuminate\Support\Facades\Route;

// In NativePHP, skip the landing page and go straight to browse
if (config('nativephp-internal.running')) {
    Route::get('/', fn () => redirect()->route('browse'));
} else {
    Route::view('/', 'welcome');
}


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return redirect()->route('browse');
    })->name('dashboard');

    Route::get('browse', FileBrowser::class)->name('browse');
    Route::get('convert', FileUploader::class)->name('convert');
    Route::get('history', ConversionHistory::class)->name('history');
    Route::get('download/{conversion}', [ConversionController::class, 'download'])->name('conversion.download');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('settings', Settings::class)
    ->middleware(['auth'])
    ->name('settings');

require __DIR__.'/auth.php';
