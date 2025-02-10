<?php
// app/Models/NotificationLog.php
// Last updated: 2025-02-06
// Model representing logs for each notification sent.

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $fillable = [
        'type',
        'gateway',
        'recipient',
        'message',
        'subject',
        'response',
    ];
}
