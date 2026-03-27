<?php

declare(strict_types=1);

use App\Console\Commands\AgencyInstall;
use App\Console\Commands\AgencyModuleEnable;
use App\Console\Commands\AgencyThemeInstall;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// AgencyStack commands are auto-discovered via Commands directory
