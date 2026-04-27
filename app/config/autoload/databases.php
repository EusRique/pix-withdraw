<?php

return [
    'default' => [
        'driver' => 'mysql',
        'host' => 'mysql',
        'database' => 'pix',
        'username' => 'root',
        'password' => 'root',
        'port' => 3306,
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'pool' => [
            'min_connections' => 1,
            'max_connections' => 10,
            'connect_timeout' => 10.0,
            'wait_timeout' => 3.0,
            'heartbeat' => -1,
            'max_idle_time' => 60,
        ],
    ],
];