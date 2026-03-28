<header
    class="site-header"
    x-data="siteHeader()"
    :class="{ 'scrolled': scrolled }"
    role="banner"
>
    <div class="container-site">
        <div class="header-inner">

            {{-- ─── Logo ────────────────────────────────────────────────── --}}
            <a href="{{ route('home') }}" class="header-logo" aria-label="{{ $settings->site_name }}">
                @if ($logoUrl)
                    {{-- Image logo from MediaLibrary --}}
                    <img
                        src="{{ $logoUrl }}"
                        alt="{{ $logoAlt ?? $settings->site_name }}"
                        class="header-logo__img"
                        width="140"
                        height="40"
                    >
                @elseif ($settings->logo_type === 'text' && $settings->logo_text)
                    {{-- Custom text/wordmark --}}
                    <span class="header-logo__text">{{ $settings->logo_text }}</span>
                @else
                    {{-- Fallback: site name --}}
                    <span class="header-logo__text">{{ $settings->site_name }}</span>
                @endif
            </a>

            {{-- ─── Desktop navigation ────────────────────────────────── --}}
            @if ($primaryMenu && !empty($primaryMenu->items))
                <nav aria-label="{{ __('Primary navigation') }}" class="header-nav" id="header-desktop-nav">
                    <ul role="list" class="header-nav__list">
                        @foreach ($primaryMenu->items as $item)
                            @php
                                $label = is_array($item['label'] ?? null)
                                    ? ($item['label'][app()->getLocale()] ?? $item['label']['en'] ?? reset($item['label']))
                                    : ($item['label'] ?? '');
                                $url   = $item['url'] ?? '#';
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

            {{-- ─── Right side ─────────────────────────────────────────── --}}
            <div class="header-actions">

                {{-- Language switcher --}}
                @if (count($settings->active_languages) > 1)
                    <div class="lang-switcher" x-data="{ open: false }">
                        @php
                            $currentLocale = app()->getLocale();
                            $flagMap = ['tr'=>'🇹🇷','en'=>'🇬🇧','de'=>'🇩🇪','fr'=>'🇫🇷','ar'=>'🇸🇦','ru'=>'🇷🇺','es'=>'🇪🇸'];
                            $currentFlag = $flagMap[$currentLocale] ?? strtoupper($currentLocale);
                        @endphp
                        <button
                            @click="open = !open"
                            @click.outside="open = false"
                            class="lang-switcher__btn"
                            aria-haspopup="listbox"
                            :aria-expanded="open"
                            aria-label="{{ __('Switch language') }}"
                        >
                            <span aria-hidden="true">{{ $currentFlag }}</span>
                            <span style="font-size:.8rem; font-weight:600;">{{ strtoupper($currentLocale) }}</span>
                            <svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor" aria-hidden="true"
                                 style="transition:transform 0.2s;" :style="open ? 'transform:rotate(180deg)' : ''">
                                <path d="M5 7L1 3h8z"/>
                            </svg>
                        </button>
                        <div
                            x-show="open"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="lang-switcher__dropdown"
                            role="listbox"
                        >
                            @foreach ($settings->active_languages as $lang)
                                <a
                                    href="{{ route('lang.switch', $lang) }}"
                                    class="lang-switcher__option {{ app()->getLocale() === $lang ? 'lang-switcher__option--active' : '' }}"
                                    role="option"
                                    :aria-selected="'{{ app()->getLocale() === $lang ? 'true' : 'false' }}'"
                                >
                                    @switch($lang)
                                        @case('tr') 🇹🇷 @break
                                        @case('en') 🇬🇧 @break
                                        @case('de') 🇩🇪 @break
                                        @case('fr') 🇫🇷 @break
                                        @case('ar') 🇸🇦 @break
                                        @case('ru') 🇷🇺 @break
                                        @case('es') 🇪🇸 @break
                                        @default {{ $lang }}
                                    @endswitch
                                    {{ strtoupper($lang) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- CTA button --}}
                @if ($ctaRow)
                    <a
                        href="{{ $ctaRow['data']['url'] ?? '#' }}"
                        class="btn-primary header-cta"
                        id="header-cta-btn"
                    >{{ $ctaRow['data']['text'] ?? __('Contact') }}</a>
                @endif

                {{-- Hamburger --}}
                <button
                    @click="mobileOpen = !mobileOpen"
                    class="hamburger"
                    id="hamburger-btn"
                    aria-label="{{ __('Toggle navigation') }}"
                    :aria-expanded="mobileOpen"
                    aria-controls="mobile-nav"
                >
                    <span class="hamburger__bar" :style="mobileOpen ? 'transform:rotate(45deg) translate(5px,6px)' : ''"></span>
                    <span class="hamburger__bar" :style="mobileOpen ? 'opacity:0; transform:scaleX(0)' : ''"></span>
                    <span class="hamburger__bar" :style="mobileOpen ? 'transform:rotate(-45deg) translate(5px,-6px)' : ''"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- ─── Mobile nav overlay ─────────────────────────────────────────── --}}
    <div
        id="mobile-nav"
        class="mobile-nav"
        x-show="mobileOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-3"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-3"
        x-trap.noscroll="mobileOpen"
        role="dialog"
        aria-modal="true"
        aria-label="{{ __('Mobile navigation') }}"
    >
        <div class="mobile-nav__header">
            <span class="mobile-nav__brand">{{ $settings->site_name }}</span>
            <button @click="mobileOpen = false" class="mobile-nav__close" aria-label="{{ __('Close menu') }}">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M18 6L6 18M6 6l12 12"/>
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
                    <a href="{{ $item['url'] ?? '#' }}" class="mobile-nav__link" @click="mobileOpen = false">
                        {{ $label }}
                    </a>
                @endforeach
            @else
                <a href="{{ route('home') }}" class="mobile-nav__link" @click="mobileOpen = false">
                    {{ __('Home') }}
                </a>
            @endif
        </nav>

        @if ($ctaRow)
            <div class="mobile-nav__footer">
                <a href="{{ $ctaRow['data']['url'] ?? '#' }}" class="btn-primary" style="width:100%; justify-content:center; text-align:center;">
                    {{ $ctaRow['data']['text'] ?? __('Contact') }}
                </a>
            </div>
        @endif
    </div>
</header>
