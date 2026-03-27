@php
    $title   = $block['data']['title'] ?? 'Get in touch';
    $subtitle = $block['data']['subtitle'] ?? '';
    $bg      = $block['data']['background'] ?? 'default';
@endphp

<section class="block-section" style="{{ $bg === 'alt' ? 'background:var(--color-surface);' : '' }}">
    <div class="container-site" style="max-width:720px; margin-inline:auto;">
        @if ($title)
            <div class="fade-up" style="text-align:center; margin-bottom:2.5rem;">
                <h2 style="font-size:clamp(1.75rem, 4vw, 2.5rem); margin:0 0 0.75rem;">{{ $title }}</h2>
                @if ($subtitle)
                    <p style="font-size:1.0625rem; color:var(--color-muted); margin:0;">{{ $subtitle }}</p>
                @endif
            </div>
        @endif

        <div class="fade-up">
            @livewire('contact::contact-form')
        </div>
    </div>
</section>
