<?php

return [
    'google' => [
        'geo' => [
            'token' => env('GOOGLE_GEO_TOKEN'),
        ],
    ],

    'smstoken' => [
        'token' => env('SMS_TOKEN'),
    ],

    'mobizon' => [
        'token' => env('SMS_TOKEN_MOBIZON'),
    ],

    'onesignal' => [
        'token' => env('ONE_SIGNAL_TOKEN'),
        'app_id' => env('ONE_SIGNAL_APP_ID'),
    ],
];