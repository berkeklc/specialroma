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
use Illuminate\Support\Facades\Mail;
use Modules\Core\App\Settings\MailSettings;

final class MailSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Mail / SMTP';

    protected static ?int $navigationSort = 12;

    protected static string $view = 'core::filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = app(MailSettings::class);

        $this->form->fill([
            'mailer' => $settings->mailer,
            'host' => $settings->host,
            'port' => $settings->port,
            'username' => $settings->username,
            'password' => $settings->password,
            'encryption' => $settings->encryption,
            'from_address' => $settings->from_address,
            'from_name' => $settings->from_name,
            'admin_notification_email' => $settings->admin_notification_email,
            'notify_admin_on_submission' => $settings->notify_admin_on_submission,
            'notify_user_on_submission' => $settings->notify_user_on_submission,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('SMTP Configuration')
                    ->schema([
                        Forms\Components\Select::make('mailer')
                            ->options([
                                'smtp' => 'SMTP',
                                'sendmail' => 'Sendmail',
                                'mailgun' => 'Mailgun',
                                'ses' => 'Amazon SES',
                                'log' => 'Log (Development)',
                                'array' => 'Array (Testing)',
                            ])
                            ->required()
                            ->live(),

                        Forms\Components\Select::make('encryption')
                            ->options(['tls' => 'TLS', 'ssl' => 'SSL', '' => 'None'])
                            ->default('tls'),

                        Forms\Components\TextInput::make('host')
                            ->label('SMTP Host')
                            ->required()
                            ->visible(fn (Forms\Get $get): bool => in_array($get('mailer'), ['smtp', 'sendmail'])),

                        Forms\Components\TextInput::make('port')
                            ->label('SMTP Port')
                            ->numeric()
                            ->default(587)
                            ->visible(fn (Forms\Get $get): bool => in_array($get('mailer'), ['smtp', 'sendmail'])),

                        Forms\Components\TextInput::make('username')
                            ->label('Username')
                            ->visible(fn (Forms\Get $get): bool => $get('mailer') === 'smtp'),

                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->visible(fn (Forms\Get $get): bool => $get('mailer') === 'smtp'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Sender Information')
                    ->schema([
                        Forms\Components\TextInput::make('from_address')
                            ->label('From Email Address')
                            ->email()
                            ->required(),

                        Forms\Components\TextInput::make('from_name')
                            ->label('From Name')
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Notification Settings')
                    ->schema([
                        Forms\Components\TextInput::make('admin_notification_email')
                            ->label('Admin Notification Email')
                            ->email()
                            ->required()
                            ->helperText('Receives email when a form is submitted.'),

                        Forms\Components\Toggle::make('notify_admin_on_submission')
                            ->label('Notify admin on form submission')
                            ->default(true),

                        Forms\Components\Toggle::make('notify_user_on_submission')
                            ->label('Send confirmation email to user on submission')
                            ->default(false),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $settings = app(MailSettings::class);

        $settings->mailer = $data['mailer'];
        $settings->host = $data['host'] ?? '127.0.0.1';
        $settings->port = (int) ($data['port'] ?? 587);
        $settings->username = $data['username'];
        $settings->password = $data['password'];
        $settings->encryption = $data['encryption'] ?? 'tls';
        $settings->from_address = $data['from_address'];
        $settings->from_name = $data['from_name'];
        $settings->admin_notification_email = $data['admin_notification_email'];
        $settings->notify_admin_on_submission = (bool) ($data['notify_admin_on_submission'] ?? true);
        $settings->notify_user_on_submission = (bool) ($data['notify_user_on_submission'] ?? false);
        $settings->save();

        Notification::make()
            ->title('Mail settings saved')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('test_email')
                ->label('Send Test Email')
                ->icon('heroicon-o-paper-airplane')
                ->color('gray')
                ->action('sendTestEmail'),

            Action::make('save')
                ->label('Save Mail Settings')
                ->action('save'),
        ];
    }

    public function sendTestEmail(): void
    {
        $settings = app(MailSettings::class);

        try {
            Mail::raw('This is a test email from AgencyStack.', function ($message) use ($settings): void {
                $message->to($settings->admin_notification_email)
                    ->subject('AgencyStack — Test Email');
            });

            Notification::make()
                ->title('Test email sent to '.$settings->admin_notification_email)
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Failed to send test email')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
