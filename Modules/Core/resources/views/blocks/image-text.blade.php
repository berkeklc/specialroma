@php
    $image     = $block['data']['image'] ?? '';
    $alt       = $block['data']['alt'] ?? '';
    $eyebrow   = $block['data']['eyebrow'] ?? '';
    $title     = $block['data']['title'] ?? '';
    $content   = $block['data']['content'] ?? '';
    $btnLabel  = $block['data']['button_label'] ?? '';
    $btnUrl    = $block['data']['button_url'] ?? '#';
    $imageLeft = ($block['data']['image_position'] ?? 'left') === 'left';
@endphp

<section class="block-section">
    <div class="container-site">
        <div
            class="fade-up"
            style="
                display:grid;
                grid-template-columns:1fr;
                gap:3rem;
                align-items:center;
            "
            class="image-text-grid"
        >
            {{-- Image --}}
            <div style="{{ !$imageLeft ? 'order:2;' : '' }}">
                @if ($image)
                    <img
                        src="{{ $image }}"
                        alt="{{ $alt }}"
                        loading="lazy"
                        style="width:100%; height:auto; border-radius:var(--radius-lg); box-shadow:var(--shadow-lg);"
                    >
                @else
                    <div style="aspect-ratio:4/3; background:var(--color-accent-light); border-radius:var(--radius-lg); display:grid; place-items:center;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--color-accent)" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                    </div>
                @endif
            </div>

            {{-- Text --}}
            <div style="{{ $imageLeft ? '' : 'order:1;' }}">
                @if ($eyebrow)
                    <span style="font-size:0.8125rem; font-weight:600; letter-spacing:0.1em; text-transform:uppercase; color:var(--color-accent); display:block; margin-bottom:0.75rem;">
                        {{ $eyebrow }}
                    </span>
                @endif
                @if ($title)
                    <h2 style="font-size:clamp(1.625rem, 3.5vw, 2.5rem); margin:0 0 1.25rem;">{{ $title }}</h2>
                @endif
                @if ($content)
                    <div style="font-size:1.0625rem; line-height:1.75; color:var(--color-muted);">{!! $content !!}</div>
                @endif
                @if ($btnLabel)
                    <a href="{{ $btnUrl }}" class="btn-primary" style="margin-top:2rem;">{{ $btnLabel }}</a>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
@media (min-width: 768px) {
    .image-text-grid { grid-template-columns: 1fr 1fr !important; }
}
</style>
