# AgencyStack

> **A production-ready, modular Laravel boilerplate for digital agencies.**  
> Build any corporate website — restaurant, portfolio, booking service, and more — by selecting modules during installation.

---

## Overview

AgencyStack lets digital agencies stop starting from scratch for every client project. Clone once, run the installer, pick your modules, and deploy.

**Core philosophy:**
- Every feature lives in its own module under `Modules/`
- `Core` is always installed — it is the foundation
- Optional modules are selected per-project via the interactive installer
- Everything is customisable without touching the base boilerplate

---

## Tech Stack

| Layer | Package |
|---|---|
| Framework | Laravel 12 (PHP 8.3+) |
| Admin panel | FilamentPHP v3 |
| Frontend | Livewire 3 + Alpine.js + Tailwind CSS v4 |
| Modules | nwidart/laravel-modules |
| Permissions | spatie/laravel-permission |
| Translations | spatie/laravel-translatable |
| Media | spatie/laravel-medialibrary |
| Settings | spatie/laravel-settings |
| QR codes | simplesoftwareio/simple-qrcode |
| Testing | Pest v4 |
| Local dev | Laravel Herd |

---

## Quick Start

```bash
# 1. Clone
git clone https://github.com/your-org/agencystack.git my-project
cd my-project

# 2. Install dependencies
herd composer install
npm install

# 3. Environment
cp .env.example .env
herd php artisan key:generate
# Edit .env — set APP_URL, DB_CONNECTION, etc.

# 4. Run the interactive installer
herd php artisan agency:install

# 5. Build assets
npm run build
```

Visit `http://your-site.test` and `/admin` to get started.

---

## Using as an Agency Boilerplate

### For every new client project

```bash
# Clone (or use as a GitHub template)
git clone https://github.com/your-org/agencystack.git client-name
cd client-name && rm -rf .git && git init

herd composer install && npm install
cp .env.example .env
herd php artisan key:generate

# Edit .env: APP_URL, DB_*, MAIL_*
herd php artisan agency:install
```

Answer the installer prompts:
1. **Site info** — name, tagline, contact email
2. **Admin account** — email + password for the super admin
3. **Languages** — TR + EN pre-selected; add more as needed
4. **Modules** — checkbox list of optional modules to activate
5. **Theme** — corporate / restaurant / portfolio / minimal / luxury

After install, the wizard automatically:
- Runs all migrations
- Creates the super admin user
- Seeds default roles and layouts
- Creates a default **Homepage** with Hero + CTA blocks
- Creates a **Contact** page stub
- Populates default Header and Footer menu items
- Links storage

### After the installer — configure in admin

1. **Settings → General** — logo type/text, site name, contact info, social links
2. **Settings → SEO** — meta defaults, Google Analytics ID, sitemap toggle
3. **Settings → Mail** — SMTP credentials, notification email
4. **Design → Navigation Menus** — add / reorder header and footer menu items
   - Link type: **Internal page** (searchable dropdown with all module pages including `/book`), **External URL**, or **Anchor** (`#section`)
   - Relative URLs like `/contact`, `/blog`, and `#section` are fully supported — no forced `https://` validation
5. **Design → Header / Footer** — upload logo image, configure layout rows
   - CTA button URLs accept relative paths (`/contact`), anchors (`#hero`), and absolute URLs
6. **Content → Pages** — create pages, add content blocks, set homepage

---

## Development Workflow

### When you make changes in the admin panel

### Public Booking Page (`/book`)

When the Meeting module is enabled, a Cal.com-style booking page is available at `/book`:

1. **Step 1** — Staff picker (auto-selected if single staff), interactive calendar, and available time slots based on staff working hours
2. **Step 2** — Client details form (name, email, phone, optional guest email, notes)
3. **Step 3** — Confirmation screen with appointment summary + email sent to client

Add it to any navigation menu via **Design → Navigation Menus** → pick **"Book an appointment"** from the Internal Pages dropdown.

---

Changes to **Settings**, **Pages**, **Menus**, and **Header/Footer** are stored in the database and reflect on the public site immediately — no cache clear needed. Both `SiteHeader` and `SiteFooter` Livewire components always fetch fresh data on every request.

If you change **PHP code** or **config files**, run:

```bash
herd php artisan agency:clear
```

This single command clears config, route, view, application, and event caches.

### Asset changes (CSS / JS)

When you modify `resources/css/app.css` or `resources/js/app.js`:

```bash
# One-time build
npm run build

# Hot-reload during development
npm run dev
```

### Full cache reset (after pulling code changes)

```bash
herd composer dump-autoload -o
herd php artisan agency:clear
npm run build
```

### Running database migrations

```bash
# Run all pending migrations
herd php artisan migrate

# Run migrations for a specific module only
herd php artisan module:migrate Blog
herd php artisan module:migrate Contact
```

### Enabling a module after install

```bash
herd php artisan agency:module:enable Blog
herd php artisan agency:module:enable QrMenu
```

### Switching theme

