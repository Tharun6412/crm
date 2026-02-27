<?php

return [

    /**
     * Multiple SMS gateway channels configuration
     * 
     * 1. w2p - Way2plus
     * 2. cst - Clever Stack
     */

    'gateways' => [
        'w2p' => [
            'api_url' => env('W2P_API_URL'),
            'username' => env('W2P_USERNAME'),
            'password' => env('W2P_PASSWORD'),
            'sender' => env('W2P_SENDER'),
        ],
        'cst' => [
            'api_url' => env('CST_API_URL'),
            'username' => env('CST_USERNAME'),
            'password' => env('CST_PASSWORD'),
            'sender' => env('CST_SENDER'),
        ]
    ]
];