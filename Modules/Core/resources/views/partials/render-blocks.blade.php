{{--
    Renders an array of page content blocks.
    Usage: @include('core::partials.render-blocks', ['blocks' => $page->blocks])

    Block types: hero | text | image | gallery | video | services | testimonials | faq | cta | contact-form | image-text | stats
--}}

@foreach ($blocks ?? [] as $block)
    @php
        $type = $block['type'] ?? '';
        $blockView = 'core::blocks.' . str_replace('_', '-', $type);
    @endphp

    @if ($type && View::exists($blockView))
        @include($blockView, ['block' => $block])
    @elseif ($type)
        {{-- Fallback: try module-specific block view --}}
        @foreach (config('core.optional_modules', []) as $moduleName => $_)
            @php $modView = Str::lower($moduleName) . '::blocks.' . $type; @endphp
            @if (View::exists($modView))
                @include($modView, ['block' => $block])
                @break
            @endif
        @endforeach
    @endif
@endforeach
