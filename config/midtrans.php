<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
    // SSL verification: disabled ONLY in local/staging via MIDTRANS_DISABLE_SSL=true
    // Never set this to true in production — MITM vulnerability.
    'curl_options' => env('MIDTRANS_DISABLE_SSL', false) ? [
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
    ] : [],
];