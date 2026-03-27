# AgencyStack

> **A production-ready, modular Laravel boilerplate for digital agencies.**
> Build any corporate website — including restaurants, portfolios, booking services, and more — by selecting modules during installation.

---

## Overview

AgencyStack is a full-featured Laravel boilerplate built for digital agencies that deliver multiple client projects per year. Instead of starting from scratch or copying files between projects, you clone this repo, run one command, select your modules, and deploy.

**Core philosophy:**
- Every feature lives in a dedicated module under `Modules/`
- The Core module is always installed and provides the foundation
- Optional modules are installed per-project via the interactive installer
- Everything is customisable without touching core boilerplate files

---

## Tech Stack

| Layer | Package |
|---|---|
| Framework | Laravel 12 (PHP 8.3+) |
| Admin Panel | FilamentPHP v3 |
| Frontend | Livewire 3 + Alpine.js + Tailwind CSS v4 |
| Modules | nwidart/laravel-modules + coolsam/modules |
| Permissions | spatie/laravel-permission |
| Translations | spatie/laravel-translatable |
| Media | spatie/laravel-medialibrary |
| Settings | spatie/laravel-settings |
| Schema.org | spatie/schema-org |
| QR Codes | simplesoftwareio/simple-qrcode |
| Testing | Pest v4 |
| Local Dev | Laravel Herd |

---

## Quick Start

### Prerequisites

- PHP 8.3+
- Composer
- Node.js 20+
- Laravel Herd (or any local PHP server)
- A database (SQLite for local, MySQL for production)

### 1 — Clone and install

```bash
git clone https://github.com/your-org/agencystack.git my-project
cd my-project

# Install PHP dependencies
herd composer install

# Install Node dependencies
npm install

# Copy environment file and generate key
cp .env.example .env
herd php artisan key:generate
```

### 2 — Configure database

Edit `.env`:

```dotenv
DB_CONNECTION=sqlite          # or mysql
# DB_HOST=127.0.0.1
# DB_DATABASE=agencystack
# DB_USERNAME=root
# DB_PASSWORD=
```

For SQLite:
```bash
touch database/database.sqlite
```

### 3 — Run the interactive installer

```bash
herd php artisan agency:install
```

The wizard will ask for:
1. **Site name, tagline, and contact email** — saved immediately to settings
2. **Admin email and password** — creates the super-admin account
3. **Languages** — TR + EN are pre-selected; add more as needed
4. **Modules** — checkbox list of optional modules to activate
5. **Theme** — corporate / restaurant / portfolio / minimal / luxury

After confirming, it runs all migrations, seeds default data, enables selected modules, and links storage.

### 4 — Build assets and start

```bash
npm run build

# Or for development with hot reload:
npm run dev
```

Visit `http://agencystack.test` (or your configured URL) to see the public site, and `/admin` for the admin panel.

---

## Using as an Agency Boilerplate — Step by Step

When you win a new client project:

### Step 1 — Set up a fresh copy

```bash
# Option A: Use this repo as a GitHub template
# Click "Use this template" on GitHub

# Option B: Clone and remove git history
git clone https://github.com/your-org/agencystack.git client-name
cd client-name
rm -rf .git
git init
```

### Step 2 — Install for the client

```bash
herd composer install && npm install
cp .env.example .env
herd php artisan key:generate

# Edit .env: APP_URL, DB_*, MAIL_*
# Then run the installer:
herd php artisan agency:install
```

Answer the installer questions for this specific client (name, languages, which modules they need).

### Step 3 — Configure in the admin panel

Go to `/admin` and:

1. **Settings → General** — Logo type, site name, contact info, social links
2. **Settings → SEO** — Meta defaults, Google Analytics ID, sitemap toggle
3. **Settings → Mail** — SMTP credentials, admin notification email
4. **Design → Header Builder** — Upload logo, configure navigation, CTA button
5. **Design → Footer Builder** — Footer navigation, social icons, copyright text
6. **Content → Pages** — Create pages and add content blocks
7. **Content → Menus** — Add menu items for header and footer navigation

