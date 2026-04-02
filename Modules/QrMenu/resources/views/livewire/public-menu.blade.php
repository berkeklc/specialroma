<div
    class="qrmenu-root min-h-screen font-sans antialiased"
    style="background:#faf5ee; color:#3d1a2e;"
    x-data="{
        showSearch: false,
        activeCategory: null,
        isDragging: false,
        clickLock: false,
        startX: 0,
        scrollLeft: 0,
        initObserver() {
            const observer = new IntersectionObserver((entries) => {
                if (this.clickLock) return;
                
                let mostVisible = null;
                let maxRatio = 0.1; // Baseline visibility
                
                entries.forEach(entry => {
                    if (entry.isIntersecting && entry.intersectionRatio > maxRatio) {
                        maxRatio = entry.intersectionRatio;
                        mostVisible = entry.target.id.replace('cat-', '');
                    }
                });
                
                if (mostVisible) {
                    this.activeCategory = mostVisible;
                    const chip = document.getElementById('chip-' + mostVisible);
                    if (chip && !this.isDragging) {
                        chip.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                    }
                }
            }, { rootMargin: '-130px 0px -45% 0px', threshold: [0, 0.1, 0.2, 0.4, 0.6, 0.8, 1] });
            
            document.querySelectorAll('.qrmenu-section').forEach(section => observer.observe(section));
        },
        scrollToCategory(id) {
            this.clickLock = true;
            this.activeCategory = id;
            
            const el = document.getElementById('cat-' + id);
            if (el) {
                const offset = window.innerWidth >= 768 ? 145 : 125;
                const y = el.getBoundingClientRect().top + window.scrollY - offset;
                window.scrollTo({ top: y, behavior: 'smooth' });
                
                // Keep the selection active highlighting during and shortly after scroll
                // then release the lock for manual scroll observation
                setTimeout(() => {
                    this.clickLock = false;
                    // Ensure the correct chip is scrolled to in the nav after manual click
                    const chip = document.getElementById('chip-' + id);
                    if (chip) chip.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                }, 850);
            }
        },
        startDragging(e) {
            const nav = this.$refs.catNav;
            this.isDragging = true;
            this.startX = e.pageX - nav.offsetLeft;
            this.scrollLeft = nav.scrollLeft;
        },
        stopDragging() {
            this.isDragging = false;
        },
        onDrag(e) {
            if (!this.isDragging) return;
            e.preventDefault();
            const nav = this.$refs.catNav;
            const x = e.pageX - nav.offsetLeft;
            const walk = (x - this.startX) * 1.5; // Multiplier for faster scroll
            nav.scrollLeft = this.scrollLeft - walk;
        }
    }"
    x-init="initObserver()"
