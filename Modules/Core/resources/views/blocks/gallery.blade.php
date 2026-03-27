@php
    $title  = $block['data']['title'] ?? '';
    $images = $block['data']['images'] ?? [];
    $cols   = (int) ($block['data']['columns'] ?? 3);
@endphp

<section class="block-section">
    <div class="container-site">
        @if ($title)
            <h2 class="fade-up" style="font-size:clamp(1.75rem, 4vw, 2.5rem); margin-bottom:2.5rem;">{{ $title }}</h2>
        @endif

        @if (!empty($images))
            <div
                x-data="lightbox({{ json_encode(array_column($images, 'url')) }})"
                class="gallery-grid fade-up"
                style="grid-template-columns:repeat({{ $cols }}, 1fr);"
            >
                @foreach ($images as $i => $image)
                    <button
                        @click="show({{ $i }})"
                        class="gallery-item"
                        aria-label="View {{ $image['alt'] ?? 'image ' . ($i + 1) }}"
                        style="border:none; cursor:zoom-in; padding:0;"
                    >
                        <img src="{{ $image['url'] }}" alt="{{ $image['alt'] ?? '' }}" loading="lazy">
                    </button>
                @endforeach
            </div>

            {{-- Lightbox --}}
            <div
                x-show="open"
                x-transition
                @keydown.escape.window="open = false"
                style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.92); z-index:500; display:flex; align-items:center; justify-content:center; padding:1rem;"
                role="dialog"
                aria-modal="true"
            >
                <button @click="open = false" aria-label="Close" style="position:absolute; top:1.25rem; right:1.25rem; background:rgba(255,255,255,0.1); border:none; color:#fff; padding:0.75rem; border-radius:50%; cursor:pointer; font-size:1.25rem; line-height:1;">✕</button>
                <button @click="prev()" aria-label="Previous" style="position:absolute; left:1.25rem; background:rgba(255,255,255,0.1); border:none; color:#fff; padding:0.75rem 1rem; border-radius:var(--radius-sm); cursor:pointer; font-size:1.25rem;">‹</button>
                <img :src="images[current]" alt="" style="max-height:90vh; max-width:90vw; object-fit:contain; border-radius:var(--radius-sm);">
                <button @click="next()" aria-label="Next" style="position:absolute; right:1.25rem; background:rgba(255,255,255,0.1); border:none; color:#fff; padding:0.75rem 1rem; border-radius:var(--radius-sm); cursor:pointer; font-size:1.25rem;">›</button>
            </div>
        @endif
    </div>
</section>
