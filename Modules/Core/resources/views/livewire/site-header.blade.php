<header class="site-header" x-data="siteHeader()" :class="{ 'scrolled': scrolled, 'site-header--glass': scrolled }"
    role="banner">
    <div class="container-site">
        <div class="header-inner">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="header-logo header-logo--roma" aria-label="{{ $settings->site_name }}">
                <img src="{{ asset('roma-logo.png') }}" alt="{{ $settings->site_name }}" class="header-logo__img"
                    width="140" height="50">
            </a>

            {{-- Desktop navigation --}}
            @if ($primaryMenu && !empty($primaryMenu->items))
                <nav aria-label="{{ __('Primary navigation') }}" class="header-nav" id="header-desktop-nav">
                    <ul role="list" class="header-nav__list">
                        @foreach ($primaryMenu->items as $item)
                            @php
                                $label = is_array($item['label'] ?? null)
                                    ? ($item['label'][app()->getLocale()] ?? $item['label']['en'] ?? reset($item['label']))
                                    : ($item['label'] ?? '');
                                $url = $item['url'] ?? '#';
                                $isActive = $url !== '#' && request()->is(ltrim($url, '/'));
                            @endphp
                            <li>
                                <a href="{{ $url }}" class="nav-link {{ $isActive ? 'active' : '' }}">
                                    {{ $label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endif

            {{-- Right side --}}
            <div class="header-actions">

                @if ($settings->contact_phone)
                    <a href="tel:{{ preg_replace('/\s+/', '', $settings->contact_phone) }}" class="header-phone">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" style="margin-right:0.4rem; opacity:0.8;">
                            <path
                                d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.81 12.81 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z" />
                        </svg>
                        {{ $settings->contact_phone }}
                    </a>
                @endif

                {{-- Language switcher --}}
                @if (count($settings->active_languages) > 1)
                    <div class="lang-switcher" x-data="{ open: false }">
                        @php
                            $currentLocale = app()->getLocale();
                            $flagMap = ['tr' => '🇹🇷', 'en' => '🇬🇧', 'de' => '🇩🇪', 'fr' => '🇫🇷', 'ar' => '🇸🇦', 'ru' => '🇷🇺', 'es' => '🇪🇸'];
                            $currentFlag = $flagMap[$currentLocale] ?? strtoupper($currentLocale);
                        @endphp
                        <button @click="open = !open" @click.outside="open = false" class="lang-switcher__btn"
                            aria-haspopup="listbox" :aria-expanded="open" aria-label="{{ __('Switch language') }}">
                            <span aria-hidden="true">{{ $currentFlag }}</span>
                            <span style="font-size:.8rem; font-weight:600;">{{ strtoupper($currentLocale) }}</span>
                        </button>
                        <div x-show="open" x-transition class="lang-switcher__dropdown" role="listbox">
                            @foreach ($settings->active_languages as $lang)
                                <a href="{{ route('lang.switch', $lang) }}"
                                    class="lang-switcher__option {{ app()->getLocale() === $lang ? 'lang-switcher__option--active' : '' }}"
                                    role="option">
                                    {{ strtoupper($lang) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($ctaRow)
                    <a href="{{ $ctaRow['data']['url'] ?? '#' }}"
                        class="btn-primary header-cta">{{ $ctaRow['data']['text'] ?? __('Contact') }}</a>
                @endif

                <button @click="mobileOpen = !mobileOpen" class="hamburger" aria-label="{{ __('Toggle navigation') }}"
                    :aria-expanded="mobileOpen" aria-controls="mobile-nav">
                    <span class="hamburger__bar"
                        :style="mobileOpen ? 'transform:rotate(45deg) translate(5px,6px)' : ''"></span>
                    <span class="hamburger__bar" :style="mobileOpen ? 'opacity:0; transform:scaleX(0)' : ''"></span>
                    <span class="hamburger__bar"
                        :style="mobileOpen ? 'transform:rotate(-45deg) translate(5px,-6px)' : ''"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile nav overlay --}}
    <div id="mobile-nav" class="mobile-nav" x-show="mobileOpen" x-transition x-trap.noscroll="mobileOpen" role="dialog"
        aria-modal="true" aria-label="{{ __('Mobile navigation') }}">
        <div class="mobile-nav__header">
            <img src="{{ asset('roma-logo.png') }}" alt="{{ $settings->site_name }}" style="height:40px; width:auto;">
            <button @click="mobileOpen = false" class="mobile-nav__close" aria-label="{{ __('Close menu') }}">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M18 6L6 18M6 6l12 12" />
                </svg>
            </button>
        </div>
        <nav aria-label="{{ __('Mobile navigation') }}" class="mobile-nav__links">
            @if ($primaryMenu && !empty($primaryMenu->items))
                @foreach ($primaryMenu->items as $item)
                    @php
                        $label = is_array($item['label'] ?? null)
                            ? ($item['label'][app()->getLocale()] ?? $item['label']['en'] ?? reset($item['label']))
                            : ($item['label'] ?? '');
                    @endphp
                    <a href="{{ $item['url'] ?? '#' }}" class="mobile-nav__link" @click="mobileOpen = false">{{ $label }}</a>
                @endforeach
            @endif
        </nav>
    </div>
</header>