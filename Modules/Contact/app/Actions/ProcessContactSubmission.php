<?php

declare(strict_types=1);

namespace Modules\Contact\App\Actions;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Modules\Contact\App\Models\ContactSubmission;
use Modules\Contact\App\Notifications\ContactSubmissionConfirmationNotification;
use Modules\Contact\App\Notifications\NewContactSubmissionNotification;
use Modules\Core\App\Settings\MailSettings;

final class ProcessContactSubmission
{
    public function execute(
        string $name,
        string $email,
        string $message,
        ?string $phone = null,
        ?string $subject = null,
        string $formKey = 'contact',
        ?string $ipAddress = null,
    ): ContactSubmission {
        $submission = ContactSubmission::create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message,
            'form_key' => $formKey,
            'ip_address' => $ipAddress,
            'status' => 'new',
        ]);

        $mailSettings = app(MailSettings::class);

        // Notify admin
        if ($mailSettings->notify_admin_on_submission) {
            Notification::route('mail', $mailSettings->admin_notification_email)
                ->notify(new NewContactSubmissionNotification($submission));
        }

        // Confirm to user
        if ($mailSettings->notify_user_on_submission) {
            Notification::route('mail', $email)
                ->notify(new ContactSubmissionConfirmationNotification($submission));
        }

        return $submission;
    }
}
