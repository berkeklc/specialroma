@php
    $heading = $block['data']['heading'] ?? ($block['data']['title'] ?? '');
    $eyebrow = $block['data']['eyebrow'] ?? '';
    $items = $block['data']['items'] ?? [];
    $accentColors = ['#FF69B4', '#FF9F1C', '#00C853', '#9C27B0'];
@endphp

<section class="block-section sr-block sr-testimonials-section">
    <div class="container-site">
        @if ($eyebrow || $heading)
            <div class="fade-up sr-section-header">
                @if ($eyebrow)
                    <span class="sr-eyebrow">{{ $eyebrow }}</span>
                @endif
                @if ($heading)
                    <h2 class="sr-section-title font-display">{{ $heading }}</h2>
                @endif
            </div>
        @endif

        @if (! empty($items))
            <div class="sr-testimonials-grid">
                @foreach ($items as $i => $item)
                    @php
                        $photo = $item['author_photo'] ?? null;
                        $photoUrl = $photo
                            ? (\Illuminate\Support\Str::startsWith($photo, ['http://', 'https://']) ? $photo : \Illuminate\Support\Facades\Storage::url($photo))
                            : null;
                        $name = $item['author_name'] ?? '';
                        $accent = $accentColors[$i % count($accentColors)];
                    @endphp
                    <blockquote class="sr-testimonial fade-up" style="--testimonial-accent: {{ $accent }}; animation-delay: {{ $i * 100 }}ms;">
                        <div class="sr-testimonial__quote-mark" aria-hidden="true">
                            <svg width="32" height="24" viewBox="0 0 32 24" fill="none"><path d="M0 24V14.4C0 10.4 0.8 7.2 2.4 4.8C4.08 2.4 6.72 0.64 10.32 0L11.52 3.12C9.28 3.76 7.6 4.88 6.48 6.48C5.44 8.08 4.88 10.08 4.8 12.48H8V24H0ZM18 24V14.4C18 10.4 18.8 7.2 20.4 4.8C22.08 2.4 24.72 0.64 28.32 0L29.52 3.12C27.28 3.76 25.6 4.88 24.48 6.48C23.44 8.08 22.88 10.08 22.8 12.48H26V24H18Z" fill="currentColor"/></svg>
                        </div>
                        <p class="sr-testimonial__text">{{ $item['quote'] ?? '' }}</p>
                        <footer class="sr-testimonial__author">
                            @if ($photoUrl)
                                <img class="sr-testimonial__avatar" src="{{ $photoUrl }}" alt="{{ $name }}" loading="lazy">
                            @else
                                <div class="sr-testimonial__avatar-placeholder" style="background: {{ $accent }}22; color: {{ $accent }};">
                                    {{ strtoupper(mb_substr($name ?: 'A', 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="sr-testimonial__name">{{ $name }}</div>
                                @if (! empty($item['author_title']))
                                    <div class="sr-testimonial__role">{{ $item['author_title'] }}</div>
                                @endif
                            </div>
                        </footer>
                    </blockquote>
                @endforeach
            </div>
        @endif
    </div>
</section>
