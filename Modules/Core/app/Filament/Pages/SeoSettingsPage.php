<?php

declare(strict_types=1);

namespace Modules\Core\App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Modules\Core\App\Settings\SeoSettings;

final class SeoSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass-circle';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'SEO & Analytics';

    protected static ?int $navigationSort = 11;

    protected static string $view = 'core::filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = app(SeoSettings::class);

        $this->form->fill([
            'default_meta_title' => $settings->default_meta_title,
            'default_meta_description' => $settings->default_meta_description,
            'google_analytics_id' => $settings->google_analytics_id,
            'google_tag_manager_id' => $settings->google_tag_manager_id,
            'facebook_pixel_id' => $settings->facebook_pixel_id,
            'generate_sitemap' => $settings->generate_sitemap,
            'robots_index' => $settings->robots_index,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Default SEO Meta')
                    ->description('These are used when a page does not have its own meta tags set.')
                    ->schema([
                        Forms\Components\TextInput::make('default_meta_title')
                            ->label('Default Meta Title')
                            ->maxLength(70)
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('char_count')
                                    ->label(fn (?string $state): string => strlen((string) $state) . '/70')
                                    ->disabled()
                            )
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('default_meta_description')
                            ->label('Default Meta Description')
                            ->maxLength(160)
                            ->rows(3)
                            ->hint('Max 160 characters')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Analytics & Tracking')
                    ->schema([
                        Forms\Components\TextInput::make('google_analytics_id')
                            ->label('Google Analytics ID')
                            ->placeholder('G-XXXXXXXXXX')
                            ->helperText('Google Analytics 4 Measurement ID'),

                        Forms\Components\TextInput::make('google_tag_manager_id')
                            ->label('Google Tag Manager ID')
                            ->placeholder('GTM-XXXXXXX'),

                        Forms\Components\TextInput::make('facebook_pixel_id')
                            ->label('Facebook Pixel ID')
                            ->placeholder('000000000000000'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Technical SEO')
                    ->schema([
                        Forms\Components\Toggle::make('generate_sitemap')
                            ->label('Auto-generate sitemap.xml')
                            ->helperText('Generates sitemap.xml automatically from published pages.')
                            ->default(true),

                        Forms\Components\Toggle::make('robots_index')
                            ->label('Allow search engine indexing')
                            ->helperText('Disabling adds noindex to all pages.')
                            ->default(true),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $settings = app(SeoSettings::class);

        $settings->default_meta_title = $data['default_meta_title'];
        $settings->default_meta_description = $data['default_meta_description'];
        $settings->google_analytics_id = $data['google_analytics_id'];
        $settings->google_tag_manager_id = $data['google_tag_manager_id'];
        $settings->facebook_pixel_id = $data['facebook_pixel_id'];
        $settings->generate_sitemap = (bool) ($data['generate_sitemap'] ?? true);
        $settings->robots_index = (bool) ($data['robots_index'] ?? true);
        $settings->save();

        Notification::make()
            ->title('SEO settings saved')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save SEO Settings')
                ->action('save'),
        ];
    }
}
