<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'], // Pastikan sanctum ada di sini
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:3000', // Izinkan Next.js App Anda
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // Ini sangat penting!
];