### Step 4 — Create pages with the Page Builder

In **Content → Pages → Create**:
- Set title, slug, SEO meta fields
- Add content blocks in the "Blocks" repeater
- Toggle "Set as Homepage" for the homepage
- Publish when ready

Available block types: `hero`, `text`, `image`, `gallery`, `video`, `services`, `testimonials`, `faq`, `cta`, `image-text`, `stats`.

### Step 5 — Deploy

```bash
# On the server:
herd composer install --no-dev --optimize-autoloader
npm run build
herd php artisan migrate --force
herd php artisan storage:link
herd php artisan config:cache
herd php artisan route:cache
herd php artisan view:cache
```

---

## Folder Structure

```
agencystack/
├── app/
│   ├── Console/Commands/        # agency:install, agency:module:enable, agency:theme:install
│   ├── Models/User.php          # Extended with FilamentUser + HasRoles
│   └── Providers/Filament/
│       └── AdminPanelProvider.php
│
├── Modules/                     # All modules live here
│   ├── Core/                    # Always installed — foundation of everything
│   │   ├── app/
│   │   │   ├── Enums/           # LayoutType, PageStatus
│   │   │   ├── Filament/        # Admin pages (Settings, etc.)
│   │   │   ├── Livewire/        # SiteHeader, SiteFooter, PublicPage
│   │   │   ├── Models/          # Layout, Menu, Page, FormSubmission
│   │   │   └── Settings/        # GeneralSettings, SeoSettings, MailSettings
│   │   ├── database/migrations/ # Core DB + Spatie settings migrations
│   │   ├── resources/
│   │   │   └── views/
│   │   │       ├── blocks/      # hero.blade, text.blade, services.blade, …
│   │   │       ├── livewire/    # site-header, site-footer, public-page
│   │   │       └── partials/    # render-blocks, social-icon
│   │   └── routes/web.php       # Homepage, slug pages, language switcher
│   │
│   ├── Blog/                    # Optional: Articles, categories, tags
│   ├── Services/                # Optional: Service listings with pricing
│   ├── Portfolio/               # Optional: Projects & case studies
│   ├── Team/                    # Optional: Staff profiles
│   ├── Contact/                 # Optional: Contact forms + email notifications
│   ├── Meeting/                 # Optional: Booking system with staff availability
│   └── QrMenu/                  # Optional: Restaurant QR menu system
│
├── resources/
│   ├── css/app.css              # Tailwind v4 + CSS custom properties (theme tokens)
│   ├── js/app.js                # Alpine.js + plugins, animations
│   └── views/
│       └── layouts/app.blade.php # Main public layout (SEO, OG, analytics)
│
├── config/
│   └── settings.php             # Spatie Settings configuration
│
└── database/
    └── seeders/                 # DatabaseSeeder
```

---

## Available Modules

### Core (always active)

| Feature | Description |
|---|---|
| Page Builder | Unlimited content blocks per page. 11+ block types. |
| Header Builder | Logo, navigation, language switcher, CTA button |
| Footer Builder | Brand info, menus, social icons, contact details |
| SEO | Meta tags, Open Graph, JSON-LD schema.org, hreflang, sitemap |
| Multi-language | TR + EN by default. Session-based locale switching. |
| User management | Roles: super_admin, admin, editor, client |
| Media Library | File uploads via Spatie MediaLibrary |
| Settings | General, SEO, Mail — all editable from admin |

### Blog

Articles with categories, tags, featured images, author assignment, and scheduled publishing. Filament resource included.

### Services

Service listings with descriptions, pricing, features list, and ordering. Linkable from navigation and page blocks.

### Portfolio

Project showcase with categories, gallery images, client name, tech stack tags, and external links.

### Team

Staff profiles with position, bio, photo, and social links. Sortable by order field.

### Contact

Contact form system with:
- Livewire public form (`@livewire('contact::contact-form')`)
- Honeypot spam protection + rate limiting (3/10 min per IP)
- Admin email notification on submission
- Optional user confirmation email
- Filament inbox for viewing and replying to submissions

