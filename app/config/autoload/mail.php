<?php

return [
    'default' => [
        'transport' => [
            'driver' => 'smtp',
            'host' => 'mailhog',
            'port' => 1025,
            'username' => null,
            'password' => null,
            'encryption' => null,
        ],
        'from' => [
            'address' => 'no-reply@pix.com',
            'name' => 'Pix System',
        ],
    ],
];