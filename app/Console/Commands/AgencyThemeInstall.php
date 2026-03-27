<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

final class AgencyThemeInstall extends Command
{
    protected $signature = 'agency:theme:install {theme : Theme name (e.g. corporate, restaurant, portfolio, minimal)}';

    protected $description = 'Install a frontend theme for AgencyStack';

    /** @var array<string, array{label: string, description: string, colors: array<string, string>}> */
    private array $themes = [
        'corporate' => [
            'label' => 'Corporate',
            'description' => 'Professional corporate theme with clean design',
            'colors' => ['primary' => '#1a1a2e', 'secondary' => '#16213e', 'accent' => '#0f3460'],
        ],
        'restaurant' => [
            'label' => 'Restaurant & Cafe',
            'description' => 'Warm, inviting theme perfect for F&B businesses',
            'colors' => ['primary' => '#8B4513', 'secondary' => '#D2691E', 'accent' => '#FFD700'],
        ],
        'portfolio' => [
            'label' => 'Portfolio & Creative',
            'description' => 'Modern creative agency portfolio theme',
            'colors' => ['primary' => '#2d2d2d', 'secondary' => '#1a1a1a', 'accent' => '#ff6b6b'],
        ],
        'minimal' => [
            'label' => 'Minimal',
            'description' => 'Clean minimal design with lots of whitespace',
            'colors' => ['primary' => '#333333', 'secondary' => '#555555', 'accent' => '#0070f3'],
        ],
        'luxury' => [
            'label' => 'Luxury',
            'description' => 'High-end luxury brand feel with gold accents',
            'colors' => ['primary' => '#1c1c1c', 'secondary' => '#2a2a2a', 'accent' => '#c9a84c'],
        ],
    ];

    public function handle(): int
    {
        $themeName = (string) $this->argument('theme');

        if (! array_key_exists($themeName, $this->themes)) {
            $this->error("Theme '{$themeName}' not found.");
            $this->line('Available themes: ' . implode(', ', array_keys($this->themes)));

            return self::FAILURE;
        }

        $theme = $this->themes[$themeName];

        $this->info("Installing theme: {$theme['label']}");
        $this->line($theme['description']);
        $this->newLine();

        // Create theme CSS variables
        $this->createThemeCss($themeName, $theme['colors']);

        // Update .env with theme
        $envPath = base_path('.env');
        $content = file_get_contents($envPath);

        if (str_contains($content, 'AGENCY_THEME=')) {
            $content = preg_replace('/^AGENCY_THEME=.*/m', "AGENCY_THEME={$themeName}", $content);
        } else {
            $content .= "\nAGENCY_THEME={$themeName}";
        }

        file_put_contents($envPath, $content);

        $this->info("✅ Theme '{$theme['label']}' installed.");
        $this->line("Run 'npm run build' to compile the new theme assets.");

        return self::SUCCESS;
    }

    /** @param array<string, string> $colors */
    private function createThemeCss(string $themeName, array $colors): void
    {
        $cssContent = <<<CSS
        /* AgencyStack Theme: {$themeName} */
        :root {
            --color-primary: {$colors['primary']};
            --color-secondary: {$colors['secondary']};
            --color-accent: {$colors['accent']};
        }
        CSS;

        $themeDir = resource_path('css/themes');
        File::ensureDirectoryExists($themeDir);
        File::put("{$themeDir}/{$themeName}.css", $cssContent);

        $this->info("Theme CSS written to resources/css/themes/{$themeName}.css");
    }
}
