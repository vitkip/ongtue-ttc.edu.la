<?php

return [

    /*
    |--------------------------------------------------------------------------
    | System maintenance page (Filament)
    |--------------------------------------------------------------------------
    |
    | Used by App\Filament\Pages\SystemMaintenance to clear caches and run the
    | front-end asset build from the admin panel.
    |
    | On this XAMPP box Apache runs as the "daemon" user, which has a minimal
    | PATH and cannot resolve "npm" on its own, so npm_path must be absolute.
    | Override any of these in .env.
    |
    */

    'npm_path' => env('ADMIN_NPM_PATH', 'npm'),

    // Extra directories prepended to PATH when running the build (so npm can
    // find node, and node can find git etc.).
    'build_path_dirs' => array_values(array_filter([
        env('ADMIN_NODE_BIN_DIR'),
        '/opt/homebrew/bin',
        '/usr/local/bin',
        '/usr/bin',
        '/bin',
    ])),

    // Seconds before the build process is killed.
    'build_timeout' => (int) env('ADMIN_BUILD_TIMEOUT', 600),

    // Writable HOME / npm cache dir for the daemon user (relative to storage/app).
    'build_home' => storage_path('app/build-home'),
];
