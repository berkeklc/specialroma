<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta -------------------------------------------------------- --}}
    @php
        $seoSettings = app(\Modules\Core\App\Settings\SeoSettings::class);
        $generalSettings = app(\Modules\Core\App\Settings\GeneralSettings::class);
        $pageTitle = $seoTitle ?? $seoSettings->default_meta_title ?? $generalSettings->site_name;
        $pageDescription = $seoDescription ?? $seoSettings->default_meta_description ?? $generalSettings->site_tagline;
        $siteName = $generalSettings->site_name;
        $canonicalUrl = $canonicalUrl ?? request()->url();
        $ogImage = $ogImage ?? null;
        $theme = config('core.theme', env('AGENCY_THEME', 'corporate'));
    @endphp

    <title>{{ $pageTitle && $pageTitle !== $siteName ? "{$pageTitle} — {$siteName}" : $siteName }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    @if ($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
        <meta name="twitter:card" content="summary_large_image">
    @else
        <meta name="twitter:card" content="summary">
    @endif

    {{-- Hreflang --}}
    @foreach ($generalSettings->active_languages as $lang)
        <link rel="alternate" hreflang="{{ $lang }}" href="{{ request()->url() . '?lang=' . $lang }}">
    @endforeach

    {{-- Schema.org JSON-LD --}}
    @if (!empty($seoSettings->default_schema_org))
        <script type="application/ld+json">{!! json_encode($seoSettings->default_schema_org, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    @endif
    @stack('schema_org')

    {{-- Robots --}}
    @if (!$seoSettings->robots_index)
        <meta name="robots" content="noindex, nofollow">
    @endif

    {{-- Analytics --}}
    @if ($seoSettings->google_analytics_id)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $seoSettings->google_analytics_id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $seoSettings->google_analytics_id }}');
        </script>
    @endif
    @if ($seoSettings->google_tag_manager_id)
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{{ $seoSettings->google_tag_manager_id }}');</script>
    @endif

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body data-theme="{{ $theme }}">

    {{-- GTM noscript --}}
    @if ($seoSettings->google_tag_manager_id)
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $seoSettings->google_tag_manager_id }}"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    {{-- Skip to content --}}
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:z-[999] focus:p-3 focus:bg-accent focus:text-white">
        {{ __('Skip to content') }}
    </a>

    {{-- Header --}}
    @livewire('core::site-header')

    {{-- Main --}}
    <main id="main-content">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    @livewire('core::site-footer')

    @livewireScripts
    @stack('scripts')
</body>
</html>
