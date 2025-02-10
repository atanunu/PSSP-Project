<?php
// app/Mail/GenericEmail.php
// Last updated: 2025-02-06
// Mailable class for sending SMTP emails.

namespace App\Mail;

use Illuminate\Mail\Mailable;

class GenericEmail extends Mailable
{
    public string $subject;
    public string $body;

    public function __construct(string $subject, string $body)
    {
        $this->subject = $subject;
        $this->body = $body;
    }

    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.generic')
                    ->with(['body' => $this->body]);
    }
}
