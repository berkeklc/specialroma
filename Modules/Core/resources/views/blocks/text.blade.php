@php
    $title     = $block['data']['title'] ?? '';
    $content   = $block['data']['content'] ?? '';
    $columns   = (int) ($block['data']['columns'] ?? 1);
    $eyebrow   = $block['data']['eyebrow'] ?? '';
    $alignment = $block['data']['alignment'] ?? 'left';
@endphp

<section class="block-section sr-block sr-text-section">
    <div class="container-site">
        @if ($eyebrow || $title)
            <div class="fade-up sr-section-header" style="text-align:{{ $alignment }};">
                @if ($eyebrow)
                    <span class="sr-eyebrow">{{ $eyebrow }}</span>
                @endif
                @if ($title)
                    <h2 class="sr-section-title font-display">{{ $title }}</h2>
                @endif
            </div>
        @endif

        @if ($content)
            <div
                class="fade-up sr-prose"
                style="text-align:{{ $alignment }}; {{ $columns > 1 ? "columns:{$columns}; column-gap:3rem;" : '' }}"
            >
                {!! $content !!}
            </div>
        @endif
    </div>
</section>
