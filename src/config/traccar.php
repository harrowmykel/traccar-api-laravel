<?php

return [

    'default' => env('TRACCAR_DEFAULT_ACCOUNT', 'debug'),

    'servers' => [
        'debug' => [
            'url' => env('TRACCAR_DEBUG_URL', 'https://demo.traccar.org:443'),

            /**
             * The authentication type to use for the Traccar API. Options are 'none', 'email', or 'token'.
             */
            'auth_type' => 'email',

            /**
             * The username and password to use for email authentication. You can set these in your .env file.
             */
            'username' => env('TRACCAR_DEBUG_USERNAME', 'admin'),
            'password' => env('TRACCAR_DEBUG_PASSWORD', 'admin'),
            /**
             * If you are using token authentication, you can set the auth_token here or in your .env file.
             */
            'auth_token' => env('TRACCAR_DEBUG_AUTH_TOKEN', null),
        ],
    ]
];
