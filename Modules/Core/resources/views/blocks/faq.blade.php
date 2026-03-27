@php
    $title   = $block['data']['title'] ?? '';
    $eyebrow = $block['data']['eyebrow'] ?? 'FAQ';
    $items   = $block['data']['items'] ?? [];

    // JSON-LD for FAQ
    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type'    => 'FAQPage',
        'mainEntity' => array_map(fn($item) => [
            '@type' => 'Question',
            'name'  => $item['question'] ?? '',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => strip_tags($item['answer'] ?? ''),
            ],
        ], $items),
    ];
@endphp

@push('schema_org')
    <script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

<section class="block-section">
    <div class="container-site" style="max-width:800px; margin-inline:auto;">
        @if ($eyebrow || $title)
            <div class="fade-up" style="text-align:center; margin-bottom:3rem;">
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

        <div x-data="faqAccordion()" class="fade-up">
            @foreach ($items as $i => $item)
                <div class="faq-item">
                    <button
                        class="faq-question"
                        @click="toggle({{ $i }})"
                        :aria-expanded="open === {{ $i }}"
                        id="faq-q-{{ $loop->index }}"
                        aria-controls="faq-a-{{ $loop->index }}"
                    >
                        {{ $item['question'] ?? '' }}
                        <svg
                            width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2"
                            style="flex-shrink:0; transition:transform 0.25s ease;"
                            :style="open === {{ $i }} ? 'transform:rotate(180deg)' : ''"
                        ><path d="M5 7.5l5 5 5-5"/></svg>
                    </button>
                    <div
                        id="faq-a-{{ $loop->index }}"
                        role="region"
                        aria-labelledby="faq-q-{{ $loop->index }}"
                        x-show="open === {{ $i }}"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        style="display:none;"
                    >
                        <div class="faq-answer">{!! $item['answer'] ?? '' !!}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
