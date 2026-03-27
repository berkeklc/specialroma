<?php

declare(strict_types=1);

namespace Modules\Contact\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Contact\App\Models\ContactSubmission;

final class NewContactSubmissionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly ContactSubmission $submission
    ) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Contact Form Submission — ' . config('app.name'))
            ->greeting('Hello!')
            ->line('You have received a new contact form submission.')
            ->line('**From:** ' . $this->submission->name . ' (' . $this->submission->email . ')')
            ->when($this->submission->phone, fn (MailMessage $m) => $m->line('**Phone:** ' . $this->submission->phone))
            ->when($this->submission->subject, fn (MailMessage $m) => $m->line('**Subject:** ' . $this->submission->subject))
            ->line('**Message:**')
            ->line($this->submission->message)
            ->action('View in Admin', url('/admin/contact-submissions/' . $this->submission->id . '/edit'))
            ->salutation('AgencyStack');
    }
}
