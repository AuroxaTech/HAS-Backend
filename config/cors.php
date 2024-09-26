<?php


    return [
        'paths' => ['api/*'], // Apply CORS to API routes
        'allowed_methods' => ['*'],
        'allowed_origins' => ['*'], // Or specify allowed origins
        'allowed_origins_patterns' => [],
        'allowed_headers' => ['*'],
        'exposed_headers' => [],
        'max_age' => 0,
        'supports_credentials' => false,
    ];
    
