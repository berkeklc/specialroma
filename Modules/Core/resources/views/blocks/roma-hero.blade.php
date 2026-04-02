@php
    $heading = $block['data']['heading'] ?? '';
    $subheading = $block['data']['subheading'] ?? '';
    $btnLabel = $block['data']['button_label'] ?? '';
    $btnUrl = $block['data']['button_url'] ?? '/menu';
    $btn2Label = $block['data']['button2_label'] ?? '';
    $btn2Url = $block['data']['button2_url'] ?? '/iletisim';
    $primaryLink = \Illuminate\Support\Str::startsWith($btnUrl, ['http://', 'https://']) ? $btnUrl : url($btnUrl);
    $secondaryLink = \Illuminate\Support\Str::startsWith($btn2Url, ['http://', 'https://']) ? $btn2Url : url($btn2Url);
    $bgImage = $block['data']['background_image'] ?? null;
    $bgUrl = $bgImage
        ? (\Illuminate\Support\Str::startsWith($bgImage, ['http://', 'https://']) ? $bgImage : \Illuminate\Support\Facades\Storage::url($bgImage))
        : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=2400&q=85';

    $scoops = [
        ['color' => '#ff69b4', 'light' => '#ffb6d9', 'dark' => '#c4366e', 'label' => 'Çilek',     'x' => '-30vw', 'y' => '-16vh', 'size' => 'lg'],
        ['color' => '#ff9f1c', 'light' => '#ffe0a8', 'dark' => '#d47f00', 'label' => 'Mango',      'x' => '28vw',  'y' => '-20vh', 'size' => 'md'],
        ['color' => '#00c853', 'light' => '#b9f6ca', 'dark' => '#007e33', 'label' => 'Fıstık',     'x' => '-26vw', 'y' => '18vh',  'size' => 'sm'],
        ['color' => '#9c27b0', 'light' => '#e1bee7', 'dark' => '#6a0080', 'label' => 'Böğürtlen',  'x' => '32vw',  'y' => '14vh',  'size' => 'md'],
        ['color' => '#c68642', 'light' => '#f5e6d0', 'dark' => '#8d5520', 'label' => 'Çikolata',   'x' => '-12vw', 'y' => '30vh',  'size' => 'sm'],
        ['color' => '#f8e8c0', 'light' => '#fffdf5', 'dark' => '#d4b56a', 'label' => 'Vanilya',    'x' => '16vw',  'y' => '-28vh', 'size' => 'lg'],
    ];
@endphp

<section data-roma-hero class="roma-hero sr-hero" aria-label="{{ $heading }}">
    <div class="roma-hero__parallax" aria-hidden="true">
        <div class="roma-hero__parallax-inner">
            <img class="roma-hero__bg-img" src="{{ $bgUrl }}" alt="" loading="eager" fetchpriority="high">
            <div class="roma-hero__overlay"></div>
        </div>
    </div>

    <div class="roma-hero__scoop-layer" aria-hidden="true">
        @foreach ($scoops as $i => $scoop)
            <div
                class="sr-ice-scoop sr-ice-scoop--{{ $scoop['size'] }}"
                data-roma-float
                data-scoop-index="{{ $i }}"
                data-start-x="{{ $scoop['x'] }}"
                data-start-y="{{ $scoop['y'] }}"
                style="
                    --scoop-color: {{ $scoop['color'] }};
                    --scoop-light: {{ $scoop['light'] }};
                    --scoop-dark: {{ $scoop['dark'] }};
                    opacity: 0;
                    transform: translate({{ $scoop['x'] }}, {{ $scoop['y'] }}) scale(0.4);
                "
            ></div>
        @endforeach
    </div>

    <div class="roma-hero__content-wrap">
        <div class="roma-hero__content fade-up sr-hero__content">
            @if ($heading)
                <img src="{{ asset('roma-logo.png') }}" alt="Special Roma" class="sr-hero__logo" width="200" height="72">
                <h1 class="sr-hero__title font-display">{{ $heading }}</h1>
            @endif
            @if ($subheading)
                <p class="sr-hero__sub font-sans">{{ $subheading }}</p>
            @endif
            <div class="roma-hero__ctas sr-hero__ctas">
                @if ($btnLabel)
                    <a href="{{ $primaryLink }}" class="btn-primary sr-btn-primary">{{ $btnLabel }}</a>
                @endif
                @if ($btn2Label)
                    <a href="{{ $secondaryLink }}" class="btn-ghost sr-btn-secondary">{{ $btn2Label }}</a>
                @endif
            </div>
        </div>
    </div>
</section>