```bash
herd php artisan agency:theme:install restaurant
# Choices: corporate | restaurant | portfolio | minimal | luxury
```

### Available artisan commands

```bash
herd php artisan agency:install           # Interactive setup wizard (idempotent)
herd php artisan agency:install --force   # Skip "already installed" check
herd php artisan agency:clear             # Clear all caches at once
herd php artisan agency:module:enable {Module}
herd php artisan agency:theme:install {theme}
```

### Running tests

```bash
herd php artisan test
herd php artisan test --filter=SomeTestName
```

### Code style

```bash
vendor/bin/pint --dirty   # Format only changed files
vendor/bin/pint           # Format everything
```

---

## Folder Structure

```
agencystack/
├── app/
│   ├── Console/Commands/    # agency:install, agency:clear, agency:module:enable, agency:theme:install
│   ├── Models/User.php      # FilamentUser + HasRoles
│   └── Providers/Filament/AdminPanelProvider.php
│
├── Modules/                 # All feature modules
│   ├── Core/                # Always active
│   │   ├── app/
│   │   │   ├── Filament/
│   │   │   │   ├── Pages/   # GeneralSettingsPage, SeoSettingsPage, MailSettingsPage
│   │   │   │   └── Resources/  # PageResource, LayoutResource, MenuResource, UserResource
│   │   │   ├── Livewire/    # SiteHeader, SiteFooter, PublicPage
│   │   │   ├── Models/      # Layout, Menu, Page, FormSubmission
│   │   │   └── Settings/    # GeneralSettings, SeoSettings, MailSettings
│   │   ├── database/migrations/
│   │   └── resources/views/
│   │       ├── blocks/      # hero, text, image, gallery, video, services,
│   │       │                # testimonials, faq, cta, image-text, stats, contact-form
│   │       ├── livewire/    # site-header, site-footer, public-page, no-homepage
│   │       └── partials/    # render-blocks, social-icon
│   │
│   ├── Blog/        # Articles, categories, tags
│   ├── Services/    # Service listings
│   ├── Portfolio/   # Projects & case studies
│   ├── Team/        # Staff profiles
│   ├── Contact/     # Contact forms + email notifications (Livewire public form)
│   ├── Meeting/     # Booking system with staff availability
│   └── QrMenu/      # Restaurant QR menu system
│
├── resources/
│   ├── css/app.css          # Tailwind v4 + CSS custom properties (5 themes)
│   ├── js/app.js            # Alpine.js + plugins
│   └── views/layouts/app.blade.php  # Public layout (SEO, OG, analytics)
│
└── .github/workflows/tests.yml  # CI: PHP matrix + Pint + Pest
```

---

## Available Modules

| Module | Description |
|---|---|
| **Core** | Page builder (11 block types), Header/Footer builder, SEO, multi-language, user roles |
| **Blog** | Articles with categories, tags, authors, scheduled publishing |
| **Services** | Service listings with pricing and feature lists |
| **Portfolio** | Project showcase with gallery and case studies |
| **Team** | Staff profiles with bio, photo, and social links |
| **Contact** | Public Livewire contact form, admin inbox, email notifications |
| **Meeting** | Staff availability, appointment booking (Cal.com-style), email confirmations — public page at `/book` |
| **QrMenu** | Restaurant/cafe QR menu, table QR codes, public menu page |

---

## Page Block Types

Add these by slug in the Page Builder repeater:

| Block | Purpose |
|---|---|
| `hero` | Full-screen hero with background, heading, sub-heading, 2 CTA buttons |
| `text` | Rich text with optional 2-column layout |
| `image` | Single image with caption and width control |
| `gallery` | Photo grid with lightbox |
| `video` | YouTube / Vimeo embed or direct video file |
| `services` | Card grid — icon, title, description, link |
| `testimonials` | Quote cards with avatar, name, role |
| `faq` | Accordion FAQ with auto JSON-LD schema.org |
| `cta` | Call-to-action section (dark / accent / minimal style) |
| `image-text` | Image + text side by side (left or right) |
| `stats` | Numeric stats row |
| `contact-form` | Embeds the Contact module's Livewire form |

---

## Theme System

Apply via `AGENCY_THEME` env variable or `agency:theme:install`:

| Theme | Best for |
|---|---|
| `corporate` | B2B, professional services |
| `restaurant` | Food & beverage |
| `portfolio` | Creative agencies, designers |
| `minimal` | SaaS, consulting |
| `luxury` | Premium brands, boutique hotels |

Custom themes: add a `[data-theme="yourtheme"]` block to `resources/css/app.css`.

---

## Deployment

```bash
# On the server (after pushing code):
herd composer install --no-dev --optimize-autoloader
npm run build
herd php artisan migrate --force
herd php artisan storage:link
herd php artisan config:cache
herd php artisan route:cache
herd php artisan view:cache
```

---

## Contributing

1. Fork → feature branch → PR
2. Run `vendor/bin/pint --dirty` before committing
3. Write Pest tests for new features
4. Keep one module per feature area

---

## License

MIT — free for personal and commercial use.
