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
    | Google Business Profile reviews. Fill these in .env once the client grants
    | access + Google approves API access; until then the portal shows the
    | sample reviews loaded via `php artisan reviews:seed-sample`.
    */
    'google_reviews' => [
        // Option A — Business Profile API (all reviews, auto-updating)
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'refresh_token' => env('GOOGLE_REFRESH_TOKEN'),
        'account_id'    => env('GOOGLE_GBP_ACCOUNT_ID'),   // e.g. accounts/123456789
        'location_id'   => env('GOOGLE_GBP_LOCATION_ID'),  // e.g. locations/987654321

        // Option B — Places API interim (rating + latest 5 reviews)
        'places_api_key' => env('GOOGLE_PLACES_API_KEY'),
        'place_id'       => env('GOOGLE_PLACE_ID'),
    ],

];
