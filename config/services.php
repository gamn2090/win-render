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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'google' => [
      'client_id' => env('GOOGLE_CLIENT_ID'),
      'client_secret' => env('GOOGLE_CLIENT_SECRET'),
      'redirect' => env('GOOGLE_REDIRECT_URI')
    ],

    'google_maps' => [
        'key' => env('GOOGLE_MAPS_API_KEY'),
    ],

    'cron' => [
        // Shared secret for the /cron/run-schedule endpoint — this app has no
        // real server crontab (containerized, apache2-foreground as PID 1), so
        // an external HTTP-pinging cron service (e.g. cron-job.org) triggers
        // `php artisan schedule:run` once a minute instead.
        'secret' => env('CRON_SECRET'),
    ],

    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
        'payment_link_price' => env('STRIPE_PAYMENT_LINK_PRICE'),
        'checkout_success_url' => env(
            'STRIPE_CHECKOUT_SUCCESS_URL',
            'https://weddinginsidersnetwork.com/vendor/subscription/confirm/{CHECKOUT_SESSION_ID}'
        ),
        // The vendor subscription's Stripe Price id — kept out of code since
        // test-mode and live-mode Stripe accounts each have their own price
        // ids for "the same" product (test mode was previously hardcoded to
        // a live-only price id, which silently broke Checkout in test mode).
        'vendor_price_id' => env('STRIPE_VENDOR_PRICE_ID'),
    ],

];
