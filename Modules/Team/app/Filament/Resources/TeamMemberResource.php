<?php

declare(strict_types=1);

namespace Modules\Team\App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Modules\Team\App\Filament\Resources\TeamMemberResource\Pages;
use Modules\Team\App\Models\TeamMember;

final class TeamMemberResource extends Resource
{
    protected static ?string $model = TeamMember::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Team';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\FileUpload::make('photo')
                ->image()
                ->imageEditor()
                ->avatar()
                ->columnSpanFull(),

            Forms\Components\TextInput::make('name')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),

            Forms\Components\TextInput::make('slug')
                ->required()
                ->unique(TeamMember::class, 'slug', ignoreRecord: true),

            Forms\Components\TextInput::make('position')->required(),

            Forms\Components\TextInput::make('email')->email(),

            Forms\Components\TextInput::make('phone')->tel(),

            Forms\Components\Textarea::make('bio')->rows(4)->columnSpanFull(),

            Forms\Components\Repeater::make('social_links')
                ->schema([
                    Forms\Components\Select::make('platform')
                        ->options(['linkedin' => 'LinkedIn', 'twitter' => 'Twitter/X', 'github' => 'GitHub', 'instagram' => 'Instagram', 'website' => 'Website']),
                    Forms\Components\TextInput::make('url')->url()->required(),
                ])
                ->columns(2)
                ->columnSpanFull(),

            Forms\Components\Toggle::make('is_active')->default(true),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')->circular(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('position'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeamMembers::route('/'),
            'create' => Pages\CreateTeamMember::route('/create'),
            'edit' => Pages\EditTeamMember::route('/{record}/edit'),
        ];
    }
}
