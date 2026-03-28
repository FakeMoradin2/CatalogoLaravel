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

    /*
    | Peticiones HTTP al backend CatalogoAPI (timeout para no bloquear la UI).
    */
    'remote_api' => [
        'timeout' => (int) env('API_HTTP_TIMEOUT', 15),
        'connect_timeout' => (int) env('API_HTTP_CONNECT_TIMEOUT', 5),
    ],

    'productos_api' => [
        'url' => env('PRODUCTOS_API_URL', 'http://127.0.0.1:8000/api'),
    ],

    'auth_api' => [
        'url' => env('AUTH_API_URL', env('PRODUCTOS_API_URL', 'http://127.0.0.1:8000/api')),
        'register' => env('AUTH_API_REGISTER_ENDPOINT', '/register'),
        'login' => env('AUTH_API_LOGIN_ENDPOINT', '/login'),
        'logout' => env('AUTH_API_LOGOUT_ENDPOINT', '/logout'),
        'profile' => env('AUTH_API_PROFILE_ENDPOINT', '/profile'),
        'avatar' => env('AUTH_API_AVATAR_ENDPOINT', '/profile/avatar'),
        'password' => env('AUTH_API_PASSWORD_ENDPOINT', '/profile/password'),
    ],

    'orders_api' => [
        'url' => env('ORDERS_API_URL', env('AUTH_API_URL', env('PRODUCTOS_API_URL', 'http://127.0.0.1:8000/api'))),
        'index' => env('ORDERS_API_INDEX_ENDPOINT', '/orders'),
        'store' => env('ORDERS_API_STORE_ENDPOINT', '/orders'),
        'show' => env('ORDERS_API_SHOW_ENDPOINT', '/orders/{id}'),
        'cancel' => env('ORDERS_API_CANCEL_ENDPOINT', '/orders/{id}/cancel'),
        'payment_prepare' => env('ORDERS_API_PAYMENT_PREPARE_ENDPOINT', '/orders/{id}/payments/prepare'),
        'payment_confirm' => env('ORDERS_API_PAYMENT_CONFIRM_ENDPOINT', '/orders/{id}/payments/confirm'),
        'payment_checkout_session' => env('ORDERS_API_PAYMENT_CHECKOUT_SESSION_ENDPOINT', '/orders/{id}/payments/checkout-session'),
        'payment_checkout_verify' => env('ORDERS_API_PAYMENT_CHECKOUT_VERIFY_ENDPOINT', '/orders/{id}/payments/checkout-verify'),
        'coupon_validate' => env('ORDERS_API_COUPON_VALIDATE_ENDPOINT', '/coupon/validate'),
    ],

];
