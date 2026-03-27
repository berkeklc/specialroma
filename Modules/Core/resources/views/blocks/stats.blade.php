@php
    $title  = $block['data']['title'] ?? '';
    $items  = $block['data']['items'] ?? [];
    $bg     = $block['data']['background'] ?? 'accent'; // accent | alt | default
@endphp

<section
    class="block-section"
    style="
        {{ $bg === 'accent' ? 'background:var(--color-primary); color:#fff;' : ($bg === 'alt' ? 'background:var(--color-surface);' : '') }}
    "
>
    <div class="container-site">
        @if ($title)
            <h2 class="fade-up" style="text-align:center; font-size:clamp(1.75rem, 4vw, 2.5rem); margin-bottom:3rem; {{ $bg === 'accent' ? 'color:#fff;' : '' }}">
                {{ $title }}
            </h2>
        @endif

        <div
            class="fade-up"
            style="display:grid; grid-template-columns:repeat(auto-fit, minmax(160px, 1fr)); gap:2rem; text-align:center;"
        >
            @foreach ($items as $stat)
                <div>
                    <div style="font-family:var(--font-serif); font-size:clamp(2.5rem, 5vw, 4rem); font-weight:700; line-height:1; color:{{ $bg === 'accent' ? '#fff' : 'var(--color-accent)' }}; margin-bottom:0.5rem;">
                        {{ $stat['value'] ?? '' }}
                    </div>
                    <div style="font-size:0.9375rem; font-weight:500; color:{{ $bg === 'accent' ? 'rgba(255,255,255,0.65)' : 'var(--color-muted)' }};">
                        {{ $stat['label'] ?? '' }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
