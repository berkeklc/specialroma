<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Core\App\Enums\LayoutType;
use Modules\Core\App\Enums\PageStatus;
use Modules\Core\App\Models\Layout;
use Modules\Core\App\Models\Menu;
use Modules\Core\App\Models\Page;
use Modules\Core\App\Settings\GeneralSettings;
use Modules\QrMenu\App\Models\MenuCategory;
use Modules\QrMenu\App\Models\MenuItem;
use Modules\QrMenu\App\Models\MenuTable;
use Modules\QrMenu\App\Models\Restaurant;
use Modules\Team\App\Models\TeamMember;

final class SpecialRomaContentSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->seedGeneralSettings();
            $this->seedHeaderFooterLayouts();
            $this->seedQrMenu();
            $this->seedTeam();
            $this->seedPages();
            $this->seedMenus();
        });

        $this->command?->info('Special Roma içerik, QR menü, sayfalar ve menüler güncellendi.');
    }

    private function seedGeneralSettings(): void
    {
        $g = app(GeneralSettings::class);
        $g->site_name = 'Special Roma Cafe & Dondurma';
        $g->site_tagline = '1995’ten beri Mordoğan’ın efsane lezzetleri';
        $g->contact_phone = '0 533 601 33 85';
        $g->contact_email = 'info@specialroma.test';
        $g->contact_address = 'Cumhuriyet Meydanı No:13, Mordoğan, Karaburun/İzmir (deniz kenarı)';
        $g->active_languages = ['tr'];
        $g->default_language = 'tr';
        $g->logo_type = 'image';
        $g->save();
    }

    private function seedHeaderFooterLayouts(): void
    {
        Layout::updateOrCreate(
            ['type' => LayoutType::Header->value],
            [
                'is_active' => true,
                'rows' => [
                    ['type' => 'logo', 'data' => ['alt' => 'Special Roma', 'width' => 160]],
                    ['type' => 'navigation', 'data' => ['menu_location' => 'primary', 'style' => 'horizontal']],
                    ['type' => 'cta_button', 'data' => ['text' => 'İletişim', 'url' => '/iletisim', 'style' => 'primary']],
                ],
            ]
        );

        Layout::updateOrCreate(
            ['type' => LayoutType::Footer->value],
            [
                'is_active' => true,
                'rows' => [
                    ['type' => 'text_block', 'data' => [
                        'content' => 'Special Roma Cafe & Dondurma — 1995’ten beri Mordoğan’da.',
                        'alignment' => 'center',
                    ]],
                    ['type' => 'navigation', 'data' => ['menu_location' => 'footer', 'style' => 'vertical']],
                    ['type' => 'text_block', 'data' => [
                        'content' => '© '.date('Y').' Special Roma. Tüm hakları saklıdır.',
                        'alignment' => 'center',
                    ]],
                ],
            ]
        );
    }

    private function seedQrMenu(): void
    {
        Restaurant::where('slug', 'my-restaurant')->update(['is_active' => false]);

        $restaurant = Restaurant::updateOrCreate(
            ['slug' => 'special-roma'],
            [
                'name' => ['tr' => 'Special Roma Cafe & Dondurma', 'en' => 'Special Roma Cafe & Gelato'],
                'description' => ['tr' => 'Mordoğan sahilinde dondurma, kahvaltı ve tatlı.', 'en' => 'Ice cream, breakfast & desserts by the sea in Mordoğan.'],
                'currency' => 'TRY',
                'primary_color' => '#c4366e',
                'is_active' => true,
            ]
        );

        $restaurant->categories()->delete();

        MenuTable::firstOrCreate(
            ['restaurant_id' => $restaurant->id, 'name' => 'Salon 1'],
            ['is_active' => true]
        );

        foreach (SpecialRomaMenuData::categories() as $order => $cat) {
            $category = MenuCategory::create([
                'restaurant_id' => $restaurant->id,
                'name' => $cat['name'],
                'description' => $cat['description'],
                'sort_order' => $order,
                'is_active' => true,
            ]);

            foreach ($cat['items'] as $i => $item) {
                MenuItem::create([
                    'restaurant_id' => $restaurant->id,
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'sort_order' => $i,
                    'is_available' => true,
                    'is_featured' => $order === 0 && $i === 0,
                ]);
            }
        }
    }

    private function seedTeam(): void
    {
        $team = [
            ['name' => ['tr' => 'Ayşe Yılmaz', 'en' => 'Ayşe Yılmaz'], 'position' => ['tr' => 'İşletme Müdürü', 'en' => 'General Manager'], 'bio' => ['tr' => 'Misafir memnuniyeti ve kalite standartlarından sorumlu.', 'en' => 'Guest experience & quality.']],
            ['name' => ['tr' => 'Mehmet Kaya', 'en' => 'Mehmet Kaya'], 'position' => ['tr' => 'Şef — Tatlı & Dondurma', 'en' => 'Pastry & Gelato Chef'], 'bio' => ['tr' => 'Roma dondurmalarının tariflerini ve sunumunu yönetiyor.', 'en' => 'Leads our gelato recipes & presentation.']],
            ['name' => ['tr' => 'Zeynep Demir', 'en' => 'Zeynep Demir'], 'position' => ['tr' => 'Kahvaltı & Mutfak', 'en' => 'Breakfast & Kitchen'], 'bio' => ['tr' => 'Serpme kahvaltı ve sıcak mutfak ürünlerinin koordinasyonu.', 'en' => 'Coordinates breakfast & hot kitchen.']],
        ];

        foreach ($team as $i => $row) {
            TeamMember::updateOrCreate(
                ['slug' => Str::slug($row['name']['en'])],
                [
                    'name' => $row['name'],
                    'position' => $row['position'],
                    'bio' => $row['bio'],
                    'is_active' => true,
                    'sort_order' => $i,
                ]
            );
        }
    }

    private function getQrMenuUrl(): string
    {
        $restaurant = Restaurant::query()->where('slug', 'special-roma')->firstOrFail();
        $tableId = $restaurant->tables()->firstOrFail()->id;

        return route('qr-menu.public', [
            'restaurant' => $restaurant->slug,
            'table' => $tableId,
        ]);
    }

    private function seedPages(): void
    {
        $qrMenuUrl = $this->getQrMenuUrl();

        Page::query()->where('is_home', true)->update(['is_home' => false]);

        $homeBlocks = [
            [
                'type' => 'roma_hero',
                'data' => [
                    'heading' => '1995’ten beri Mordoğan’ın efsane lezzetleri',
                    'subheading' => 'Deniz kenarında premium ama samimi bir mola: Roma dondurmaları, serpme kahvaltı ve tatlı bar.',
                    'button_label' => 'Menüyü İncele',
                    'button_url' => $qrMenuUrl,
                    'button2_label' => 'Şimdi Gel',
                    'button2_url' => '/iletisim',
                ],
            ],
            [
                'type' => 'services_grid',
                'data' => [
                    'heading' => 'Öne çıkan deneyimler',
                    'columns' => '3',
                    'services' => [
                        ['title' => 'Roma Dondurmaları', 'description' => 'Klasik ve special aromalar — her gün taze.', 'icon' => '🍨', 'url' => $qrMenuUrl],
                        ['title' => 'Serpme Kahvaltı', 'description' => 'Zengin peynir & zeytin seçkisi, deniz manzarası.', 'icon' => '☀️', 'url' => $qrMenuUrl],
                        ['title' => 'Patisserie', 'description' => 'Dilim pasta, waffle ve şerbetli tatlılar.', 'icon' => '🍰', 'url' => $qrMenuUrl],
                    ],
                ],
            ],
            [
                'type' => 'roma_dondurmalar',
                'data' => [
                    'heading' => 'Roma Dondurmaları',
                    'text' => 'Onlarca aromayla hazırlanan geleneksel Roma tarzı dondurma — hem klasik hem special çeşitler.',
                    'button_label' => 'Tüm menüyü gör',
                    'button_url' => $qrMenuUrl,
                ],
            ],
            [
                'type' => 'services_grid',
                'data' => [
                    'heading' => 'En popüler lezzetler',
                    'columns' => '3',
                    'services' => [
                        ['title' => 'Serpme Kahvaltı', 'description' => 'Paylaşımlı zengin kahvaltı sofrası.', 'icon' => '🥐', 'url' => $qrMenuUrl],
                        ['title' => 'Roma Burger', 'description' => 'Özel sos ve kaliteli et.', 'icon' => '🍔', 'url' => $qrMenuUrl],
                        ['title' => 'Fragola Sogna', 'description' => 'Meyve ve dondurma uyumu.', 'icon' => '🍓', 'url' => $qrMenuUrl],
                        ['title' => 'Penne Mexicana', 'description' => 'Baharatlı ve doyurucu.', 'icon' => '🍝', 'url' => $qrMenuUrl],
                        ['title' => 'Roma Waffle', 'description' => 'Çıtır waffle, seçtiğiniz malzemeler.', 'icon' => '🧇', 'url' => $qrMenuUrl],
                        ['title' => 'Karamel Frappe', 'description' => 'Serinleten soğuk kahve.', 'icon' => '☕', 'url' => $qrMenuUrl],
                    ],
                ],
            ],
            [
                'type' => 'text',
                'data' => [
                    'alignment' => 'center',
                    'content' => '<h2>Hikayemiz</h2><p class="sr-story-lead">1995’ten bu yana, Mordoğan Cumhuriyet Meydanı’nda denizin hemen kenarında misafirlerimize lezzet ve huzur sunuyoruz.</p><div class="sr-story-timeline"><div class="sr-story-year"><span class="sr-story-year__badge">1995</span><p>Mordoğan meydanında küçük bir dondurma dükkânı olarak kapılarımızı açtık. İlk yıldan Ege’nin en sevilen durağı olmayı başardık.</p></div><div class="sr-story-year"><span class="sr-story-year__badge">2005</span><p>Kahvaltı ve tatlı bar menümüzü genişlettik. Serpme kahvaltımız Mordoğan’ın simgesi haline geldi.</p></div><div class="sr-story-year"><span class="sr-story-year__badge">2015</span><p>Mekanımızı yeniledik, modern bir tasarımla deniz manzarasını sofraya taşıdık.</p></div><div class="sr-story-year"><span class="sr-story-year__badge">Bugün</span><p>30 yılı aşkın tecrübe, aynı tutku. Onlarca dondurma aroması, geniş menü ve samimi bir hizmet anlayışıyla buradayız.</p></div></div>',
                ],
            ],
            [
                'type' => 'testimonials',
                'data' => [
                    'heading' => 'Misafirlerimiz ne diyor?',
                    'eyebrow' => 'Yorumlar',
                    'items' => [
                        ['quote' => 'Dondurmalar harika, manzara eşsiz. Her yaz mutlaka uğruyoruz.', 'author_name' => 'Elif S.', 'author_title' => 'İzmir'],
                        ['quote' => 'Serpme kahvaltı gerçekten doyurucu ve taze. Personel çok ilgili.', 'author_name' => 'Can T.', 'author_title' => 'Mordoğan'],
                        ['quote' => 'Roma waffle ve special dondurma seçimi ile çocuklar bayıldı.', 'author_name' => 'Pınar K.', 'author_title' => 'Alaçatı'],
                    ],
                ],
            ],
            [
                'type' => 'text',
                'data' => [
                    'alignment' => 'center',
                    'content' => '<h2>Konum</h2><p><strong>Adres:</strong> Cumhuriyet Meydanı No:13, Mordoğan — deniz kenarı</p><div style="margin: 2rem 0; border-radius: 1.5rem; overflow: hidden; border: 1px solid rgb(196 54 110 / 0.1); box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1);"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d780.4180906623133!2d26.625279969662788!3d38.51826659823799!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14bbbba1501077af%3A0x599969cc9c850e5f!2sSpecial%20Roma%20cafe%20%26%20Patisseria!5e0!3m2!1str!2sus!4v1775059217298!5m2!1str!2sus" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div><p><a class="btn-primary" style="display:inline-flex;margin-top:0.5rem;" href="/iletisim">Yol tarifi & iletişim</a></p>',
                ],
            ],
            [
                'type' => 'cta_banner',
                'data' => [
                    'heading' => 'Bugün Mordoğan’da mısınız?',
                    'subheading' => 'Rezervasyon ve sorularınız için arayın veya WhatsApp’tan yazın.',
                    'button_text' => 'İletişim & rezervasyon',
                    'button_url' => '/iletisim',
                    'background_color' => '#c4366e',
                ],
            ],
        ];

        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => ['tr' => 'Ana Sayfa', 'en' => 'Home'],
                'status' => PageStatus::Published->value,
                'is_home' => true,
                'sort_order' => 0,
                'blocks' => $homeBlocks,
                'meta_title' => ['tr' => 'Special Roma — Mordoğan Cafe & Dondurma', 'en' => 'Special Roma'],
                'meta_description' => ['tr' => '1995’ten beri Mordoğan’da dondurma, kahvaltı ve tatlı. Cumhuriyet Meydanı deniz kenarı.', 'en' => 'Gelato & breakfast in Mordoğan since 1995.'],
            ]
        );

        Page::updateOrCreate(
            ['slug' => 'menu'],
            [
                'title' => ['tr' => 'Menü', 'en' => 'Menu'],
                'status' => PageStatus::Published->value,
                'is_home' => false,
                'sort_order' => 5,
                'blocks' => [
                    [
                        'type' => 'hero',
                        'data' => [
                            'heading' => 'QR Menü',
                            'subheading' => 'Güncel fiyatlar ve tüm kategoriler telefonunuzda. Masanızda QR ile de açabilirsiniz.',
                            'button_label' => 'Menüyü aç',
                            'button_url' => $qrMenuUrl,
                            'button2_label' => 'PDF menü (basılı)',
                            'button2_url' => '/roma-menu.pdf',
                            'alignment' => 'center',
                            'min_height' => '55vh',
                        ],
                    ],
                    [
                        'type' => 'text',
                        'data' => [
                            'alignment' => 'center',
                            'content' => '<p>Tüm ürünlerimizi aşağıdaki interaktif menüden inceleyebilirsiniz. PDF menümüzü indirmek için ikinci butonu kullanın.</p>',
                        ],
                    ],
                    [
                        'type' => 'cta_banner',
                        'data' => [
                            'heading' => 'Hemen inceleyin',
                            'subheading' => 'Kategoriler arasında gezinin; fiyatlar ₺ olarak güncellenir.',
                            'button_text' => 'QR menüyü aç',
                            'button_url' => $qrMenuUrl,
                            'background_color' => '#c4366e',
                        ],
                    ],
                ],
            ]
        );

        Page::updateOrCreate(
            ['slug' => 'hakkimizda'],
            [
                'title' => ['tr' => 'Hakkımızda', 'en' => 'About'],
                'status' => PageStatus::Published->value,
                'is_home' => false,
                'sort_order' => 10,
                'blocks' => [
                    [
                        'type' => 'hero',
                        'data' => [
                            'heading' => 'Hikayemiz',
                            'subheading' => '1995’ten beri aynı çatı altında: lezzet, misafirperverlik ve Ege’nin eşsiz deniz esintisi.',
                            'alignment' => 'center',
                            'min_height' => '45vh',
                        ],
                    ],
                    [
                        'type' => 'text',
                        'data' => [
                            'alignment' => 'center',
                            'content' => '<div style="max-width:720px; margin:0 auto;"><h2 style="text-align:center;">Mordoğan’ın Efsane Durağı</h2><p style="font-size:1.1rem; line-height:1.8; text-align:center;">Special Roma, İzmir’in saklı cenneti Mordoğan’da, denizin hemen kenarında, Cumhuriyet Meydanı’nda kapılarını açtı. 1995 yılında küçük bir dondurma dükkânı olarak başlayan yolculuğumuz, bugün tam donanımlı bir cafe, kahvaltı evi ve patisserie’ye dönüştü.</p><p style="font-size:1.05rem; line-height:1.8; text-align:center;">Aslan & Musa kardeşlerin kurduğu Special Roma, 30 yılı aşkın süredir aile sıcaklığıyla misafirlerini ağırlıyor. Her sabah taze hazırlanan serpme kahvaltımız, el yapımı Roma dondurmaları ve özenle hazırlanan tatlılarımız ile Mordoğan’ın vazgeçilmez buluşma noktası olduk.</p></div>',
                        ],
                    ],
                    [
                        'type' => 'services_grid',
                        'data' => [
                            'heading' => 'Değerlerimiz',
                            'columns' => '3',
                            'services' => [
                                ['title' => 'Tazelik & Kalite', 'description' => 'Her gün taze malzemeyle hazırlanan ürünler, şeffaf mutfak kültürü.', 'icon' => '🌿', 'url' => '#'],
                                ['title' => 'Aile Sıcaklığı', 'description' => 'Aslan & Musa kardeşlerin kurduğu aile işletmesi sıcaklığı.', 'icon' => '🤝', 'url' => '#'],
                                ['title' => 'Ege Ruhu', 'description' => 'Mordoğan’ın doğasına ve yerel lezzetlerine derin saygı.', 'icon' => '🌊', 'url' => '#'],
                            ],
                        ],
                    ],
                    [
                        'type' => 'text',
                        'data' => [
                            'alignment' => 'center',
                            'content' => '<h2>Yolculuğumuz</h2><p class="sr-story-lead">Küçük bir dondurma dükkânından Mordoğan’ın simge mekanına…</p><div class="sr-story-timeline"><div class="sr-story-year"><span class="sr-story-year__badge">1995</span><p>Mordoğan Cumhuriyet Meydanı’nda küçük bir dondurma dükkânı olarak kapılarımızı açtık. Aslan & Musa kardeşler, el yapımı Roma dondurmalarıyla ilk günden Ege’nin en sevilen durağı olmayı başardı.</p></div><div class="sr-story-year"><span class="sr-story-year__badge">2005</span><p>Kahvaltı ve tatlı bar menümüzü genişlettik. Mordoğan’ın efsane serpme kahvaltısı bu yıllarda doğdu.</p></div><div class="sr-story-year"><span class="sr-story-year__badge">2015</span><p>Mekanımızı tamamen yeniledik. Modern ve ferah bir tasarımla deniz manzarasını sofraya taşıdık.</p></div><div class="sr-story-year"><span class="sr-story-year__badge">Bugün</span><p>30 yılı aşkın tecrübe, aynı tutku. Onlarca dondurma aroması, geniş bir menü ve samimi bir hizmet anlayışıyla buradayız.</p></div></div>',
                        ],
                    ],
                    [
                        'type' => 'text',
                        'data' => [
                            'alignment' => 'center',
                            'content' => '<div style="max-width:720px; margin:0 auto;"><h2 style="text-align:center;">Ne Sunuyoruz?</h2><div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1.5rem; margin-top:2rem;"><div style="text-align:center; padding:1.5rem; border-radius:1.25rem; background:#fff; border:1px solid rgb(196 54 110 / 0.06);"><p style="font-size:2rem; margin:0 0 0.5rem;">🍨</p><p style="font-weight:600; margin:0 0 0.35rem;">Roma Dondurmaları</p><p style="font-size:0.9rem; color:var(--color-muted); margin:0;">Klasik ve special aromalar, her gün taze. 30+ çeşit.</p></div><div style="text-align:center; padding:1.5rem; border-radius:1.25rem; background:#fff; border:1px solid rgb(196 54 110 / 0.06);"><p style="font-size:2rem; margin:0 0 0.5rem;">☀️</p><p style="font-weight:600; margin:0 0 0.35rem;">Serpme Kahvaltı</p><p style="font-size:0.9rem; color:var(--color-muted); margin:0;">Zengin peynir & zeytin seçkisi, deniz kenarında huzur.</p></div><div style="text-align:center; padding:1.5rem; border-radius:1.25rem; background:#fff; border:1px solid rgb(196 54 110 / 0.06);"><p style="font-size:2rem; margin:0 0 0.5rem;">🍰</p><p style="font-weight:600; margin:0 0 0.35rem;">Patisserie & Tatlılar</p><p style="font-size:0.9rem; color:var(--color-muted); margin:0;">Waffle, dilim pasta, şerbetli tatlılar ve milkshake.</p></div><div style="text-align:center; padding:1.5rem; border-radius:1.25rem; background:#fff; border:1px solid rgb(196 54 110 / 0.06);"><p style="font-size:2rem; margin:0 0 0.5rem;">🍔</p><p style="font-weight:600; margin:0 0 0.35rem;">Ana Yemekler</p><p style="font-size:0.9rem; color:var(--color-muted); margin:0;">Burger, makarna, salata ve aperatifler.</p></div></div></div>',
                        ],
                    ],
                    [
                        'type' => 'testimonials',
                        'data' => [
                            'heading' => 'Misafirlerimiz ne diyor?',
                            'eyebrow' => 'Yorumlar',
                            'items' => [
                                ['quote' => 'Dondurmalar harika, manzara eşsiz. Her yaz mutlaka uğruyoruz.', 'author_name' => 'Elif S.', 'author_title' => 'İzmir'],
                                ['quote' => 'Serpme kahvaltı gerçekten doyurucu ve taze. Personel çok ilgili.', 'author_name' => 'Can T.', 'author_title' => 'Mordoğan'],
                                ['quote' => 'Roma waffle ve special dondurma seçimi ile çocuklar bayıldı.', 'author_name' => 'Pınar K.', 'author_title' => 'Alaçatı'],
                            ],
                        ],
                    ],
                    [
                        'type' => 'cta_banner',
                        'data' => [
                            'heading' => 'Bizi ziyaret edin',
                            'subheading' => 'Mordoğan sahilinde, Cumhuriyet Meydanı No:13. Her gün 08:00 – 24:00.',
                            'button_text' => 'İletişim',
                            'button_url' => '/iletisim',
                            'background_color' => '#c4366e',
                        ],
                    ],
                ],
            ]
        );

        Page::updateOrCreate(
            ['slug' => 'galeri'],
            [
                'title' => ['tr' => 'Galeri'],
                'status' => PageStatus::Published->value,
                'is_home' => false,
                'sort_order' => 15,
                'blocks' => [
                    [
                        'type' => 'hero',
                        'data' => [
                            'heading' => 'Galeri',
                            'subheading' => 'Özel lezzetlerimiz ve nezih mekânımızdan en taze kareler.',
                            'alignment' => 'center',
                            'min_height' => '40vh',
                        ],
                    ],
                    [
                        'type' => 'gallery',
                        'data' => [
                            'title' => 'Special Roma’dan Kareler',
                            'layout' => 'masonry',
                            'columns' => '3',
                            'images' => [
                                'https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=800&q=80',
                                'https://images.unsplash.com/photo-1497534444932-c925b458314e?auto=format&fit=crop&w=800&q=80',
                                'https://images.unsplash.com/photo-1504753793650-d4ad2b62020e?auto=format&fit=crop&w=800&q=80',
                                'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=800&q=80',
                                'https://images.unsplash.com/photo-1551024506-0bccd828d307?auto=format&fit=crop&w=800&q=80',
                                'https://images.unsplash.com/photo-1464306076886-debca5dce908?auto=format&fit=crop&w=800&q=80',
                            ],
                        ],
                    ],
                ],
            ]
        );

        Page::updateOrCreate(
            ['slug' => 'iletisim'],
            [
                'title' => ['tr' => 'İletişim'],
                'status' => PageStatus::Published->value,
                'is_home' => false,
                'sort_order' => 20,
                'blocks' => [
                    [
                        'type' => 'hero',
                        'data' => [
                            'heading' => 'Bize Ulaşın',
                            'subheading' => 'Sorularınız, önerileriniz veya bilgi almak için her zaman buradayız.',
                            'alignment' => 'center',
                            'min_height' => '40vh',
                        ],
                    ],
                    [
                        'type' => 'contact_form',
                        'data' => [
                            'heading' => 'İletişim Formu',
                            'subtitle' => 'Merak ettiğiniz her şey için bize bir mesaj bırakın.',
                            'form_key' => 'contact',
                            'show_map' => true,
                            'background' => 'alt',
                        ],
                    ],
                ],
            ]
        );

        Page::where('slug', 'contact')->delete();
    }

    private function seedMenus(): void
    {
        $qrMenuUrl = $this->getQrMenuUrl();

        $items = [
            [
                'link_type' => 'page',
                'page_slug' => '/',
                'url' => '/',
                'open_new_tab' => false,
                'label' => ['tr' => 'Ana Sayfa'],
            ],
            [
                'link_type' => 'url',
                'page_slug' => null,
                'url' => $qrMenuUrl,
                'open_new_tab' => false,
                'label' => ['tr' => 'Menü'],
            ],
            [
                'link_type' => 'page',
                'page_slug' => '/hakkimizda',
                'url' => '/hakkimizda',
                'open_new_tab' => false,
                'label' => ['tr' => 'Hakkımızda'],
            ],
            [
                'link_type' => 'page',
                'page_slug' => '/galeri',
                'url' => '/galeri',
                'open_new_tab' => false,
                'label' => ['tr' => 'Galeri'],
            ],
            [
                'link_type' => 'page',
                'page_slug' => '/iletisim',
                'url' => '/iletisim',
                'open_new_tab' => false,
                'label' => ['tr' => 'İletişim'],
            ],
        ];

        Menu::where('location', 'primary')->update(['items' => $items]);
        Menu::where('location', 'footer')->update(['items' => $items]);
    }
}
