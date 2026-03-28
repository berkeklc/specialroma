<?php

declare(strict_types=1);

namespace Modules\Meeting\App\Livewire;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Modules\Meeting\App\Models\Appointment;
use Modules\Meeting\App\Models\Staff;
use Modules\Meeting\App\Notifications\AppointmentConfirmedNotification;

#[Layout('layouts.app')]
final class BookingPage extends Component
{
    // ── State machine: 1 = pick staff/date/slot, 2 = form, 3 = confirmed ──
    public int $step = 1;

    // Step 1
    public ?int $staffId = null;

    #[Url]
    public string $selectedDate = '';

    public ?string $selectedSlot = null; // "HH:MM"

    // Step 2 — client form
    public string $clientName = '';

    public string $clientSurname = '';

    public string $clientEmail = '';

    public string $clientPhone = '';

    public string $guestEmail = '';

    public string $notes = '';

    // Confirmation
    public ?int $confirmedAppointmentId = null;

    // Validation
    protected function rules(): array
    {
        return [
            'clientName' => ['required', 'string', 'min:2'],
            'clientSurname' => ['required', 'string', 'min:2'],
            'clientEmail' => ['required', 'email'],
            'clientPhone' => ['nullable', 'string'],
            'guestEmail' => ['nullable', 'email'],
        ];
    }

    protected function messages(): array
    {
        return [
            'clientName.required' => 'First name is required.',
            'clientSurname.required' => 'Last name is required.',
            'clientEmail.required' => 'Email is required.',
            'clientEmail.email' => 'Please enter a valid email address.',
        ];
    }

    public function mount(): void
    {
        // Auto-select the only staff member if there's exactly one
        $staff = Staff::where('is_active', true)->get();
        if ($staff->count() === 1) {
            $this->staffId = $staff->first()->id;
        }

        if (! $this->selectedDate) {
            $this->selectedDate = now()->toDateString();
        }
    }

    public function selectStaff(int $staffId): void
    {
        $this->staffId = $staffId;
        $this->selectedSlot = null;
    }

    public function prevMonth(): void
    {
        $this->selectedDate = Carbon::parse($this->selectedDate)
            ->subMonth()
            ->startOfMonth()
            ->toDateString();
        $this->selectedSlot = null;
    }

    public function nextMonth(): void
    {
        $this->selectedDate = Carbon::parse($this->selectedDate)
            ->addMonth()
            ->startOfMonth()
            ->toDateString();
        $this->selectedSlot = null;
    }

    public function selectDate(string $date): void
    {
        $this->selectedDate = $date;
        $this->selectedSlot = null;
    }

    public function selectSlot(string $slot): void
    {
        $this->selectedSlot = $slot;
    }

    public function goToStep2(): void
    {
        if (! $this->staffId || ! $this->selectedDate || ! $this->selectedSlot) {
            return;
        }

        $this->step = 2;
    }

    public function back(): void
    {
        $this->step = max(1, $this->step - 1);
    }

    public function confirm(): void
    {
        $this->validate();

        $staff = Staff::findOrFail($this->staffId);

        $startsAt = Carbon::parse($this->selectedDate.' '.$this->selectedSlot, 'UTC');
        $endsAt = $startsAt->copy()->addMinutes($staff->meeting_duration ?: 30);

        $appointment = Appointment::create([
            'staff_id' => $staff->id,
            'client_name' => trim($this->clientName.' '.$this->clientSurname),
            'client_email' => $this->clientEmail,
            'client_phone' => $this->clientPhone ?: null,
            'notes' => $this->notes ?: null,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'timezone' => 'UTC',
            'status' => 'confirmed',
            'meeting_type' => 'in_person',
        ]);

        $this->confirmedAppointmentId = $appointment->id;
        $this->step = 3;

        // Send notification
        try {
            $appointment->load('staff');
            Notification::route('mail', $this->clientEmail)
                ->notify(new AppointmentConfirmedNotification($appointment));
        } catch (\Throwable) {
            // Mail may not be configured — don't fail the booking.
        }
    }

