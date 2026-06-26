<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'profissionais',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'profissionais',
        ],
    ],

    'providers' => [
        'profissionais' => [
            'driver' => 'eloquent',
            'model' => App\Models\Profissional::class,
        ],
    ],

    'passwords' => [
        'profissionais' => [
            'provider' => 'profissionais',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
