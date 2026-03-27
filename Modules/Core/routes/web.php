<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Core\App\Livewire\PublicPage;

// ── Language switcher ─────────────────────────────────────────────────────
Route::get('/lang/{locale}', function (string $locale) {
    $allowed = app(\Modules\Core\App\Settings\GeneralSettings::class)->active_languages;
    if (in_array($locale, $allowed, strict: true)) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }

    return redirect()->back()->withHeaders(['Vary' => 'Accept-Language']);
})->name('lang.switch')->where('locale', '[a-z]{2}');

// ── Homepage ──────────────────────────────────────────────────────────────
Route::get('/', PublicPage::class)->name('home');

// ── Dynamic pages ─────────────────────────────────────────────────────────
Route::get('/{slug}', PublicPage::class)
    ->name('page.show')
    ->where('slug', '^(?!admin|livewire|api)[a-z0-9\-\/]+$');
