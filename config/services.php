<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'productos_api' => [
        'url' => env('PRODUCTOS_API_URL', 'http://127.0.0.1:8001/api'),
    ],

    'auth_api' => [
        'url' => env('AUTH_API_URL', env('PRODUCTOS_API_URL', 'http://127.0.0.1:8001/api')),
        'register' => env('AUTH_API_REGISTER_ENDPOINT', '/register'),
        'login' => env('AUTH_API_LOGIN_ENDPOINT', '/login'),
        'logout' => env('AUTH_API_LOGOUT_ENDPOINT', '/logout'),
        'profile' => env('AUTH_API_PROFILE_ENDPOINT', '/profile'),
        'avatar' => env('AUTH_API_AVATAR_ENDPOINT', '/profile/avatar'),
        'password' => env('AUTH_API_PASSWORD_ENDPOINT', '/profile/password'),
    ],

    'orders_api' => [
        'url' => env('ORDERS_API_URL', env('AUTH_API_URL', env('PRODUCTOS_API_URL', 'http://127.0.0.1:8001/api'))),
        'index' => env('ORDERS_API_INDEX_ENDPOINT', '/orders'),
        'store' => env('ORDERS_API_STORE_ENDPOINT', '/orders'),
        'show' => env('ORDERS_API_SHOW_ENDPOINT', '/orders/{id}'),
        'cancel' => env('ORDERS_API_CANCEL_ENDPOINT', '/orders/{id}/cancel'),
    ],

];
