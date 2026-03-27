<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\QrMenu\App\Http\Controllers\QrMenuController;

Route::prefix('menu')
    ->name('qr-menu.')
    ->group(function (): void {
        Route::get('/{restaurant:slug}/{table}', QrMenuController::class)
            ->name('public')
            ->where('table', '[0-9]+');
    });
