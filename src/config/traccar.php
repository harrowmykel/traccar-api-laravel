<?php

return [

    'default' => env('TRACCAR_DEFAULT_ACCOUNT', 'default'),

    'servers' => [
        'debug' => [
            'url' => env('TRACCAR_DEBUG_URL', 'https://demo.traccar.org:443'),
            'username' => env('TRACCAR_DEBUG_USERNAME', 'admin'),
            'password' => env('TRACCAR_DEBUG_PASSWORD', 'admin'),
            'auth_type' => 'email'
        ],
        'traccar' => [
            'url' => env('TRACCAR_URL', 'http://localhost:8082'),
            'username' => env('TRACCAR_USERNAME', 'admin'),
            'password' => env('TRACCAR_PASSWORD', 'admin'),
        ]
    ]
];
