<?php
// app/Http/Controllers/AnotherController.php
// Last updated: 2025-02-06
// Example controller demonstrating usage of NotificationService, OtpService, and PushNotificationService.

namespace App\Http\Controllers;

use App\Services\NotificationService;
use App\Services\OtpService;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;

class AnotherController extends Controller
{
    protected NotificationService $notificationService;
    protected OtpService $otpService;
    protected PushNotificationService $pushNotificationService;
    
    public function __construct(NotificationService $notificationService, OtpService $otpService, PushNotificationService $pushNotificationService)
    {
        $this->notificationService = $notificationService;
        $this->otpService = $otpService;
        $this->pushNotificationService = $pushNotificationService;
    }
    
    // Sends a custom SMS.
    public function customSms(Request $request)
    {
        $to = '+1234567890';
        $message = 'This is a custom SMS from AnotherController.';
        $response = $this->notificationService->sendSms($to, $message);
        return response()->json($response);
    }
    
    // Sends a custom Email.
    public function customEmail(Request $request)
    {
        $to = 'user@example.com';
        $subject = 'Custom Email';
        $body = 'This is a custom email body.';
        $response = $this->notificationService->sendEmail($to, $subject, $body);
        return response()->json($response);
    }
    
    // Sends a custom OTP.
    public function customOtp(Request $request)
    {
        $to = '+1234567890';
        $message = 'Your OTP is {{otp}}. It expires in 5 minutes.';
        $expire = 300;
        $response = $this->otpService->sendOtp($to, $message, $expire);
        return response()->json($response);
    }
    
    // Sends a custom push notification.
    public function customPush(Request $request)
    {
        $to = 'your_device_token_or_topic';
        $payload = [
            'title' => 'Test Push Notification',
            'body'  => 'This is a test push notification sent from AnotherController.',
            'data'  => ['key' => 'value'],
        ];
        $response = $this->pushNotificationService->sendPush($to, $payload);
        return response()->json($response);
    }
}
