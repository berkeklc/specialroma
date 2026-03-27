@php
    $title  = $block['data']['title'] ?? '';
    $eyebrow = $block['data']['eyebrow'] ?? 'What clients say';
    $items  = $block['data']['items'] ?? [];
    $layout = $block['data']['layout'] ?? 'grid'; // grid | carousel
@endphp

<section class="block-section" style="background:var(--color-surface);">
    <div class="container-site">
        @if ($eyebrow || $title)
            <div class="fade-up" style="text-align:center; max-width:560px; margin-inline:auto; margin-bottom:3rem;">
                @if ($eyebrow)
                    <span style="font-size:0.8125rem; font-weight:600; letter-spacing:0.1em; text-transform:uppercase; color:var(--color-accent); display:block; margin-bottom:0.75rem;">
                        {{ $eyebrow }}
                    </span>
                @endif
                @if ($title)
                    <h2 style="font-size:clamp(1.75rem, 4vw, 2.5rem); margin:0;">{{ $title }}</h2>
                @endif
            </div>
        @endif

        <div
            class="fade-up"
            style="display:grid; grid-template-columns:repeat(auto-fill, minmax(min(100%, 340px), 1fr)); gap:1.5rem;"
        >
            @foreach ($items as $item)
                <blockquote class="testimonial-card">
                    <div style="font-size:2rem; color:var(--color-accent); line-height:1; margin-bottom:0.5rem;" aria-hidden="true">"</div>
                    <p class="testimonial-quote">{{ $item['quote'] ?? '' }}</p>
                    <footer class="testimonial-author">
                        @if (!empty($item['avatar']))
                            <img src="{{ $item['avatar'] }}" alt="{{ $item['name'] ?? '' }}" loading="lazy">
                        @else
                            <div style="width:44px; height:44px; border-radius:50%; background:var(--color-accent-light); display:grid; place-items:center; font-weight:700; font-size:1.125rem; color:var(--color-accent); flex-shrink:0;">
                                {{ strtoupper(substr($item['name'] ?? 'A', 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div style="font-weight:600; font-size:0.9375rem; color:var(--color-primary);">{{ $item['name'] ?? '' }}</div>
                            @if (!empty($item['role']))
                                <div style="font-size:0.875rem; color:var(--color-muted);">{{ $item['role'] }}</div>
                            @endif
                        </div>
                    </footer>
                </blockquote>
            @endforeach
        </div>
    </div>
</section>
