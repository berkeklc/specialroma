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
use Modules\Core\App\Settings\GeneralSettings;

final class GeneralSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'General';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'core::filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = app(GeneralSettings::class);

        $this->form->fill([
            'site_name'        => $settings->site_name,
            'site_tagline'     => $settings->site_tagline,
            'site_description' => $settings->site_description,
            'logo_type'        => $settings->logo_type,
            'logo_text'        => $settings->logo_text,
            'contact_email'    => $settings->contact_email,
            'contact_phone'    => $settings->contact_phone,
            'contact_address'  => $settings->contact_address,
            'social_links'     => $settings->social_links ?? [],
            'active_languages' => $settings->active_languages,
            'default_language' => $settings->default_language,
            'maintenance_mode' => $settings->maintenance_mode,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Site Information')
                    ->schema([
                        Forms\Components\TextInput::make('site_name')
                            ->label('Site Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('site_tagline')
                            ->label('Site Tagline')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('site_description')
                            ->label('Site Description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('maintenance_mode')
                            ->label('Maintenance Mode')
                            ->helperText('When enabled, only admins can view the site.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Logo')
                    ->description('Choose whether to display an image logo (upload in Header Builder) or a text logo.')
                    ->schema([
                        Forms\Components\Select::make('logo_type')
                            ->label('Logo Type')
                            ->options(['text' => 'Text / Wordmark', 'image' => 'Image (upload in Header Builder)'])
                            ->required()
                            ->live(),

                        Forms\Components\TextInput::make('logo_text')
                            ->label('Logo Text')
                            ->helperText('Displayed when Logo Type is set to "Text". Leave blank to use Site Name.')
                            ->maxLength(100)
                            ->visible(fn (Forms\Get $get) => $get('logo_type') === 'text'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('contact_email')
                            ->label('Contact Email')
                            ->email(),

                        Forms\Components\TextInput::make('contact_phone')
                            ->label('Contact Phone')
                            ->tel(),

                        Forms\Components\Textarea::make('contact_address')
                            ->label('Address')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Social Media Links')
                    ->description('Enter the full URL for each platform.')
                    ->schema([
                        Forms\Components\Repeater::make('social_links')
                            ->schema([
                                Forms\Components\Select::make('platform')
                                    ->options([
                                        'facebook' => 'Facebook',
                                        'instagram' => 'Instagram',
                                        'twitter' => 'Twitter / X',
                                        'linkedin' => 'LinkedIn',
                                        'youtube' => 'YouTube',
                                        'tiktok' => 'TikTok',
                                        'whatsapp' => 'WhatsApp',
                                        'pinterest' => 'Pinterest',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('url')
                                    ->label('URL')
                                    ->url()
                                    ->required(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Add Social Link')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Language Settings')
                    ->schema([
                        Forms\Components\CheckboxList::make('active_languages')
                            ->label('Active Languages')
                            ->options([
                                'tr' => '🇹🇷 Türkçe',
                                'en' => '🇬🇧 English',
                                'de' => '🇩🇪 Deutsch',
                                'fr' => '🇫🇷 Français',
                                'ar' => '🇸🇦 العربية',
                                'ru' => '🇷🇺 Русский',
                                'es' => '🇪🇸 Español',
                            ])
                            ->columns(4),

                        Forms\Components\Select::make('default_language')
                            ->label('Default Language')
                            ->options([
                                'tr' => '🇹🇷 Türkçe',
                                'en' => '🇬🇧 English',
                                'de' => '🇩🇪 Deutsch',
                                'fr' => '🇫🇷 Français',
                                'ar' => '🇸🇦 العربية',
                                'ru' => '🇷🇺 Русский',
                                'es' => '🇪🇸 Español',
                            ])
                            ->required(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $settings = app(GeneralSettings::class);

        // Convert repeater social_links to keyed array
        $socialLinks = [];
        foreach ($data['social_links'] ?? [] as $item) {
            if (isset($item['platform'], $item['url'])) {
                $socialLinks[$item['platform']] = $item['url'];
            }
        }

        $settings->site_name        = $data['site_name'];
        $settings->site_tagline     = $data['site_tagline'] ?? '';
        $settings->site_description = $data['site_description'];
        $settings->logo_type        = $data['logo_type'] ?? 'text';
        $settings->logo_text        = $data['logo_text'] ?: null;
        $settings->contact_email    = $data['contact_email'];
        $settings->contact_phone    = $data['contact_phone'];
        $settings->contact_address  = $data['contact_address'];
        $settings->social_links     = $socialLinks;
        $settings->active_languages = $data['active_languages'] ?? ['tr', 'en'];
        $settings->default_language = $data['default_language'];
        $settings->maintenance_mode = (bool) ($data['maintenance_mode'] ?? false);
        $settings->save();

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->action('save'),
        ];
    }
}
