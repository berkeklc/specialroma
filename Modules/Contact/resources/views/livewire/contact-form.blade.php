<div>
    @if ($submitted)
        {{-- Success state --}}
        <div
            style="
                background:var(--color-accent-light);
                border:1.5px solid var(--color-accent);
                border-radius:var(--radius-md);
                padding:2.5rem;
                text-align:center;
            "
            role="alert"
            aria-live="polite"
        >
            <div style="font-size:2.5rem; margin-bottom:1rem;" aria-hidden="true">✓</div>
            <h3 style="font-size:1.25rem; color:var(--color-primary); margin:0 0 0.75rem;">
                Mesajınız Gönderildi!
            </h3>
            <p style="color:var(--color-muted); margin:0 0 1.5rem;">
                Bizimle iletişime geçtiğiniz için teşekkürler. En kısa sürede size geri dönüş yapacağız.
            </p>
            <button
                wire:click="resetForm"
                class="btn-ghost"
                style="font-size:0.9375rem;"
            >
                Yeni bir mesaj gönder
            </button>
        </div>

    @else
        {{-- Error message --}}
        @if ($errorMessage)
            <div
                style="background:#fef2f2; border:1.5px solid #fca5a5; border-radius:var(--radius-sm); padding:1rem 1.25rem; margin-bottom:1.5rem; color:#991b1b; font-size:0.9375rem;"
                role="alert"
            >
                {{ $errorMessage }}
            </div>
        @endif

        <form wire:submit="submit" novalidate>
            {{-- Honeypot (hidden from users) --}}
            <div style="display:none;" aria-hidden="true">
                <label for="website">Burası boş kalsın</label>
                <input id="website" type="text" wire:model="website" tabindex="-1" autocomplete="off">
            </div>

            <div style="display:grid; gap:1.5rem;">

                {{-- Name + Email row --}}
                <div style="display:grid; grid-template-columns:1fr; gap:1.5rem;" class="form-row-2">
                    <div class="form-field">
                        <label for="cf-name" class="form-label">
                            Ad Soyad <span style="color:#ef4444;" aria-hidden="true">*</span>
                        </label>
                        <input
                            id="cf-name"
                            type="text"
                            wire:model.lazy="name"
                            class="form-input"
                            placeholder="Örn: Ahmet Yılmaz"
                            autocomplete="name"
                            required
                            aria-describedby="cf-name-error"
                        >
                        @error('name')
                            <span id="cf-name-error" class="form-error" role="alert">Bu alan zorunludur.</span>
                        @enderror
                    </div>

                    <div class="form-field">
                        <label for="cf-email" class="form-label">
                            E-posta Adresi <span style="color:#ef4444;" aria-hidden="true">*</span>
                        </label>
                        <input
                            id="cf-email"
                            type="email"
                            wire:model.lazy="email"
                            class="form-input"
                            placeholder="eposta@adresiniz.com"
                            autocomplete="email"
                            required
                            aria-describedby="cf-email-error"
                        >
                        @error('email')
                            <span id="cf-email-error" class="form-error" role="alert">Geçerli bir e-posta adresi giriniz.</span>
                        @enderror
                    </div>
                </div>

                {{-- Phone + Subject row --}}
                <div style="display:grid; grid-template-columns:1fr; gap:1.5rem;" class="form-row-2">
                    <div class="form-field">
                        <label for="cf-phone" class="form-label">Telefon <span style="color:var(--color-muted); font-weight:400; font-size:0.875rem;">(isteğe bağlı)</span></label>
                        <input
                            id="cf-phone"
                            type="tel"
                            wire:model.lazy="phone"
                            class="form-input"
                            placeholder="0 533 XXX XX XX"
                            autocomplete="tel"
                        >
                    </div>
                    <div class="form-field">
                        <label for="cf-subject" class="form-label">Konu <span style="color:var(--color-muted); font-weight:400; font-size:0.875rem;">(isteğe bağlı)</span></label>
                        <input
                            id="cf-subject"
                            type="text"
                            wire:model.lazy="subject"
                            class="form-input"
                            placeholder="Nasıl yardımcı olabiliriz?"
                        >
                    </div>
                </div>

                {{-- Message --}}
                <div class="form-field">
                    <label for="cf-message" class="form-label">
                        Mesajınız <span style="color:#ef4444;" aria-hidden="true">*</span>
                    </label>
                    <textarea
                        id="cf-message"
                        wire:model.lazy="message"
                        class="form-textarea"
                        rows="6"
                        placeholder="Mesajınızı buraya yazınız..."
                        required
                        aria-describedby="cf-message-error"
                        style="resize:vertical;"
                    ></textarea>
                    @error('message')
                        <span id="cf-message-error" class="form-error" role="alert">Lütfen bir mesaj yazınız.</span>
                    @enderror
                </div>

                {{-- Submit --}}
                <div>
                    <button
                        type="submit"
                        class="btn-primary"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-75"
                        style="min-width:180px; justify-content:center;"
                    >
                        <span wire:loading.remove wire:target="submit">
                            Mesajı Gönder →
                        </span>
                        <span wire:loading wire:target="submit" style="display:none;">
                            <svg style="animation:spin 1s linear infinite; width:18px; height:18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                            </svg>
                            Gönderiliyor...
                        </span>
                    </button>
                    <p style="margin-top:0.75rem; font-size:0.8125rem; color:var(--color-muted);">
                        İş günlerinde 24 saat içinde dönüş sağlıyoruz.
                    </p>
                </div>
            </div>
        </form>

        <style>
        @media (min-width: 640px) {
            .form-row-2 { grid-template-columns: 1fr 1fr !important; }
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        </style>
    @endif
</div>
