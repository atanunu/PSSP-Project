Below is an example implementation that adds a new controller to handle sending notifications to multiple recipients (separated by commas). In this example, we create a new controller called `MultiRecipientController.php` with two endpoints: one for SMS notifications and one for Email notifications. We also update the routes file so that you can call these endpoints.

---

## 1. Create the Controller

**File:** `app/Http/Controllers/MultiRecipientController.php`

```php
<?php
// app/Http/Controllers/MultiRecipientController.php
// Last updated: 2025-02-06
//
// This controller handles sending notifications to multiple recipients.
// Recipients can be provided as a comma-separated string.

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SmsNotification;
use App\Notifications\EmailNotification;

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
     * Example: atanunu@esebun.com, john@esebun.com, mark@mark.com
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
}
```

---

## 2. Update Routes

Open the routes file at **routes/api.php** and add the new endpoints for multiple recipients:

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

// Example endpoints for custom usage (if any)
// ...
```

---

## 3. Usage Instructions

### SMS Notifications to Multiple Recipients

**Endpoint:** `POST /api/multi/sms/send`

**Example Payload:**
```json
{
  "to": "+2348164898637,+2347080400123,+2349092002000",
  "message": "Hello, this is a multi-recipient SMS test.",
  "gateway": "twilio"  // Optional: if omitted, the default gateway from .env/config is used.
}
```

### Email Notifications to Multiple Recipients

**Endpoint:** `POST /api/multi/email/send`

**Example Payload:**
```json
{
  "to": "atanunu@esebun.com,john@esebun.com,mark@mark.com",
  "subject": "Test Email to Multiple Recipients",
  "body": "Hello, this is a test email for multiple recipients.",
  "gateway": "smtp"  // Optional: if omitted, the default gateway from .env/config is used.
}
```

When you send a request to these endpoints, the controller will:
1. Validate the input.
2. Split the recipient string by commas.
3. Loop through each recipient and send the notification individually using Laravel’s Notification system.
4. Return a JSON response with the status and details for each recipient.

---

## 4. Composer Dependencies

Ensure you have installed all required dependencies by running:

```bash
composer require twilio/sdk
composer require aws/aws-sdk-php
composer require sendgrid/sendgrid
composer require mailgun/mailgun-php
composer require guzzlehttp/guzzle
```

This command installs:
- **Twilio SDK** for SMS integration.
- **AWS SDK for PHP** for AWS SNS and SES.
- **Sendgrid SDK** for Sendgrid Email integration.
- **Mailgun PHP SDK** for Mailgun Email integration.
- **Guzzle** for making HTTP API requests.

---

## 5. License

This project is open-sourced under the [MIT License](LICENSE).

---

## Conclusion

This project provides a unified multi-gateway notification system for Laravel 11. With the new endpoints, you can send notifications to multiple recipients (SMS and Email) by passing a comma-separated list. The project is fully modular, configurable, and easily extendable.

Happy Coding!
```

---

Simply copy the entire content above into a file named `README.md` in your project root. This file provides comprehensive implementation and usage instructions, including the new endpoints for sending to multiple recipients and all required composer dependencies.
