<?php
return [
    // this resources has been auto load to layout
    'dist' => [
        'js/main.js',
        'js/main.legacy.js',
        'css/main.css',
    ],
    'routes' => [
        // all routes is active
        'active' => true,
        // section installations
        'installation' => [
            'active' => true,
            'prefix' => '/installation/allegro',
            'name_prefix' => 'allegro.installation.',
            // this routes has beed except for installation module
            'expect' => [
                'module-assets.assets',
                'allegro.installation.index',
                'allegro.installation.store',
            ]
        ],

        'setting' => [
            'active' => true,
            'prefix' => '/admin/allegro/settings',
            'name_prefix' => 'allegro.setting.',
            'middleware' => [
                'web',
                'auth',
                'can:manage_allegro_settings'
            ]
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Use permissions in application.
    |--------------------------------------------------------------------------
    |
    | This permission has been insert to database with migrations
    | of module permission.
    |
    */
    'permissions' =>[
        'install_packages', 'manage_allegro_settings',
    ],

    /*
    |--------------------------------------------------------------------------
    | Can merge permissions to module permission
    |--------------------------------------------------------------------------
    */
    'merge_permissions' => true,

    'installation' => [
        'auto_redirect' => [
            // user with this permission has been automation redirect to
            // installation package
            'permission' => 'install_packages'
        ]
    ],

    'database' => [
        'tables' => [
            'users' => 'users',
            'allegro_settings' => 'allegro_settings',
        ]
    ],

];
