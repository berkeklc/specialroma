<?php

declare(strict_types=1);

namespace Modules\Contact\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Contact\App\Models\ContactSubmission;
use Modules\Core\App\Settings\GeneralSettings;

final class ContactSubmissionConfirmationNotification extends Notification implements ShouldQueue
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
        $siteName = app(GeneralSettings::class)->site_name ?? config('app.name');

        return (new MailMessage)
            ->subject('We received your message — ' . $siteName)
            ->greeting('Hello, ' . $this->submission->name . '!')
            ->line('Thank you for contacting us. We have received your message and will get back to you as soon as possible.')
            ->line('**Your message:**')
            ->line($this->submission->message)
            ->salutation('Best regards, ' . $siteName);
    }
}
