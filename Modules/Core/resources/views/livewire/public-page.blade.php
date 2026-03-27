<div>
    @if (!empty($page->blocks))
        @include('core::partials.render-blocks', ['blocks' => $page->blocks])
    @else
        <div class="block-section">
            <div class="container-site" style="text-align:center; padding-block:5rem;">
                <h1 style="font-size:clamp(1.75rem, 4vw, 3rem); margin-bottom:1rem;">
                    {{ $page->getTranslation('title', app()->getLocale()) }}
                </h1>
                <p style="color:var(--color-muted);">{{ __('This page has no content blocks yet.') }}</p>
            </div>
        </div>
    @endif
</div>
