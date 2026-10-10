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
            'primary'   => '#E9500E',
            'secondary' => '#2d3441',

            'v-bg-primary'   => '#1f2937',
            'v-bg-secondary' => '#111827',
            'v-bg-floating'  => '#1f2937',
        ],
    ],

    'ui' => [
        'aligned_form_controls' => false,
        'explicit_table_clear' => false,
        'translations' => false,
        'numbered_pagination' => false,
        'wide_column_picker' => false,
        'hide_reset_on_required_selects' => false,
    ],

    'components' => [
        'buttons' => true,
        'forms'   => true,
        'tables'  => true,
        'modals'  => true,
        'layout'  => true,
    ],

    'sidebar' => [
        'brand' => 'Verdant',
        'profile_routes' => ['profile.show', 'profile.edit', 'profile.index', 'profile'],
        'settings_routes' => ['settings.index', 'settings.edit', 'settings.show', 'settings'],
        'logout_routes' => ['logout'],
        'user_image_attributes' => ['profile_photo_url', 'avatar_url', 'avatar'],
    ],

    'advanced' => [
        'use_scoped_wrapper' => true,
        'views_path' => null,
    ],
];
