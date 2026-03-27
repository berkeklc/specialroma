# AgencyStack

> **Modüler, tekrar kullanılabilir Laravel boilerplate ajanslar için.**  
> Her türlü kurumsal siteyi (restoran, kafe, portföy, booking sistemleri vb.) modül seçerek hızlıca kurun.

---

## Genel Bakış

AgencyStack, dijital ajansların yılda birden fazla müşteri projesi teslim etmesi için geliştirilmiş, **tamamen modüler** bir Laravel boilerplate’tir.

**Temel Felsefe:**
- Her özellik `Modules/` klasörü altında ayrı bir modülde bulunur
- `Core` modülü her projede zorunludur
- İsteğe bağlı modüller `agency:install` sihirbazı ile seçilir
- Temel dosyaları bozmadan her proje için kolayca kopyalanıp kullanılabilir

---

## Teknoloji Stack (2026)

| Katman              | Teknoloji                          |
|---------------------|------------------------------------|
| Framework           | Laravel 12 (PHP 8.3+)             |
| Admin Panel         | FilamentPHP v3.2+                 |
| Frontend            | Livewire 3 + Volt + Alpine.js + Tailwind CSS v4 |
| Modül Sistemi       | nwidart/laravel-modules + coolsam/modules |
| Yetkilendirme       | Spatie Laravel Permission         |
| Çoklu Dil           | Spatie Translatable               |
| Medya               | Spatie Media Library              |
| Ayarlar             | Spatie Laravel Settings           |
| Schema.org          | Spatie Schema.org                 |
| QR Kod              | simple-qrcode                     |
| Test                | Pest                              |
| Local Development   | Laravel Herd                      |

---

## Hızlı Başlangıç

### Gereksinimler
- PHP 8.3+
- Composer
- Node.js 20+
- Laravel Herd
- MySQL / SQLite

### Adımlar

```bash
git clone https://github.com/berkeklc/agencystack.git my-project
cd my-project

herd composer install
npm install

cp .env.example .env
herd php artisan key:generate

# Veritabanı ayarlarını yap (.env)
# Sonra installer'ı çalıştır:
herd php artisan agency:install