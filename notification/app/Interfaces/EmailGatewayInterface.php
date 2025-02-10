<?php
// app/Interfaces/EmailGatewayInterface.php
// Last updated: 2025-02-06
// Interface for Email gateway services.

namespace App\Interfaces;

interface EmailGatewayInterface
{
    /**
     * Send an email.
     *
     * @param string $to Recipient email address.
     * @param string $subject Email subject.
     * @param string $body Email body.
     * @return array Response data from the gateway.
     */
    public function send(string $to, string $subject, string $body): array;
}