    public function render(): View
    {
        view()->share('seoTitle', __('Book an Appointment'));
        view()->share('seoDescription', __('Schedule a meeting with our team.'));

        $allStaff = Staff::where('is_active', true)->orderBy('name')->get();
        $staff = $this->staffId ? $allStaff->find($this->staffId) : null;

        $calendarDays = $this->buildCalendar();
        $availableSlots = $staff ? $this->buildSlots($staff) : collect();

        $confirmedAppointment = $this->confirmedAppointmentId
            ? Appointment::with('staff')->find($this->confirmedAppointmentId)
            : null;

        return view('meeting::livewire.booking-page', compact(
            'allStaff',
            'staff',
            'calendarDays',
            'availableSlots',
            'confirmedAppointment',
        ));
    }

    // ── Calendar helpers ──────────────────────────────────────────────────

    /**
     * @return Collection<int, array{date: string, day: int, isToday: bool, isPast: bool, isSelected: bool}>
     */
    private function buildCalendar(): Collection
    {
        $pivot = Carbon::parse($this->selectedDate);
        $firstDay = $pivot->copy()->startOfMonth();
        $lastDay = $pivot->copy()->endOfMonth();
        $today = Carbon::today();

        // Pad to Monday
        $startPad = $firstDay->copy()->startOfWeek(CarbonInterface::MONDAY);
        $endPad = $lastDay->copy()->endOfWeek(CarbonInterface::MONDAY);

        $days = collect();
        $cur = $startPad->copy();

        while ($cur->lessThanOrEqualTo($endPad)) {
            $days->push([
                'date' => $cur->toDateString(),
                'day' => $cur->day,
                'inMonth' => $cur->month === $firstDay->month,
                'isToday' => $cur->isSameDay($today),
                'isPast' => $cur->lt($today),
                'isSelected' => $cur->toDateString() === $this->selectedDate,
            ]);
            $cur->addDay();
        }

        return $days;
    }

    /**
     * @return Collection<int, array{time: string, available: bool}>
     */
    private function buildSlots(Staff $staff): Collection
    {
        $date = Carbon::parse($this->selectedDate);
        $dayName = strtolower($date->format('l')); // monday, tuesday…
        $workingHrs = $staff->working_hours ?? [];

        $dayConfig = $workingHrs[$dayName] ?? null;

        if (! $dayConfig || empty($dayConfig['enabled'])) {
            return collect();
        }

        $start = Carbon::parse($this->selectedDate.' '.($dayConfig['start'] ?? '09:00'));
        $end = Carbon::parse($this->selectedDate.' '.($dayConfig['end'] ?? '17:00'));
        $duration = $staff->meeting_duration ?: 30;
        $buffer = $staff->buffer_time ?: 0;

        // Existing confirmed/pending appointments this day
        $booked = Appointment::where('staff_id', $staff->id)
            ->whereDate('starts_at', $this->selectedDate)
            ->whereIn('status', ['confirmed', 'pending'])
            ->get();

        $slots = collect();
        $cur = $start->copy();

        while ($cur->copy()->addMinutes($duration)->lessThanOrEqualTo($end)) {
            $slotEnd = $cur->copy()->addMinutes($duration);

            $isBooked = $booked->contains(function (Appointment $appt) use ($cur, $slotEnd): bool {
                return $appt->starts_at->lt($slotEnd) && $appt->ends_at->gt($cur);
            });

            $slots->push([
                'time' => $cur->format('H:i'),
                'available' => ! $isBooked && ! $cur->isPast(),
            ]);

            $cur->addMinutes($duration + $buffer);
        }

        return $slots;
    }

    /** Month label for the calendar header (e.g. "March 2026") */
    public function getMonthLabelProperty(): string
    {
        return Carbon::parse($this->selectedDate)->translatedFormat('F Y');
    }

    /** Formatted selected date for display (e.g. "Tuesday, March 31") */
    public function getSelectedDateLabelProperty(): string
    {
        return Carbon::parse($this->selectedDate)->translatedFormat('l, F j');
    }

    /** Formatted confirmation time */
    public function getConfirmationTimeProperty(): string
    {
        if (! $this->selectedSlot || ! $this->selectedDate) {
            return '';
        }

        $starts = Carbon::parse($this->selectedDate.' '.$this->selectedSlot);
        $staff = $this->staffId ? Staff::find($this->staffId) : null;
        $ends = $starts->copy()->addMinutes($staff?->meeting_duration ?: 30);

        return $starts->format('g:i A').' – '.$ends->format('g:i A');
    }
}
