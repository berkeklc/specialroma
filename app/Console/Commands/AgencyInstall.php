<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Core\App\Models\Menu;
use Modules\Core\App\Models\Page;
use Modules\Core\App\Settings\GeneralSettings;
use Modules\Core\Database\Seeders\CoreDatabaseSeeder;
use Modules\Meeting\Database\Seeders\MeetingDatabaseSeeder;
use Modules\QrMenu\Database\Seeders\QrMenuDatabaseSeeder;
use Nwidart\Modules\Facades\Module;
use Throwable;

final class AgencyInstall extends Command
{
    protected $signature = 'agency:install {--force : Skip confirmation prompts}';

    protected $description = 'Interactive wizard to install and configure AgencyStack';

    /** @var array<string, string> */
    private array $availableLanguages = [
        'tr' => '🇹🇷 Türkçe',
        'en' => '🇬🇧 English',
        'de' => '🇩🇪 Deutsch',
        'fr' => '🇫🇷 Français',
        'ar' => '🇸🇦 العربية',
        'ru' => '🇷🇺 Русский',
        'es' => '🇪🇸 Español',
        'it' => '🇮🇹 Italiano',
        'nl' => '🇳🇱 Nederlands',
        'pt' => '🇵🇹 Português',
    ];

    /** @var array<string, array{label: string, description: string}> */
    private array $availableModules = [
        'Blog' => ['label' => 'Blog / News',            'description' => 'Articles, categories, tags, scheduled publishing'],
        'Services' => ['label' => 'Services',               'description' => 'Service listings with pricing and features'],
        'Portfolio' => ['label' => 'Portfolio / Projects',   'description' => 'Project showcase with gallery'],
        'Team' => ['label' => 'Team Members',           'description' => 'Staff profiles with social links'],
        'Contact' => ['label' => 'Contact & Forms',        'description' => 'Contact forms with email notifications'],
        'Meeting' => ['label' => 'Meeting / Booking',      'description' => 'Appointment booking with staff availability'],
        'QrMenu' => ['label' => 'QR Menu',                'description' => 'Restaurant/cafe QR menu system'],
    ];

    /** @var array<string, array{label: string, description: string, colors: array<string,string>}> */
    private array $availableThemes = [
        'corporate' => ['label' => 'Corporate',           'description' => 'Professional, clean business theme',       'colors' => ['primary' => '#1a1a2e', 'accent' => '#4f46e5']],
        'restaurant' => ['label' => 'Restaurant & Cafe',   'description' => 'Warm, inviting F&B theme',                'colors' => ['primary' => '#78350f', 'accent' => '#d97706']],
        'portfolio' => ['label' => 'Portfolio & Creative', 'description' => 'Modern creative agency theme',            'colors' => ['primary' => '#111827', 'accent' => '#f43f5e']],
        'minimal' => ['label' => 'Minimal',             'description' => 'Clean whitespace-focused design',         'colors' => ['primary' => '#1f2937', 'accent' => '#0ea5e9']],
        'luxury' => ['label' => 'Luxury',              'description' => 'Premium with gold accents',               'colors' => ['primary' => '#1c1c1c', 'accent' => '#c9a84c']],
    ];

