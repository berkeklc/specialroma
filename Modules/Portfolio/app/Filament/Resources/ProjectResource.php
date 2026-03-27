<?php

declare(strict_types=1);

namespace Modules\Portfolio\App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Modules\Portfolio\App\Filament\Resources\ProjectResource\Pages;
use Modules\Portfolio\App\Models\Project;

final class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';

    protected static ?string $navigationGroup = 'Portfolio';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Project')->tabs([
                Forms\Components\Tabs\Tab::make('Content')->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug((string) $state)))
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('slug')->required()->unique(Project::class, 'slug', ignoreRecord: true),
                    Forms\Components\Select::make('status')->options(['draft' => 'Draft', 'published' => 'Published'])->default('draft')->required(),
                    Forms\Components\Select::make('category_id')->label('Category')->relationship('category', 'name')->searchable()->preload(),
                    Forms\Components\TextInput::make('client_name')->label('Client Name'),
                    Forms\Components\TextInput::make('client_url')->label('Client URL')->url(),
                    Forms\Components\DatePicker::make('completed_at')->label('Completion Date'),
                    Forms\Components\TagsInput::make('technologies')->label('Technologies Used'),
                    Forms\Components\Toggle::make('is_featured'),
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                    Forms\Components\Textarea::make('short_description')->rows(3)->columnSpanFull(),
                    Forms\Components\RichEditor::make('description')->columnSpanFull(),
                    Forms\Components\FileUpload::make('cover_image')->image()->imageEditor()->columnSpanFull(),
                ])->columns(2),

                Forms\Components\Tabs\Tab::make('SEO')->schema([
                    Forms\Components\TextInput::make('meta_title')->maxLength(70)->columnSpanFull(),
                    Forms\Components\Textarea::make('meta_description')->maxLength(160)->rows(3)->columnSpanFull(),
                ]),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category.name')->badge(),
                Tables\Columns\TextColumn::make('client_name'),
                Tables\Columns\BadgeColumn::make('status')->colors(['gray' => 'draft', 'success' => 'published']),
                Tables\Columns\IconColumn::make('is_featured')->boolean(),
                Tables\Columns\TextColumn::make('completed_at')->date()->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
