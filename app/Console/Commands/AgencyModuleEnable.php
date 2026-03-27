<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Nwidart\Modules\Facades\Module;

final class AgencyModuleEnable extends Command
{
    protected $signature = 'agency:module:enable {moduleName : The name of the module to enable (e.g. QrMenu, Blog)}';

    protected $description = 'Enable an optional AgencyStack module';

    public function handle(): int
    {
        $moduleName = (string) $this->argument('moduleName');
        $optionalModules = config('core.optional_modules', []);

        if (! array_key_exists($moduleName, $optionalModules)) {
            $this->error("Module '{$moduleName}' is not a valid optional module.");
            $this->line('Available modules: ' . implode(', ', array_keys($optionalModules)));

            return self::FAILURE;
        }

        $module = Module::find($moduleName);

        if (! $module) {
            $this->info("Module '{$moduleName}' not found. Creating it...");
            Artisan::call('module:make', ['name' => [$moduleName]]);
            $this->line(Artisan::output());
            $module = Module::find($moduleName);
        }

        if (! $module) {
            $this->error("Failed to create module '{$moduleName}'.");

            return self::FAILURE;
        }

        if ($module->isEnabled()) {
            $this->warn("Module '{$moduleName}' is already enabled.");

            return self::SUCCESS;
        }

        Artisan::call('module:enable', ['module' => $moduleName]);
        $this->info("✅ Module '{$moduleName}' enabled.");

        // Run module migrations
        Artisan::call('module:migrate', ['module' => $moduleName, '--force' => true]);
        $this->info("Migrations for '{$moduleName}' completed.");

        return self::SUCCESS;
    }
}
