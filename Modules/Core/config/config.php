<?php

declare(strict_types=1);

return [
    'name' => 'Core',

    /*
    |--------------------------------------------------------------------------
    | Language Settings
    |--------------------------------------------------------------------------
    */
    'active_languages' => array_filter(
        explode(',', env('AGENCY_DEFAULT_LANGUAGES', 'tr,en'))
    ),

    'default_language' => env('APP_LOCALE', 'tr'),

    /*
    |--------------------------------------------------------------------------
    | SEO Settings
    |--------------------------------------------------------------------------
    */
    'seo' => [
        'default_title_separator' => ' | ',
        'generate_hreflang' => true,
        'generate_sitemap' => true,
        'sitemap_cache_ttl' => 3600,
    ],

    /*
    |--------------------------------------------------------------------------
    | Page Builder Settings
    |--------------------------------------------------------------------------
    */
    'page_builder' => [
        'cache_ttl' => 300,
        'allowed_blocks' => [
            'hero', 'text', 'image', 'gallery', 'video',
            'services_grid', 'testimonials', 'faq',
            'contact_form', 'cta_banner',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Media Settings
    |--------------------------------------------------------------------------
    */
    'media' => [
        'disk' => env('MEDIA_DISK', 'public'),
        'max_file_size' => 10240,
        'allowed_mime_types' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'application/pdf'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Roles
    |--------------------------------------------------------------------------
    */
    'roles' => [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'editor' => 'Editor',
        'agency_user' => 'Agency User',
        'client' => 'Client',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Registry
    |--------------------------------------------------------------------------
    | List of optional modules with their display names and descriptions
    */
    'optional_modules' => [
        'Blog' => ['label' => 'Blog / News', 'description' => 'Blog posts and news articles'],
        'Portfolio' => ['label' => 'Portfolio / Projects', 'description' => 'Project showcase and references'],
        'Services' => ['label' => 'Services', 'description' => 'Service listings'],
        'Products' => ['label' => 'Products & Categories', 'description' => 'Product catalog with categories'],
        'Team' => ['label' => 'Team Members', 'description' => 'Staff and team profiles'],
        'Offers' => ['label' => 'Offers / Campaigns', 'description' => 'Special offers and promotions'],
        'Gallery' => ['label' => 'Gallery', 'description' => 'Photo and media gallery'],
        'Faq' => ['label' => 'FAQ', 'description' => 'Frequently asked questions'],
        'Contact' => ['label' => 'Contact & Forms', 'description' => 'Advanced contact forms'],
        'Meeting' => ['label' => 'Meeting / Booking', 'description' => 'Appointment scheduling system'],
        'QrMenu' => ['label' => 'QR Menu', 'description' => 'Restaurant/cafe QR menu system'],
        'Video' => ['label' => 'Video Module', 'description' => 'Video content management'],
        'Room' => ['label' => 'Room / Reservation', 'description' => 'Room booking system'],
        'Popup' => ['label' => 'Popup Manager', 'description' => 'Website popups and modals'],
        'Newsletter' => ['label' => 'Newsletter', 'description' => 'Email newsletter subscription'],
        'Cv' => ['label' => 'CV / Resume', 'description' => 'Resume upload and management'],
        'HomeSpot' => ['label' => 'Home Spot / Special Blocks', 'description' => 'Homepage special sections'],
    ],
];
