<?php
// config/NotificationResponse.php
// Last updated: 2025-02-06
// Contains response templates and HTTP status codes for API responses.

return [
    'success' => [
        'http_code' => 200,
        'status' => 'success',
        'response_code' => '00',
        'message' => 'Notification sent successfully.',
    ],
    'error' => [
        'http_code' => 500,
        'status' => 'failed',
        'response_code' => '09',
        'message' => 'Failed to send notification.',
    ],
    'missing_parameters' => [
        'http_code' => 400,
        'status' => 'failed',
        'response_code' => '99',
        'message' => 'Missing required parameters in API request.',
    ],
];
