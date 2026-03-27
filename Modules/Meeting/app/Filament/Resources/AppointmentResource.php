<?php

declare(strict_types=1);

namespace Modules\Meeting\App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Modules\Meeting\App\Filament\Resources\AppointmentResource\Pages;
use Modules\Meeting\App\Models\Appointment;
use Modules\Meeting\App\Notifications\AppointmentConfirmedNotification;

final class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Meeting';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Client')
                ->schema([
                    Forms\Components\TextInput::make('client_name')->required(),
                    Forms\Components\TextInput::make('client_email')->email()->required(),
                    Forms\Components\TextInput::make('client_phone')->tel(),
                ])
                ->columns(3),

            Forms\Components\Section::make('Appointment')
                ->schema([
                    Forms\Components\Select::make('staff_id')
                        ->label('Staff Member')
                        ->relationship('staff', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),

                    Forms\Components\Select::make('status')
                        ->options(['pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled', 'completed' => 'Completed'])
                        ->default('pending')
                        ->required(),

                    Forms\Components\Select::make('meeting_type')
                        ->options(['zoom' => 'Zoom', 'google_meet' => 'Google Meet', 'teams' => 'MS Teams', 'phone' => 'Phone', 'in_person' => 'In Person'])
                        ->default('zoom')
                        ->required(),

                    Forms\Components\TextInput::make('timezone')->default('Europe/Istanbul'),

                    Forms\Components\DateTimePicker::make('starts_at')->required()->seconds(false),
                    Forms\Components\DateTimePicker::make('ends_at')->required()->seconds(false),

                    Forms\Components\TextInput::make('meeting_link')->url()->label('Meeting Link'),
                    Forms\Components\TextInput::make('meeting_id')->label('Meeting ID'),
                    Forms\Components\TextInput::make('meeting_password')->label('Meeting Password'),

                    Forms\Components\Textarea::make('notes')->rows(3)->columnSpanFull(),
                    Forms\Components\Textarea::make('cancellation_reason')->rows(2)
                        ->visible(fn (Forms\Get $get) => $get('status') === 'cancelled')
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('client_email')->searchable(),
                Tables\Columns\TextColumn::make('staff.name'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors(['warning' => 'pending', 'success' => 'confirmed', 'danger' => 'cancelled', 'gray' => 'completed']),
                Tables\Columns\BadgeColumn::make('meeting_type')
                    ->colors(['primary' => 'zoom', 'success' => 'google_meet', 'info' => 'teams']),
                Tables\Columns\TextColumn::make('starts_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled', 'completed' => 'Completed']),
                Tables\Filters\SelectFilter::make('staff_id')->relationship('staff', 'name'),
            ])
            ->actions([
                Tables\Actions\Action::make('confirm')
                    ->label('Confirm')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Appointment $r): bool => $r->status === 'pending')
                    ->action(function (Appointment $record): void {
                        $record->update(['status' => 'confirmed']);
                        NotificationFacade::route('mail', $record->client_email)
                            ->notify(new AppointmentConfirmedNotification($record));
                        Notification::make()->title('Appointment confirmed. Client notified.')->success()->send();
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('starts_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
