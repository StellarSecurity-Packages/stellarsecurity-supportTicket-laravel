<?php

return [
    'base_url' => env('STELLAR_SUPPORT_BASE_URL', 'http://127.0.0.1:8000'),
    'timeout' => (int) env('STELLAR_SUPPORT_TIMEOUT', 15),

    // Basic auth
    'basic_user' => env('STELLAR_SUPPORT_BASIC_USER', ''),
    'basic_pass' => env('STELLAR_SUPPORT_BASIC_PASS', ''),

    // API prefix (your routes live under /api/v1/...)
    'prefix' => env('STELLAR_SUPPORT_API_PREFIX', '/api/v1'),
];
