<?php
// app/Services/NotificationService.php
// Last updated: 2025-02-06
// Provides a common interface for sending SMS and Email notifications.

namespace App\Services;

use App\Models\NotificationLog;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    // Sends an SMS notification via the appropriate gateway.
    public function sendSms(string $to, string $message, ?string $gateway = null): array
    {
        $gateway = $gateway ?? config('notifications.default_sms_gateway');
        $smsGateway = $this->resolveSmsGateway($gateway);
        $response = $smsGateway->send($to, $message);
        NotificationLog::create([
            'type'      => 'sms',
            'gateway'   => $gateway,
            'recipient' => $to,
            'message'   => $message,
            'response'  => json_encode($response),
        ]);
        return $response;
    }

    // Sends an Email notification via the appropriate gateway.
    public function sendEmail(string $to, string $subject, string $body, ?string $gateway = null): array
    {
        $gateway = $gateway ?? config('notifications.default_email_gateway');
        $emailGateway = $this->resolveEmailGateway($gateway);
        $response = $emailGateway->send($to, $subject, $body);
        NotificationLog::create([
            'type'      => 'email',
            'gateway'   => $gateway,
            'recipient' => $to,
            'subject'   => $subject,
            'message'   => $body,
            'response'  => json_encode($response),
        ]);
        return $response;
    }

    // Resolves the SMS gateway instance.
    protected function resolveSmsGateway(string $gateway)
    {
        switch (strtolower($gateway)) {
            case 'twilio':
                return new \App\Services\SMSGateways\TwilioGatewayService();
            case 'africastalking':
                return new \App\Services\SMSGateways\AfricasTalkingGatewayService();
            case 'smpp':
                return new \App\Services\SMSGateways\SMPPGatewayService();
            case 'aws_sns':
                return new \App\Services\SMSGateways\AwsSnsGatewayService();
            case 'mocit_sms':
                return new \App\Services\SMSGateways\MocitSmsGatewayService();
            case 'mocit_whatsapp':
                return new \App\Services\SMSGateways\MocitWhatsappGatewayService();
            case 'termii':
                return new \App\Services\SMSGateways\TermiiSmsGatewayService();
            case 'http':
            default:
                return new \App\Services\SMSGateways\HttpGatewayService();
        }
    }

    // Resolves the Email gateway instance.
    protected function resolveEmailGateway(string $gateway)
    {
        switch (strtolower($gateway)) {
            case 'aws_ses':
                return new \App\Services\EmailGateways\AwsSesGatewayService();
            case 'mailgun':
                return new \App\Services\EmailGateways\MailgunGatewayService();
            case 'gmail':
                return new \App\Services\EmailGateways\GmailGatewayService();
            case 'sendgrid':
                return new \App\Services\EmailGateways\SendgridGatewayService();
            case 'custom_http':
                return new \App\Services\EmailGateways\CustomHttpEmailGatewayService();
            case 'smtp':
            default:
                return new \App\Services\EmailGateways\SmtpGatewayService();
        }
    }
}
