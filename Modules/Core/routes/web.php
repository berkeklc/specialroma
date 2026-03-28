<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Core\App\Livewire\PublicPage;
use Modules\Core\App\Settings\GeneralSettings;

// ── Language switcher ─────────────────────────────────────────────────────
Route::get('/lang/{locale}', function (string $locale) {
    $allowed = app(GeneralSettings::class)->active_languages;
    if (in_array($locale, $allowed, strict: true)) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }

    return redirect()->back()->withHeaders(['Vary' => 'Accept-Language']);
})->name('lang.switch')->where('locale', '[a-z]{2}');

// ── Homepage ──────────────────────────────────────────────────────────────
Route::get('/', PublicPage::class)->name('home');

// ── Dynamic CMS pages (single-segment slugs, module prefixes excluded) ───
// When adding a new module with its own /prefix route, add the prefix here.
Route::get('/{slug}', PublicPage::class)
    ->name('page.show')
    ->where('slug', '^(?!admin|livewire|api|menu|qr-menu|lang|blog|services|portfolio|team|book|booking|appointments)[a-z0-9][a-z0-9\-]*$');
