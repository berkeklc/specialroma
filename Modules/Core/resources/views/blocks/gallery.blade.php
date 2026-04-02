@php
    $title  = $block['data']['title'] ?? '';
    $images = $block['data']['images'] ?? [];
    $cols   = (int) ($block['data']['columns'] ?? 3);
    $layout = $block['data']['layout'] ?? 'grid';

    $normalizeGalleryUrl = function (mixed $image): string {
        if (is_string($image)) {
            return \Illuminate\Support\Str::startsWith($image, ['http://', 'https://'])
                ? $image
                : \Illuminate\Support\Facades\Storage::url($image);
        }
        if (is_array($image)) {
            if (! empty($image['url'])) {
                return $image['url'];
            }
            if (! empty($image['path'])) {
                return \Illuminate\Support\Facades\Storage::url($image['path']);
            }
        }

        return '';
    };

    $normalized = [];
    foreach ($images as $image) {
        $url = $normalizeGalleryUrl($image);
        if ($url === '') {
            continue;
        }
        $alt = is_array($image) ? ($image['alt'] ?? '') : '';
        $normalized[] = ['url' => $url, 'alt' => $alt];
    }
@endphp

<section class="block-section sr-block sr-gallery-section">
    <div class="container-site">
        @if ($title)
            <h2 class="fade-up" style="font-size:clamp(1.75rem, 4vw, 2.5rem); margin-bottom:2.5rem; text-align:center;">{{ $title }}</h2>
        @endif

        @if (!empty($normalized))
            <div x-data="lightbox({{ json_encode(array_column($normalized, 'url')) }})">
                <div
                    class="fade-up sr-gallery {{ $layout === 'masonry' ? 'gallery-grid gallery-grid--masonry' : 'gallery-grid' }}"
                    @if ($layout !== 'masonry')
                        style="grid-template-columns:repeat({{ $cols }}, 1fr);"
                    @endif
                >
                    @foreach ($normalized as $i => $image)
                        <div class="sr-gallery-card">
                            <button
                                @click="show({{ $i }})"
                                class="sr-gallery-item"
                                aria-label="{{ __('Görseli Büyüt') }} {{ $image['alt'] ?: ($i + 1) }}"
                                style="border:none; cursor:zoom-in; padding:0; width:100%; display:block; position:relative; overflow:hidden;"
                            >
                                <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" loading="lazy" class="sr-gallery-img">
                                <div class="sr-gallery-overlay">
                                    <div class="sr-gallery-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
                                    </div>
                                </div>
                            </button>
                        </div>
                    @endforeach
                </div>

                {{-- Lightbox --}}
                <div
                    x-show="open"
                    x-cloak
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    @keydown.escape.window="open = false"
                    @keydown.arrow-right.window="open && next()"
                    @keydown.arrow-left.window="open && prev()"
                    class="sr-lightbox"
                    role="dialog"
                    aria-modal="true"
                    aria-label="Image lightbox"
                >
                    <div class="sr-lightbox__backdrop" @click="open = false"></div>
                    <button @click="open = false" class="sr-lightbox__close" aria-label="Close">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    </button>
                    <button @click="prev()" class="sr-lightbox__nav sr-lightbox__nav--prev" aria-label="Previous">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <img
                        :src="images[current]"
                        alt=""
                        class="sr-lightbox__img"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                    >
                    <button @click="next()" class="sr-lightbox__nav sr-lightbox__nav--next" aria-label="Next">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                    <div class="sr-lightbox__counter" aria-live="polite">
                        <span x-text="current + 1"></span> / <span x-text="images.length"></span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
