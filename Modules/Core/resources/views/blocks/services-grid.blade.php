@php
    $title    = $block['data']['heading'] ?? $block['data']['title'] ?? '';
    $subtitle = $block['data']['subtitle'] ?? '';
    $eyebrow  = $block['data']['eyebrow'] ?? '';
    // Page Builder uses 'services' key; legacy Layout builder uses 'items'
    $items    = $block['data']['services'] ?? $block['data']['items'] ?? [];
    $cols     = (int) ($block['data']['columns'] ?? 3);
    $bg       = $block['data']['background'] ?? 'default';
@endphp

<section
    class="block-section"
    style="{{ $bg === 'alt' ? 'background:var(--color-surface);' : ($bg === 'accent' ? 'background:var(--color-primary); color:#fff;' : '') }}"
>
    <div class="container-site">
        @if ($eyebrow || $title || $subtitle)
            <div class="fade-up" style="text-align:center; max-width:680px; margin-inline:auto; margin-bottom:3.5rem;">
                @if ($eyebrow)
                    <span style="font-size:0.8125rem; font-weight:600; letter-spacing:0.1em; text-transform:uppercase; color:var(--color-accent); display:block; margin-bottom:0.75rem;">
                        {{ $eyebrow }}
                    </span>
                @endif
                @if ($title)
                    <h2 style="font-size:clamp(1.75rem, 4vw, 2.75rem); margin:0 0 1rem; {{ $bg === 'accent' ? 'color:#fff;' : '' }}">{{ $title }}</h2>
                @endif
                @if ($subtitle)
                    <p style="font-size:1.0625rem; color:{{ $bg === 'accent' ? 'rgba(255,255,255,0.75)' : 'var(--color-muted)' }}; margin:0;">{{ $subtitle }}</p>
                @endif
            </div>
        @endif

        @if (! empty($items))
            <div class="services-grid fade-up" style="grid-template-columns:repeat(auto-fill, minmax(min(100%, {{ max(240, (int) floor(1200 / $cols)) }}px), 1fr));">
                @foreach ($items as $service)
                    <div class="service-card">
                        @if (! empty($service['icon']))
                            <div class="service-card__icon" aria-hidden="true">
                                {!! $service['icon'] !!}
                            </div>
                        @endif
                        @if (! empty($service['title']))
                            <h3 style="font-size:1.125rem; margin:0 0 0.75rem;">{{ $service['title'] }}</h3>
                        @endif
                        @if (! empty($service['description']))
                            <p style="font-size:0.9375rem; color:var(--color-muted); margin:0; line-height:1.65;">{{ $service['description'] }}</p>
                        @endif
                        @if (! empty($service['url']))
                            <a href="{{ $service['url'] }}" style="display:inline-block; margin-top:1.25rem; font-size:0.9375rem; font-weight:600; color:var(--color-accent); text-decoration:none;">
                                {{ $service['link_label'] ?? 'Learn more' }} →
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p style="text-align:center; color:var(--color-muted); padding:2rem 0;">
                {{ __('No services added yet. Edit this block to add services.') }}
            </p>
        @endif
    </div>
</section>
