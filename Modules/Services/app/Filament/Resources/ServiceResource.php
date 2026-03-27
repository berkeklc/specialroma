<?php

declare(strict_types=1);

namespace Modules\Services\App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Modules\Services\App\Filament\Resources\ServiceResource\Pages;
use Modules\Services\App\Models\Service;

final class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Services';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Service')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Content')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug((string) $state)))
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('slug')
                                ->required()
                                ->unique(Service::class, 'slug', ignoreRecord: true),

                            Forms\Components\Select::make('status')
                                ->options(['draft' => 'Draft', 'published' => 'Published'])
                                ->default('draft')
                                ->required(),

                            Forms\Components\TextInput::make('icon')
                                ->label('Icon (heroicon name or SVG class)')
                                ->placeholder('heroicon-o-sparkles'),

                            Forms\Components\TextInput::make('price_from')
                                ->label('Starting Price')
                                ->numeric()
                                ->prefix('₺'),

                            Forms\Components\Toggle::make('is_featured'),
                            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),

                            Forms\Components\Textarea::make('short_description')
                                ->rows(3)
                                ->columnSpanFull(),

                            Forms\Components\RichEditor::make('description')
                                ->columnSpanFull(),

                            Forms\Components\FileUpload::make('featured_image')
                                ->image()->imageEditor()->columnSpanFull(),

                            Forms\Components\Repeater::make('features')
                                ->label('Service Features')
                                ->schema([
                                    Forms\Components\TextInput::make('feature')->required(),
                                ])
                                ->columnSpanFull(),
                        ])
                        ->columns(2),

                    Forms\Components\Tabs\Tab::make('SEO')
                        ->schema([
                            Forms\Components\TextInput::make('meta_title')->maxLength(70)->columnSpanFull(),
                            Forms\Components\Textarea::make('meta_description')->maxLength(160)->rows(3)->columnSpanFull(),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image'),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('price_from')->money('TRY')->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors(['gray' => 'draft', 'success' => 'published']),
                Tables\Columns\IconColumn::make('is_featured')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
