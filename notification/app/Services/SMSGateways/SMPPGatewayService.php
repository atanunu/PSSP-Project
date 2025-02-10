<?php
// app/Services/SMSGateways/SMPPGatewayService.php
// Last updated: 2025-02-06
// Simulates sending SMS using SMPP. Replace with real integration as needed.

namespace App\Services\SMSGateways;

use App\Interfaces\SmsGatewayInterface;
use Illuminate\Support\Facades\Log;

class SMPPGatewayService implements SmsGatewayInterface
{
    public function send(string $to, string $message): array
    {
        Log::info("Simulated SMPP send to {$to}");
        return ['status' => 'success', 'gateway' => 'smpp', 'to' => $to, 'message' => $message];
    }
}
