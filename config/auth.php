<?php

return [
    
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'registers',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'registers',
        ],
    ],

    'providers' => [
        'registers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Register::class,
        ],
    ],

    'passwords' => [
        'registers' => [
            'provider' => 'registers',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];