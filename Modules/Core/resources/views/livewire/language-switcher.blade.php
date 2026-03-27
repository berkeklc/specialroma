<div x-data="{ open: false }" class="relative inline-block text-left">
    <button
        @click="open = !open"
        @click.away="open = false"
        class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
        type="button"
        aria-haspopup="listbox"
        :aria-expanded="open"
    >
        @php
            $current = collect($availableLanguages)->firstWhere('code', $currentLocale);
        @endphp
        <span>{{ $current['flag'] ?? '🌐' }}</span>
        <span>{{ strtoupper($currentLocale) }}</span>
        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
        </svg>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 z-50 mt-2 w-44 origin-top-right rounded-lg bg-white dark:bg-gray-900 shadow-lg ring-1 ring-black/5 focus:outline-none"
        role="listbox"
    >
        <div class="py-1">
            @foreach ($availableLanguages as $language)
                <button
                    wire:click="switchLanguage('{{ $language['code'] }}')"
                    class="flex w-full items-center gap-2.5 px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors
                        {{ $currentLocale === $language['code'] ? 'font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950' : 'text-gray-700 dark:text-gray-300' }}"
                    role="option"
                    :aria-selected="{{ $currentLocale === $language['code'] ? 'true' : 'false' }}"
                >
                    <span>{{ $language['flag'] }}</span>
                    <span>{{ $language['label'] }}</span>
                    @if ($currentLocale === $language['code'])
                        <svg class="ml-auto w-4 h-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </button>
            @endforeach
        </div>
    </div>
</div>
