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
