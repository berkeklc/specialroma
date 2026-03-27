<?php

declare(strict_types=1);

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\App\Enums\LayoutType;
use Modules\Core\App\Models\Layout;
use Modules\Core\App\Models\Menu;
use Spatie\Permission\Models\Role;

final class CoreDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedRoles();
        $this->seedDefaultMenus();
        $this->seedDefaultLayouts();
        $this->seedAdminUser();
    }

    private function seedRoles(): void
    {
        $roles = config('core.roles', []);

        foreach ($roles as $key => $label) {
            Role::firstOrCreate(['name' => $key, 'guard_name' => 'web']);
        }

        $this->command->info('Roles seeded: ' . implode(', ', array_keys($roles)));
    }

    private function seedDefaultMenus(): void
    {
        $menus = [
            ['location' => 'primary', 'label' => ['tr' => 'Ana Menü', 'en' => 'Primary Menu'], 'items' => []],
            ['location' => 'footer', 'label' => ['tr' => 'Alt Menü', 'en' => 'Footer Menu'], 'items' => []],
        ];

        foreach ($menus as $menu) {
            Menu::firstOrCreate(['location' => $menu['location']], $menu);
        }

        $this->command->info('Default menus seeded.');
    }

    private function seedDefaultLayouts(): void
    {
        Layout::firstOrCreate(
            ['type' => LayoutType::Header->value],
            [
                'is_active' => true,
                'rows' => [
                    ['type' => 'logo', 'data' => ['alt' => 'Logo', 'width' => 150]],
                    ['type' => 'navigation', 'data' => ['menu_location' => 'primary', 'style' => 'horizontal']],
                    ['type' => 'language_switcher', 'data' => ['style' => 'dropdown']],
                    ['type' => 'cta_button', 'data' => ['text' => 'Contact Us', 'url' => '/contact', 'style' => 'primary']],
                ],
            ]
        );

        Layout::firstOrCreate(
            ['type' => LayoutType::Footer->value],
            [
                'is_active' => true,
                'rows' => [
                    ['type' => 'logo', 'data' => ['alt' => 'Logo', 'width' => 120]],
                    ['type' => 'navigation', 'data' => ['menu_location' => 'footer', 'style' => 'vertical']],
                    ['type' => 'social_icons', 'data' => ['links' => []]],
                    ['type' => 'text_block', 'data' => ['content' => '© ' . date('Y') . ' AgencyStack. All rights reserved.', 'alignment' => 'center']],
                ],
            ]
        );

        $this->command->info('Default header & footer layouts seeded.');
    }

    private function seedAdminUser(): void
    {
        $admin = \App\Models\User::firstOrCreate(
            ['email' => env('AGENCY_ADMIN_EMAIL', 'admin@agencystack.test')],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('super_admin');

        $this->command->info('Admin user created: ' . $admin->email);
    }
}
