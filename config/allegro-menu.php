<?php

return [
    'merge_to_navigation' => true,

    'navs' => [
        'sidebar' =>[

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
