<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Meeting\App\Livewire\BookingPage;

Route::get('/book', BookingPage::class)->name('booking.page');
