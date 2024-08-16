<?php
/**
 * Playground
 */

declare(strict_types=1);

/**
 * Playground: Directory Resource Configuration and Environment Variables
 */
return [

    /*
    |--------------------------------------------------------------------------
    | About Information
    |--------------------------------------------------------------------------
    |
    | By default, information will be displayed about this package when using:
    |
    | `artisan about`
    |
    */

    'about' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_ABOUT', true),

    /*
    |--------------------------------------------------------------------------
    | Loading
    |--------------------------------------------------------------------------
    |
    | By default, translations and views are loaded.
    |
    */

    'load' => [
        'policies' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_LOAD_POLICIES', true),
        'routes' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_LOAD_ROUTES', true),
        'translations' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_LOAD_TRANSLATIONS', true),
        'views' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_LOAD_VIEWS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    |
    */

    'middleware' => [
        'default' => env('PLAYGROUND_DIRECTORY_RESOURCE_MIDDLEWARE_DEFAULT', ['web']),
        'auth' => env('PLAYGROUND_DIRECTORY_RESOURCE_MIDDLEWARE_AUTH', ['web', 'auth']),
        'guest' => env('PLAYGROUND_DIRECTORY_RESOURCE_MIDDLEWARE_GUEST', ['web']),
    ],

    /*
    |--------------------------------------------------------------------------
    | Policies
    |--------------------------------------------------------------------------
    |
    |
    */

    'policies' => [
        Playground\Directory\Models\Location::class => Playground\Directory\Resource\Policies\LocationPolicy::class,
        Playground\Directory\Models\LocationRevision::class => Playground\Directory\Resource\Policies\LocationPolicy::class,
        Playground\Directory\Models\Sublocation::class => Playground\Directory\Resource\Policies\SublocationPolicy::class,
        Playground\Directory\Models\SublocationRevision::class => Playground\Directory\Resource\Policies\SublocationPolicy::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Revisions
    |--------------------------------------------------------------------------
    |
    |
    */

    'revisions' => [
        'optional' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_ROUTES_OPTIONAL', false),
        'locations' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_REVISIONS_LOCATIONS', true),
        'sublocations' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_REVISIONS_SUBLOCATIONS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    |
    */

    'routes' => [
        'directory' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_DIRECTORY', true),
        'locations' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_LOCATIONS', true),
        'sublocations' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_SUBLOCATIONS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Sitemap
    |--------------------------------------------------------------------------
    |
    |
    */

    'sitemap' => [
        'enable' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_SITEMAP_ENABLE', true),
        'guest' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_SITEMAP_GUEST', true),
        'user' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_SITEMAP_USER', true),
        'view' => env('PLAYGROUND_DIRECTORY_RESOURCE_SITEMAP_VIEW', 'playground-directory-resource::sitemap'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Templates
    |--------------------------------------------------------------------------
    |
    |
    */

    'blade' => env('PLAYGROUND_DIRECTORY_RESOURCE_BLADE', 'playground-directory-resource::'),

    /*
    |--------------------------------------------------------------------------
    | Abilities
    |--------------------------------------------------------------------------
    |
    |
    */

    'abilities' => [
        'admin' => [
            'playground-directory-resource:*',
        ],
        'manager' => [
            'playground-directory-resource:location:*',
            'playground-directory-resource:sublocation:*',
        ],
        'user' => [
            'playground-directory-resource:location:view',
            'playground-directory-resource:location:viewAny',
            'playground-directory-resource:sublocation:view',
            'playground-directory-resource:sublocation:viewAny',
        ],
    ],
];
