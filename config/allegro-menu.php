<?php

return [
    'merge_to_navigation' => true,

    'navs' => [
        'sidebar' =>[
            [
                'name' => 'Allegro',
                'link' => '/allegro/accounts',
                'icon' => 'speed',
                'key' => 'allegro::menus.allegro',
                'children_top' => [
                    [
                        'name' => 'Accounts',
                        'link' => '/allegro/accounts',
                        'key' => 'allegro::menus.accounts',
                    ],
                    [
                        'name' => 'Applications',
                        'link' => '/allegro/applications',
                        'key' => 'allegro::menus.applications',
                    ],
                ],
                'children' => [
                    [
                        'name' => 'Accounts',
                        'link' => '/allegro/accounts',
                        'key' => 'allegro::menus.accounts',
                    ],
                    [
                        'name' => 'Applications',
                        'link' => '/allegro/applications',
                        'key' => 'allegro::menus.applications',
                    ],
                ],
            ]
        ],
        'adminSidebar' =>[
            [
                'name' => 'Settings',
                'link' => '/admin/allegro/settings',
                'icon' => 'speed',
                'permissions' => 'manage_allegro_settings',
                'key' => 'allegro::menus.allegro',
                'children_top' => [
                    [
                        'name' => 'Settings',
                        'link' => '/admin/allegro/settings',
                        'key' => 'allegro::menus.settings',
                    ],
                ],
                'children' => [
                    [
                        'name' => 'Settings',
                        'link' => '/admin/allegro/settings',
                        'key' => 'allegro::menus.settings',
                    ],
                ],
            ]
        ]
    ]
];
