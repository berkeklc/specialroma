<?php

declare(strict_types=1);

namespace Modules\Contact\App\Livewire;

use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Modules\Contact\App\Actions\ProcessContactSubmission;

final class ContactForm extends Component
{
    #[Rule(['required', 'string', 'min:2', 'max:100'])]
    public string $name = '';

    #[Rule(['required', 'email', 'max:200'])]
    public string $email = '';

    #[Rule(['nullable', 'string', 'max:30'])]
    public ?string $phone = null;

    #[Rule(['nullable', 'string', 'max:200'])]
    public ?string $subject = null;

    #[Rule(['required', 'string', 'min:10', 'max:5000'])]
    public string $message = '';

    /** @var string Honeypot field — must remain empty */
    public string $website = '';

    public bool $submitted = false;
    public ?string $errorMessage = null;

    public function submit(ProcessContactSubmission $action): void
    {
        // Honeypot check
        if ($this->website !== '') {
            $this->submitted = true;
            return;
        }

        // Rate limiting: 3 submissions per IP per 10 minutes
        $key = 'contact-form:' . (request()->ip() ?? 'unknown');
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $this->errorMessage = __('Too many submissions. Please wait :seconds seconds before trying again.', ['seconds' => $seconds]);
            return;
        }

        $this->validate();

        try {
            $action->execute(
                name: $this->name,
                email: $this->email,
                message: $this->message,
                phone: $this->phone,
                subject: $this->subject,
                formKey: 'contact',
                ipAddress: request()->ip(),
            );

            RateLimiter::hit($key, 600);

            $this->reset(['name', 'email', 'phone', 'subject', 'message', 'website']);
            $this->errorMessage = null;
            $this->submitted = true;

        } catch (\Throwable $e) {
            $this->errorMessage = __('Something went wrong. Please try again or contact us directly.');
            report($e);
        }
    }

    public function resetForm(): void
    {
        $this->submitted = false;
        $this->errorMessage = null;
    }

    public function render(): \Illuminate\View\View
    {
        return view('contact::livewire.contact-form');
    }
}
