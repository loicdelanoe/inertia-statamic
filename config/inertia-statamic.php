<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Multi-lingual Support
    |--------------------------------------------------------------------------
    |
    | Enable or disable multi-lingual support for your InertiaStatamic forms
    | and pages. When set to true, the package will handle locale-specific
    | data, translations, and routing. Set to false if your site is single-language.
    |
    | Default: false
    |
    */

    'multi_lingual' => env('INERTIA_STATAMIC_MULTI_LINGUAL', false),

    /*
    |--------------------------------------------------------------------------
    | Supported Locales
    |--------------------------------------------------------------------------
    |
    | Define the list of locales supported by your site. This array should
    | contain all locales that your multi-lingual content will use.
    | Only relevant if 'multi_lingual' is set to true.
    |
    | Example: ['en', 'fr', 'es']
    |
    */

    'supported_locales' => ['en', 'fr'],

    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    |
    | This prefix will be applied to all routes defined by the InertiaStatamic
    | package, including form submission endpoints. Using a unique prefix
    | helps prevent route collisions with your application's existing routes.
    |
    | You can override this value via the environment variable:
    | INERTIA_STATAMIC_ROUTE_PREFIX
    |
    | Example: 'inertia-statamic'
    |
    */

    'route_prefix' => env('INERTIA_STATAMIC_ROUTE_PREFIX', 'inertia-statamic'),
];
