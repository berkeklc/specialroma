<footer class="site-footer" role="contentinfo">
    <div class="container-site">
        <div class="footer-top">

            {{-- Brand column --}}
            <div class="footer-brand">
                <a href="{{ route('home') }}" style="text-decoration:none; display:inline-block; margin-bottom:1.5rem;">
                    <img
                        src="{{ asset('roma-logo.png') }}"
                        alt="{{ $settings->site_name }}"
                        width="160"
                        height="60"
                        style="height:60px; width:auto; display:block;"
                    >
                </a>

                @if ($settings->site_tagline)
                    <p class="footer-tagline">{{ $settings->site_tagline }}</p>
                @endif

                @if (!empty($socialLinks))
                    <div class="footer-social">
                        @foreach ($socialLinks as $network => $url)
                            @if ($url)
                                <a
                                    href="{{ $url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    aria-label="{{ ucfirst((string) $network) }}"
                                    class="footer-social__icon"
                                >
                                    @include('core::partials.social-icon', ['network' => $network])
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Footer nav --}}
            @if ($footerMenu && !empty($footerMenu->items))
                <div class="footer-col">
                    <h3 class="footer-col__heading">{{ __('Navigation') }}</h3>
                    <ul role="list" class="footer-col__list">
                        @foreach ($footerMenu->items as $item)
                            @php
                                $label = is_array($item['label'] ?? null)
                                    ? ($item['label'][app()->getLocale()] ?? $item['label']['en'] ?? reset($item['label']))
                                    : ($item['label'] ?? '');
                            @endphp
                            <li>
                                <a href="{{ $item['url'] ?? '#' }}" class="footer-col__link">{{ $label }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Contact info --}}
            @if ($settings->contact_email || $settings->contact_phone || $settings->contact_address)
                <div class="footer-col">
                    <h3 class="footer-col__heading">{{ __('Contact') }}</h3>
                    <ul role="list" class="footer-col__list">
                        @if ($settings->contact_email)
                            <li>
                                <a href="mailto:{{ $settings->contact_email }}" class="footer-col__link">
                                    {{ $settings->contact_email }}
                                </a>
                            </li>
                        @endif
                        @if ($settings->contact_phone)
                            <li>
                                <a href="tel:{{ preg_replace('/\s+/', '', $settings->contact_phone) }}" class="footer-col__link">
                                    {{ $settings->contact_phone }}
                                </a>
                            </li>
                        @endif
                        @if ($settings->contact_address)
                            <li class="footer-col__text">{{ $settings->contact_address }}</li>
                        @endif
                    </ul>
                </div>
            @endif
        </div>

        <hr class="footer-divider">

        <div class="footer-bottom">
            <p class="footer-copy">
                @if ($textRow && !empty($textRow['data']['content']))
                    {!! $textRow['data']['content'] !!}
                @else
                    &copy; {{ date('Y') }} {{ $settings->site_name }}. All rights reserved.
                @endif
            </p>

            @if (count($settings->active_languages) > 1)
                <div class="footer-langs">
                    @foreach ($settings->active_languages as $lang)
                        <a
                            href="{{ route('lang.switch', $lang) }}"
                            class="footer-lang {{ app()->getLocale() === $lang ? 'footer-lang--active' : '' }}"
                        >{{ strtoupper($lang) }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</footer>
