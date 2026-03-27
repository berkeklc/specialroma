@php
    $src     = $block['data']['image'] ?? '';
    $alt     = $block['data']['alt'] ?? '';
    $caption = $block['data']['caption'] ?? '';
    $width   = $block['data']['width'] ?? 'full'; // full | wide | narrow
    $rounded = $block['data']['rounded'] ?? false;
@endphp

<section class="block-section" style="padding-block:clamp(2rem, 5vw, 4rem);">
    <div class="{{ $width === 'narrow' ? 'container-site' : ($width === 'wide' ? '' : 'container-site') }}" style="{{ $width === 'full' ? 'max-width:100%;' : ($width === 'wide' ? 'max-width:1440px; margin-inline:auto; padding-inline:clamp(1rem,3vw,2rem);' : '') }}">
        @if ($src)
            <figure class="fade-up" style="margin:0;">
                <img
                    src="{{ $src }}"
                    alt="{{ $alt }}"
                    loading="lazy"
                    style="width:100%; height:auto; display:block; {{ $rounded ? 'border-radius:var(--radius-lg);' : '' }} box-shadow:var(--shadow-md);"
                >
                @if ($caption)
                    <figcaption style="margin-top:0.75rem; font-size:0.875rem; color:var(--color-muted); text-align:center; font-style:italic;">
                        {{ $caption }}
                    </figcaption>
                @endif
            </figure>
        @endif
    </div>
</section>
