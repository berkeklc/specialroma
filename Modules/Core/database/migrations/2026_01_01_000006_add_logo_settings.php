<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.logo_type', 'text');
        $this->migrator->add('general.logo_text', null);
    }

    public function down(): void
    {
        $this->migrator->delete('general.logo_type');
        $this->migrator->delete('general.logo_text');
    }
};
