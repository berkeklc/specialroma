<?php

declare(strict_types=1);

namespace Modules\Core\App\Http\Livewire;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

final class LanguageSwitcher extends Component
{
    public string $currentLocale;

    /** @var list<array{code: string, label: string, flag: string}> */
    public array $availableLanguages;

    public function mount(): void
    {
        $this->currentLocale = App::getLocale();
        $this->availableLanguages = $this->buildLanguageList();
    }

    public function switchLanguage(string $locale): void
    {
        $availableCodes = array_column($this->availableLanguages, 'code');

        if (! in_array($locale, $availableCodes, true)) {
            return;
        }

        Session::put('locale', $locale);
        $this->currentLocale = $locale;

        $this->dispatch('locale-changed', locale: $locale);
        $this->redirect(request()->header('Referer', '/'));
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('core::livewire.language-switcher');
    }

    /** @return list<array{code: string, label: string, flag: string}> */
    private function buildLanguageList(): array
    {
        $languages = [
            'tr' => ['label' => 'Türkçe', 'flag' => '🇹🇷'],
            'en' => ['label' => 'English', 'flag' => '🇬🇧'],
            'de' => ['label' => 'Deutsch', 'flag' => '🇩🇪'],
            'fr' => ['label' => 'Français', 'flag' => '🇫🇷'],
            'ar' => ['label' => 'العربية', 'flag' => '🇸🇦'],
        ];

        $active = config('core.active_languages', ['tr', 'en']);

        return array_values(array_map(
            fn (string $code) => ['code' => $code, ...$languages[$code] ?? ['label' => strtoupper($code), 'flag' => '🌐']],
            array_filter($active, fn (string $code) => isset($languages[$code]))
        ));
    }
}
