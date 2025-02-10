<?php
// config/notifications.php
// Last updated: 2025-02-06
// Default gateway selections for notifications.

return [
    'default_sms_gateway'   => env('DEFAULT_SMS_GATEWAY', 'twilio'),
    'default_email_gateway' => env('DEFAULT_EMAIL_GATEWAY', 'smtp'),
    'default_otp_gateway'   => env('DEFAULT_OTP_GATEWAY', 'mocit_sms'),
    'default_push_gateway'  => env('DEFAULT_PUSH_GATEWAY', 'google_fcm'),
];
