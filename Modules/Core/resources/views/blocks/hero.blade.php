@php
    $heading   = $block['data']['heading'] ?? '';
    $subheading = $block['data']['subheading'] ?? '';
    $eyebrow   = $block['data']['eyebrow'] ?? '';
    $btnLabel  = $block['data']['button_label'] ?? '';
    $btnUrl    = $block['data']['button_url'] ?? '#';
    $btn2Label = $block['data']['button2_label'] ?? '';
    $btn2Url   = $block['data']['button2_url'] ?? '#';
    $bgImage   = $block['data']['background_image'] ?? '';
    $overlay   = $block['data']['overlay_opacity'] ?? '0.5';
    $alignment = $block['data']['alignment'] ?? 'left';
    $minHeight = $block['data']['min_height'] ?? '80vh';
@endphp

<section
    class="block-hero"
    style="min-height:{{ $minHeight }}; text-align:{{ $alignment }};"
    aria-label="{{ $heading }}"
>
    @if ($bgImage)
        @php
            $bgSrc = \Illuminate\Support\Str::startsWith($bgImage, ['http://', 'https://']) ? $bgImage : \Illuminate\Support\Facades\Storage::url($bgImage);
        @endphp
        <img class="block-hero__bg" src="{{ $bgSrc }}" alt="" aria-hidden="true" loading="lazy">
        <div class="block-hero__overlay" style="background:linear-gradient(to bottom, rgba(0,0,0,{{ (float)$overlay * 0.4 }}) 0%, rgba(0,0,0,{{ $overlay }}) 100%);"></div>
    @else
        <div style="position:absolute; inset:0; background:linear-gradient(to bottom, var(--color-accent) 0%, var(--color-primary) 100%); opacity:0.95;"></div>
    @endif

    <div class="container-site">
        <div class="block-hero__content fade-up" style="{{ $alignment === 'center' ? 'margin-inline:auto; text-align:center;' : '' }}">
            @if ($eyebrow)
                <span class="block-hero__eyebrow">{{ $eyebrow }}</span>
            @endif

            @if ($heading)
                <h1>{{ $heading }}</h1>
            @endif

            @if ($subheading)
                <p class="block-hero__sub" style="{{ $alignment === 'center' ? 'margin-inline:auto;' : '' }}">
                    {{ $subheading }}
                </p>
            @endif

            @if ($btnLabel || $btn2Label)
                <div style="display:flex; gap:1rem; flex-wrap:wrap; {{ $alignment === 'center' ? 'justify-content:center;' : '' }}">
                    @if ($btnLabel)
                        <a href="{{ $btnUrl }}" class="btn-primary">{{ $btnLabel }}</a>
                    @endif
                    @if ($btn2Label)
                        <a href="{{ $btn2Url }}" class="btn-ghost" style="color:#fff; border-color:rgba(255,255,255,0.35);">{{ $btn2Label }}</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
