<?php
// app/Notifications/EmailNotification.php
// Last updated: 2025-02-06
// Notification class for sending Email messages.

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Channels\EmailGatewayChannel;

class EmailNotification extends Notification
{
    protected string $subject;
    protected string $body;
    protected ?string $gateway;

    public function __construct(string $subject, string $body, ?string $gateway = null)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->gateway = $gateway;
    }

    // Specifies the channels to use.
    public function via($notifiable): array
    {
        return [EmailGatewayChannel::class];
    }

    // Formats the data for the Email channel.
    public function toEmail($notifiable): array
    {
        return [
            'email' => $notifiable->routeNotificationFor('email'),
            'subject' => $this->subject,
            'body' => $this->body,
            'gateway' => $this->gateway,
        ];
    }
}
