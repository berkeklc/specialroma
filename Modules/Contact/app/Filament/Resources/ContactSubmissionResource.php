<?php

declare(strict_types=1);

namespace Modules\Contact\App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Contact\App\Filament\Resources\ContactSubmissionResource\Pages;
use Modules\Contact\App\Models\ContactSubmission;

final class ContactSubmissionResource extends Resource
{
    protected static ?string $model = ContactSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?string $navigationGroup = 'Contact';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Submission';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'new')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Submission Details')
                ->schema([
                    Forms\Components\TextInput::make('name')->disabled(),
                    Forms\Components\TextInput::make('email')->disabled(),
                    Forms\Components\TextInput::make('phone')->disabled(),
                    Forms\Components\TextInput::make('subject')->disabled(),
                    Forms\Components\Textarea::make('message')->disabled()->rows(5)->columnSpanFull(),
                    Forms\Components\TextInput::make('ip_address')->disabled()->label('IP Address'),
                    Forms\Components\TextInput::make('status')->disabled(),
                    Forms\Components\DateTimePicker::make('created_at')->disabled()->label('Received'),
                    Forms\Components\DateTimePicker::make('read_at')->disabled()->label('Read At'),
                ])
                ->columns(2),

            Forms\Components\Section::make('Admin Notes')
                ->schema([
                    Forms\Components\Textarea::make('admin_notes')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('subject')->limit(40),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors(['danger' => 'new', 'warning' => 'read', 'success' => 'replied']),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['new' => 'New', 'read' => 'Read', 'replied' => 'Replied']),
            ])
            ->actions([
                Tables\Actions\Action::make('mark_read')
                    ->label('Mark Read')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (ContactSubmission $r): bool => $r->status === 'new')
                    ->action(fn (ContactSubmission $r) => $r->markAsRead()),
                Tables\Actions\EditAction::make()->label('View / Reply'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactSubmissions::route('/'),
            'edit' => Pages\EditContactSubmission::route('/{record}/edit'),
        ];
    }
}
