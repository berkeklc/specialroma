<?php

declare(strict_types=1);

namespace Modules\Core\App\Actions;

use Illuminate\Support\Facades\App;
use Modules\Core\App\Models\Page;
use Modules\Core\App\Settings\GeneralSettings;
use Modules\Core\App\Settings\SeoSettings;

final class GenerateSeoMeta
{
    public function __construct(
        private readonly GeneralSettings $generalSettings,
        private readonly SeoSettings $seoSettings,
    ) {}

    /**
     * @return array{
     *     title: string,
     *     description: string|null,
     *     og_title: string,
     *     og_description: string|null,
     *     og_image: string|null,
     *     canonical: string,
     *     hreflang: array<string, string>,
     *     schema_org: string|null,
     *     robots: string,
     * }
     */
    public function execute(?Page $page = null): array
    {
        $locale = App::getLocale();
        $siteName = $this->generalSettings->site_name ?? config('app.name');
        $separator = config('core.seo.default_title_separator', ' | ');

        $title = $page
            ? (($page->getTranslation('meta_title', $locale) ?: $page->getTranslation('title', $locale)) . $separator . $siteName)
            : ($this->seoSettings->default_meta_title ?? $siteName);

        $description = $page
            ? ($page->getTranslation('meta_description', $locale) ?: $this->seoSettings->default_meta_description)
            : $this->seoSettings->default_meta_description;

        $ogTitle = $page?->getTranslation('og_title', $locale) ?: $title;
        $ogDescription = $page?->getTranslation('og_description', $locale) ?: $description;
        $ogImage = $page?->og_image ?? null;

        return [
            'title' => $title,
            'description' => $description,
            'og_title' => $ogTitle,
            'og_description' => $ogDescription,
            'og_image' => $ogImage,
            'canonical' => request()->url(),
            'hreflang' => $this->buildHreflangTags($page),
            'schema_org' => $this->buildSchemaOrg($page),
            'robots' => $this->seoSettings->robots_index ?? true ? 'index, follow' : 'noindex, nofollow',
        ];
    }

    /** @return array<string, string> */
    private function buildHreflangTags(?Page $page): array
    {
        if (! config('core.seo.generate_hreflang', true)) {
            return [];
        }

        $activeLanguages = config('core.active_languages', ['tr', 'en']);
        $hreflang = [];

        foreach ($activeLanguages as $lang) {
            $hreflang[$lang] = route('home', [], true);
        }

        return $hreflang;
    }

    private function buildSchemaOrg(?Page $page): ?string
    {
        if ($page?->schema_org) {
            return json_encode($page->schema_org, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $this->generalSettings->site_name ?? config('app.name'),
            'url' => config('app.url'),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => $this->generalSettings->contact_phone,
                'email' => $this->generalSettings->contact_email,
                'contactType' => 'customer service',
            ],
        ];

        if ($this->generalSettings->social_links ?? false) {
            $schema['sameAs'] = array_values($this->generalSettings->social_links);
        }

        return json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
