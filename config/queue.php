<?php

return [

    'default' => env('QUEUE_DRIVER', 'database'),

    'connections' => [

        'database' => [
            'driver' => 'database',
            'table'  => 'jobs',
            'queue'  => 'default',
            'expire' => 300,
        ],

    ],

    'failed' => [
        'database' => env('DB_CONNECTION', 'mysql'),
        'table'    => 'failed_jobs',
    ],

];
