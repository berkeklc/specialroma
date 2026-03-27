<?php

declare(strict_types=1);

namespace Modules\Meeting\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Meeting\App\Models\Appointment;

final class AppointmentConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Appointment $appointment
    ) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $staff = $this->appointment->staff;
        $date = $this->appointment->starts_at->format('l, F j, Y');
        $time = $this->appointment->starts_at->format('H:i') . ' – ' . $this->appointment->ends_at->format('H:i');

        $mail = (new MailMessage)
            ->subject('Appointment Confirmed — ' . config('app.name'))
            ->greeting('Hello, ' . $this->appointment->client_name . '!')
            ->line('Your appointment has been confirmed.')
            ->line('**Staff:** ' . $staff->name)
            ->line('**Date:** ' . $date)
            ->line('**Time:** ' . $time . ' (' . $this->appointment->timezone . ')');

        if ($this->appointment->meeting_link) {
            $mail->action('Join Meeting', $this->appointment->meeting_link);
        }

        return $mail->salutation('See you soon! — ' . config('app.name'));
    }
}
