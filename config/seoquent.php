<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Meta Tags
    |--------------------------------------------------------------------------
    |
    | These are the default meta tag values used when no specific values
    | are set for a page. They act as fallbacks.
    |
    */

    'defaults' => [
        'title' => env('APP_NAME', 'Laravel'),
        'title_separator' => ' | ',
        'title_suffix' => env('APP_NAME', 'Laravel'),
        'description' => '',
        'keywords' => [],
        'robots' => 'index, follow',
        'canonical' => null,
        'author' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Open Graph Defaults
    |--------------------------------------------------------------------------
    |
    | Default Open Graph meta tag values for social media sharing.
    | These are used when sharing pages on Facebook, LinkedIn, etc.
    |
    */

    'open_graph' => [
        'type' => 'website',
        'site_name' => env('APP_NAME', 'Laravel'),
        'locale' => 'en_US',
        'image' => null,
        'image_width' => 1200,
        'image_height' => 630,
    ],

    /*
    |--------------------------------------------------------------------------
    | Twitter Card Defaults
    |--------------------------------------------------------------------------
    |
    | Default Twitter Card meta tag values.
    | Card types: summary, summary_large_image, app, player
    |
    */

    'twitter' => [
        'card' => 'summary_large_image',
        'site' => null,   // @username
        'creator' => null, // @username
    ],

    /*
    |--------------------------------------------------------------------------
    | JSON-LD Defaults
    |--------------------------------------------------------------------------
    |
    | Default values for JSON-LD structured data (Schema.org).
    | Organization info is used across multiple schema types.
    |
    */

    'json_ld' => [
        'enabled' => true,
        'organization' => [
            'name' => env('APP_NAME', 'Laravel'),
            'url' => env('APP_URL', 'http://localhost'),
            'logo' => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Sitemap Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the XML sitemap generation.
    |
    */

    'sitemap' => [
        'enabled' => true,
        'route' => 'sitemap.xml',
        'cache_enabled' => true,
        'cache_duration' => 3600, // seconds
        'default_frequency' => 'weekly',
        'default_priority' => 0.5,
        'max_urls' => 50000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Robots.txt Configuration
    |--------------------------------------------------------------------------
    |
    | Configure dynamic robots.txt generation.
    | Set 'enabled' to false to use a static robots.txt file instead.
    |
    */

    'robots' => [
        'enabled' => true,
        'route' => 'robots.txt',
        'allow' => ['/'],
        'disallow' => [],
        'sitemap_url' => null, // auto-generated if null
    ],

    /*
    |--------------------------------------------------------------------------
    | Database SEO Meta
    |--------------------------------------------------------------------------
    |
    | Configuration for the database-backed SEO meta system.
    | When enabled, models using the HasSeo trait can store
    | SEO data in the database.
    |
    */

    'database' => [
        'enabled' => true,
        'table_name' => 'seo_meta',
    ],

    /*
    |--------------------------------------------------------------------------
    | Trailing Slash
    |--------------------------------------------------------------------------
    |
    | Whether to enforce or remove trailing slashes on canonical URLs.
    | Options: null (don't modify), true (add), false (remove)
    |
    */

    'trailing_slash' => null,

];