>
    <style>
        .active-cat { color: white !important; }
        .active-cat:hover { color: white !important; opacity: 1 !important; }
        
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { 
            -ms-overflow-style: none; 
            scrollbar-width: none; 
            cursor: grab;
            user-select: none;
            -webkit-user-select: none;
        }
        .scrollbar-hide:active { cursor: grabbing; }
        
        /* 100% Solid Sticky Headers - Fixed CSS widths and tops */
        .qrmenu-topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background-color: #faf5ee !important;
            opacity: 1 !important;
            border-bottom: 1px solid rgba(196, 54, 110, 0.05);
        }
        
        .qrmenu-catnav {
            position: sticky;
            z-index: 40;
            background-color: #faf5ee !important;
            opacity: 1 !important;
            border-bottom: 1px solid rgba(196, 54, 110, 0.05);
            /* Fixed offset tops via media queries to stop jitter */
            top: 50px; 
        }
        
        @media (min-width: 768px) {
            .qrmenu-catnav { top: 62.5px; } /* Exact Sync for Desktop header height */
        }
        
        /* Ensure short sections don't cause navigation jumps */
        .qrmenu-section { min-height: 45vh; }
        .qrmenu-section:last-of-type { min-height: 70vh; }
    </style>

    {{-- Header --}}
    <header class="qrmenu-topbar shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-2.5 md:py-3.5 flex items-center justify-between gap-4">
            <a href="{{ url('/') }}" class="shrink-0 flex items-center">
                <img src="{{ asset('roma-logo.png') }}" alt="Special Roma" class="h-7 md:h-10 w-auto object-contain">
            </a>

            <div class="flex items-center gap-3 md:gap-5">
                @if ($primaryMenu && !empty($primaryMenu->items))
                    <div class="hidden lg:flex items-center gap-6 font-semibold text-[0.875rem] mr-2" style="color:#3d1a2e;">
                        @foreach ($primaryMenu->items as $item)
                            @php
                                $label = is_array($item['label'] ?? null)
                                    ? ($item['label'][app()->getLocale()] ?? $item['label']['en'] ?? reset($item['label']))
                                    : ($item['label'] ?? '');
                                $url = $item['url'] ?? '#';
                            @endphp
                            <a href="{{ url($url) }}" class="opacity-60 hover:opacity-100 transition-opacity whitespace-nowrap">{{ $label }}</a>
                        @endforeach
                    </div>
                @endif

                <div class="flex items-center gap-2">
                    {{-- Language switcher --}}
                    @if (count($settings->active_languages ?? []) > 1)
                        <div class="lang-switcher" x-data="{ open: false }">
                            @php
                                $currentLocale = app()->getLocale();
                                $flagMap = ['tr' => '🇹🇷', 'en' => '🇬🇧', 'de' => '🇩🇪', 'fr' => '🇫🇷', 'ar' => '🇸🇦', 'ru' => '🇷🇺', 'es' => '🇪🇸'];
                                $currentFlag = $flagMap[$currentLocale] ?? strtoupper($currentLocale);
                            @endphp
                            <button @click="open = !open" @click.outside="open = false" 
                                class="inline-flex items-center justify-center h-9 px-3 rounded-full transition-all bg-white shadow-sm border border-pink-100 text-[#3d1a2e] hover:border-pink-300"
                                :class="open ? 'ring-2 ring-pink-100' : ''">
                                <span class="mr-1.5">{{ $currentFlag }}</span>
                                <span class="text-[0.75rem] font-bold">{{ strtoupper($currentLocale) }}</span>
                            </button>
                            <div x-show="open" x-transition 
                                class="absolute right-0 mt-2 min-w-[120px] bg-white border border-pink-100 rounded-2xl shadow-xl z-[60] overflow-hidden" 
                                style="top: 100%;">
                                @foreach ($settings->active_languages as $lang)
                                    <a href="{{ route('lang.switch', $lang) }}"
                                        class="flex items-center px-4 py-2.5 text-sm font-semibold hover:bg-pink-50 {{ app()->getLocale() === $lang ? 'text-[#c4366e] bg-pink-50/50' : 'text-[#3d1a2e]' }}">
                                        {{ strtoupper($lang) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <button
                        @click="showSearch = !showSearch"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-full transition-all bg-white shadow-sm border border-pink-100 text-[#7a5568] hover:text-[#c4366e]"
                        :class="showSearch ? 'ring-2 ring-pink-100' : ''"
                        aria-label="Ara"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                    
                    <span class="text-[0.7rem] md:text-sm font-bold px-3 py-2 text-[#c4366e] bg-white rounded-full shadow-sm border border-pink-50">
                        #{{ $tableId }}
                    </span>
                </div>
                
                <a href="{{ url('/') }}" class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-full text-[#7a5568] hover:text-[#c4366e] bg-white shadow-sm border border-pink-50" aria-label="Siteye dön">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Expandable search w/ slide animation --}}
        <div x-show="showSearch" x-collapse.duration.300ms class="max-w-4xl mx-auto px-4 pb-4 bg-[#faf5ee]">
            <div class="relative">
                <input wire:model.live.debounce.300ms="search"
                       type="search"
                       placeholder="Ne yemek istersiniz?"
                       class="w-full pl-12 pr-5 py-3 rounded-2xl text-base font-medium shadow-sm border focus:outline-none focus:ring-2 focus:ring-offset-1 transition-all"
                       style="background:#fff !important; color:#3d1a2e !important; border-color:rgb(196 54 110 / 0.1); --tw-ring-color:rgb(196 54 110 / 0.15);"
                       x-ref="searchInput"
                       @keydown.escape="showSearch = false"
                       x-init="$watch('showSearch', v => v && $nextTick(() => $refs.searchInput.focus()))">
                <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <div wire:loading.delay class="absolute right-4 top-1/2 -translate-y-1/2">
                    <svg class="animate-spin h-5 w-5 text-[#c4366e]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </header>

    {{-- Category navigation --}}
    @if ($allCategories->isNotEmpty())
        <nav class="qrmenu-catnav shadow-sm">
            <div class="max-w-4xl mx-auto relative">
                <div class="flex overflow-x-auto scrollbar-hide gap-1.5 px-3 py-3" 
                     x-ref="catNav"
                     @mousedown="startDragging($event)"
                     @mousemove="onDrag($event)"
                     @mouseup="stopDragging()"
                     @mouseleave="stopDragging()"
                     style="scroll-padding-inline: 1rem; scroll-behavior: smooth;">
                    @foreach ($allCategories as $cat)
                        @php
                            $catImg = $cat->getFirstMediaUrl('image') ?: $cat->getFirstMediaUrl('photo');
                        @endphp
                        <button
                            type="button"
                            id="chip-{{ $cat->id }}"
                            @click="if(!isDragging) scrollToCategory('{{ $cat->id }}')"
                            class="qrmenu-chip shrink-0 relative px-4 py-2 md:px-6 md:py-3 rounded-2xl text-[0.875rem] md:text-sm font-bold whitespace-nowrap transition-all duration-300 flex items-center gap-3 border"
                            :class="activeCategory == '{{ $cat->id }}' ? 'border-transparent active-cat' : 'border-gray-100 bg-white text-[#3d1a2e] opacity-80 hover:opacity-100 hover:border-gray-200'"
                            :style="activeCategory == '{{ $cat->id }}' ? 'background:#c4366e; color: white !important; box-shadow: 0 6px 15px rgb(196 54 110 / 0.2);' : ''"
                        >
                            @if($catImg)
                                <img src="{{ $catImg }}" alt="{{ $cat->name }}" class="w-6 h-6 md:w-8 md:h-8 rounded-full object-cover shadow-sm bg-white p-0.5">
                            @endif
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </div>
        </nav>
    @endif

    {{-- Menu content --}}
    <main class="max-w-4xl mx-auto px-4 pt-8 md:pt-12 pb-24">
        @php
            $accents = ['#c4366e', '#ff9f1c', '#00c853', '#9c27b0', '#b8945a', '#c4366e'];
        @endphp

        @forelse ($categories as $category)
            <section id="cat-{{ $category->id }}" class="qrmenu-section mb-12 md:mb-16 scroll-mt-44 md:scroll-mt-52">
                <div class="flex items-center gap-3.5 mb-6 md:mb-8 pb-4 border-b border-dashed border-pink-100">
                    <div class="w-2 h-8 md:h-10 rounded-full shadow-sm" style="background: {{ $accents[$loop->index % count($accents)] }};"></div>
                    <div>
                        <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight" style="color:#3d1a2e;">{{ $category->name }}</h2>
                        @if ($category->description)
                            <p class="text-[0.8rem] md:text-[0.9rem] mt-1.5 leading-relaxed opacity-60" style="color:#7a5568;">{{ $category->description }}</p>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-7">
                    @foreach ($category->items as $item)
                        @php
                            $imageUrl = $item->getFirstMediaUrl('image') ?: $item->getFirstMediaUrl('photo');
                            $hasImage = (bool) $imageUrl;
                        @endphp

                        @if($hasImage)
                            <div class="group flex flex-col bg-white rounded-3xl border overflow-hidden transition-all duration-300 shadow-sm hover:shadow-xl relative" style="border-color:rgb(196 54 110 / 0.04);">
                                <div class="w-full h-48 md:h-60 overflow-hidden relative bg-gray-50">
                                    <img src="{{ $imageUrl }}" alt="{{ $item->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                    @if ($item->is_featured)
                                        <div class="absolute top-3 left-3 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
                                            <span class="text-amber-400 text-xs">★</span>
                                            <span class="text-[0.65rem] font-bold uppercase tracking-wider text-gray-800">Şefin Önerisi</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-6 flex-1 flex flex-col justify-between">
                                    <div>
                                        <h3 class="font-bold text-lg md:text-xl leading-snug tracking-tight mb-2" style="color:#3d1a2e;">{{ $item->name }}</h3>
                                        @if ($item->description)
                                            <p class="text-sm leading-relaxed opacity-60 line-clamp-2" style="color:#5c394c;">{{ $item->description }}</p>
                                        @endif
                                    </div>
                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="font-extrabold text-[#c4366e] text-xl md:text-2xl flex items-center gap-0.5">
                                            <span class="text-xs font-bold mr-0.5" style="opacity: 0.8">{{ $restaurant->currency === 'TRY' ? '₺' : '$' }}</span>
                                            {{ number_format((float)$item->price, 0, ',', '.') }}
                                        </div>
                                        
                                        @if ($item->badges && count($item->badges) > 0)
                                            <div class="flex gap-1.5">
                                                @foreach (array_slice($item->badges, 0, 3) as $badge)
                                                    <span class="w-8 h-8 flex items-center justify-center rounded-full bg-pink-50 text-[0.8rem] border border-pink-100" title="{{ $badge }}">
                                                        {{ [ 'vegan' => '🌱', 'vegetarian' => '🥦', 'gluten_free' => '🌾', 'spicy' => '🌶', 'new' => '✨', 'popular' => '🔥' ][$badge] ?? '•' }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="group flex bg-white rounded-[1.25rem] border transition-all duration-300 shadow-sm hover:shadow-md" style="border-color:rgb(196 54 110 / 0.04);">
                                <div class="p-5 flex-1 flex flex-col justify-center">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <h3 class="font-bold text-base md:text-[1.1rem] leading-tight truncate" style="color:#3d1a2e;">{{ $item->name }}</h3>
                                                @if ($item->is_featured)
                                                    <span class="w-5 h-5 flex items-center justify-center bg-amber-50 rounded-full text-amber-500 text-[0.7rem]" title="Şefin Önerisi">★</span>
                                                @endif
                                            </div>
                                            @if ($item->description)
                                                <p class="text-[0.85rem] leading-snug opacity-60 line-clamp-2" style="color:#5c394c;">{{ $item->description }}</p>
                                            @endif
                                        </div>
                                        <div class="shrink-0 flex items-center font-extrabold text-[#c4366e] text-lg md:text-xl">
                                            <span class="text-xs mr-0.5 opacity-80">{{ $restaurant->currency === 'TRY' ? '₺' : '$' }}</span>
                                            {{ number_format((float)$item->price, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    @if ($item->badges && count($item->badges) > 0)
                                        <div class="flex flex-wrap gap-1.5 mt-3">
                                            @foreach ($item->badges as $badge)
                                                <span class="text-[0.625rem] font-bold px-2.5 py-1 rounded-full bg-gray-50 text-gray-500 border border-gray-100 flex items-center gap-1.5">
                                                    {{ [ 'vegan' => '🌱', 'vegetarian' => '🥦', 'gluten_free' => '🌾', 'spicy' => '🌶', 'new' => '✨', 'popular' => '🔥' ][$badge] ?? '' }}
                                                    {{ ucfirst(str_replace('_', ' ', $badge)) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </section>
        @empty
            <div class="text-center py-20 px-4">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-pink-50 mb-4">
                    <svg class="w-8 h-8 text-[#c4366e]/30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <h3 class="text-lg font-bold" style="color:#3d1a2e;">{{ $search ? 'Sonuç bulunamadı' : 'Menü boş' }}</h3>
                <p class="text-sm text-[#7a5568]/60 max-w-xs mx-auto mt-2">
                    {{ $search ? "\"" . $search . "\" ile eşleşen lezzetli bir ürün bulamadık." : "Bu kategoride henüz ürün bulunmuyor." }}
                </p>
                @if($search)
                    <button wire:click="$set('search', '')" class="mt-5 text-sm font-bold text-[#c4366e] hover:underline bg-white px-5 py-2 rounded-full shadow-sm border border-pink-50 transition-transform active:scale-95">Aramayı Temizle</button>
                @endif
            </div>
        @endforelse
    </main>

    @livewire('core::site-footer')
</div>
