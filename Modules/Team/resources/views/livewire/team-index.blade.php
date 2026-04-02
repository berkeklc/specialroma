<div class="sr-page sr-team-page">
    <section class="page-header sr-page-header">
        <div class="container-site">
            <h1 class="page-header__title font-display">{{ __('Our Team') }}</h1>
            <p class="page-header__subtitle font-sans">{{ __('The people behind our work.') }}</p>
        </div>
    </section>

    <section class="section sr-section">
        <div class="container-site">
            @if ($members->isEmpty())
                <div class="empty-state">
                    <div class="empty-state__icon">👥</div>
                    <h3 class="empty-state__title">{{ __('Team profiles coming soon') }}</h3>
                    <p class="empty-state__text">{{ __('Add team members in the admin panel.') }}</p>
                </div>
            @else
                <div class="card-grid card-grid--4">
                    @foreach ($members as $member)
                        @php $locale = app()->getLocale(); @endphp
                        <div class="card card--team" style="text-align:center;">
                            @if ($member->getFirstMediaUrl('photo'))
                                <img src="{{ $member->getFirstMediaUrl('photo') }}"
                                     alt="{{ $member->getTranslation('name', $locale) }}"
                                     style="width:100px; height:100px; border-radius:50%; object-fit:cover; margin:0 auto 1rem;">
                            @else
                                <div style="width:100px; height:100px; border-radius:50%; background:var(--color-surface); margin:0 auto 1rem; display:flex; align-items:center; justify-content:center; font-size:2.5rem;">
                                    👤
                                </div>
                            @endif
                            <div class="card__body">
                                <h3 class="card__title" style="font-size:1.1rem;">
                                    {{ $member->getTranslation('name', $locale) }}
                                </h3>
                                @if ($member->getTranslation('position', $locale))
                                    <p style="color:var(--color-primary); font-weight:500; font-size:.9rem; margin:.25rem 0;">
                                        {{ $member->getTranslation('position', $locale) }}
                                    </p>
                                @endif
                                @if ($member->getTranslation('bio', $locale))
                                    <p style="color:var(--color-text-muted); font-size:.875rem; margin-top:.5rem; line-height:1.5;">
                                        {{ Str::limit($member->getTranslation('bio', $locale), 100) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>
