<?php

declare(strict_types=1);

namespace Database\Seeders;

/**
 * QR menü kategorileri ve ürünleri — roma-menu.pdf OCR + el ile düzeltilmiş isim/fiyatlar (Filament’ten güncellenebilir).
 */
final class SpecialRomaMenuData
{
    /**
     * @return list<array{name: array{tr: string, en: string}, description: array{tr: string, en: string}|null, items: list<array{name: array{tr: string, en: string}, description: array{tr: string, en: string}|null, price: float}>}>
     */
    public static function categories(): array
    {
        $t = fn (string $tr, ?string $en = null): array => ['tr' => $tr, 'en' => $en ?? $tr];

        $klasik = [
            'Sade', 'Tutti Frutti', 'Sakızlı', 'Kestaneli', 'Çikolatalı', 'Limonlu', 'Karameli', 'Çilekli',
            'Kahveli', 'Vişneli', 'Bal-Badem',
        ];

        $special = [
            'Bitter', 'Honey Comb', 'Kuvertür', 'Pesca Golosa', 'Big Babol', 'Muz', 'Blue Sky', 'Fındık', 'Nane',
            'Sneakers', 'Hindistan Cevizi', 'Böğürtlen', 'Tahin', 'Ahududu', 'Karadut', 'Ceviz-İncir', 'Kavun',
            'Crunchy', 'Mandalina', 'Yoğurt', 'Yeşil Elma', 'Cookies', 'Oreo', 'Zencefil-Tarçın', 'Kivi',
            'Mango-Ananas', 'Frenk Üzümü', 'Portakal', 'Cool Lime', 'Antep Fıstık', 'Güllü', 'Hibiscus', 'Lotus',
            'Menekşe', 'Kinder Bueno', 'Acı Badem', 'Passion Fruit', 'Lavanta', 'Blown',
        ];

        $iceNote = 'Top: 65 ₺ | Kilo: 950 ₺ (self servis)';

        return [
            [
                'name' => $t('Serpme Kahvaltı & Kahvaltı Çeşitleri'),
                'description' => $t('Mordoğan’ın en keyifli kahvaltı sofralarından.'),
                'items' => [
                    ['name' => $t('Serpme Kahvaltı'), 'description' => $t('Peynir, zeytin, domates, salatalık, bal-kaymak, reçel, tahin-pekmez, patates kızartması, haşlanmış yumurta, söğüş ve daha fazlası.'), 'price' => 650],
                    ['name' => $t('Hızlı Kahvaltı'), 'description' => $t('Peynir, söğüş, reçel, zeytin, gevrek, haşlanmış yumurta, çay.'), 'price' => 360],
                ],
            ],
            [
                'name' => $t('Börek & Omlet Çeşitleri'),
                'description' => null,
                'items' => [
                    ['name' => $t('Kıymalı Börek'), 'description' => null, 'price' => 110],
                    ['name' => $t('Peynirli Börek'), 'description' => null, 'price' => 110],
                    ['name' => $t('Ispanaklı Börek'), 'description' => null, 'price' => 110],
                    ['name' => $t('Açma'), 'description' => null, 'price' => 30],
                    ['name' => $t('Gevrek'), 'description' => null, 'price' => 20],
                    ['name' => $t('Poğaça'), 'description' => null, 'price' => 25],
                    ['name' => $t('Sade Omlet'), 'description' => null, 'price' => 170],
                    ['name' => $t('Sucuklu Omlet'), 'description' => null, 'price' => 215],
                    ['name' => $t('Kaşarlı Omlet'), 'description' => null, 'price' => 200],
                    ['name' => $t('Maydanozlu Omlet'), 'description' => null, 'price' => 170],
                    ['name' => $t('Sucuklu Yumurta'), 'description' => null, 'price' => 215],
                    ['name' => $t('Beyaz Peynirli Yumurta'), 'description' => null, 'price' => 180],
                    ['name' => $t('Sucuk'), 'description' => null, 'price' => 180],
                    ['name' => $t('Menemen'), 'description' => null, 'price' => 180],
                    ['name' => $t('Sucuklu Menemen'), 'description' => null, 'price' => 230],
                ],
            ],
            [
                'name' => $t('Makarnalar'),
                'description' => null,
                'items' => [
                    ['name' => $t('Penne Mexicana'), 'description' => $t('Acı sos, renkli biberler, mısır, zeytin, tavuk, sweet chili, jalapeno.'), 'price' => 250],
                ],
            ],
            [
                'name' => $t('Aperatifler'),
                'description' => null,
                'items' => [
                    ['name' => $t('Cheddar Soslu Patates Tava'), 'description' => null, 'price' => 250],
                    ['name' => $t('Çıtır Tavuk'), 'description' => null, 'price' => 285],
                    ['name' => $t('Cajun Baharatlı Patates Tava'), 'description' => null, 'price' => 200],
                    ['name' => $t('Çıtır Dürüm Tavuk'), 'description' => null, 'price' => 220],
                    ['name' => $t('Çıtır Dürüm Et'), 'description' => null, 'price' => 400],
                    ['name' => $t('Çıtır Sepeti'), 'description' => null, 'price' => 290],
                    ['name' => $t('Patates'), 'description' => null, 'price' => 170],
                    ['name' => $t('Kalem Böreği'), 'description' => null, 'price' => 170],
                    ['name' => $t('Kare Kaşarlı Tost'), 'description' => null, 'price' => 170],
                    ['name' => $t('Kare Sucuk-Kaşarlı Tost'), 'description' => null, 'price' => 200],
                ],
            ],
            [
                'name' => $t('Burgerler & Burger Menüler'),
                'description' => $t('Etler 120–240 gr; menülerde patates ve içecek dahildir.'),
                'items' => [
                    ['name' => $t('Roma Burger'), 'description' => $t('240 gr hamburger eti, dana bacon, füme et, karamelize soğan, house sos, marul, turşu, domates.'), 'price' => 395],
                    ['name' => $t('Hamburger'), 'description' => $t('120 gr hamburger eti, marul, turşu, domates.'), 'price' => 395],
                    ['name' => $t('Cheese Burger'), 'description' => $t('120 gr hamburger eti, cheddar, marul, turşu, domates.'), 'price' => 345],
                    ['name' => $t('Chicken Burger'), 'description' => $t('120 gr tavuk burger eti, marul, turşu, domates.'), 'price' => 290],
                    ['name' => $t('Double Burger'), 'description' => $t('İki adet 120 gr hamburger eti, marul, turşu, domates.'), 'price' => 380],
                    ['name' => $t('Roma Burger Menü'), 'description' => $t('Roma Burger + patates + içecek.'), 'price' => 450],
                    ['name' => $t('Hamburger Menü'), 'description' => $t('Hamburger + patates + içecek.'), 'price' => 475],
                    ['name' => $t('Cheese Burger Menü'), 'description' => $t('Cheese Burger + patates + içecek.'), 'price' => 400],
                    ['name' => $t('Chicken Burger Menü'), 'description' => $t('Chicken Burger + patates + içecek.'), 'price' => 400],
                    ['name' => $t('Double Burger Menü'), 'description' => $t('Double Burger + patates + içecek.'), 'price' => 500],
                ],
            ],
            [
                'name' => $t('Ana Yemekler'),
                'description' => null,
                'items' => [
                    ['name' => $t('Barbekü Soslu Tavuk Tava'), 'description' => $t('Barbekü sosu, tavuk, pilav, patates, coleslaw salata.'), 'price' => 355],
                    ['name' => $t('Kremalı Soslu Tavuk Tava'), 'description' => $t('Krema sosu, tavuk, pilav, patates, coleslaw salata.'), 'price' => 325],
                    ['name' => $t('Barbekü Soslu Antrikot Tava'), 'description' => $t('Barbekü sosu, antrikot, pilav, patates, coleslaw salata.'), 'price' => 450],
                    ['name' => $t('Kremalı Soslu Antrikot Tava'), 'description' => $t('Krema sosu, antrikot, pilav, patates, coleslaw salata.'), 'price' => 480],
                    ['name' => $t('Izgara Köfte'), 'description' => null, 'price' => 360],
                    ['name' => $t('Tavuk Schnitzel'), 'description' => null, 'price' => 300],
                ],
            ],
            [
                'name' => $t('Salatalar'),
                'description' => null,
                'items' => [
                    ['name' => $t('Ege Salatası'), 'description' => $t('Mevsim yeşillikleri, şeker domates, salatalık, peynir.'), 'price' => 220],
                    ['name' => $t('Sezar Salata'), 'description' => $t('Sezar sos, tavuk, kızarmış ekmek, mısır.'), 'price' => 280],
                    ['name' => $t('Ton Balıklı Salata'), 'description' => $t('Ton balığı, yeşil salata.'), 'price' => 350],
                    ['name' => $t('Special Roma Salatası'), 'description' => $t('Roma köfte, parmesan, mevsim yeşillikleri.'), 'price' => 320],
                ],
            ],
            [
                'name' => $t('Klasik Dondurmalar'),
                'description' => $t($iceNote),
                'items' => array_map(fn (array $item): array => [
                    'name' => $item['name'],
                    'description' => $t($iceNote),
                    'price' => 65,
                ], array_map(fn (string $n): array => ['name' => $t($n)], $klasik)),
            ],
            [
                'name' => $t('Special Dondurmalar'),
                'description' => $t($iceNote),
                'items' => array_map(fn (array $item): array => [
                    'name' => $item['name'],
                    'description' => $t($iceNote),
                    'price' => 65,
                ], array_map(fn (string $n): array => ['name' => $t($n)], $special)),
            ],
            [
                'name' => $t('Dondurmalı Tatlılar & Milkshake'),
                'description' => null,
                'items' => [
                    ['name' => $t('Fragola Sogna'), 'description' => $t('Karışık dondurma, taze çilek, krem şanti, yaban mersini, muz, kivi, çilek sosu.'), 'price' => 360],
                    ['name' => $t('Fragola Frappe'), 'description' => $t('Kavunlu ve mangolu dondurma, taze çilek, krem şanti, hindistan cevizi, çilek sosu.'), 'price' => 360],
                    ['name' => $t('Fragola Bambini'), 'description' => $t('Vanilyalı ve çilekli dondurma, taze çilek, krem şanti, çilek sosu.'), 'price' => 315],
                    ['name' => $t('Çilekli Fragola Frappe'), 'description' => $t('Çilekli dondurma, süt, taze çilek, krem şanti, çilek sosu.'), 'price' => 280],
                    ['name' => $t('Fragola Italia'), 'description' => $t('Karışık dondurma, taze çilek, muz, yeşil elma, kivi, krem şanti, çilek sosu.'), 'price' => 350],
                    ['name' => $t('Mangolu Fragola Bambini'), 'description' => $t('Mangolu ve çilekli dondurma, taze çilek, krem şanti, çilek sosu.'), 'price' => 330],
                    ['name' => $t('Milkshake (istediğiniz dondurma seçeneği ile)'), 'description' => null, 'price' => 210],
                ],
            ],
            [
                'name' => $t('Dilim Pastalar'),
                'description' => null,
                'items' => [
                    ['name' => $t('Limonlu Cheesecake'), 'description' => null, 'price' => 150],
                    ['name' => $t('Frambuazlı Cheesecake'), 'description' => null, 'price' => 150],
                    ['name' => $t('Ananas-Badem'), 'description' => null, 'price' => 150],
                    ['name' => $t('Vişneli Karaorman'), 'description' => null, 'price' => 150],
                    ['name' => $t('Kestane Karaorman'), 'description' => null, 'price' => 150],
                    ['name' => $t('Devil\'s Pasta'), 'description' => null, 'price' => 150],
                    ['name' => $t('Antep Rüyası Pasta'), 'description' => null, 'price' => 160],
                    ['name' => $t('Krokanlı Pasta'), 'description' => null, 'price' => 180],
                    ['name' => $t('Profiterol Pasta'), 'description' => null, 'price' => 200],
                    ['name' => $t('Mozaik Pasta'), 'description' => null, 'price' => 140],
                    ['name' => $t('Ekler'), 'description' => null, 'price' => 90],
                ],
            ],
            [
                'name' => $t('Waffle (Roma Waffle + Kendin Yap)'),
                'description' => $t('İstediğiniz malzemeyi seçin, biz hazırlayalım.'),
                'items' => [
                    ['name' => $t('Roma Waffle'), 'description' => $t('Seçeceğiniz taban malzemeler ve 1 top dondurma (65 ₺) ile.'), 'price' => 260],
                    ['name' => $t('Waffle’ini Kendin Yap'), 'description' => $t('Malzemeler menü fiyatları üzerinden hesaplanır.'), 'price' => 200],
                ],
            ],
            [
                'name' => $t('Şerbetli Tatlılar'),
                'description' => null,
                'items' => [
                    ['name' => $t('Antep Fıstıklı Baklava'), 'description' => null, 'price' => 220],
                    ['name' => $t('Cevizli Ev Baklavası'), 'description' => null, 'price' => 200],
                    ['name' => $t('Antep Fıstıklı Burma Kadayıf'), 'description' => null, 'price' => 210],
                    ['name' => $t('Cevizli Burma Kadayıf'), 'description' => null, 'price' => 190],
                    ['name' => $t('Lor Tatlısı'), 'description' => null, 'price' => 130],
                    ['name' => $t('Şambali'), 'description' => null, 'price' => 130],
                    ['name' => $t('Kalburabastı'), 'description' => null, 'price' => 120],
                    ['name' => $t('Ekmek Kadayıfı'), 'description' => null, 'price' => 130],
                    ['name' => $t('Antep Fıstıklı Basma Kadayıf'), 'description' => null, 'price' => 250],
                ],
            ],
            [
                'name' => $t('Sütlü Tatlılar'),
                'description' => null,
                'items' => [
                    ['name' => $t('Keşkül'), 'description' => null, 'price' => 130],
                    ['name' => $t('Supangle'), 'description' => null, 'price' => 150],
                    ['name' => $t('Kazandibi'), 'description' => null, 'price' => 130],
                    ['name' => $t('Karamelli Trileçe'), 'description' => null, 'price' => 120],
                    ['name' => $t('Frambuazlı Trileçe'), 'description' => null, 'price' => 150],
                ],
            ],
            [
                'name' => $t('Frozen, Smoothie & Mocktail'),
                'description' => null,
                'items' => [
                    ['name' => $t('Orman Meyveli Frozen'), 'description' => null, 'price' => 150],
                    ['name' => $t('Çilekli Frozen'), 'description' => null, 'price' => 150],
                    ['name' => $t('Muzlu Frozen'), 'description' => null, 'price' => 150],
                    ['name' => $t('Kivili Frozen'), 'description' => null, 'price' => 150],
                    ['name' => $t('Naneli Frozen'), 'description' => null, 'price' => 150],
                    ['name' => $t('Limonlu Frozen'), 'description' => null, 'price' => 150],
                    ['name' => $t('Karpuzlu Frozen'), 'description' => null, 'price' => 150],
                    ['name' => $t('Böğürtlenli Smoothie'), 'description' => null, 'price' => 200],
                    ['name' => $t('Karadutlu Smoothie'), 'description' => null, 'price' => 200],
                    ['name' => $t('Kivili Smoothie'), 'description' => null, 'price' => 200],
                    ['name' => $t('Muzlu Smoothie'), 'description' => null, 'price' => 200],
                    ['name' => $t('Coollime'), 'description' => null, 'price' => 140],
                    ['name' => $t('Berry Hibiscus'), 'description' => null, 'price' => 140],
                    ['name' => $t('Passion Lime'), 'description' => null, 'price' => 130],
                    ['name' => $t('Barbie'), 'description' => null, 'price' => 130],
                    ['name' => $t('Blue Sky (Mocktail)'), 'description' => null, 'price' => 120],
                    ['name' => $t('Hibiscus Passion'), 'description' => null, 'price' => 120],
                    ['name' => $t('Apple Mint'), 'description' => null, 'price' => 140],
                    ['name' => $t('Churchill'), 'description' => null, 'price' => 80],
                    ['name' => $t('Limonata'), 'description' => null, 'price' => 100],
                ],
            ],
            [
                'name' => $t('Soğuk İçecekler'),
                'description' => $t('Soğuk kahveler, İtalyan soda ve meşrubatlar.'),
                'items' => [
                    ['name' => $t('Karamel Frappe'), 'description' => null, 'price' => 140],
                    ['name' => $t('Ice Cappuccino'), 'description' => null, 'price' => 160],
                    ['name' => $t('Cold Brew'), 'description' => null, 'price' => 130],
                    ['name' => $t('Affogato'), 'description' => null, 'price' => 180],
                    ['name' => $t('Ice Americano'), 'description' => null, 'price' => 130],
                    ['name' => $t('Ice Honey Lemon Americano'), 'description' => null, 'price' => 140],
                    ['name' => $t('Ice Caramel Macchiato'), 'description' => null, 'price' => 150],
                    ['name' => $t('Naneli İtalyan Soda'), 'description' => null, 'price' => 110],
                    ['name' => $t('Çilekli İtalyan Soda'), 'description' => null, 'price' => 110],
                    ['name' => $t('Kivili İtalyan Soda'), 'description' => null, 'price' => 110],
                    ['name' => $t('Limonlu İtalyan Soda'), 'description' => null, 'price' => 110],
                    ['name' => $t('Coca Cola'), 'description' => null, 'price' => 80],
                    ['name' => $t('Coca Cola Zero'), 'description' => null, 'price' => 80],
                    ['name' => $t('Fanta'), 'description' => null, 'price' => 80],
                    ['name' => $t('Sprite'), 'description' => null, 'price' => 80],
                    ['name' => $t('Limonlu Soda'), 'description' => null, 'price' => 60],
                    ['name' => $t('Elmalı Soda'), 'description' => null, 'price' => 60],
                    ['name' => $t('Mandalinalı Soda'), 'description' => null, 'price' => 60],
                    ['name' => $t('Karpuz-Çilekli Soda'), 'description' => null, 'price' => 80],
                    ['name' => $t('Sade Soda'), 'description' => null, 'price' => 60],
                    ['name' => $t('Şeftalili Meyve Suyu'), 'description' => null, 'price' => 80],
                    ['name' => $t('Vişneli Meyve Suyu'), 'description' => null, 'price' => 80],
                    ['name' => $t('Karışık Meyve Suyu'), 'description' => null, 'price' => 80],
                    ['name' => $t('Fuse Tea Şeftali'), 'description' => null, 'price' => 80],
                    ['name' => $t('Fuse Tea Limon'), 'description' => null, 'price' => 80],
                    ['name' => $t('Fuse Tea Mango'), 'description' => null, 'price' => 80],
                    ['name' => $t('Ayran'), 'description' => null, 'price' => 50],
                    ['name' => $t('Su'), 'description' => null, 'price' => 20],
                ],
            ],
            [
                'name' => $t('Sıcak İçecekler & Kahveler'),
                'description' => null,
                'items' => [
                    ['name' => $t('Fincan Çay'), 'description' => null, 'price' => 70],
                    ['name' => $t('Sahlep'), 'description' => null, 'price' => 120],
                    ['name' => $t('Sıcak Çikolata'), 'description' => null, 'price' => 130],
                    ['name' => $t('Ihlamur'), 'description' => null, 'price' => 100],
                    ['name' => $t('Kuşburnu'), 'description' => null, 'price' => 100],
                    ['name' => $t('Adaçayı'), 'description' => null, 'price' => 100],
                    ['name' => $t('Yeşil Çay'), 'description' => null, 'price' => 100],
                    ['name' => $t('Elma Çayı'), 'description' => null, 'price' => 100],
                    ['name' => $t('Kış Çayı'), 'description' => null, 'price' => 100],
                    ['name' => $t('Papatya Çayı'), 'description' => null, 'price' => 100],
                    ['name' => $t('Limonlu Yeşil Çay'), 'description' => null, 'price' => 100],
                    ['name' => $t('Yaseminli Yeşil Çay'), 'description' => null, 'price' => 100],
                    ['name' => $t('Reyhanlı Yeşil Çay'), 'description' => null, 'price' => 100],
                    ['name' => $t('Türk Kahvesi'), 'description' => null, 'price' => 80],
                    ['name' => $t('Double Türk Kahvesi'), 'description' => null, 'price' => 95],
                    ['name' => $t('Espresso'), 'description' => null, 'price' => 80],
                    ['name' => $t('Double Espresso'), 'description' => null, 'price' => 95],
                    ['name' => $t('Americano'), 'description' => null, 'price' => 120],
                    ['name' => $t('Cappuccino'), 'description' => null, 'price' => 120],
                    ['name' => $t('Latte'), 'description' => null, 'price' => 120],
                    ['name' => $t('Nescafe'), 'description' => null, 'price' => 90],
                    ['name' => $t('Mocha'), 'description' => null, 'price' => 95],
                ],
            ],
        ];
    }
}
