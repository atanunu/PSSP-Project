<?php
// app/Http/Controllers/NotificationController.php
// Last updated: 2025-02-06
// Controller for sending SMS, Email, and OTP notifications.

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SmsNotification;
use App\Notifications\EmailNotification;
use App\Notifications\OtpNotification;
use App\Services\OtpService;

class NotificationController extends Controller
{
    protected OtpService $otpService;
    
    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }
    
    // Sends an SMS notification.
    public function sendSms(Request $request)
    {
        $validated = $request->validate([
            'to'      => 'required',
            'message' => 'required',
            'gateway' => 'sometimes|string',
        ]);
        $response = Notification::route('sms', $validated['to'])
            ->notify(new SmsNotification($validated['message'], $validated['gateway'] ?? null));
        return response()->json([
            'status' => 'SMS notification sent',
            'response' => $response
        ]);
    }
    
    // Sends an Email notification.
    public function sendEmail(Request $request)
    {
        $validated = $request->validate([
            'to'      => 'required|email',
            'subject' => 'required|string',
            'body'    => 'required|string',
            'gateway' => 'sometimes|string',
        ]);
        $response = Notification::route('email', $validated['to'])
            ->notify(new EmailNotification($validated['subject'], $validated['body'], $validated['gateway'] ?? null));
        return response()->json([
            'status' => 'Email notification sent',
            'response' => $response
        ]);
    }
    
    // Sends an OTP notification.
    public function sendOtp(Request $request)
    {
        $validated = $request->validate([
            'to'      => 'required',
            'message' => 'required',  // Include the {{otp}} shortcode.
            'expire'  => 'required|integer',
            'gateway' => 'sometimes|string', // e.g., "mocit_sms", "mocit_whatsapp", "termii_sms", etc.
        ]);
        $response = Notification::route('otp', $validated['to'])
            ->notify(new OtpNotification($validated['message'], $validated['expire'], $validated['gateway'] ?? null));
        return response()->json([
            'status' => 'OTP notification sent',
            'response' => $response
        ]);
    }
    
    // Verifies an OTP.
    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'otp'     => 'required|string',
            'phone'   => 'required',
            'gateway' => 'sometimes|string',
        ]);
        $result = $this->otpService->verifyOtp($validated['otp'], $validated['gateway'] ?? null, ['phone' => $validated['phone']]);
        return response()->json($result);
    }
}
