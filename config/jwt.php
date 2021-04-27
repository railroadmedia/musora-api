<?php

return [

    'secret' => env('JWT_SECRET','jwt_secret_key_123_mobile'),

    'keys' => [

        'public' => env('JWT_PUBLIC_KEY'),

        'private' => env('JWT_PRIVATE_KEY'),

        'passphrase' => env('JWT_PASSPHRASE'),
    ],

    /**
     * Specify the length of time (in minutes) that the token will be valid for. Defaults to 1 hour
     */
    'ttl' => env('JWT_TTL', 60),

    'refresh_ttl' => null,

    'algo' => env('JWT_ALGO', 'HS256'),

    'required_claims' => [
        'iss',
        'exp',
        'nbf',
        'sub',
        'jti',
    ],

    'persistent_claims' => [
        // 'foo',
        // 'bar',
    ],

    'lock_subject' => true,

    'leeway' => 60,

    'blacklist_enabled' => false,

    'blacklist_grace_period' => env('JWT_BLACKLIST_GRACE_PERIOD', 0),

    'decrypt_cookies' => false,

    'providers' => [
        'jwt' => \Tymon\JWTAuth\Providers\JWT\Namshi::class,

        'auth' => \Tymon\JWTAuth\Providers\Auth\Illuminate::class,

        'storage' => \Tymon\JWTAuth\Providers\Storage\Illuminate::class,
    ],
];