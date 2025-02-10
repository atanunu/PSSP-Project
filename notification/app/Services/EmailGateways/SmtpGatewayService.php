<?php
// app/Services/EmailGateways/SmtpGatewayService.php
// Last updated: 2025-02-06
// Sends email using SMTP via Laravel's Mail facade.

namespace App\Services\EmailGateways;

use App\Interfaces\EmailGatewayInterface;
use Illuminate\Support\Facades\Mail;
use App\Mail\GenericEmail;
use Illuminate\Support\Facades\Log;

class SmtpGatewayService implements EmailGatewayInterface
{
    public function send(string $to, string $subject, string $body): array
    {
        try {
            Mail::to($to)->send(new GenericEmail($subject, $body));
            return ['status' => 'success', 'gateway' => 'smtp'];
        } catch (\Exception $e) {
            Log::error("SMTP Email error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
