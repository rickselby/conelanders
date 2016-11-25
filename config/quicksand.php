<?php

return [
    // Days before deleting soft deleted content
    'days' => 7,

    // Whether to log the number of soft deleted records per model
    'log' => true,

    // List of models to run Quicksand on
    'models' => [
        \App\Models\DirtRally\DirtResult::class,
    ]
];
