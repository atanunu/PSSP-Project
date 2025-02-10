<?php
// app/Interfaces/PushGatewayInterface.php
// Last updated: 2025-02-06
// Interface for push notification gateway services.

namespace App\Interfaces;

interface PushGatewayInterface
{
    /**
     * Send a push notification.
     *
     * @param mixed $to Recipient identifier (e.g., device token or topic).
     * @param array $payload Data payload for the notification.
     * @return array Response data from the push gateway.
     */
    public function sendPush($to, array $payload): array;
}
