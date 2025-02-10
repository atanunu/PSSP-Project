Below is an example implementation that adds a new controller to handle sending notifications to multiple recipients (separated by commas). In this example, we create a new controller called `MultiRecipientController.php` with two endpoints: one for SMS notifications and one for Email notifications. We also update the routes file so that you can call these endpoints.

Below is an updated version of the multi‐recipient controller that now also supports sending push notifications to multiple recipients. This example assumes that multiple recipients for push notifications are provided as a comma-separated list. The controller splits the list, sends a push notification to each recipient, and returns a consolidated response.

---

### 1. Updated MultiRecipientController.php

**File:** `app/Http/Controllers/MultiRecipientController.php`

```php
<?php
// app/Http/Controllers/MultiRecipientController.php
// Last updated: 2025-02-06
//
// This controller handles sending notifications to multiple recipients.
// Recipients should be provided as a comma-separated string.
// This controller now supports SMS, Email, and Push notifications.

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SmsNotification;
use App\Notifications\EmailNotification;
use App\Notifications\PushNotification;

class MultiRecipientController extends Controller
{
    /**
     * Send SMS notifications to multiple recipients.
     *
     * Expected Request parameters:
     * - to: A comma-separated string of phone numbers.
     * - message: The SMS message content.
     * - gateway (optional): The SMS gateway to use.
     *
     * Example: +2348164898637,+2347080400123,+2349092002000
     */
    public function sendSmsMulti(Request $request)
    {
        $validated = $request->validate([
            'to'      => 'required|string',
            'message' => 'required|string',
            'gateway' => 'sometimes|string',
        ]);

        // Split the "to" string by commas and trim spaces.
        $recipients = array_map('trim', explode(',', $validated['to']));

        $results = [];
        foreach ($recipients as $recipient) {
            // Use Laravel Notification system to send SMS for each recipient.
            $result = Notification::route('sms', $recipient)
                ->notify(new SmsNotification($validated['message'], $validated['gateway'] ?? null));

            // Collect results (if you wish to process response data).
            $results[] = [
                'recipient' => $recipient,
                'result'    => $result,
            ];
        }

        return response()->json([
            'status'  => 'SMS notifications sent to multiple recipients',
            'details' => $results,
        ]);
    }

    /**
     * Send Email notifications to multiple recipients.
     *
     * Expected Request parameters:
     * - to: A comma-separated string of email addresses.
     * - subject: The email subject.
     * - body: The email body content.
     * - gateway (optional): The Email gateway to use.
     *
     * Example: atanunu@esebun.com,john@esebun.com,mark@mark.com
     */
    public function sendEmailMulti(Request $request)
    {
        $validated = $request->validate([
            'to'      => 'required|string',
            'subject' => 'required|string',
            'body'    => 'required|string',
            'gateway' => 'sometimes|string',
        ]);

        // Split the "to" string by commas and trim spaces.
        $recipients = array_map('trim', explode(',', $validated['to']));

        $results = [];
        foreach ($recipients as $recipient) {
            // Use Laravel Notification system to send Email for each recipient.
            $result = Notification::route('email', $recipient)
                ->notify(new EmailNotification($validated['subject'], $validated['body'], $validated['gateway'] ?? null));

            // Collect results.
            $results[] = [
                'recipient' => $recipient,
                'result'    => $result,
            ];
        }

        return response()->json([
            'status'  => 'Email notifications sent to multiple recipients',
            'details' => $results,
        ]);
    }

    /**
     * Send Push notifications to multiple recipients.
     *
     * Expected Request parameters:
     * - to: A comma-separated string of device tokens or topics.
     * - payload: An array containing the push notification data (e.g., title, body, and optional data).
     * - gateway (optional): The push notification gateway to use.
     *
     * Example: "recipient1_token,recipient2_token,recipient3_token"
     */
    public function sendPushMulti(Request $request)
    {
        $validated = $request->validate([
            'to'      => 'required|string',
            'payload' => 'required|array',
            'gateway' => 'sometimes|string',
        ]);

        // Split the "to" string by commas and trim spaces.
        $recipients = array_map('trim', explode(',', $validated['to']));

        $results = [];
        foreach ($recipients as $recipient) {
            // Use Laravel Notification system to send Push notifications for each recipient.
            $result = Notification::route('push', $recipient)
                ->notify(new PushNotification($validated['payload'], $validated['gateway'] ?? null));

            // Collect results.
            $results[] = [
                'recipient' => $recipient,
                'result'    => $result,
            ];
        }

        return response()->json([
            'status'  => 'Push notifications sent to multiple recipients',
            'details' => $results,
        ]);
    }
}
```

