<?php

declare(strict_types=1);

namespace Modules\Core\App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Blog\App\Models\Post;
use Modules\Core\App\Filament\Resources\MenuResource\Pages;
use Modules\Core\App\Models\Menu;
use Modules\Core\App\Models\Page;
use Modules\Portfolio\App\Models\Project;
use Modules\QrMenu\App\Models\Restaurant;
use Modules\Services\App\Models\Service;
use Nwidart\Modules\Facades\Module;

final class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationGroup = 'Design';

    protected static ?string $navigationLabel = 'Navigation Menus';

    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'location';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Menu Details')
                ->schema([
                    Forms\Components\Select::make('location')
                        ->label('Menu Location')
                        ->options([
                            'primary' => 'Header — Primary Navigation',
                            'footer' => 'Footer Navigation',
                        ])
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->helperText('Each location can only have one active menu.'),

                    Forms\Components\TextInput::make('label.tr')
                        ->label('Menu Label (TR)')
                        ->maxLength(100),

                    Forms\Components\TextInput::make('label.en')
                        ->label('Menu Label (EN)')
                        ->maxLength(100),
                ])
                ->columns(3),

            Forms\Components\Section::make('Menu Items')
                ->description('Add links to pages, external URLs, or anchors. Items appear in the order listed.')
                ->schema([
                    Forms\Components\Repeater::make('items')
                        ->label('')
                        ->schema(self::menuItemSchema())
                        ->addActionLabel('Add menu item')
                        ->reorderable()
                        ->reorderableWithButtons()
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['label']['en']
                            ?? $state['label']['tr']
                            ?? $state['url']
                            ?? null
                        )
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('location')
                    ->label('Location')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'primary' => 'success',
                        'footer' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'primary' => 'Header',
                        'footer' => 'Footer',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('items')
                    ->label('Items')
                    ->formatStateUsing(fn ($state): string => count($state ?? []).' items')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last updated')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('preview')
                    ->label('Preview site')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(config('app.url'), true)
                    ->color('gray'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('preview_site')
                    ->label('Preview site')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(config('app.url'), true)
                    ->color('gray'),
            ])
            ->defaultSort('location');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }

    /** @return array<int, Forms\Components\Component> */
    private static function menuItemSchema(): array
    {
        return [
            Forms\Components\Grid::make(12)->schema([
                // Link type selector
                Forms\Components\Select::make('link_type')
                    ->label('Link type')
                    ->options([
                        'page' => '📄 Internal page',
                        'url' => '🔗 External URL',
                        'anchor' => '⚓ Anchor (#)',
                    ])
                    ->default('page')
                    ->selectablePlaceholder(false)
                    ->live()
                    ->afterStateUpdated(fn (Forms\Set $set) => $set('url', null))
                    ->columnSpan(2),

                // Page picker — only when link_type = page
                Forms\Components\Select::make('page_slug')
                    ->label('Page / Section')
                    ->options(fn () => self::allPublicPages())
                    ->searchable()
                    ->placeholder('Search or browse pages…')
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set, ?string $state): void {
                        if ($state && ! str_starts_with($state, 'group:')) {
                            $url = str_starts_with($state, 'http')
                                ? $state
                                : '/'.ltrim($state, '/');
                            $set('url', $url);
                        }
                    })
                    ->visible(fn (Forms\Get $get) => $get('link_type') === 'page')
                    ->columnSpan(5),

                // URL / anchor field — always present; adapts label/placeholder to type
                Forms\Components\TextInput::make('url')
                    ->label(fn (Forms\Get $get) => match ($get('link_type')) {
                        'anchor' => 'Anchor',
                        'page' => 'URL',
                        default => 'URL',
                    })
                    ->placeholder(fn (Forms\Get $get) => match ($get('link_type')) {
                        'anchor' => '#section-id',
                        'page' => 'Auto-filled — override if needed',
                        default => 'https://example.com  or  /custom-path',
                    })
                    ->helperText(fn (Forms\Get $get) => $get('link_type') === 'page'
                        ? 'Filled automatically when you pick a page above.'
                        : null
                    )
                    ->columnSpan(fn (Forms\Get $get) => $get('link_type') === 'page' ? 3 : 8),

                Forms\Components\Toggle::make('open_new_tab')
                    ->label('New tab')
                    ->default(false)
                    ->columnSpan(2),
            ]),

            // Multi-language labels
            Forms\Components\Grid::make(4)->schema([
                Forms\Components\TextInput::make('label.tr')
                    ->label('Label (TR)')
                    ->placeholder('Anasayfa'),

                Forms\Components\TextInput::make('label.en')
                    ->label('Label (EN)')
                    ->placeholder('Home'),

                Forms\Components\TextInput::make('label.de')
                    ->label('Label (DE)')
                    ->placeholder('Startseite'),

                Forms\Components\TextInput::make('label.fr')
                    ->label('Label (FR)')
                    ->placeholder('Accueil'),
            ]),
        ];
    }

    /**
     * Build a flat options array for the page-picker Select.
     *
     * Keys starting with 'group:' act as non-selectable visual dividers.
     * Module models are referenced via fully-qualified names inside try/catch
     * to prevent fatal errors when a module is disabled or not yet migrated.
     *
     * @return array<string, string>
     */
    private static function allPublicPages(): array
    {
        $locale = app()->getLocale();
        $options = [];

        // ── Core CMS pages ────────────────────────────────────────────────────
        $options['group:core'] = '── Core Pages ──────────────────';
        $options['/'] = '🏠 Home';

        $pages = Page::query()
            ->where('status', 'published')
            ->where('is_home', false)
            ->orderBy('sort_order')
            ->get();

        foreach ($pages as $page) {
            $label = $page->getTranslation('title', $locale, useFallbackLocale: true);
            $options['/'.$page->slug] = $label;
        }

        // ── Blog ──────────────────────────────────────────────────────────────
        if (Module::isEnabled('Blog')) {
            try {
                $options['group:blog'] = '── Blog ────────────────────────';
                $options['/blog'] = '📝 Blog (index)';

                $posts = Post::where('status', 'published')
                    ->orderByDesc('published_at')
                    ->limit(50)
                    ->get();

                foreach ($posts as $post) {
                    $title = $post->getTranslation('title', $locale, useFallbackLocale: true);
                    $options['/blog/'.$post->slug] = '   ↳ '.$title;
                }
            } catch (\Throwable) {
            }
        }

        // ── Services ──────────────────────────────────────────────────────────
        if (Module::isEnabled('Services')) {
            try {
                $options['group:services'] = '── Services ────────────────────';
                $options['/services'] = '⚙️ Services (index)';

                $services = Service::where('status', 'published')
                    ->orderBy('sort_order')
                    ->get();

                foreach ($services as $svc) {
                    $title = $svc->getTranslation('title', $locale, useFallbackLocale: true);
                    $options['/services/'.$svc->slug] = '   ↳ '.$title;
                }
            } catch (\Throwable) {
            }
        }

        // ── Portfolio ─────────────────────────────────────────────────────────
        if (Module::isEnabled('Portfolio')) {
            try {
                $options['group:portfolio'] = '── Portfolio ───────────────────';
                $options['/portfolio'] = '🖼️ Portfolio (index)';

                $projects = Project::where('status', 'published')
                    ->orderBy('sort_order')
                    ->get();

                foreach ($projects as $project) {
                    $title = $project->getTranslation('title', $locale, useFallbackLocale: true);
                    $options['/portfolio/'.$project->slug] = '   ↳ '.$title;
                }
            } catch (\Throwable) {
            }
        }

        // ── Team ──────────────────────────────────────────────────────────────
        if (Module::isEnabled('Team')) {
            $options['group:team'] = '── Team ────────────────────────';
            $options['/team'] = '👥 Team (index)';
        }

        // ── Contact ───────────────────────────────────────────────────────────
        if (Module::isEnabled('Contact') && ! isset($options['/contact'])) {
            $options['group:contact'] = '── Contact ─────────────────────';
            $options['/contact'] = '✉️ Contact page';
        }

        // ── Meeting / Booking ─────────────────────────────────────────────────
        if (Module::isEnabled('Meeting')) {
            $options['group:meeting'] = '── Meeting / Booking ───────────';
            $options['/book'] = '📅 Book an appointment';
        }

        // ── QR Menu restaurants ───────────────────────────────────────────────
        if (Module::isEnabled('QrMenu')) {
            try {
                $restaurants = Restaurant::with('tables')
                    ->where('is_active', true)
                    ->get();

                if ($restaurants->isNotEmpty()) {
                    $options['group:qrmenu'] = '── QR Menu ─────────────────────';

                    foreach ($restaurants as $restaurant) {
                        $name = $restaurant->getTranslation('name', $locale, useFallbackLocale: true);
                        $table = $restaurant->tables->first();

                        if ($table) {
                            $url = route('qr-menu.public', [
                                'restaurant' => $restaurant->slug,
                                'table' => $table->id,
                            ]);
                            $options[$url] = '🍽️ '.$name.' (QR Menu)';
                        }
                    }
                }
            } catch (\Throwable) {
            }
        }

        return $options;
    }
}
