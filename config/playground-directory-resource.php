<?php

/**
 * Playground
 */

declare(strict_types=1);
use Illuminate\Database\Eloquent\Model;
use Playground\Auth\Policies\Policy;
use Playground\Directory\Models\Contact;
use Playground\Directory\Models\ContactRevision;
use Playground\Directory\Models\Location;
use Playground\Directory\Models\LocationRevision;
use Playground\Directory\Resource\Policies\ContactPolicy;
use Playground\Directory\Resource\Policies\LocationPolicy;

/**
 * Playground: Directory Resource Configuration and Environment Variables
 *
 * @return array{
 *       about: bool,
 *       layout: string,
 *       load: array{
 *           policies: bool,
 *           routes: bool,
 *           translations: bool,
 *           views: bool
 *       },
 *       matrix: array{
 *           enabled: bool,
 *       },
 *       middleware: array{
 *           default: string|string[],
 *           auth: string|string[],
 *           guest: string|string[]
 *       },
 *       policies: array<
 *           class-string<Model>,
 *           class-string<Policy>
 *       >,
 *       revisions: array{
 *           options: bool,
 *           contacts: bool,
 *           locations: bool,
 *       },
 *       routes: array{
 *           directory: bool,
 *           contacts: bool,
 *           locations: bool,
 *       },
 *       blade: string,
 *       cache: array{
 *           enable: bool,
 *           contact: bool,
 *           contact_store: string,
 *           contact_ttl: int,
 *           location: bool,
 *           location_store: string,
 *           location_ttl: int,
 *       },
 *       abilities: array<string, string[]>,
 *       sitemap: array{
 *            enable: bool,
 *            guest: bool,
 *            user: bool,
 *            view: string
 *       }
 *   }
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
    | Matrix
    |--------------------------------------------------------------------------
    |
    |
    */

    'matrix' => [
        'enabled' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_MATRIX_ENABLED', false),
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
        Contact::class => ContactPolicy::class,
        ContactRevision::class => ContactPolicy::class,
        Location::class => LocationPolicy::class,
        LocationRevision::class => LocationPolicy::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Revisions
    |--------------------------------------------------------------------------
    |
    |
    */

    'revisions' => [
        'optional' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_REVISIONS_OPTIONAL', false),
        'contacts' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_REVISIONS_CONTACTS', true),
        'locations' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_REVISIONS_LOCATIONS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    |
    */

    'routes' => [
        'directory' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_ROUTES_DIRECTORY', true),
        'contacts' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_ROUTES_CONTACTS', true),
        'locations' => (bool) env('PLAYGROUND_DIRECTORY_RESOURCE_ROUTES_LOCATIONS', true),
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
            'playground-directory-resource:contact:*',
            'playground-directory-resource:location:*',
        ],
        'user' => [
            'playground-directory-resource:contact:view',
            'playground-directory-resource:contact:viewAny',
            'playground-directory-resource:location:view',
            'playground-directory-resource:location:viewAny',
        ],
    ],
];