---

### 2. Update Routes

In your **routes/api.php** file, add the new endpoints for multiple recipients:

```php
<?php
// routes/api.php
// Last updated: 2025-02-06
// API routes for sending notifications, including endpoints for multiple recipients.

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PushNotificationController;
use App\Http\Controllers\NotificationLogController;
use App\Http\Controllers\NotificationMessageController;
use App\Http\Controllers\AnotherController;
use App\Http\Controllers\MultiRecipientController;

Route::post('/sms/send', [NotificationController::class, 'sendSms']);
Route::post('/email/send', [NotificationController::class, 'sendEmail']);
Route::post('/otp/send', [NotificationController::class, 'sendOtp']);
Route::post('/otp/verify', [NotificationController::class, 'verifyOtp']);
Route::post('/push/send', [PushNotificationController::class, 'sendPush']);

// Endpoints for managing logs and messages (existing endpoints)
// ...

// New endpoints for sending to multiple recipients:
Route::post('/multi/sms/send', [MultiRecipientController::class, 'sendSmsMulti']);
Route::post('/multi/email/send', [MultiRecipientController::class, 'sendEmailMulti']);
Route::post('/multi/push/send', [MultiRecipientController::class, 'sendPushMulti']);

// Example endpoints for custom usage (if any)
// ...
```

---

### 3. Usage Instructions for Multiple Recipients

#### SMS Notifications to Multiple Recipients

**Endpoint:** `POST /api/multi/sms/send`

**Example Payload:**
```json
{
  "to": "+2348164898637,+2347080400123,+2349092002000",
  "message": "Hello, this is a multi-recipient SMS test.",
  "gateway": "twilio"  // Optional; if omitted, default gateway is used.
}
```

#### Email Notifications to Multiple Recipients

**Endpoint:** `POST /api/multi/email/send`

**Example Payload:**
```json
{
  "to": "atanunu@esebun.com,john@esebun.com,mark@mark.com",
  "subject": "Test Email to Multiple Recipients",
  "body": "Hello, this is a test email for multiple recipients.",
  "gateway": "smtp"  // Optional; if omitted, default gateway is used.
}
```

#### Push Notifications to Multiple Recipients

**Endpoint:** `POST /api/multi/push/send`

**Example Payload:**
```json
{
  "to": "recipient1_token,recipient2_token,recipient3_token",
  "payload": {
     "title": "Test Push",
     "body": "This is a test push notification.",
     "data": {"key": "value"}
  },
  "gateway": "google_fcm"  // Optional; if omitted, default gateway is used.
}
```

When you send a request to any of these endpoints, the controller:
1. Validates the input.
2. Splits the recipient string by commas.
3. Loops through each recipient and sends the notification individually using Laravel’s Notification system.
4. Returns a consolidated JSON response containing details for each recipient.

---

### 4. Composer Dependencies

Make sure you have installed the following dependencies:

```bash
composer require twilio/sdk
composer require aws/aws-sdk-php
composer require sendgrid/sendgrid
composer require mailgun/mailgun-php
composer require guzzlehttp/guzzle
```

---

### 5. Final Steps

1. **Installation:**
   - Create a Laravel project and copy the files into their corresponding directories.
   - Update your `.env` file with your API keys and configuration settings.
   - Run `php artisan migrate` to create the necessary database tables.
   - Install required composer dependencies as shown above.
   - Start your server using `php artisan serve`.

2. **Testing:**
   - Use Postman or cURL to test the endpoints for multiple recipients (SMS, Email, and Push).
   - Verify that the responses include a status and details for each recipient.

3. **Customization:**
   - Adjust the default gateway settings and API keys in your `.env` and configuration files as needed.
   - Extend the notification classes and services if additional functionality is required.

---

## License

This project is open-sourced under the [MIT License](LICENSE).

---

## Conclusion

This updated Laravel multi-gateway notification system now supports sending notifications to multiple recipients for SMS, Email, and Push. The modular design and clear endpoints make it easy to integrate with multiple providers and customize as needed.

Happy Coding!
```

---

Simply copy the entire content above into your `README.md` file in your project's root directory. This comprehensive README file now includes instructions and endpoints for sending notifications to multiple recipients for SMS, Email, and Push, along with all required composer dependencies and usage details.

Happy coding!