### Meeting / Booking

- Staff members with availability slots
- `BlockedSlot` for holidays/exceptions
- Appointment creation with status management (pending, confirmed, cancelled)
- `AppointmentConfirmedNotification` email to client
- Filament resource for staff calendar view

### QR Menu

- Menu categories and items (name, description, price, photo, allergens)
- Table management with unique QR codes
- Public menu page: `/qr-menu/{slug}`
- QR code generation action (`GenerateTableQrCode`)

---

## Available Artisan Commands

```bash
# Full interactive installer (idempotent — safe to run multiple times)
herd php artisan agency:install
herd php artisan agency:install --force   # Skip already-installed check

# Enable a specific module
herd php artisan agency:module:enable Blog
herd php artisan agency:module:enable QrMenu

# Install a frontend theme
herd php artisan agency:theme:install corporate
herd php artisan agency:theme:install restaurant
herd php artisan agency:theme:install portfolio
herd php artisan agency:theme:install minimal
herd php artisan agency:theme:install luxury
```

---

## Theme System

Themes are applied via the `data-theme` attribute on `<body>`, driven by the `AGENCY_THEME` env variable (set by `agency:install` or `agency:theme:install`).

Each theme overrides CSS custom properties defined in `resources/css/app.css`:

```css
[data-theme="restaurant"] {
    --color-bg:        #fef9f4;
    --color-primary:   #78350f;
    --color-accent:    #d97706;
    --color-footer-bg: #1c0a00;
}
```

**Available themes:**

| Theme | Best for |
|---|---|
| `corporate` | Professional B2B companies |
| `restaurant` | Restaurants, cafes, food & beverage |
| `portfolio` | Creative agencies, designers, developers |
| `minimal` | Consulting, SaaS, clean modern brands |
| `luxury` | Premium brands, boutique hotels, jewellery |

To add a custom theme: add a new `[data-theme="yourtheme"]` block to `app.css` and register it in `AgencyThemeInstall.php`.

---

## Customization Guide

### Adding a new block type

1. Create `Modules/Core/resources/views/blocks/my-block.blade.php`
2. Use `$block['data']` to access the block's data array
3. Register the block schema in your Filament PageResource (`ContentBlocksRepeater`)
4. The `render-blocks.blade.php` partial auto-discovers it by type name

### Adding a new module

```bash
herd php artisan module:make MyModule
```

Then:
1. Move the generated provider to `Modules/MyModule/Providers/MyModuleServiceProvider.php`
2. Update `Modules/MyModule/module.json` to point to the correct provider namespace
3. Create your model, migration, and Filament resource
4. Register the module in `app/Providers/Filament/AdminPanelProvider.php`
5. Run `herd composer dump-autoload -o`

### Modifying the admin panel

The admin panel is configured in `app/Providers/Filament/AdminPanelProvider.php`. Add new resources, pages, and widgets there.

### Adding a language

1. Go to **Settings → General** in the admin and check the new language
2. Add translation files under `resources/lang/{locale}/` or `Modules/*/resources/lang/{locale}/`
3. For translatable model fields, the `HasTranslations` trait handles storage automatically

---

## Running Tests

```bash
# Run all tests
herd php artisan test

# Run specific test
herd php artisan test --filter=SomeTestName

# Run with coverage
herd php artisan test --coverage
```

Tests live in `tests/Feature/` and `tests/Unit/`. The project uses Pest v4.

---

## Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/my-feature`
3. Follow PSR-12 + Laravel conventions. Run Pint before committing:
   ```bash
   vendor/bin/pint --dirty
   ```
4. Write Pest tests for new features
5. Open a pull request with a clear description

---

## Versioning

| Version | Laravel | Filament | PHP |
|---|---|---|---|
| 1.x | 12.x | 3.x | 8.3+ |

---

## License

MIT — free for personal and commercial use.

---

*Built with care by a senior Laravel architect. Questions? Open an issue or start a discussion.*
