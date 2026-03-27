<?php

declare(strict_types=1);

namespace Modules\QrMenu\App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\QrMenu\App\Filament\Resources\MenuItemResource\Pages;
use Modules\QrMenu\App\Models\MenuCategory;
use Modules\QrMenu\App\Models\MenuItem;

final class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'QR Menu';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('category_id')
                ->label('Category')
                ->options(MenuCategory::with('restaurant')->get()->mapWithKeys(
                    fn (MenuCategory $c) => [$c->id => $c->restaurant->name . ' → ' . $c->name]
                ))
                ->required()
                ->searchable()
                ->live()
                ->afterStateUpdated(function (Forms\Set $set, ?int $state): void {
                    if ($state) {
                        $category = MenuCategory::find($state);
                        $set('restaurant_id', $category?->restaurant_id);
                    }
                }),

            Forms\Components\Hidden::make('restaurant_id'),

            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->rows(3)
                ->columnSpanFull(),

            Forms\Components\TextInput::make('price')
                ->numeric()
                ->prefix('₺')
                ->required()
                ->default(0),

            Forms\Components\FileUpload::make('image')
                ->image()
                ->imageEditor(),

            Forms\Components\TagsInput::make('allergens')
                ->label('Allergens')
                ->placeholder('Add allergen...'),

            Forms\Components\CheckboxList::make('badges')
                ->options([
                    'vegan' => '🌱 Vegan',
                    'vegetarian' => '🥦 Vegetarian',
                    'gluten_free' => '🌾 Gluten Free',
                    'spicy' => '🌶 Spicy',
                    'new' => '✨ New',
                    'popular' => '🔥 Popular',
                    'featured' => '⭐ Featured',
                ])
                ->columns(3),

            Forms\Components\Toggle::make('is_featured')->default(false),
            Forms\Components\Toggle::make('is_available')->default(true),

            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('category.name'),
                Tables\Columns\TextColumn::make('price')
                    ->money('TRY')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')->boolean()->label('Featured'),
                Tables\Columns\IconColumn::make('is_available')->boolean()->label('Available'),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('is_available'),
                Tables\Filters\TernaryFilter::make('is_featured'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