    public function handle(): int
    {
        $this->printBanner();

        $isAlreadyInstalled = $this->checkIfInstalled();

        if ($isAlreadyInstalled && ! $this->option('force')) {
            $this->warn('AgencyStack appears to already be installed.');
            if (! $this->confirm('Run installer again? This is safe — it will not overwrite existing data.', false)) {
                $this->line('Aborted. Use <comment>--force</comment> to skip this check.');

                return self::SUCCESS;
            }
        }

        // ─── Step 1: Site Info ─────────────────────────────────────────────
        $this->section('Step 1 / 5 — Site Information');

        $siteName = $this->ask('Site name', $this->currentSetting('general.site_name', config('app.name', 'AgencyStack')));
        $siteTagline = $this->ask('Site tagline (optional)', $this->currentSetting('general.site_tagline', ''));
        $contactEmail = $this->ask('Contact email', $this->currentSetting('general.contact_email', ''));
        $contactPhone = $this->ask('Contact phone (optional)', $this->currentSetting('general.contact_phone', ''));

        // ─── Step 2: Admin Account ─────────────────────────────────────────
        $this->section('Step 2 / 5 — Admin Account');

        $adminEmail = $this->ask('Admin email', env('AGENCY_ADMIN_EMAIL', 'admin@'.$this->siteHost()));
        $adminName = $this->ask('Admin name', 'Super Admin');

        $changePassword = true;
        if ($isAlreadyInstalled) {
            $changePassword = $this->confirm('Change admin password?', false);
        }
        $adminPassword = 'password';
        if ($changePassword) {
            $adminPassword = $this->secret('Admin password (leave blank for "password")') ?: 'password';
        }

        // ─── Step 3: Languages ─────────────────────────────────────────────
        $this->section('Step 3 / 5 — Languages');
        $this->line('TR + EN are selected by default. Space to toggle, Enter to confirm.');
        $this->newLine();

        $langChoices = array_map(
            fn (string $code, string $label) => "{$code}  {$label}",
            array_keys($this->availableLanguages),
            $this->availableLanguages,
        );

        $selectedLangLines = $this->choice(
            'Active languages',
            $langChoices,
            '0,1', // tr, en default
            null,
            true
        );

        $languageCodes = array_values(array_map(
            fn (string $line) => explode('  ', $line)[0],
            $selectedLangLines
        ));

        $defaultLanguage = $this->choice(
            'Default language',
            $languageCodes,
            array_search('tr', $languageCodes) !== false ? (string) array_search('tr', $languageCodes) : '0'
        );

        // ─── Step 4: Modules ───────────────────────────────────────────────
        $this->section('Step 4 / 5 — Optional Modules');
        $this->line('Core is always enabled. Select additional modules:');
        $this->newLine();

        $moduleChoiceLines = array_map(
            fn (string $key, array $m) => sprintf('%-12s %s', $key, $m['label'].' — '.$m['description']),
            array_keys($this->availableModules),
            $this->availableModules,
        );

        $selectedModuleLines = $this->choice(
            'Modules to install',
            $moduleChoiceLines,
            null,
            null,
            true
        );

        $selectedModuleKeys = array_map(
            fn (string $line) => trim(explode(' ', $line)[0]),
            $selectedModuleLines
        );

        // ─── Step 5: Theme ─────────────────────────────────────────────────
        $this->section('Step 5 / 5 — Theme');

        $themeChoices = array_map(
            fn (string $key, array $t) => sprintf('%-12s %s', $key, $t['label'].' — '.$t['description']),
            array_keys($this->availableThemes),
            $this->availableThemes,
        );

        $selectedThemeLine = $this->choice('Select a theme', $themeChoices, 0);
        $selectedTheme = trim(explode(' ', $selectedThemeLine)[0]);

        // ─── Summary ──────────────────────────────────────────────────────
        $this->newLine();
        $this->line('<fg=yellow;options=bold>📋 Configuration Summary</>');
        $this->table(
            ['Setting', 'Value'],
            [
                ['Site Name',        $siteName],
                ['Tagline',          $siteTagline ?: '—'],
                ['Contact Email',    $contactEmail ?: '—'],
                ['Admin Email',      $adminEmail],
                ['Languages',        implode(', ', array_map('strtoupper', $languageCodes))],
                ['Default Language', strtoupper($defaultLanguage)],
                ['Modules',          $selectedModuleKeys ? implode(', ', $selectedModuleKeys) : 'None'],
                ['Theme',            $this->availableThemes[$selectedTheme]['label']],
            ]
        );

        if (! $this->option('force') && ! $this->confirm('Proceed with installation?', true)) {
            $this->warn('Installation cancelled.');

            return self::FAILURE;
        }

        // ─── Execute Installation ─────────────────────────────────────────
        $this->newLine();
        $this->line('<fg=green;options=bold>⚙  Installing AgencyStack...</>');
        $this->newLine();

        $this->step('Updating .env', fn () => $this->updateEnv([
            'APP_NAME' => '"'.$siteName.'"',
            'APP_LOCALE' => $defaultLanguage,
            'APP_FALLBACK_LOCALE' => 'en',
            'AGENCY_DEFAULT_LANGUAGES' => implode(',', $languageCodes),
            'AGENCY_ADMIN_EMAIL' => $adminEmail,
            'AGENCY_THEME' => $selectedTheme,
        ]));

        $this->step('Running database migrations', function (): void {
            Artisan::call('migrate', ['--force' => true]);
            $output = trim(Artisan::output());
            if ($output) {
                $this->line('   '.str_replace("\n", "\n   ", $output));
            }
        });

        $this->step('Seeding default data (roles, menus, layouts)', function (): void {
            Artisan::call('db:seed', [
                '--class' => CoreDatabaseSeeder::class,
                '--force' => true,
            ]);
        });

        $this->step('Creating / updating admin account', function () use ($adminEmail, $adminName, $adminPassword): void {
            $user = User::updateOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => $adminName,
                    'password' => Hash::make($adminPassword),
                    'email_verified_at' => now(),
                ]
            );
            $user->syncRoles(['super_admin']);
        });

        $this->step('Saving settings', function () use ($siteName, $siteTagline, $contactEmail, $contactPhone, $languageCodes, $defaultLanguage): void {
            $general = app(GeneralSettings::class);
            $general->site_name = $siteName;
            $general->site_tagline = $siteTagline;
            $general->contact_email = $contactEmail ?: null;
            $general->contact_phone = $contactPhone ?: null;
            $general->active_languages = $languageCodes;
            $general->default_language = $defaultLanguage;
            $general->save();
        });

        $this->step('Installing theme: '.$this->availableThemes[$selectedTheme]['label'], function () use ($selectedTheme): void {
            Artisan::call('agency:theme:install', ['theme' => $selectedTheme]);
        });

        foreach ($selectedModuleKeys as $moduleKey) {
            $this->step("Enabling module: {$moduleKey}", function () use ($moduleKey): void {
                if (! Module::isEnabled($moduleKey)) {
                    Artisan::call('module:enable', ['module' => $moduleKey]);
                }
                Artisan::call('module:migrate', ['module' => $moduleKey, '--force' => true]);
            });

            // Seed default data for modules that need it out-of-the-box.
            if ($moduleKey === 'QrMenu') {
                $this->step('Seeding default QR Menu restaurant & sample data', function (): void {
                    $seeder = new QrMenuDatabaseSeeder;
                    $seeder->setCommand($this);
                    $seeder->run();
                });
            }

            if ($moduleKey === 'Meeting') {
                $this->step('Seeding default Meeting staff', function (): void {
                    $seeder = new MeetingDatabaseSeeder;
                    $seeder->setCommand($this);
                    $seeder->run();
                });
            }
        }

        $this->step('Linking storage', function (): void {
            if (! file_exists(public_path('storage'))) {
                Artisan::call('storage:link');
            }
        });

        $this->step('Publishing Livewire & Filament assets', function (): void {
            Artisan::call('vendor:publish', ['--tag' => 'livewire:assets', '--force' => true]);
            Artisan::call('filament:upgrade');
        });

        $this->step('Seeding default homepage & navigation menus', function () use ($siteName, $languageCodes, $defaultLanguage): void {
            $this->seedDefaultContent($siteName, $languageCodes, $defaultLanguage);
        });

        $this->step('Clearing all caches', function (): void {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
        });

        // ─── Done ─────────────────────────────────────────────────────────
        $this->newLine();
        $this->line('┌─────────────────────────────────────────────┐');
        $this->line('│                                             │');
        $this->line('│   <fg=green;options=bold>✅  AgencyStack installed successfully!</>    │');
        $this->line('│                                             │');
        $this->line('└─────────────────────────────────────────────┘');
        $this->newLine();
        $this->line('  <fg=cyan>🌐  Site:</>         <options=bold>'.config('app.url').'</>');
        $this->line('  <fg=cyan>⚙   Admin panel:</> <options=bold>'.config('app.url').'/admin</>');
        $this->line("  <fg=cyan>📧  Admin email:</>  <options=bold>{$adminEmail}</>");
        $this->line('  <fg=cyan>🎨  Theme:</>        <options=bold>'.$this->availableThemes[$selectedTheme]['label'].'</>');
        $this->line('  <fg=cyan>🌍  Languages:</>    <options=bold>'.implode(', ', array_map('strtoupper', $languageCodes)).'</>');
        $this->newLine();

        if ($adminPassword === 'password') {
            $this->warn('  ⚠  Admin password is still the default "password" — change it after first login!');
            $this->newLine();
        }

        $this->line('  <fg=gray>Next steps:</>');
        $this->line('  <fg=gray>  1. Run: npm install && npm run build</>');
        $this->line('  <fg=gray>  2. Visit '.config('app.url').'/admin and log in</>');
        $this->line('  <fg=gray>  3. Configure Header / Footer under Design → Header/Footer</>');
        $this->line('  <fg=gray>  4. Create your first page under Content → Pages</>');
        $this->newLine();

        return self::SUCCESS;
    }

    // ─── Helpers ──────────────────────────────────────────────────────────

    private function printBanner(): void
    {
        $this->newLine();
        $this->line('  <fg=blue;options=bold>     _                          ____  _             _    </>');
        $this->line('  <fg=blue;options=bold>    / \   __ _  ___ _ __   ___ / ___|| |_ __ _  ___| | __</>');
        $this->line('  <fg=blue;options=bold>   / _ \ / _` |/ _ \ \'_ \ / __\___ \| __/ _` |/ __| |/ /</>');
        $this->line('  <fg=blue;options=bold>  / ___ \ (_| |  __/ | | | (__ ___) | || (_| | (__|   < </>');
        $this->line('  <fg=blue;options=bold> /_/   \_\__, |\___|_| |_|\___|____/ \__\__,_|\___|_|\_\</>');
        $this->line('  <fg=blue;options=bold>         |___/                                          </>');
        $this->newLine();
        $this->line('  <fg=white;options=bold>Agency Boilerplate for Laravel — v1.0</>');
        $this->line('  <fg=gray>Build any corporate website in minutes.</> ');
        $this->newLine();
        $this->line('  ──────────────────────────────────────────────────────');
        $this->newLine();
    }

    private function section(string $title): void
    {
        $this->newLine();
        $this->line("  <fg=yellow;options=bold>{$title}</>");
        $this->line('  '.str_repeat('─', strlen($title)));
        $this->newLine();
    }

    private function step(string $label, callable $callback): void
    {
        $this->output->write("  <fg=cyan>→</> {$label}... ");
        try {
            $callback();
            $this->line('<fg=green>✓</>');
        } catch (Throwable $e) {
            $this->line('<fg=red>✗</>');
            $this->warn('    '.$e->getMessage());
        }
    }

    private function checkIfInstalled(): bool
    {
        try {
            return DB::table('settings')->where('name', 'site_name')->exists();
        } catch (Throwable) {
            return false;
        }
    }

    private function currentSetting(string $key, string $default = ''): string
    {
        // Spatie settings stores keys without the group prefix (e.g. "site_name" not "general.site_name")
        $bare = str_contains($key, '.') ? substr($key, strpos($key, '.') + 1) : $key;

        try {
            $row = DB::table('settings')->where('name', $bare)->first();
            if ($row) {
                $val = json_decode($row->payload, true);

                return is_string($val) ? $val : $default;
            }
        } catch (Throwable) {
        }

        return $default;
    }

    private function siteHost(): string
    {
        $host = parse_url(config('app.url', 'http://localhost'), PHP_URL_HOST);

        return is_string($host) ? $host : 'agencystack.test';
    }

    /**
     * Create the default homepage and navigation menus if they don't exist yet.
     *
     * @param  list<string>  $languageCodes
     */
    private function seedDefaultContent(string $siteName, array $languageCodes, string $defaultLanguage): void
    {
        // ── Homepage ──────────────────────────────────────────────────────
        $hasTr = in_array('tr', $languageCodes, strict: true);
        $hasEn = in_array('en', $languageCodes, strict: true);

        $homeTitles = [];
        if ($hasTr) {
            $homeTitles['tr'] = 'Anasayfa';
        }
        if ($hasEn) {
            $homeTitles['en'] = 'Home';
        }
        if (empty($homeTitles)) {
            $homeTitles[$defaultLanguage] = 'Home';
        }

        Page::firstOrCreate(
            ['is_home' => true],
            [
                'title' => $homeTitles,
                'slug' => 'home',
                'status' => 'published',
                'is_home' => true,
                'sort_order' => 0,
                'blocks' => [
                    [
                        'type' => 'hero',
                        'data' => [
                            'heading' => $siteName,
                            'subheading' => 'Welcome to our website. We are ready to serve you.',
                            'eyebrow' => 'Welcome',
                            'button_label' => 'Contact us',
                            'button_url' => '/contact',
                            'alignment' => 'left',
                            'min_height' => '85vh',
                        ],
                    ],
                    [
                        'type' => 'cta',
                        'data' => [
                            'heading' => 'Ready to work together?',
                            'subtext' => 'Get in touch and let us help you grow.',
                            'button_label' => 'Get in touch',
                            'button_url' => '/contact',
                            'style' => 'dark',
                        ],
                    ],
                ],
            ]
        );

        // ── Contact page stub ─────────────────────────────────────────────
        $contactTitles = [];
        if ($hasTr) {
            $contactTitles['tr'] = 'İletişim';
        }
        if ($hasEn) {
            $contactTitles['en'] = 'Contact';
        }
        if (empty($contactTitles)) {
            $contactTitles[$defaultLanguage] = 'Contact';
        }

        Page::firstOrCreate(
            ['slug' => 'contact'],
            [
                'title' => $contactTitles,
                'slug' => 'contact',
                'status' => 'published',
                'is_home' => false,
                'sort_order' => 10,
                'blocks' => [
                    [
                        'type' => 'contact-form',
                        'data' => [
                            'title' => $hasTr ? 'İletişime Geçin' : 'Get in touch',
                            'subtitle' => $hasTr ? 'Size yardımcı olmaktan mutluluk duyarız.' : 'We\'d love to hear from you.',
                        ],
                    ],
                ],
            ]
        );

        // ── Header menu items ─────────────────────────────────────────────
        $defaultMenuItems = [
            [
                'link_type' => 'page',
                'page_slug' => '/',
                'url' => '/',
                'open_new_tab' => false,
                'label' => array_filter([
                    'tr' => $hasTr ? 'Anasayfa' : null,
                    'en' => $hasEn ? 'Home' : null,
                ]),
            ],
            [
                'link_type' => 'page',
                'page_slug' => '/contact',
                'url' => '/contact',
                'open_new_tab' => false,
                'label' => array_filter([
                    'tr' => $hasTr ? 'İletişim' : null,
                    'en' => $hasEn ? 'Contact' : null,
                ]),
            ],
        ];

        $primaryMenu = Menu::where('location', 'primary')->first();
        if ($primaryMenu && empty($primaryMenu->items)) {
            $primaryMenu->update(['items' => $defaultMenuItems]);
        }

        $footerMenu = Menu::where('location', 'footer')->first();
        if ($footerMenu && empty($footerMenu->items)) {
            $footerMenu->update(['items' => $defaultMenuItems]);
        }
    }

    /** @param array<string, string> $variables */
    private function updateEnv(array $variables): void
    {
        $envPath = base_path('.env');
        $content = (string) file_get_contents($envPath);

        foreach ($variables as $key => $value) {
            if (preg_match("/^{$key}=/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
            } else {
                $content .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $content);
    }
}
