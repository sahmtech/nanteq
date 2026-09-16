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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'ai_model' => [
        'base_url' => env('AI_MODEL_BASE_URL'),
        'token' => env('AI_MODEL_TOKEN'),
        'ca' => env('AI_MODEL_CA'),
        'verify' => filter_var(env('AI_MODEL_VERIFY', false), FILTER_VALIDATE_BOOLEAN),
        'timeout' => (int) env('AI_MODEL_TIMEOUT', 90),
        'pronunciation_model' => env('AI_MODEL_PRONUNCIATION', 'ar-pronunciation'),
        'store_uploads' => filter_var(env('AI_MODEL_STORE_UPLOADS', false), FILTER_VALIDATE_BOOLEAN),
        'review_webhook' => env('PRONUNCIATION_REVIEW_WEBHOOK'),
        'models' => [
            0 => [
                'driver' => 'pronunciation',
                'path' => '/v1/pronunciation',
                'stage' => 1,
            ],
            1 => [
                'driver' => 'pronunciation',
                'path' => '/v1/pronunciation',
                'stage' => 2,
            ],
            2 => [
                'driver' => 'stt',
                'path' => '/v1/stt',
            ],
        ],
    ],

];
