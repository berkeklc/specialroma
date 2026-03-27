<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // General settings
        $this->migrator->add('general.site_name', config('app.name', 'AgencyStack'));
        $this->migrator->add('general.site_tagline', '');
        $this->migrator->add('general.site_description', null);
        $this->migrator->add('general.contact_email', null);
        $this->migrator->add('general.contact_phone', null);
        $this->migrator->add('general.contact_address', null);
        $this->migrator->add('general.social_links', []);
        $this->migrator->add('general.active_languages', ['tr', 'en']);
        $this->migrator->add('general.default_language', 'tr');
        $this->migrator->add('general.maintenance_mode', false);

        // SEO settings
        $this->migrator->add('seo.default_meta_title', null);
        $this->migrator->add('seo.default_meta_description', null);
        $this->migrator->add('seo.google_analytics_id', null);
        $this->migrator->add('seo.google_tag_manager_id', null);
        $this->migrator->add('seo.facebook_pixel_id', null);
        $this->migrator->add('seo.generate_sitemap', true);
        $this->migrator->add('seo.robots_index', true);
        $this->migrator->add('seo.default_schema_org', []);

        // Mail settings
        $this->migrator->add('mail.mailer', 'log');
        $this->migrator->add('mail.host', '127.0.0.1');
        $this->migrator->add('mail.port', 587);
        $this->migrator->add('mail.username', null);
        $this->migrator->add('mail.password', null);
        $this->migrator->add('mail.encryption', 'tls');
        $this->migrator->add('mail.from_address', 'hello@example.com');
        $this->migrator->add('mail.from_name', config('app.name', 'AgencyStack'));
        $this->migrator->add('mail.admin_notification_email', env('AGENCY_ADMIN_EMAIL', 'admin@agencystack.test'));
        $this->migrator->add('mail.notify_admin_on_submission', true);
        $this->migrator->add('mail.notify_user_on_submission', false);
    }
};
