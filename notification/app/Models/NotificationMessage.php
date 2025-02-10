<?php
// app/Models/NotificationMessage.php
// Last updated: 2025-02-06
// Model representing each notification request with HTTP status and response.

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationMessage extends Model
{
    protected $fillable = [
        'type',
        'gateway',
        'recipient',
        'request_payload',
        'http_status_code',
        'response_status',
    ];
}
