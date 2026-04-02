@php
    $title    = $block['data']['heading'] ?? $block['data']['title'] ?? '';
    $subtitle = $block['data']['subtitle'] ?? '';
    $eyebrow  = $block['data']['eyebrow'] ?? '';
    $items    = $block['data']['services'] ?? $block['data']['items'] ?? [];
    $cols     = (int) ($block['data']['columns'] ?? 3);
    $bg       = $block['data']['background'] ?? 'default';

    $accentColors = ['#FF69B4', '#FF9F1C', '#00C853', '#9C27B0', '#42A5F5', '#FF69B4'];
    $accentBgs = [
        'linear-gradient(135deg, #fff0f6 0%, #ffe0ed 100%)',
        'linear-gradient(135deg, #fff8ed 0%, #ffe8c8 100%)',
        'linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%)',
        'linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%)',
        'linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%)',
        'linear-gradient(135deg, #fff0f6 0%, #ffe0ed 100%)',
    ];
@endphp

<section
    class="block-section sr-block sr-services-section"
    style="{{ $bg === 'alt' ? 'background:var(--color-surface);' : ($bg === 'accent' ? 'background:var(--color-primary); color:#fff;' : '') }}"
>
    <div class="container-site">
        @if ($eyebrow || $title || $subtitle)
            <div class="fade-up sr-section-header">
                @if ($eyebrow)
                    <span class="sr-eyebrow">{{ $eyebrow }}</span>
                @endif
                @if ($title)
                    <h2 class="sr-section-title font-display" style="{{ $bg === 'accent' ? 'color:#fff; background:none; -webkit-text-fill-color:#fff;' : '' }}">{{ $title }}</h2>
                @endif
                @if ($subtitle)
                    <p class="sr-section-subtitle">{{ $subtitle }}</p>
                @endif
            </div>
        @endif

        @if (! empty($items))
            <div class="sr-cards-grid sr-cards-grid--{{ count($items) <= 3 ? 'featured' : 'compact' }}">
                @foreach ($items as $i => $service)
                    <a
                        href="{{ $service['url'] ?? '#' }}"
                        class="sr-feature-card fade-up"
                        style="--card-accent: {{ $accentColors[$i % count($accentColors)] }}; --card-bg: {{ $accentBgs[$i % count($accentBgs)] }}; animation-delay: {{ $i * 80 }}ms;"
                    >
                        @if (! empty($service['icon']))
                            <div class="sr-feature-card__icon" aria-hidden="true">
                                <span>{{ $service['icon'] }}</span>
                            </div>
                        @endif
                        <div class="sr-feature-card__body">
                            @if (! empty($service['title']))
                                <h3 class="sr-feature-card__title font-display">{{ $service['title'] }}</h3>
                            @endif
                            @if (! empty($service['description']))
                                <p class="sr-feature-card__desc">{{ $service['description'] }}</p>
                            @endif
                        </div>
                        <span class="sr-feature-card__arrow" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </span>
                    </a>
                @endforeach
            </div>
        @else
            <p style="text-align:center; color:var(--color-muted); padding:2rem 0;">
                {{ __('No services added yet. Edit this block to add services.') }}
            </p>
        @endif
    </div>
</section>
