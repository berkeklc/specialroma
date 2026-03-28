<?php

declare(strict_types=1);

namespace Modules\Core\App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Core\App\Enums\LayoutType;
use Modules\Core\App\Filament\Resources\LayoutResource\Pages;
use Modules\Core\App\Models\Layout;

final class LayoutResource extends Resource
{
    protected static ?string $model = Layout::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Design';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Header/Footer';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('type')
                ->options(collect(LayoutType::cases())->mapWithKeys(fn (LayoutType $t) => [$t->value => $t->label()]))
                ->required()
                ->disabled(fn (string $operation): bool => $operation === 'edit'),

            Forms\Components\Toggle::make('is_active')
                ->label('Active')
                ->default(true),

            Forms\Components\Builder::make('rows')
                ->label('Layout Rows')
                ->columnSpanFull()
                ->blocks(self::getLayoutBlocks())
                ->collapsible()
                ->reorderable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('type')
                    ->colors(['primary' => LayoutType::Header->value, 'success' => LayoutType::Footer->value]),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLayouts::route('/'),
            'create' => Pages\CreateLayout::route('/create'),
            'edit' => Pages\EditLayout::route('/{record}/edit'),
        ];
    }

    /** @return array<Block> */
    private static function getLayoutBlocks(): array
    {
        return [
            Block::make('logo')
                ->label('Logo')
                ->icon('heroicon-o-photo')
                ->schema([
                    Forms\Components\FileUpload::make('image')->image()->label('Logo Image'),
                    Forms\Components\TextInput::make('alt')->default('Logo'),
                    Forms\Components\TextInput::make('width')->numeric()->default(150),
                ]),

            Block::make('navigation')
                ->label('Navigation Menu')
                ->icon('heroicon-o-bars-3')
                ->schema([
                    Forms\Components\Select::make('menu_location')
                        ->options(['primary' => 'Primary Menu', 'footer' => 'Footer Menu', 'secondary' => 'Secondary Menu'])
                        ->required(),
                    Forms\Components\Select::make('style')
                        ->options(['horizontal' => 'Horizontal', 'vertical' => 'Vertical', 'dropdown' => 'Dropdown'])
                        ->default('horizontal'),
                ]),

            Block::make('language_switcher')
                ->label('Language Switcher')
                ->icon('heroicon-o-language')
                ->schema([
                    Forms\Components\Select::make('style')
                        ->options(['dropdown' => 'Dropdown', 'flags' => 'Flags', 'text' => 'Text Links'])
                        ->default('dropdown'),
                ]),

            Block::make('social_icons')
                ->label('Social Icons')
                ->icon('heroicon-o-share')
                ->schema([
                    Forms\Components\Repeater::make('links')
                        ->schema([
                            Forms\Components\Select::make('platform')
                                ->options([
                                    'facebook' => 'Facebook', 'instagram' => 'Instagram',
                                    'twitter' => 'Twitter/X', 'linkedin' => 'LinkedIn',
                                    'youtube' => 'YouTube', 'tiktok' => 'TikTok',
                                    'whatsapp' => 'WhatsApp',
                                ]),
                            Forms\Components\TextInput::make('url')->required()->helperText('Use relative (/contact) or absolute (https://...) URLs, or anchors (#section)'),
                        ])
                        ->columns(2),
                ]),

            Block::make('contact_info')
                ->label('Contact Info')
                ->icon('heroicon-o-phone')
                ->schema([
                    Forms\Components\TextInput::make('phone'),
                    Forms\Components\TextInput::make('email')->email(),
                    Forms\Components\Textarea::make('address'),
                ]),

            Block::make('cta_button')
                ->label('CTA Button')
                ->icon('heroicon-o-cursor-arrow-ripple')
                ->schema([
                    Forms\Components\TextInput::make('text')->required(),
                    Forms\Components\TextInput::make('url')->required()->helperText('Use relative (/contact) or absolute (https://...) URLs, or anchors (#section)'),
                    Forms\Components\Select::make('style')
                        ->options(['primary' => 'Primary', 'secondary' => 'Secondary', 'outline' => 'Outline'])
                        ->default('primary'),
                ]),

            Block::make('text_block')
                ->label('Text / Copyright')
                ->icon('heroicon-o-document-text')
                ->schema([
                    Forms\Components\Textarea::make('content')->required(),
                    Forms\Components\Select::make('alignment')
                        ->options(['left' => 'Left', 'center' => 'Center', 'right' => 'Right'])
                        ->default('center'),
                ]),

            Block::make('search_bar')
                ->label('Search Bar')
                ->icon('heroicon-o-magnifying-glass')
                ->schema([
                    Forms\Components\TextInput::make('placeholder')->default('Search...'),
                ]),
        ];
    }
}
