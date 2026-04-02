<?php

declare(strict_types=1);

namespace Modules\Contact\App\Livewire;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Modules\Contact\App\Actions\ProcessContactSubmission;

final class ContactForm extends Component
{
    /** @var string Form identifier stored on submissions (e.g. contact, reservation). */
    public string $formKey = 'contact';

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

    protected function validationAttributes(): array
    {
        return [
            'name' => 'Ad Soyad',
            'email' => 'E-posta',
            'message' => 'Mesaj',
            'phone' => 'Telefon',
            'subject' => 'Konu',
        ];
    }

    protected function messages(): array
    {
        return [
            'required' => ':attribute alanı zorunludur.',
            'email' => 'Geçerli bir :attribute adresi giriniz.',
            'min' => ':attribute en az :min karakter olmalıdır.',
            'max' => ':attribute en fazla :max karakter olmalıdır.',
        ];
    }

    public function submit(ProcessContactSubmission $action): void
    {
        // Honeypot check
        if ($this->website !== '') {
            $this->submitted = true;

            return;
        }

        // Rate limiting: 3 submissions per IP per 10 minutes
        $key = 'contact-form:'.(request()->ip() ?? 'unknown');
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $this->errorMessage = "Çok fazla deneme yaptınız. Lütfen {$seconds} saniye sonra tekrar deneyiniz.";

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
                formKey: $this->formKey,
                ipAddress: request()->ip(),
            );

            RateLimiter::hit($key, 600);

            $this->reset(['name', 'email', 'phone', 'subject', 'message', 'website']);
            $this->errorMessage = null;
            $this->submitted = true;

        } catch (\Throwable $e) {
            $this->errorMessage = 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin veya bizimle doğrudan iletişime geçin.';
            report($e);
        }
    }

    public function resetForm(): void
    {
        $this->submitted = false;
        $this->errorMessage = null;
    }

    public function render(): View
    {
        return view('contact::livewire.contact-form');
    }
}
