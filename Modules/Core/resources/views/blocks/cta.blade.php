@php
    $heading   = $block['data']['heading'] ?? '';
    $subtext   = $block['data']['subtext'] ?? '';
    $btnLabel  = $block['data']['button_label'] ?? 'Get started';
    $btnUrl    = $block['data']['button_url'] ?? '#';
    $btn2Label = $block['data']['button2_label'] ?? '';
    $btn2Url   = $block['data']['button2_url'] ?? '#';
    $style     = $block['data']['style'] ?? 'dark'; // dark | accent | minimal
@endphp

<section
    class="block-cta block-section"
    style="
        {{ $style === 'accent' ? 'background:var(--color-accent);' : ($style === 'minimal' ? 'background:var(--color-surface); border-top:1px solid var(--color-border); border-bottom:1px solid var(--color-border);' : 'background:var(--color-primary);') }}
    "
>
    <div class="container-site" style="text-align:center; max-width:720px; margin-inline:auto;">
        <div class="fade-up">
            @if ($heading)
                <h2 style="font-size:clamp(1.875rem, 4vw, 3rem); margin:0 0 1.25rem; color:{{ $style === 'minimal' ? 'var(--color-primary)' : '#fff' }};">
                    {{ $heading }}
                </h2>
            @endif
            @if ($subtext)
                <p style="font-size:1.125rem; margin:0 0 2.5rem; color:{{ $style === 'minimal' ? 'var(--color-muted)' : 'rgba(255,255,255,0.75)' }}; max-width:60ch; margin-inline:auto;">
                    {{ $subtext }}
                </p>
            @endif
            <div style="display:flex; gap:1rem; justify-content:center; flex-wrap:wrap;">
                @if ($btnLabel)
                    <a href="{{ $btnUrl }}" class="btn-primary" style="{{ $style === 'accent' ? 'background:#fff; color:var(--color-accent);' : '' }}">
                        {{ $btnLabel }}
                    </a>
                @endif
                @if ($btn2Label)
                    <a href="{{ $btn2Url }}" class="btn-ghost" style="{{ $style !== 'minimal' ? 'color:#fff; border-color:rgba(255,255,255,0.35);' : '' }}">
                        {{ $btn2Label }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
