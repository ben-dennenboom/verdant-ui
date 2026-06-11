<?php

return [
    'assets' => [
        'include_fontawesome' => true,
        'include_alpine' => true,
        'path_prefix' => 'vendor/verdant',
    ],

    'prefix' => [
        'component' => 'v-',
        'css'       => 'v-',
    ],

    'theme' => [
        'colors' => [
            'primary'   => [
                'default' => '#E9500E',
            ],
            'secondary' => [
                'default' => '#2d3441',
            ],

            'v-bg-primary'   => '#ffffff',
            'v-bg-secondary' => '#f9fafb',
            'v-bg-floating'  => '#ffffff',
        ],

        'dark_colors' => [
            'v-bg-primary'   => '#1f2937',
            'v-bg-secondary' => '#111827',
            'v-bg-floating'  => '#1f2937',
        ],
    ],

    'components' => [
        'buttons' => true,
        'forms'   => true,
        'tables'  => true,
        'modals'  => true,
        'layout'  => true,
    ],

    'advanced' => [
        'use_scoped_wrapper' => true,
        'views_path' => null,
    ],
];
