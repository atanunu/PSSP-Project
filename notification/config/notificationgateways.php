<?php
// config/notificationgateways.php
// Last updated: 2025-02-06
// Contains configuration settings for SMS, Email, OTP, and Push notification gateways.

return [
    'http_sms_url' => env('HTTP_SMS_URL', 'https://example.com/api/sms'),
    'twilio' => [
        'sid'   => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'from'  => env('SMS_SENDER_ID', env('TWILIO_FROM')),
    ],
    'africastalking' => [
        'username' => env('AFRICASTALKING_USERNAME'),
        'api_key'  => env('AFRICASTALKING_API_KEY'),
    ],
    'aws' => [
        'region'           => env('AWS_DEFAULT_REGION', 'us-east-1'),
        'key'              => env('AWS_ACCESS_KEY_ID'),
        'secret'           => env('AWS_SECRET_ACCESS_KEY'),
        'ses_source_email' => env('EMAIL_FROM', env('AWS_SES_SOURCE_EMAIL', 'noreply@example.com')),
    ],
    'mocit' => [
        'api_secret'       => env('MOCIT_API_SECRET'),
        'whatsapp_account' => env('MOCIT_WHATSAPP_ACCOUNT'),
        'sim'              => env('MOCIT_SIM', 1),
        'device'           => env('MOCIT_DEVICE'),
        'gateway_url'      => env('MOCIT_GATEWAY_URL', 'https://zender.in.mocit.com/api'),
    ],
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_API_KEY'),
    ],
    'gmail' => [
        'access_token' => env('GMAIL_ACCESS_TOKEN'),
    ],
    'sendgrid' => [
        'api_key' => env('SENDGRID_API_KEY'),
        'from'    => env('EMAIL_FROM', env('SENDGRID_FROM', 'noreply@example.com')),
    ],
    'termii' => [
        'api_key'   => env('TERMII_API_KEY'),
        'sender_id' => env('SMS_SENDER_ID', env('TERMII_SENDER_ID', 'Termii')),
    ],
    'push' => [
        'google_fcm' => [
            'server_key' => env('GOOGLE_FCM_SERVER_KEY'),
        ],
        'onesignal' => [
            'app_id'       => env('ONESIGNAL_APP_ID'),
            'rest_api_key' => env('ONESIGNAL_REST_API_KEY'),
        ],
        'wonderpush' => [
            'api_key' => env('WONDERPUSH_API_KEY'),
        ],
        'airship' => [
            'app_key'       => env('AIRSHIP_APP_KEY'),
            'master_secret' => env('AIRSHIP_MASTER_SECRET'),
        ],
        'pushengage' => [
            'api_key' => env('PUSHENGAGE_API_KEY'),
        ],
    ],
];
