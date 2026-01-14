<?php
// English comments only.
return [
    'base_url' => env('STELLAR_SUPPORT_BASE_URL', ''),
    'api_prefix' => env('STELLAR_SUPPORT_API_PREFIX', '/api/v1'),

    // Basic Auth
    'basic_user' => env('STELLAR_SUPPORT_BASIC_USER', ''),
    'basic_pass' => env('STELLAR_SUPPORT_BASIC_PASS', ''),

    // HTTP settings
    'timeout' => (int) env('STELLAR_SUPPORT_TIMEOUT', 15),
];
