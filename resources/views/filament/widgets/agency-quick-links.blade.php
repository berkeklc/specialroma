<x-filament-widgets::widget class="fi-agency-quick-links">
    <x-filament::section
        :heading="__('Quick actions')"
        :description="__('Jump to the areas you use most.')"
        icon="heroicon-o-bolt"
        icon-color="primary"
    >
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
            @foreach ($links as $link)
                <a
                    href="{{ $link['url'] }}"
                    @if (! empty($link['open']))
                        target="_blank" rel="noopener noreferrer"
                    @else
                        wire:navigate
                    @endif
                    @class([
                        'group flex gap-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition dark:border-white/10 dark:bg-gray-900',
                        'hover:border-primary-300 hover:shadow-md dark:hover:border-primary-600/50' => true,
                    ])
                >
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:text-primary-400">
                        <x-filament::icon :icon="$link['icon']" class="h-5 w-5" />
                    </span>
                    <span class="min-w-0 text-start">
                        <span class="block font-semibold text-gray-950 dark:text-white">
                            {{ $link['label'] }}
                        </span>
                        <span class="mt-0.5 block text-xs text-gray-500 dark:text-gray-400">
                            {{ $link['description'] }}
                        </span>
                    </span>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
