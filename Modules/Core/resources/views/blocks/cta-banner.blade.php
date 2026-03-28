@php
    $heading  = $block['data']['heading'] ?? $block['data']['title'] ?? '';
    $subtext  = $block['data']['subheading'] ?? $block['data']['subtext'] ?? '';
    $btnLabel = $block['data']['button_text'] ?? $block['data']['button_label'] ?? '';
    $btnUrl   = $block['data']['button_url'] ?? '#';
    $bgColor  = $block['data']['background_color'] ?? null;
@endphp

<section
    class="block-cta block-section"
    style="{{ $bgColor ? 'background:' . e($bgColor) . ';' : 'background:var(--color-primary);' }}"
>
    <div class="container-site" style="text-align:center; max-width:720px; margin-inline:auto;">
        <div class="fade-up">
            @if ($heading)
                <h2 style="font-size:clamp(1.875rem, 4vw, 3rem); margin:0 0 1.25rem; color:#fff;">{{ $heading }}</h2>
            @endif
            @if ($subtext)
                <p style="font-size:1.125rem; color:rgba(255,255,255,0.8); margin:0 0 2.5rem; max-width:54ch; margin-inline:auto;">{{ $subtext }}</p>
            @endif
            @if ($btnLabel)
                <a href="{{ $btnUrl }}" class="btn-primary" style="font-size:1rem; padding:0.875rem 2.25rem;">
                    {{ $btnLabel }}
                </a>
            @endif
        </div>
    </div>
</section>
