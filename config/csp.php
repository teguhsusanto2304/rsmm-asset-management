<?php

return [
    'enabled' => env('CSP_ENABLED', true),

    'report_uri' => env('CSP_REPORT_URI', null),

    'report_only' => false,

    'block_all_mixed_content' => false,

    'upgrade_insecure_requests' => false,

    'policies' => [
        'base_uri' => ["'self'"],
        'default_src' => ["'self'"],
        'script_src' => [
            "'self'",
            'https://cdn.tailwindcss.com',
            'https://fonts.googleapis.com',
            "'unsafe-inline'", // Hindari jika mungkin
        ],
        'style_src' => [
            "'self'",
            'https://fonts.googleapis.com',
            'https://cdn.tailwindcss.com',
            "'unsafe-inline'",
        ],
        'img_src' => [
            "'self'",
            'data:',
            'https:',
            'http:',
        ],
        'font_src' => [
            "'self'",
            'https://fonts.gstatic.com',
            'data:',
        ],
        'connect_src' => [
            "'self'",
            'https:',
        ],
        'media_src' => ["'self'"],
        'object_src' => ["'none'"],
        'frame_ancestors' => ["'self'"],
        'form_action' => ["'self'"],
    ],
];