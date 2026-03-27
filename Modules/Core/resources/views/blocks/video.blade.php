@php
    $title    = $block['data']['title'] ?? '';
    $url      = $block['data']['url'] ?? '';
    $caption  = $block['data']['caption'] ?? '';
    $autoplay = $block['data']['autoplay'] ?? false;

    // Parse YouTube / Vimeo embed
    $embedUrl = $url;
    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([A-Za-z0-9_-]+)/', $url, $m)) {
        $embedUrl = 'https://www.youtube.com/embed/' . $m[1] . ($autoplay ? '?autoplay=1&mute=1' : '');
    } elseif (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
        $embedUrl = 'https://player.vimeo.com/video/' . $m[1] . ($autoplay ? '?autoplay=1&muted=1' : '');
    }
    $isEmbed = $embedUrl !== $url || str_contains($embedUrl, 'youtube.com/embed') || str_contains($embedUrl, 'vimeo.com/video');
@endphp

<section class="block-section">
    <div class="container-site">
        @if ($title)
            <h2 class="fade-up" style="font-size:clamp(1.75rem, 4vw, 2.5rem); margin-bottom:2rem;">{{ $title }}</h2>
        @endif

        @if ($url)
            <div class="fade-up" style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden; border-radius:var(--radius-md); box-shadow:var(--shadow-lg);">
                @if ($isEmbed)
                    <iframe
                        src="{{ $embedUrl }}"
                        title="{{ $title ?: 'Video' }}"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen
                        loading="lazy"
                        style="position:absolute; top:0; left:0; width:100%; height:100%;"
                    ></iframe>
                @else
                    <video
                        src="{{ $url }}"
                        controls
                        {{ $autoplay ? 'autoplay muted' : '' }}
                        style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover;"
                    ></video>
                @endif
            </div>
            @if ($caption)
                <p style="margin-top:0.75rem; text-align:center; font-size:0.875rem; color:var(--color-muted); font-style:italic;">{{ $caption }}</p>
            @endif
        @endif
    </div>
</section>
