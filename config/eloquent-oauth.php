<?php

use App\Models\User;

return [
    'model' => User::class,
    'table' => 'oauth_identities',
    'providers' => [
        'google' => [
            'client_id' => env('GOOGLE_OAUTH_ID'),
            'client_secret' => env('GOOGLE_OAUTH_SECRET'),
            'redirect_uri' => env('APP_URL').'login/google/done',
            'scope' => [],
        ],
    ],
];
