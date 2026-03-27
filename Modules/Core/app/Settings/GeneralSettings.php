<?php

declare(strict_types=1);

namespace Modules\Core\App\Settings;

use Spatie\LaravelSettings\Settings;

final class GeneralSettings extends Settings
{
    public string $site_name = 'AgencyStack';

    public string $site_tagline = '';

    public ?string $site_description = null;

    /** image | text */
    public string $logo_type = 'text';

    /** Shown when logo_type is "text" — defaults to site_name if empty */
    public ?string $logo_text = null;

    public ?string $contact_email = null;

    public ?string $contact_phone = null;

    public ?string $contact_address = null;

    /** @var array<string, string> */
    public array $social_links = [];

    /** @var list<string> */
    public array $active_languages = ['tr', 'en'];

    public string $default_language = 'tr';

    public bool $maintenance_mode = false;

    public static function group(): string
    {
        return 'general';
    }
}
