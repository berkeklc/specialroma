<div class="min-h-screen bg-gray-50">
    {{-- Restaurant Header --}}
    <header class="sticky top-0 z-10 shadow-sm" style="background-color: {{ $restaurant->primary_color ?? '#1a1a2e' }}">
        <div class="max-w-2xl mx-auto px-4 py-4">
            <div class="flex items-center gap-3">
                @if ($restaurant->getFirstMediaUrl('logo'))
                    <img src="{{ $restaurant->getFirstMediaUrl('logo') }}"
                         alt="{{ $restaurant->name }}"
                         class="h-10 w-auto rounded-lg object-cover">
                @endif
                <div class="flex-1 min-w-0">
                    <h1 class="text-white font-bold text-lg leading-tight truncate">
                        {{ $restaurant->name }}
                    </h1>
                    @if ($restaurant->description)
                        <p class="text-white/70 text-xs truncate">{{ $restaurant->description }}</p>
                    @endif
                </div>
                <span class="shrink-0 bg-white/20 text-white text-xs font-medium px-2 py-1 rounded-full">
                    🪑 #{{ $tableId }}
                </span>
            </div>
        </div>

        {{-- Search --}}
        <div class="max-w-2xl mx-auto px-4 pb-3">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input wire:model.live.debounce.300ms="search"
                       type="search"
                       placeholder="Search menu..."
                       class="w-full bg-white/10 text-white placeholder-white/60 pl-9 pr-4 py-2 rounded-xl text-sm border border-white/20 focus:outline-none focus:bg-white/20">
            </div>
        </div>
    </header>

    {{-- Category Tabs --}}
    @if ($allCategories->isNotEmpty())
        <div class="sticky top-[108px] z-10 bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-2xl mx-auto px-4">
                <div class="flex gap-2 overflow-x-auto py-3 scrollbar-hide">
                    <button wire:click="selectCategory(0)"
                            class="shrink-0 px-4 py-1.5 rounded-full text-sm font-medium transition-colors
                                {{ $activeCategory === null ? 'text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                            style="{{ $activeCategory === null ? 'background-color: ' . ($restaurant->primary_color ?? '#1a1a2e') . ';' : '' }}">
                        All
                    </button>
                    @foreach ($allCategories as $cat)
                        <button wire:click="selectCategory({{ $cat->id }})"
                                class="shrink-0 px-4 py-1.5 rounded-full text-sm font-medium transition-colors
                                    {{ $activeCategory === $cat->id ? 'text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                                style="{{ $activeCategory === $cat->id ? 'background-color: ' . ($restaurant->primary_color ?? '#1a1a2e') . ';' : '' }}">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Menu Content --}}
    <main class="max-w-2xl mx-auto px-4 py-6 space-y-8">
        @forelse ($categories as $category)
            <section>
                <div class="flex items-center gap-3 mb-4">
                    @if ($category->getFirstMediaUrl('image'))
                        <img src="{{ $category->getFirstMediaUrl('image') }}"
                             alt="{{ $category->name }}"
                             class="w-10 h-10 rounded-lg object-cover">
                    @endif
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">{{ $category->name }}</h2>
                        @if ($category->description)
                            <p class="text-sm text-gray-500">{{ $category->description }}</p>
                        @endif
                    </div>
                </div>

                <div class="space-y-3">
                    @forelse ($category->items as $item)
                        <article class="bg-white rounded-2xl shadow-sm overflow-hidden">
                            <div class="flex gap-3 p-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-900 text-sm leading-tight">
                                                {{ $item->name }}
                                                @if ($item->is_featured)
                                                    <span class="ml-1 text-yellow-500">⭐</span>
                                                @endif
                                            </h3>
                                            @if ($item->description)
                                                <p class="text-xs text-gray-500 mt-1 leading-relaxed line-clamp-2">
                                                    {{ $item->description }}
                                                </p>
                                            @endif
                                        </div>
                                        <span class="shrink-0 font-bold text-gray-900 text-sm">
                                            {{ $restaurant->currency === 'TRY' ? '₺' : '$' }}{{ number_format((float) $item->price, 2) }}
                                        </span>
                                    </div>

                                    @if ($item->badges && count($item->badges) > 0)
                                        <div class="flex flex-wrap gap-1 mt-2">
                                            @foreach ($item->badges as $badge)
                                                @php
                                                    $badgeLabels = [
                                                        'vegan' => '🌱 Vegan',
                                                        'vegetarian' => '🥦 Vejetaryen',
                                                        'gluten_free' => '🌾 Glütensiz',
                                                        'spicy' => '🌶 Acılı',
                                                        'new' => '✨ Yeni',
                                                        'popular' => '🔥 Popüler',
                                                        'featured' => '⭐ Öne Çıkan',
                                                    ];
                                                @endphp
                                                <span class="inline-flex items-center text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                                                    {{ $badgeLabels[$badge] ?? $badge }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if ($item->allergens && count($item->allergens) > 0)
                                        <p class="text-xs text-orange-600 mt-1">
                                            ⚠️ {{ implode(', ', $item->allergens) }}
                                        </p>
                                    @endif
                                </div>

                                @if ($item->getFirstMediaUrl('photo'))
                                    <img src="{{ $item->getFirstMediaUrl('photo') }}"
                                         alt="{{ $item->name }}"
                                         class="w-20 h-20 rounded-xl object-cover shrink-0">
                                @endif
                            </div>
                        </article>
                    @empty
                        <p class="text-center text-gray-400 py-8">No items in this category.</p>
                    @endforelse
                </div>
            </section>
        @empty
            <div class="text-center py-16">
                <p class="text-gray-400 text-lg">
                    {{ $search ? 'No results for "' . $search . '"' : 'Menu is empty.' }}
                </p>
            </div>
        @endforelse
    </main>

    {{-- Footer --}}
    <footer class="max-w-2xl mx-auto px-4 py-8 text-center">
        <p class="text-xs text-gray-400">
            Powered by <span class="font-semibold">AgencyStack QR Menu</span>
        </p>
    </footer>
</div>
