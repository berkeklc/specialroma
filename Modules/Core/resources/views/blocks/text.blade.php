@php
    $title     = $block['data']['title'] ?? '';
    $content   = $block['data']['content'] ?? '';
    $columns   = (int) ($block['data']['columns'] ?? 1);
    $eyebrow   = $block['data']['eyebrow'] ?? '';
    $alignment = $block['data']['alignment'] ?? 'left';
@endphp

<section class="block-section">
    <div class="container-site">
        @if ($eyebrow || $title)
            <div class="fade-up" style="margin-bottom:2.5rem; text-align:{{ $alignment }};">
                @if ($eyebrow)
                    <span style="font-size:0.8125rem; font-weight:600; letter-spacing:0.1em; text-transform:uppercase; color:var(--color-accent); display:block; margin-bottom:0.75rem;">
                        {{ $eyebrow }}
                    </span>
                @endif
                @if ($title)
                    <h2 style="font-size:clamp(1.75rem, 4vw, 2.75rem); margin:0;">{{ $title }}</h2>
                @endif
            </div>
        @endif

        @if ($content)
            <div
                class="fade-up prose"
                style="
                    {{ $columns > 1 ? "columns:{$columns}; column-gap:3rem;" : '' }}
                    font-size:1.0625rem;
                    line-height:1.75;
                    color:var(--color-muted);
                    text-align:{{ $alignment }};
                "
            >
                {!! $content !!}
            </div>
        @endif
    </div>
</section>
