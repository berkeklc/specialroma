@php
    $heading  = $block['data']['heading'] ?? $block['data']['title'] ?? '';
    $subtext  = $block['data']['subheading'] ?? $block['data']['subtext'] ?? '';
    $btnLabel = $block['data']['button_text'] ?? $block['data']['button_label'] ?? '';
    $btnUrl   = $block['data']['button_url'] ?? '#';
    $bgColor  = $block['data']['background_color'] ?? null;
@endphp

<section class="sr-block sr-cta-section">
    <div class="sr-cta-inner" style="{{ $bgColor ? '--cta-bg:' . e($bgColor) . ';' : '' }}">
        <div class="sr-cta-glow" aria-hidden="true"></div>
        <div class="container-site" style="position: relative; z-index: 2;">
            <div class="fade-up sr-cta-content">
                @if ($heading)
                    <h2 class="sr-cta-title font-display">{{ $heading }}</h2>
                @endif
                @if ($subtext)
                    <p class="sr-cta-subtitle">{{ $subtext }}</p>
                @endif
                @if ($btnLabel)
                    <a href="{{ $btnUrl }}" class="sr-cta-btn">
                        {{ $btnLabel }}
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
