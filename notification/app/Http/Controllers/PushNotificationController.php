<?php
// app/Http/Controllers/PushNotificationController.php
// Last updated: 2025-02-06
// Controller for sending push notifications.

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\PushNotification;
use Illuminate\Support\Facades\Notification;

class PushNotificationController extends Controller
{
    // Sends a push notification.
    public function sendPush(Request $request)
    {
        $validated = $request->validate([
            'to'      => 'required', // Device token or topic
            'payload' => 'required|array', // Must contain keys like title, body, data, etc.
            'gateway' => 'sometimes|string', // Optional: specify push gateway (e.g., google_fcm, onesignal, etc.)
        ]);

        $response = Notification::route('push', $validated['to'])
            ->notify(new PushNotification($validated['payload'], $validated['gateway'] ?? null));

        return response()->json([
            'status' => 'Push notification sent',
            'response' => $response
        ]);
    }
}
