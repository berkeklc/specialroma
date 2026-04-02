@php
    $heading = $block['data']['heading'] ?? 'Roma Dondurmaları';
    $text = $block['data']['text'] ?? '';
    $btnLabel = $block['data']['button_label'] ?? '';
    $btnUrl = $block['data']['button_url'] ?? '/menu';
    $btnLink = \Illuminate\Support\Str::startsWith($btnUrl, ['http://', 'https://']) ? $btnUrl : url($btnUrl);

@endphp

<section
    data-roma-showcase
    class="sr-showcase block-section sr-block"
    style="--roma-showcase-hue: 340; --roma-stack: 0;"
    aria-labelledby="roma-dondurmalar-heading"
>
    <div class="container-site">
        {{-- Ice Cream Assembly Animation Box --}}
        <div class="sr-icecream-assembly-wrapper">
            <div class="sr-icecream-assembly-pinned">
                <div class="sr-icecream-pinned-grid">
                    {{-- Left Column (Content) --}}
                    <div class="sr-column sr-col-left">
                        <header class="sr-section-header no-margin text-left">
                            <span class="sr-eyebrow">El Yapımı</span>
                            <h2 id="roma-dondurmalar-heading" class="font-display sr-section-title text-left lg:text-6xl">{{ $heading }}</h2>
                        </header>
                        
                        @if ($text)
                            <p class="sr-section-subtitle text-left">{{ $text }}</p>
                        @endif

                        @if ($btnLabel)
                            <div class="sr-icecream-btn-box sr-icecream-btn" style="opacity: 0; pointer-events: none;">
                                <a href="{{ $btnLink }}" class="btn-primary sr-btn-primary" style="background: var(--roma-pink) !important;">{{ $btnLabel }}</a>
                            </div>
                        @endif
                    </div>

                    {{-- Right Column (Animation) --}}
                    <div class="sr-column sr-col-right">
                        <div class="sr-icecream-layers">
                            <canvas id="sr-splash-canvas" class="sr-splash-canvas"></canvas>
                            <img src="{{ asset('kulah-back-asset.png') }}" class="sr-ice-part sr-cone-back" alt="">
                            <img src="{{ asset('top1.png') }}" class="sr-ice-part sr-scoop sr-top1" alt="">
                            <img src="{{ asset('top2.png') }}" class="sr-ice-part sr-scoop sr-top2" alt="">
                            <img src="{{ asset('top3.png') }}" class="sr-ice-part sr-scoop sr-top3" alt="">
                            <img src="{{ asset('top4.png') }}" class="sr-ice-part sr-scoop sr-top4" alt="">
                            <img src="{{ asset('kulah-front-asset.png') }}" class="sr-ice-part sr-cone-front" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
