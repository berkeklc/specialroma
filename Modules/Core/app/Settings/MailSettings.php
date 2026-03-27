<?php

declare(strict_types=1);

namespace Modules\Core\App\Settings;

use Spatie\LaravelSettings\Settings;

final class MailSettings extends Settings
{
    public string $mailer = 'log';

    public string $host = '127.0.0.1';

    public int $port = 587;

    public ?string $username = null;

    public ?string $password = null;

    public string $encryption = 'tls';

    public string $from_address = 'hello@example.com';

    public string $from_name = 'AgencyStack';

    public string $admin_notification_email = 'admin@agencystack.test';

    public bool $notify_admin_on_submission = true;

    public bool $notify_user_on_submission = false;

    public static function group(): string
    {
        return 'mail';
    }
}
