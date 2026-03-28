<?php

declare(strict_types=1);

namespace Modules\Meeting\App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\Meeting\App\Filament\Resources\StaffResource\Pages;
use Modules\Meeting\App\Models\Staff;
use Modules\Team\App\Models\TeamMember;
use Nwidart\Modules\Facades\Module;

final class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'Meeting';

    protected static ?string $navigationLabel = 'Staff';

    protected static ?int $navigationSort = 1;

    /**
     * Default weekly schedule for new records (Mon–Fri on; weekend off).
     *
     * @return array<string, array{enabled: bool, start: string, end: string}>
     */
    public static function defaultWorkingHours(): array
    {
        $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $out = [];
        foreach ($days as $day) {
            $weekday = in_array($day, $weekdays, true);
            $out[$day] = [
                'enabled' => $weekday,
                'start' => '09:00',
                'end' => '17:00',
            ];
        }

        return $out;
    }

    public static function form(Form $form): Form
    {
        $dayFieldsets = [];
        foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
            $weekday = in_array($day, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'], true);
            $dayFieldsets[] = Forms\Components\Fieldset::make(ucfirst($day))
                ->schema([
                    Forms\Components\Toggle::make("working_hours.{$day}.enabled")
                        ->label('Available')
                        ->default($weekday),
                    Forms\Components\TimePicker::make("working_hours.{$day}.start")
                        ->seconds(false)
                        ->default('09:00'),
                    Forms\Components\TimePicker::make("working_hours.{$day}.end")
                        ->seconds(false)
                        ->default('17:00'),
                ])
                ->columns(3);
        }

        return $form
            ->schema([
                Forms\Components\Section::make('Profile')
                    ->description('Shown on the public /book flow. Name and email are required.')
                    ->schema([
                        Forms\Components\Select::make('team_member_id')
                            ->label('Link to Team profile')
                            ->helperText('Optional. When you pick someone from Team, name, email, phone and job title are filled in — you can edit them.')
                            ->options(function (): array {
                                if (! Module::isEnabled('Team')) {
                                    return [];
                                }

                                return TeamMember::query()
                                    ->where('is_active', true)
                                    ->orderBy('sort_order')
                                    ->get()
                                    ->mapWithKeys(fn (TeamMember $m): array => [$m->id => (string) $m->name])
                                    ->all();
                            })
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (?string $state, Forms\Set $set): void {
                                if (! $state || ! Module::isEnabled('Team')) {
                                    return;
                                }
                                $member = TeamMember::find($state);
                                if (! $member instanceof TeamMember) {
                                    return;
                                }
                                $set('name', (string) $member->name);
                                if ($member->email) {
                                    $set('email', $member->email);
                                }
                                if ($member->phone) {
                                    $set('phone', $member->phone);
                                }
                                $positions = $member->getTranslations('position');
                                if ($positions !== []) {
                                    $set('title', $positions);
                                }
                            })
                            ->visible(fn (): bool => Module::isEnabled('Team'))
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(50),

                        Forms\Components\TextInput::make('title')
                            ->label('Job title')
                            ->maxLength(255)
                            ->helperText('Translatable — use your active locales like other content.'),

                        Forms\Components\Textarea::make('bio')
                            ->rows(3)
                            ->columnSpanFull()
                            ->helperText('Optional. Translatable.'),

                        Forms\Components\TagsInput::make('expertise')
                            ->placeholder('Add tag')
                            ->separator(',')
                            ->columnSpanFull(),

                        Forms\Components\SpatieMediaLibraryFileUpload::make('photo')
                            ->label('Photo')
                            ->collection('photo')
                            ->image()
                            ->imageEditor()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('meeting_duration')
                            ->label('Slot length (minutes)')
                            ->numeric()
                            ->default(30)
                            ->minValue(5)
                            ->maxValue(480)
                            ->required(),

                        Forms\Components\TextInput::make('buffer_time')
                            ->label('Buffer between meetings (minutes)')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(120),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Accepts public bookings')
                            ->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Working hours')
                    ->description('Used to build available time slots on /book.')
                    ->schema($dayFieldsets)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('teamMember.name')
                    ->label('Team link')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')->boolean()->label('Bookable'),
                Tables\Columns\TextColumn::make('meeting_duration')->label('Min')->suffix(' min'),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Accepts bookings'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('name');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('teamMember');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStaff::route('/'),
            'create' => Pages\CreateStaff::route('/create'),
            'edit' => Pages\EditStaff::route('/{record}/edit'),
        ];
    }
}
