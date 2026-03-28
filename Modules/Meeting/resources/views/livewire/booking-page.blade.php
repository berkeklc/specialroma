<div class="booking-root">

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- STEP 3 — CONFIRMED                                              --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if ($this->step === 3 && $confirmedAppointment)
    <div class="booking-card booking-card--confirm" role="main">
        <div class="booking-confirm__icon" aria-hidden="true">
            <svg width="64" height="64" viewBox="0 0 64 64" fill="none">
                <circle cx="32" cy="32" r="32" fill="#22c55e" opacity=".15"/>
                <circle cx="32" cy="32" r="24" fill="#22c55e" opacity=".2"/>
                <path d="M20 33l9 9 15-16" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h1 class="booking-confirm__title">{{ __('You are booked!') }}</h1>
        <p class="booking-confirm__sub">{{ __('A confirmation has been sent to') }} <strong>{{ $confirmedAppointment->client_email }}</strong>.</p>

        <div class="booking-confirm__details">
            @if ($confirmedAppointment->staff)
                <div class="booking-confirm__row">
                    <span class="booking-confirm__label">{{ __('With') }}</span>
                    <span>{{ $confirmedAppointment->staff->name }}</span>
                </div>
            @endif
            <div class="booking-confirm__row">
                <span class="booking-confirm__label">{{ __('Date') }}</span>
                <span>{{ $confirmedAppointment->starts_at->translatedFormat('l, F j Y') }}</span>
            </div>
            <div class="booking-confirm__row">
                <span class="booking-confirm__label">{{ __('Time') }}</span>
                <span>{{ $confirmedAppointment->starts_at->format('g:i A') }} – {{ $confirmedAppointment->ends_at->format('g:i A') }}</span>
            </div>
            <div class="booking-confirm__row">
                <span class="booking-confirm__label">{{ __('Duration') }}</span>
                <span>{{ $confirmedAppointment->staff?->meeting_duration ?? 30 }} {{ __('minutes') }}</span>
            </div>
        </div>

        <a href="{{ route('home') }}" class="btn-primary" style="margin-top:2rem; display:inline-block;">
            ← {{ __('Back to home') }}
        </a>
    </div>

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- STEP 2 — PERSONAL INFORMATION                                    --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@elseif ($this->step === 2)
    <div class="booking-card" role="main">

        {{-- Left sidebar: summary --}}
        <aside class="booking-sidebar" aria-label="{{ __('Appointment summary') }}">
            @if ($staff)
                <div class="booking-who">
                    @if ($staff->getFirstMediaUrl('photo'))
                        <img src="{{ $staff->getFirstMediaUrl('photo') }}" alt="{{ $staff->name }}" class="booking-who__avatar">
                    @else
                        <div class="booking-who__avatar booking-who__avatar--placeholder" aria-hidden="true">
                            {{ mb_substr($staff->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <p class="booking-who__name">{{ $staff->name }}</p>
                        @if ($staff->getTranslation('title', app()->getLocale(), true))
                            <p class="booking-who__role">{{ $staff->getTranslation('title', app()->getLocale()) }}</p>
                        @endif
                    </div>
                </div>
            @endif

            <h2 class="booking-sidebar__type">{{ __('Appointment') }}</h2>

            <ul class="booking-sidebar__meta">
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $staff?->meeting_duration ?? 30 }} {{ __('min') }}
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ \Carbon\Carbon::parse($this->selectedDate)->translatedFormat('l, F j') }}
                </li>
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $this->confirmationTime }}
                </li>
            </ul>

            <button wire:click="back" class="booking-sidebar__back">
                ← {{ __('Change') }}
            </button>
        </aside>

        {{-- Right: form --}}
        <div class="booking-form-panel">
            <h2 class="booking-form-panel__title">{{ __('Enter your details') }}</h2>

            <form wire:submit.prevent="confirm" novalidate class="booking-form">

                <div class="booking-form__row">
                    <div class="booking-form__field">
                        <label for="clientName" class="booking-form__label">{{ __('First name') }} <span aria-hidden="true" class="req">*</span></label>
                        <input id="clientName" type="text" wire:model="clientName" class="booking-form__input @error('clientName') booking-form__input--error @enderror" placeholder="{{ __('Jane') }}" autocomplete="given-name" required>
                        @error('clientName')<span class="booking-form__error" role="alert">{{ $message }}</span>@enderror
                    </div>
                    <div class="booking-form__field">
                        <label for="clientSurname" class="booking-form__label">{{ __('Last name') }} <span aria-hidden="true" class="req">*</span></label>
                        <input id="clientSurname" type="text" wire:model="clientSurname" class="booking-form__input @error('clientSurname') booking-form__input--error @enderror" placeholder="{{ __('Doe') }}" autocomplete="family-name" required>
                        @error('clientSurname')<span class="booking-form__error" role="alert">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="booking-form__field">
                    <label for="clientEmail" class="booking-form__label">{{ __('Email') }} <span aria-hidden="true" class="req">*</span></label>
                    <input id="clientEmail" type="email" wire:model="clientEmail" class="booking-form__input @error('clientEmail') booking-form__input--error @enderror" placeholder="jane@example.com" autocomplete="email" required>
                    @error('clientEmail')<span class="booking-form__error" role="alert">{{ $message }}</span>@enderror
                </div>

                <div class="booking-form__row">
                    <div class="booking-form__field">
                        <label for="clientPhone" class="booking-form__label">{{ __('Phone') }} <span class="booking-form__optional">({{ __('optional') }})</span></label>
                        <input id="clientPhone" type="tel" wire:model="clientPhone" class="booking-form__input" placeholder="+90 5xx xxx xx xx" autocomplete="tel">
                    </div>
                    <div class="booking-form__field">
                        <label for="guestEmail" class="booking-form__label">{{ __('Guest email') }} <span class="booking-form__optional">({{ __('optional') }})</span></label>
                        <input id="guestEmail" type="email" wire:model="guestEmail" class="booking-form__input @error('guestEmail') booking-form__input--error @enderror" placeholder="colleague@example.com">
                        @error('guestEmail')<span class="booking-form__error" role="alert">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="booking-form__field">
                    <label for="notes" class="booking-form__label">{{ __('Additional notes') }} <span class="booking-form__optional">({{ __('optional') }})</span></label>
                    <textarea id="notes" wire:model="notes" class="booking-form__input" rows="3" placeholder="{{ __('Anything you\'d like us to know?') }}"></textarea>
                </div>

                <div class="booking-form__actions">
                    <button type="button" wire:click="back" class="btn-secondary">← {{ __('Back') }}</button>
                    <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ __('Confirm booking') }}</span>
                        <span wire:loading class="booking-spinner" aria-hidden="true"></span>
                        <span wire:loading class="sr-only">{{ __('Processing…') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- STEP 1 — STAFF + DATE + SLOT PICKER                             --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@else
    <div class="booking-card" role="main">

        {{-- Left sidebar --}}
        <aside class="booking-sidebar" aria-label="{{ __('Booking info') }}">

            {{-- Staff selector --}}
            @if ($allStaff->count() > 1)
                <div class="booking-staff-list" role="list" aria-label="{{ __('Select a team member') }}">
                    <p class="booking-sidebar__section-title">{{ __('Who would you like to meet?') }}</p>
                    @foreach ($allStaff as $member)
                        <button
                            wire:click="selectStaff({{ $member->id }})"
                            role="listitem"
                            class="booking-staff-btn {{ $this->staffId === $member->id ? 'booking-staff-btn--active' : '' }}"
                            aria-pressed="{{ $this->staffId === $member->id ? 'true' : 'false' }}"
                        >
                            @if ($member->getFirstMediaUrl('photo'))
                                <img src="{{ $member->getFirstMediaUrl('photo') }}" alt="" class="booking-staff-btn__avatar" aria-hidden="true">
                            @else
                                <span class="booking-staff-btn__avatar booking-staff-btn__avatar--placeholder" aria-hidden="true">{{ mb_substr($member->name, 0, 1) }}</span>
                            @endif
                            <span class="booking-staff-btn__info">
                                <span class="booking-staff-btn__name">{{ $member->name }}</span>
                                @if ($member->getTranslation('title', app()->getLocale(), true))
                                    <span class="booking-staff-btn__role">{{ $member->getTranslation('title', app()->getLocale()) }}</span>
                                @endif
                            </span>
                        </button>
                    @endforeach
                </div>
            @elseif ($staff)
                <div class="booking-who">
                    @if ($staff->getFirstMediaUrl('photo'))
                        <img src="{{ $staff->getFirstMediaUrl('photo') }}" alt="{{ $staff->name }}" class="booking-who__avatar">
                    @else
                        <div class="booking-who__avatar booking-who__avatar--placeholder" aria-hidden="true">{{ mb_substr($staff->name, 0, 1) }}</div>
                    @endif
                    <div>
                        <p class="booking-who__name">{{ $staff->name }}</p>
                        @if ($staff->getTranslation('title', app()->getLocale(), true))
                            <p class="booking-who__role">{{ $staff->getTranslation('title', app()->getLocale()) }}</p>
                        @endif
                    </div>
                </div>
            @else
                <div class="booking-who">
                    <div class="booking-who__avatar booking-who__avatar--placeholder" aria-hidden="true">?</div>
                    <p class="booking-who__name" style="color:var(--color-text-muted);">{{ __('No staff configured yet') }}</p>
                </div>
            @endif

            <h2 class="booking-sidebar__type">{{ __('Appointment') }}</h2>

            <ul class="booking-sidebar__meta">
                <li>
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $staff?->meeting_duration ?? 30 }} {{ __('min') }}
                </li>
            </ul>
        </aside>

        {{-- Center: Calendar --}}
        <div class="booking-calendar-panel" aria-label="{{ __('Date picker') }}">
            <div class="booking-cal__header">
                <button wire:click="prevMonth" class="booking-cal__nav" aria-label="{{ __('Previous month') }}">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <span class="booking-cal__month-label" aria-live="polite">{{ $this->monthLabel }}</span>
                <button wire:click="nextMonth" class="booking-cal__nav" aria-label="{{ __('Next month') }}">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            {{-- Day-of-week headers --}}
            <div class="booking-cal__grid booking-cal__grid--header" aria-hidden="true">
                @foreach (['MON','TUE','WED','THU','FRI','SAT','SUN'] as $dh)
                    <span>{{ $dh }}</span>
                @endforeach
            </div>

            {{-- Day cells --}}
            <div class="booking-cal__grid" role="grid" aria-label="{{ __('Calendar') }}">
                @foreach ($calendarDays as $day)
                    @if ($day['isPast'] || ! $day['inMonth'])
                        <span
                            class="booking-cal__day booking-cal__day--disabled {{ ! $day['inMonth'] ? 'booking-cal__day--out' : '' }}"
                            aria-hidden="{{ ! $day['inMonth'] ? 'true' : 'false' }}"
                        >{{ $day['day'] }}</span>
                    @else
                        <button
                            wire:click="selectDate('{{ $day['date'] }}')"
                            class="booking-cal__day {{ $day['isToday'] ? 'booking-cal__day--today' : '' }} {{ $day['isSelected'] ? 'booking-cal__day--selected' : '' }}"
                            role="gridcell"
                            aria-label="{{ \Carbon\Carbon::parse($day['date'])->translatedFormat('l j F') }}"
                            aria-pressed="{{ $day['isSelected'] ? 'true' : 'false' }}"
                        >{{ $day['day'] }}</button>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Right: Slot picker --}}
        <div class="booking-slots-panel" aria-label="{{ __('Time slots') }}">
            @if ($this->selectedDate)
                <p class="booking-slots__day-label">{{ $this->selectedDateLabel }}</p>
            @endif

            @if (! $staff)
                <p class="booking-slots__empty">{{ __('Please select a team member first.') }}</p>
            @elseif ($availableSlots->isEmpty())
                <p class="booking-slots__empty">{{ __('No available times on this day.') }}</p>
            @else
                <div class="booking-slots__list" role="list">
                    @foreach ($availableSlots as $slot)
                        @if ($slot['available'])
                            @if ($this->selectedSlot === $slot['time'])
                                <div class="booking-slot-row" role="listitem">
                                    <button class="booking-slot booking-slot--selected" aria-pressed="true">
                                        {{ $slot['time'] }}
                                    </button>
                                    <button
                                        wire:click="goToStep2"
                                        class="booking-slot-next"
                                        aria-label="{{ __('Confirm') }} {{ $slot['time'] }}"
                                    >
                                        {{ __('Next') }} →
                                    </button>
                                </div>
                            @else
                                <button
                                    wire:click="selectSlot('{{ $slot['time'] }}')"
                                    class="booking-slot"
                                    role="listitem"
                                    aria-label="{{ $slot['time'] }}"
                                >
                                    {{ $slot['time'] }}
                                </button>
                            @endif
                        @else
                            <button class="booking-slot booking-slot--disabled" disabled aria-disabled="true">
                                {{ $slot['time'] }}
                            </button>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endif

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- STYLES (scoped to .booking-root)                               --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<style>
.booking-root {
    min-height: 70vh;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 3rem 1rem 5rem;
    background: var(--color-bg, #f8fafc);
}

/* ── Card ── */
.booking-card {
    display: flex;
    flex-wrap: wrap;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 1.25rem;
    box-shadow: 0 4px 32px rgba(0,0,0,.06);
    max-width: 960px;
    width: 100%;
    overflow: hidden;
}
.booking-card--confirm {
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 3.5rem 2.5rem;
    max-width: 520px;
}

/* ── Sidebar ── */
.booking-sidebar {
    width: 280px;
    flex-shrink: 0;
    padding: 2.25rem 1.75rem;
    border-right: 1px solid #e2e8f0;
    background: #fcfcfd;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}
.booking-sidebar__section-title {
    font-size: .8125rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #64748b;
    margin: 0;
}
.booking-sidebar__type {
    font-size: 1.375rem;
    font-weight: 700;
    margin: 0;
    color: #0f172a;
}
.booking-sidebar__meta {
    list-style: none;
    padding: 0; margin: 0;
    display: flex;
    flex-direction: column;
    gap: .5rem;
}
.booking-sidebar__meta li {
    display: flex;
    align-items: center;
    gap: .5rem;
    font-size: .9375rem;
    color: #475569;
}
.booking-sidebar__back {
    margin-top: auto;
    font-size: .875rem;
    color: var(--color-primary, #3b82f6);
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
    text-align: left;
    font-weight: 500;
}
.booking-sidebar__back:hover { text-decoration: underline; }

/* ── Who ── */
.booking-who {
    display: flex;
    align-items: center;
    gap: .875rem;
}
.booking-who__avatar {
    width: 52px; height: 52px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
.booking-who__avatar--placeholder {
    background: var(--color-primary, #3b82f6);
    color: #fff;
    font-size: 1.25rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}
.booking-who__name { font-weight: 700; font-size: .9375rem; margin: 0; color: #0f172a; }
.booking-who__role { font-size: .8125rem; color: #64748b; margin: .1rem 0 0; }

/* ── Staff list ── */
.booking-staff-list { display: flex; flex-direction: column; gap: .5rem; }
.booking-staff-btn {
    display: flex; align-items: center; gap: .75rem;
    padding: .625rem .75rem;
    border: 1.5px solid #e2e8f0;
    border-radius: .75rem;
    background: #fff;
    cursor: pointer;
    text-align: left;
    transition: border-color .15s, box-shadow .15s;
}
.booking-staff-btn:hover { border-color: var(--color-primary, #3b82f6); }
.booking-staff-btn--active { border-color: var(--color-primary, #3b82f6); box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-primary, #3b82f6) 15%, transparent); }
.booking-staff-btn__avatar {
    width: 36px; height: 36px; border-radius: 50%; object-fit: cover; flex-shrink: 0;
}
.booking-staff-btn__avatar--placeholder {
    background: var(--color-primary, #3b82f6); color: #fff;
    font-size: .9rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}
.booking-staff-btn__info { display: flex; flex-direction: column; }
.booking-staff-btn__name { font-size: .9rem; font-weight: 600; color: #0f172a; }
.booking-staff-btn__role { font-size: .775rem; color: #64748b; }

/* ── Calendar panel ── */
.booking-calendar-panel {
    flex: 1; min-width: 260px;
    padding: 2rem 1.5rem;
    border-right: 1px solid #e2e8f0;
}
.booking-cal__header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 1.25rem;
}
.booking-cal__month-label { font-size: 1rem; font-weight: 700; color: #0f172a; }
.booking-cal__nav {
    width: 32px; height: 32px; border-radius: 50%;
    border: 1.5px solid #e2e8f0; background: #fff;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: #475569;
    transition: border-color .15s, background .15s;
}
.booking-cal__nav:hover { border-color: var(--color-primary, #3b82f6); background: #f0f7ff; }

.booking-cal__grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: .25rem;
}
.booking-cal__grid--header span {
    text-align: center;
    font-size: .6875rem;
    font-weight: 700;
    letter-spacing: .05em;
    color: #94a3b8;
    padding: .25rem 0;
}
.booking-cal__day {
    aspect-ratio: 1;
    border-radius: 50%;
    border: none;
    background: none;
    font-size: .9rem;
    font-weight: 500;
    color: #1e293b;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .15s, color .15s;
}
.booking-cal__day:hover:not(.booking-cal__day--disabled) {
    background: #eff6ff; color: var(--color-primary, #3b82f6);
}
.booking-cal__day--today {
    background: #eff6ff; color: var(--color-primary, #3b82f6); font-weight: 700;
}
.booking-cal__day--selected {
    background: var(--color-primary, #3b82f6) !important;
    color: #fff !important;
    font-weight: 700;
}
.booking-cal__day--disabled {
    color: #cbd5e1; cursor: default;
}
.booking-cal__day--out { opacity: 0; pointer-events: none; }

/* ── Slots panel ── */
.booking-slots-panel {
    width: 200px; flex-shrink: 0;
    padding: 2rem 1.25rem;
    overflow-y: auto;
    max-height: 520px;
}
.booking-slots__day-label {
    font-size: .9rem; font-weight: 700; color: #0f172a;
    margin: 0 0 1rem;
}
.booking-slots__empty {
    font-size: .875rem; color: #94a3b8; text-align: center; padding-top: 2rem;
}
.booking-slots__list {
    display: flex; flex-direction: column; gap: .5rem;
}
.booking-slot-row { display: flex; gap: .375rem; }
.booking-slot {
    flex: 1;
    padding: .625rem .5rem;
    border: 1.5px solid var(--color-primary, #3b82f6);
    border-radius: .625rem;
    background: #fff;
    color: var(--color-primary, #3b82f6);
    font-size: .9rem; font-weight: 600;
    cursor: pointer;
    text-align: center;
    transition: background .15s, color .15s;
}
.booking-slot:hover:not(.booking-slot--disabled):not(.booking-slot--selected) {
    background: #eff6ff;
}
.booking-slot--selected {
    background: #1e293b; color: #fff; border-color: #1e293b;
}
.booking-slot--disabled {
    border-color: #e2e8f0; color: #cbd5e1; cursor: not-allowed;
}
.booking-slot-next {
    padding: .625rem .625rem;
    border-radius: .625rem;
    background: var(--color-primary, #3b82f6);
    color: #fff;
    font-size: .875rem; font-weight: 700;
    border: none; cursor: pointer;
    white-space: nowrap;
    transition: opacity .15s;
}
.booking-slot-next:hover { opacity: .9; }

/* ── Form panel ── */
.booking-form-panel {
    flex: 1; min-width: 280px;
    padding: 2.25rem 2rem;
}
.booking-form-panel__title {
    font-size: 1.25rem; font-weight: 700; color: #0f172a;
    margin: 0 0 1.75rem;
}
.booking-form { display: flex; flex-direction: column; gap: 1.25rem; }
.booking-form__row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.booking-form__field { display: flex; flex-direction: column; gap: .375rem; }
.booking-form__label { font-size: .875rem; font-weight: 600; color: #374151; }
.booking-form__optional { font-weight: 400; color: #94a3b8; }
.req { color: #ef4444; margin-left: .1rem; }
.booking-form__input {
    padding: .625rem .875rem;
    border: 1.5px solid #e2e8f0;
    border-radius: .625rem;
    font-size: .9375rem;
    background: #fff;
    color: #0f172a;
    outline: none;
    transition: border-color .15s, box-shadow .15s;
    width: 100%;
    box-sizing: border-box;
}
.booking-form__input:focus {
    border-color: var(--color-primary, #3b82f6);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-primary, #3b82f6) 15%, transparent);
}
.booking-form__input--error { border-color: #ef4444; }
.booking-form__error { font-size: .8125rem; color: #ef4444; }
.booking-form__actions {
    display: flex; gap: 1rem; justify-content: flex-end; padding-top: .5rem;
}

/* ── Confirmation ── */
.booking-confirm__icon { margin-bottom: 1.25rem; }
.booking-confirm__title { font-size: 1.875rem; font-weight: 800; color: #0f172a; margin: 0 0 .5rem; }
.booking-confirm__sub { color: #64748b; margin: 0 0 2rem; }
.booking-confirm__details {
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: .875rem;
    overflow: hidden;
}
.booking-confirm__row {
    display: flex; justify-content: space-between;
    padding: .875rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    font-size: .9375rem; color: #1e293b;
}
.booking-confirm__row:last-child { border-bottom: none; }
.booking-confirm__label { color: #64748b; font-weight: 500; }

/* ── Spinner ── */
.booking-spinner {
    display: inline-block;
    width: 18px; height: 18px;
    border: 2.5px solid rgba(255,255,255,.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Responsive ── */
@media (max-width: 768px) {
    .booking-card { flex-direction: column; }
    .booking-sidebar { width: 100%; border-right: none; border-bottom: 1px solid #e2e8f0; }
    .booking-slots-panel { width: 100%; max-height: none; border-top: 1px solid #e2e8f0; }
    .booking-calendar-panel { border-right: none; }
    .booking-form__row { grid-template-columns: 1fr; }
    .booking-form-panel { padding: 1.5rem; }
}
</style>
</div>
