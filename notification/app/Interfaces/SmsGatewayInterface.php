<?php
// app/Interfaces/SmsGatewayInterface.php
// Last updated: 2025-02-06
// Interface for SMS gateway services.

namespace App\Interfaces;

interface SmsGatewayInterface
{
    /**
     * Send an SMS message.
     *
     * @param string $to Recipient phone number.
     * @param string $message Message content.
     * @return array Response data from the gateway.
     */
    public function send(string $to, string $message): array;
}
