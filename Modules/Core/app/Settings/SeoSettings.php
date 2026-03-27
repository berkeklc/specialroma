<?php

declare(strict_types=1);

namespace Modules\Core\App\Settings;

use Spatie\LaravelSettings\Settings;

final class SeoSettings extends Settings
{
    public ?string $default_meta_title = null;

    public ?string $default_meta_description = null;

    public ?string $google_analytics_id = null;

    public ?string $google_tag_manager_id = null;

    public ?string $facebook_pixel_id = null;

    public bool $generate_sitemap = true;

    public bool $robots_index = true;

    /** @var array<string, string> */
    public array $default_schema_org = [];

    public static function group(): string
    {
        return 'seo';
    }
}
