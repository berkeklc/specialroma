@php
    use Modules\Core\App\Settings\GeneralSettings;

    $heading = $block['data']['heading'] ?? ($block['data']['title'] ?? 'İletişim');
    $subtitle = $block['data']['subtitle'] ?? '';
    $bg = $block['data']['background'] ?? 'default';
    $showMap = (bool) ($block['data']['show_map'] ?? false);
    $formKey = $block['data']['form_key'] ?? 'contact';
    $settings = app(GeneralSettings::class);
@endphp

<section class="block-section sr-block sr-contact-forms"
    style="{{ $bg === 'alt' ? 'background:var(--color-surface);' : '' }}">
    <div class="container-site">
        @if ($heading)
            <div class="fade-up sr-section-header">
                <span class="sr-eyebrow">{{ __('Bize Ulaşın') }}</span>
                <h2 class="sr-h1">{{ $heading }}</h2>
                @if ($subtitle)
                    <p class="sr-lead">{{ $subtitle }}</p>
                @endif
            </div>
        @endif

        <div class="sr-contact-grid">
            <div class="fade-up sr-contact-info">
                <div class="sr-info-card">
                    <div class="sr-info-blob"></div>
                    <h3 class="sr-info-title">{{ __('İrtibat Bilgileri') }}</h3>

                    <div class="sr-info-item">
                        <div class="sr-info-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                        </div>
                        <div class="sr-info-text">
                            <label>{{ __('Adres') }}</label>
                            <p>{{ $settings->contact_address ?: 'Mordoğan, İzmir' }}</p>
                        </div>
                    </div>

                    <div class="sr-info-item">
                        <div class="sr-info-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path
                                    d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.81 12.81 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z" />
                            </svg>
                        </div>
                        <div class="sr-info-text">
                            <label>{{ __('Telefon') }}</label>
                            <p>{{ $settings->contact_phone ?: '+90 5XX XXX XX XX' }}</p>
                        </div>
                    </div>

                    <div class="sr-info-item">
                        <div class="sr-info-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                <polyline points="22,6 12,13 2,6" />
                            </svg>
                        </div>
                        <div class="sr-info-text">
                            <label>E-posta</label>
                            <p>{{ $settings->contact_email ?: 'info@romaspecial.com' }}</p>
                        </div>
                    </div>

                    <div class="sr-info-footer">
                        <span>{{ __('Her gün taze, her gün sizinle.') }}</span>
                    </div>
                </div>

                @if ($showMap)
                    <div class="sr-map-wrapper">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d780.4180906623133!2d26.625279969662788!3d38.51826659823799!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14bbbba1501077af%3A0x599969cc9c850e5f!2sSpecial%20Roma%20cafe%20%26%20Patisseria!5e0!3m2!1str!2sus!4v1775059217298!5m2!1str!2sus"
                            width="100%" height="240" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                @endif
            </div>

            <div class="fade-up sr-contact-form-wrapper">
                <div class="sr-form-card">
                    <h4 class="sr-form-title">{{ __('Mesaj Gönderin') }} </h4>
                    @livewire('contact::contact-form', ['formKey' => $formKey], key('contact-form-' . $formKey))
                </div>
            </div>
        </div>
    </div>
</section>