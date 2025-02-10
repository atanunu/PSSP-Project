Below is the complete code base for the Laravel 11 multi‑gateway notification system. This guide includes the entire folder/file structure, every file’s code, and step‐by‐step instructions for installation, usage, and implementation.

---

# 1. Folder and File Structure

```
my-notification-project/
├── .env
├── artisan
├── composer.json
├── composer.lock
├── package.json
├── phpunit.xml
├── webpack.mix.js
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── broadcasting.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── notificationgateways.php
│   ├── notifications.php
│   └── NotificationResponse.php
├── database/
│   └── migrations/
│       ├── 2025_02_04_000000_create_notification_logs_table.php
│       └── 2025_02_04_000001_create_notification_messages_table.php
├── app/
│   ├── Channels/
│   │   ├── SmsGatewayChannel.php
│   │   ├── EmailGatewayChannel.php
│   │   ├── OtpGatewayChannel.php
│   │   └── PushNotificationChannel.php
│   ├── Controllers/
│   │   ├── NotificationController.php
│   │   ├── PushNotificationController.php
│   │   ├── NotificationLogController.php
│   │   ├── NotificationMessageController.php
│   │   └── AnotherController.php
│   ├── Interfaces/
│   │   ├── SmsGatewayInterface.php
│   │   ├── EmailGatewayInterface.php
│   │   ├── OtpGatewayInterface.php
│   │   └── PushGatewayInterface.php
│   ├── Mail/
│   │   └── GenericEmail.php
│   ├── Models/
│   │   ├── NotificationLog.php
│   │   └── NotificationMessage.php
│   ├── Notifications/
│   │   ├── SmsNotification.php
│   │   ├── EmailNotification.php
│   │   ├── OtpNotification.php
│   │   └── PushNotification.php
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   └── Services/
│       ├── NotificationService.php
│       ├── OtpService.php
│       ├── PushNotificationService.php
│       ├── SMSGateways/
│       │   ├── HttpGatewayService.php
│       │   ├── TwilioGatewayService.php
│       │   ├── AfricasTalkingGatewayService.php
│       │   ├── SMPPGatewayService.php
│       │   ├── AwsSnsGatewayService.php
│       │   ├── MocitSmsGatewayService.php
│       │   ├── MocitWhatsappGatewayService.php
│       │   └── TermiiSmsGatewayService.php
│       ├── EmailGateways/
│       │   ├── SmtpGatewayService.php
│       │   ├── AwsSesGatewayService.php
│       │   ├── MailgunGatewayService.php
│       │   ├── GmailGatewayService.php
│       │   ├── SendgridGatewayService.php
│       │   └── CustomHttpEmailGatewayService.php
│       ├── OtpGateways/
│       │   ├── MocitSmsOtpGatewayService.php
│       │   ├── MocitWhatsappOtpGatewayService.php
│       │   ├── TermiiSmsOtpGatewayService.php
│       │   ├── TermiiWhatsappOtpGatewayService.php
│       │   ├── TermiiEmailOtpGatewayService.php
│       │   └── TermiiVoiceOtpGatewayService.php
│       └── PushGateways/
│           ├── GoogleFcmGatewayService.php
│           ├── OneSignalGatewayService.php
│           ├── WonderpushGatewayService.php
│           ├── AirshipGatewayService.php
│           └── PushEngageGatewayService.php
├── resources/
│   └── views/
│       └── emails/
│           └── generic.blade.php
└── routes/
    └── api.php
```

---

# 2. Step-by-Step Instructions

## 2.1 Installation

1. **Create a New Laravel Project:**

   Open your terminal and run:
   ```bash
   composer create-project laravel/laravel my-notification-project
   ```

2. **Copy Files:**

   Place the files (as listed below) into their corresponding directories in your project:
   - **.env** → project root.
   - All configuration files (`notificationgateways.php`, `notifications.php`, `NotificationResponse.php`) → `config/` directory.
   - Migration files → `database/migrations/`.
   - Models → `app/Models/`.
   - Interfaces → `app/Interfaces/`.
   - Service classes and their subdirectories → `app/Services/` (create subdirectories as shown).
   - Channels → `app/Channels/`.
   - Notification classes → `app/Notifications/`.
   - Controllers → `app/Http/Controllers/`.
   - Routes → `routes/api.php`.
   - Service provider binding → `app/Providers/AppServiceProvider.php`.
   - Mailable → `app/Mail/` and view → `resources/views/emails/`.

3. **Update Environment Variables:**

   Edit the **.env** file with your API keys, credentials, and default settings.

4. **Run Migrations:**

   Execute:
   ```bash
   php artisan migrate
   ```

5. **Install Required Packages:**

   For example, install Guzzle and the Twilio SDK:
   ```bash
   composer require guzzlehttp/guzzle
   composer require twilio/sdk
   ```

6. **Start the Server:**

   Run:
   ```bash
   php artisan serve
   ```

---

## 2.2 Usage

- **SMS Notifications:**  
  **POST** `/api/sms/send`  
  **Example Payload:**
  ```json
  {
    "to": "+1234567890",
    "message": "Your SMS message goes here.",
    "gateway": "twilio"  // Optional; default is set in .env
  }
  ```

- **Email Notifications:**  
  **POST** `/api/email/send`  
  **Example Payload:**
  ```json
  {
    "to": "user@example.com",
    "subject": "Test Email",
    "body": "This is a test email message.",
    "gateway": "smtp"  // Optional; default is set in .env
  }
  ```

- **OTP Notifications:**  
  **POST** `/api/otp/send`  
  **Example Payload:**
  ```json
  {
    "to": "+1234567890",
    "message": "Your OTP is {{otp}}. It expires in 5 minutes.",
    "expire": 300,
    "gateway": "mocit_sms"  // Optional; default is set in .env
  }
  ```
  **POST** `/api/otp/verify`  
  **Example Payload:**
  ```json
  {
    "otp": "123456",
    "phone": "+1234567890",
    "gateway": "mocit_sms"  // Optional
  }
  ```

- **Push Notifications:**  
  **POST** `/api/push/send`  
  **Example Payload:**
  ```json
  {
    "to": "your_device_token_or_topic",
    "payload": {
       "title": "Test Push",
       "body": "This is a test push notification.",
       "data": {"key": "value"}
    },
    "gateway": "google_fcm"  // Optional; default is set in .env
  }
  ```

- **Logs & Messages Management:**  
  - **GET** `/api/logs` – List all logs  
  - **GET** `/api/logs/{id}` – Get a specific log  
  - **DELETE** `/api/logs/{id}` – Delete a specific log  
  - **DELETE** `/api/logs/empty` – Empty the logs table  
  - **GET** `/api/messages` – List all notification messages  
  - **GET** `/api/messages/{id}` – Get a specific message  
  - **DELETE** `/api/messages/{id}` – Delete a specific message  
  - **DELETE** `/api/messages/empty` – Empty the messages table  
  - **GET** `/api/messages/filter?gateway=twilio&type=sms` – Filter messages  
  - **GET** `/api/messages/retry` – Retry failed messages

- **Custom Endpoints:**  
  - **GET** `/api/another/sms`  
  - **GET** `/api/another/email`  
  - **GET** `/api/another/otp`  
  - **GET** `/api/another/push`

- **API Response Template:**  
  Use the templates in `config/NotificationResponse.php` to standardize responses. For example, if parameters are missing:
  ```php
  $responseTemplate = config('NotificationResponse.missing_parameters');
  return response()->json($responseTemplate, $responseTemplate['http_code']);
  ```

---

# 3. Complete Code for All Files

Below is every file’s code. Copy each code block into its corresponding file and directory.

---

## 3.1 Environment & Configuration Files

### 3.1.1 File: `.env`
```dotenv
# .env for My Notifications Project
# Last updated: 2025-02-06
#
# Default Options:
#   DEFAULT_SMS_GATEWAY: Options (e.g., twilio, africastalking, smpp, aws_sns, mocit_sms, mocit_whatsapp, termii, http)
#   DEFAULT_EMAIL_GATEWAY: Options (e.g., smtp, aws_ses, mailgun, gmail, sendgrid, custom_http)
#   DEFAULT_OTP_GATEWAY: Options (e.g., mocit_sms, mocit_whatsapp, termii_sms, termii_whatsapp, termii_email, termii_voice)
#   DEFAULT_PUSH_GATEWAY: Options (e.g., google_fcm, onesignal, wonderpush, airship, pushengage)
#
# SMS_SENDER_ID: Sender ID for SMS gateways (if supported)
# EMAIL_FROM: From email address for email gateways (if supported)
#
DEFAULT_SMS_GATEWAY=twilio
DEFAULT_EMAIL_GATEWAY=smtp
DEFAULT_OTP_GATEWAY=mocit_sms
DEFAULT_PUSH_GATEWAY=google_fcm

# HTTP endpoints
HTTP_SMS_URL=https://example.com/api/sms
CUSTOM_HTTP_EMAIL_URL=https://example.com/api/email

# Twilio credentials
TWILIO_SID=your_twilio_sid
TWILIO_TOKEN=your_twilio_token
TWILIO_FROM=+1234567890

# Africa's Talking credentials
AFRICASTALKING_USERNAME=your_africastalking_username
AFRICASTALKING_API_KEY=your_africastalking_api_key

# AWS credentials
AWS_DEFAULT_REGION=us-east-1
AWS_ACCESS_KEY_ID=your_aws_access_key
AWS_SECRET_ACCESS_KEY=your_aws_secret
AWS_SES_SOURCE_EMAIL=noreply@example.com

# Mocit credentials (for SMS, WhatsApp, and OTP)
MOCIT_API_SECRET=your_mocit_api_secret
MOCIT_WHATSAPP_ACCOUNT=your_mocit_whatsapp_account
MOCIT_SIM=1
MOCIT_DEVICE=your_linked_device_unique_id
MOCIT_GATEWAY_URL=https://zender.in.mocit.com/api

# Mailgun credentials
MAILGUN_DOMAIN=your_mailgun_domain
MAILGUN_API_KEY=your_mailgun_api_key

# Gmail credentials (for simulation)
GMAIL_ACCESS_TOKEN=your_gmail_access_token

# Sendgrid credentials
SENDGRID_API_KEY=your_sendgrid_api_key
SENDGRID_FROM=noreply@example.com

# Termii credentials (for OTP gateways only)
TERMII_API_KEY=your_termii_api_key
TERMII_SENDER_ID=YourTermiiSenderID

# Push Notification Gateway credentials:
# Google FCM: Server key from your Firebase project.
GOOGLE_FCM_SERVER_KEY=your_google_fcm_server_key
# OneSignal: App ID and REST API key from your OneSignal account.
ONESIGNAL_APP_ID=your_onesignal_app_id
ONESIGNAL_REST_API_KEY=your_onesignal_rest_api_key
# Wonderpush: API key from your Wonderpush account.
WONDERPUSH_API_KEY=your_wonderpush_api_key
# Airship: App key and master secret from your Airship account.
AIRSHIP_APP_KEY=your_airship_app_key
AIRSHIP_MASTER_SECRET=your_airship_master_secret
# PushEngage: API key from your PushEngage account.
PUSHENGAGE_API_KEY=your_pushengage_api_key

# Sender information
SMS_SENDER_ID=+1234567890
EMAIL_FROM=sender@example.com
```

---

### 3.1.2 File: `config/notificationgateways.php`
```php
<?php
// config/notificationgateways.php
// Last updated: 2025-02-06
// Contains configuration settings for SMS, Email, OTP, and Push notification gateways.

return [
    'http_sms_url' => env('HTTP_SMS_URL', 'https://example.com/api/sms'),
    'twilio' => [
        'sid'   => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'from'  => env('SMS_SENDER_ID', env('TWILIO_FROM')),
    ],
    'africastalking' => [
        'username' => env('AFRICASTALKING_USERNAME'),
        'api_key'  => env('AFRICASTALKING_API_KEY'),
    ],
    'aws' => [
        'region'           => env('AWS_DEFAULT_REGION', 'us-east-1'),
        'key'              => env('AWS_ACCESS_KEY_ID'),
        'secret'           => env('AWS_SECRET_ACCESS_KEY'),
        'ses_source_email' => env('EMAIL_FROM', env('AWS_SES_SOURCE_EMAIL', 'noreply@example.com')),
    ],
    'mocit' => [
        'api_secret'       => env('MOCIT_API_SECRET'),
        'whatsapp_account' => env('MOCIT_WHATSAPP_ACCOUNT'),
        'sim'              => env('MOCIT_SIM', 1),
        'device'           => env('MOCIT_DEVICE'),
        'gateway_url'      => env('MOCIT_GATEWAY_URL', 'https://zender.in.mocit.com/api'),
    ],
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_API_KEY'),
    ],
    'gmail' => [
        'access_token' => env('GMAIL_ACCESS_TOKEN'),
    ],
    'sendgrid' => [
        'api_key' => env('SENDGRID_API_KEY'),
        'from'    => env('EMAIL_FROM', env('SENDGRID_FROM', 'noreply@example.com')),
    ],
    'termii' => [
        'api_key'   => env('TERMII_API_KEY'),
        'sender_id' => env('SMS_SENDER_ID', env('TERMII_SENDER_ID', 'Termii')),
    ],
    'push' => [
        'google_fcm' => [
            'server_key' => env('GOOGLE_FCM_SERVER_KEY'),
        ],
        'onesignal' => [
            'app_id'       => env('ONESIGNAL_APP_ID'),
            'rest_api_key' => env('ONESIGNAL_REST_API_KEY'),
        ],
        'wonderpush' => [
            'api_key' => env('WONDERPUSH_API_KEY'),
        ],
        'airship' => [
            'app_key'       => env('AIRSHIP_APP_KEY'),
            'master_secret' => env('AIRSHIP_MASTER_SECRET'),
        ],
        'pushengage' => [
            'api_key' => env('PUSHENGAGE_API_KEY'),
        ],
    ],
];
```

---

### 3.1.3 File: `config/notifications.php`
```php
<?php
// config/notifications.php
// Last updated: 2025-02-06
// Default gateway selections for notifications.

return [
    'default_sms_gateway'   => env('DEFAULT_SMS_GATEWAY', 'twilio'),
    'default_email_gateway' => env('DEFAULT_EMAIL_GATEWAY', 'smtp'),
    'default_otp_gateway'   => env('DEFAULT_OTP_GATEWAY', 'mocit_sms'),
    'default_push_gateway'  => env('DEFAULT_PUSH_GATEWAY', 'google_fcm'),
];
```

---

### 3.1.4 File: `config/NotificationResponse.php`
```php
<?php
// config/NotificationResponse.php
// Last updated: 2025-02-06
// Contains response templates and HTTP status codes for API responses.

return [
    'success' => [
        'http_code' => 200,
        'status' => 'success',
        'response_code' => '00',
        'message' => 'Notification sent successfully.',
    ],
    'error' => [
        'http_code' => 500,
        'status' => 'failed',
        'response_code' => '09',
        'message' => 'Failed to send notification.',
    ],
    'missing_parameters' => [
        'http_code' => 400,
        'status' => 'failed',
        'response_code' => '99',
        'message' => 'Missing required parameters in API request.',
    ],
];
```

---

## 3.2 Database Migrations & Models

### 3.2.1 File: `database/migrations/2025_02_04_000000_create_notification_logs_table.php`
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // Last updated: 2025-02-06
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['sms', 'email', 'otp', 'push']);
            $table->string('gateway');
            $table->string('recipient');
            $table->text('message')->nullable();
            $table->string('subject')->nullable();
            $table->json('response')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
```

---

### 3.2.2 File: `database/migrations/2025_02_04_000001_create_notification_messages_table.php`
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // Last updated: 2025-02-06
    public function up(): void
    {
        Schema::create('notification_messages', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['sms', 'email', 'otp', 'push']);
            $table->string('gateway');
            $table->string('recipient');
            $table->text('request_payload')->nullable();
            $table->integer('http_status_code')->nullable();
            $table->string('response_status')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('notification_messages');
    }
};
```

---

### 3.2.3 File: `app/Models/NotificationLog.php`
```php
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
```

---

### 3.2.4 File: `app/Models/NotificationMessage.php`
```php
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
```

---

## 3.3 Interfaces

### 3.3.1 File: `app/Interfaces/SmsGatewayInterface.php`
```php
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
```

---

### 3.3.2 File: `app/Interfaces/EmailGatewayInterface.php`
```php
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
```

---

### 3.3.3 File: `app/Interfaces/OtpGatewayInterface.php`
```php
<?php
// app/Interfaces/OtpGatewayInterface.php
// Last updated: 2025-02-06
// Interface for OTP gateway services.

namespace App\Interfaces;

interface OtpGatewayInterface
{
    /**
     * Send an OTP message.
     *
     * @param string $phone Recipient phone number.
     * @param string $message OTP message with the {{otp}} shortcode.
     * @param int $expire Expiration time in seconds.
     * @param array $options Additional parameters.
     * @return array Response data from the gateway.
     */
    public function sendOtp(string $phone, string $message, int $expire, array $options = []): array;

    /**
     * Verify an OTP.
     *
     * @param string $otp The OTP provided by the user.
     * @param array $options Additional parameters (e.g., phone number).
     * @return array Response data from the gateway.
     */
    public function verifyOtp(string $otp, array $options = []): array;
}
```

---

### 3.3.4 File: `app/Interfaces/PushGatewayInterface.php`
```php
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
```

---

## 3.4 Service Classes

### 3.4.1 SMS Gateway Services  
*(All files in `app/Services/SMSGateways/`)*

#### 3.4.1.1 File: `HttpGatewayService.php`
```php
<?php
// app/Services/SMSGateways/HttpGatewayService.php
// Last updated: 2025-02-06
// Sends SMS using a generic HTTP API.

namespace App\Services\SMSGateways;

use App\Interfaces\SmsGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class HttpGatewayService implements SmsGatewayInterface
{
    public function send(string $to, string $message): array
    {
        $client = new Client();
        $url = config('notificationgateways.http_sms_url');
        try {
            $response = $client->post($url, [
                'form_params' => ['to' => $to, 'message' => $message],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("HTTP SMS error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
```

---

#### 3.4.1.2 File: `TwilioGatewayService.php`
```php
<?php
// app/Services/SMSGateways/TwilioGatewayService.php
// Last updated: 2025-02-06
// Sends SMS using the Twilio API.

namespace App\Services\SMSGateways;

use App\Interfaces\SmsGatewayInterface;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioGatewayService implements SmsGatewayInterface
{
    public function send(string $to, string $message): array
    {
        $sid   = config('notificationgateways.twilio.sid');
        $token = config('notificationgateways.twilio.token');
        $from  = config('notificationgateways.twilio.from');
        try {
            $client = new Client($sid, $token);
            $msg = $client->messages->create($to, ['from' => $from, 'body' => $message]);
            return ['status' => 'success', 'gateway' => 'twilio', 'messageSid' => $msg->sid];
        } catch (\Exception $e) {
            Log::error("Twilio SMS error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
```

---

#### 3.4.1.3 File: `AfricasTalkingGatewayService.php`
```php
<?php
// app/Services/SMSGateways/AfricasTalkingGatewayService.php
// Last updated: 2025-02-06
// Sends SMS using Africa's Talking API.

namespace App\Services\SMSGateways;

use App\Interfaces\SmsGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class AfricasTalkingGatewayService implements SmsGatewayInterface
{
    public function send(string $to, string $message): array
    {
        $username = config('notificationgateways.africastalking.username');
        $apiKey   = config('notificationgateways.africastalking.api_key');
        $url      = 'https://api.africastalking.com/version1/messaging';
        $client   = new Client();
        try {
            $response = $client->post($url, [
                'headers' => ['apiKey' => $apiKey],
                'form_params' => [
                    'username' => $username,
                    'to' => $to,
                    'message' => $message,
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Africa's Talking SMS error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
```

---

#### 3.4.1.4 File: `SMPPGatewayService.php`
```php
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
```

---

#### 3.4.1.5 File: `AwsSnsGatewayService.php`
```php
<?php
// app/Services/SMSGateways/AwsSnsGatewayService.php
// Last updated: 2025-02-06
// Sends SMS using AWS SNS.

namespace App\Services\SMSGateways;

use App\Interfaces\SmsGatewayInterface;
use Aws\Sns\SnsClient;
use Illuminate\Support\Facades\Log;

class AwsSnsGatewayService implements SmsGatewayInterface
{
    public function send(string $to, string $message): array
    {
        $client = new SnsClient([
            'region' => config('notificationgateways.aws.region'),
            'version' => 'latest',
            'credentials' => [
                'key' => config('notificationgateways.aws.key'),
                'secret' => config('notificationgateways.aws.secret'),
            ],
        ]);
        try {
            $result = $client->publish(['Message' => $message, 'PhoneNumber' => $to]);
            return ['status' => 'success', 'gateway' => 'aws_sns', 'messageId' => $result->get('MessageId')];
        } catch (\Exception $e) {
            Log::error("AWS SNS SMS error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
```

---

#### 3.4.1.6 File: `MocitSmsGatewayService.php`
```php
<?php
// app/Services/SMSGateways/MocitSmsGatewayService.php
// Last updated: 2025-02-06
// Sends SMS via Mocit API using SMS mode.
// Required parameters: secret, type="sms", message, phone, mode="devices", device, sim.

namespace App\Services\SMSGateways;

use App\Interfaces\SmsGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class MocitSmsGatewayService implements SmsGatewayInterface
{
    public function send(string $to, string $message): array
    {
        $apiSecret = config('notificationgateways.mocit.api_secret');
        $device = config('notificationgateways.mocit.device');
        $sim = config('notificationgateways.mocit.sim', 1);
        $gatewayUrl = config('notificationgateways.mocit.gateway_url');
        $client = new Client();
        $url = $gatewayUrl . '/send/sms';
        try {
            $response = $client->post($url, [
                'multipart' => [
                    ['name' => 'secret', 'contents' => $apiSecret],
                    ['name' => 'type', 'contents' => 'sms'],
                    ['name' => 'message', 'contents' => $message],
                    ['name' => 'phone', 'contents' => $to],
                    ['name' => 'mode', 'contents' => 'devices'],
                    ['name' => 'device', 'contents' => $device],
                    ['name' => 'sim', 'contents' => $sim],
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Mocit SMS error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
```

---

#### 3.4.1.7 File: `MocitWhatsappGatewayService.php`
```php
<?php
// app/Services/SMSGateways/MocitWhatsappGatewayService.php
// Last updated: 2025-02-06
// Sends SMS via Mocit API using WhatsApp mode.
// Required parameters: secret, type="WhatsApp", message, phone, expire, account.

namespace App\Services\SMSGateways;

use App\Interfaces\SmsGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class MocitWhatsappGatewayService implements SmsGatewayInterface
{
    public function send(string $to, string $message): array
    {
        $apiSecret = config('notificationgateways.mocit.api_secret');
        $account = config('notificationgateways.mocit.whatsapp_account');
        $gatewayUrl = config('notificationgateways.mocit.gateway_url');
        $client = new Client();
        $url = $gatewayUrl . '/send/whatsapp';
        try {
            $response = $client->post($url, [
                'multipart' => [
                    ['name' => 'secret', 'contents' => $apiSecret],
                    ['name' => 'type', 'contents' => 'WhatsApp'],
                    ['name' => 'message', 'contents' => $message],
                    ['name' => 'phone', 'contents' => $to],
                    ['name' => 'account', 'contents' => $account],
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Mocit WhatsApp SMS error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
```

---

#### 3.4.1.8 File: `TermiiSmsGatewayService.php`
```php
<?php
// app/Services/SMSGateways/TermiiSmsGatewayService.php
// Last updated: 2025-02-06
// Sends SMS using Termii's send token API (for SMS).
// Note: Termii splitting does not apply to the SMS gateway.

namespace App\Services\SMSGateways;

use App\Interfaces\SmsGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TermiiSmsGatewayService implements SmsGatewayInterface
{
    public function send(string $to, string $message): array
    {
        $client = new Client();
        $apiKey = config('notificationgateways.termii.api_key');
        $senderId = config('notificationgateways.termii.sender_id');
        $url = 'https://api.ng.termii.com/api/sms/send';
        $params = [
            'api_key' => $apiKey,
            'to' => $to,
            'from' => $senderId,
            'sms' => $message,
        ];
        try {
            $response = $client->post($url, ['form_params' => $params]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Termii SMS error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
```

---

### 3.4.2 Email Gateway Services  
*(All files in `app/Services/EmailGateways/`)*

#### 3.4.2.1 File: `SmtpGatewayService.php`
```php
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
```

---

#### 3.4.2.2 File: `AwsSesGatewayService.php`
```php
<?php
// app/Services/EmailGateways/AwsSesGatewayService.php
// Last updated: 2025-02-06
// Sends email using AWS SES.

namespace App\Services\EmailGateways;

use App\Interfaces\EmailGatewayInterface;
use Aws\Ses\SesClient;
use Illuminate\Support\Facades\Log;

class AwsSesGatewayService implements EmailGatewayInterface
{
    public function send(string $to, string $subject, string $body): array
    {
        $client = new SesClient([
            'region' => config('notificationgateways.aws.region'),
            'version' => 'latest',
            'credentials' => [
                'key' => config('notificationgateways.aws.key'),
                'secret' => config('notificationgateways.aws.secret'),
            ],
        ]);
        try {
            $result = $client->sendEmail([
                'Source' => config('notificationgateways.aws.ses_source_email'),
                'Destination' => ['ToAddresses' => [$to]],
                'Message' => [
                    'Subject' => ['Data' => $subject],
                    'Body' => ['Html' => ['Data' => $body]]
                ],
            ]);
            return ['status' => 'success', 'gateway' => 'aws_ses', 'messageId' => $result->get('MessageId')];
        } catch (\Exception $e) {
            Log::error("AWS SES Email error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
```

---

#### 3.4.2.3 File: `MailgunGatewayService.php`
```php
<?php
// app/Services/EmailGateways/MailgunGatewayService.php
// Last updated: 2025-02-06
// Sends email using the Mailgun API.

namespace App\Services\EmailGateways;

use App\Interfaces\EmailGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class MailgunGatewayService implements EmailGatewayInterface
{
    public function send(string $to, string $subject, string $body): array
    {
        $domain = config('notificationgateways.mailgun.domain');
        $apiKey = config('notificationgateways.mailgun.secret');
        $url = "https://api.mailgun.net/v3/{$domain}/messages";
        $client = new Client();
        try {
            $response = $client->post($url, [
                'auth' => ['api', $apiKey],
                'form_params' => [
                    'from' => "Mailgun Sandbox <postmaster@{$domain}>",
                    'to' => $to,
                    'subject' => $subject,
                    'text' => $body,
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Mailgun Email error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
```

---

#### 3.4.2.4 File: `GmailGatewayService.php`
```php
<?php
// app/Services/EmailGateways/GmailGatewayService.php
// Last updated: 2025-02-06
// Sends email using the Gmail API (simulated).

namespace App\Services\EmailGateways;

use App\Interfaces\EmailGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GmailGatewayService implements EmailGatewayInterface
{
    public function send(string $to, string $subject, string $body): array
    {
        $accessToken = config('notificationgateways.gmail.access_token');
        $client = new Client();
        try {
            $response = $client->post('https://gmail.googleapis.com/gmail/v1/users/me/messages/send', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'to' => $to,
                    'subject' => $subject,
                    'body' => $body,
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Gmail Email error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
```

---

#### 3.4.2.5 File: `SendgridGatewayService.php`
```php
<?php
// app/Services/EmailGateways/SendgridGatewayService.php
// Last updated: 2025-02-06
// Sends email using the Sendgrid API.

namespace App\Services\EmailGateways;

use App\Interfaces\EmailGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SendgridGatewayService implements EmailGatewayInterface
{
    public function send(string $to, string $subject, string $body): array
    {
        $apiKey = config('notificationgateways.sendgrid.api_key');
        $url = 'https://api.sendgrid.com/v3/mail/send';
        $client = new Client();
        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'personalizations' => [
                        [
                            'to' => [['email' => $to]],
                            'subject' => $subject,
                        ],
                    ],
                    'from' => ['email' => config('notificationgateways.sendgrid.from')],
                    'content' => [
                        [
                            'type' => 'text/html',
                            'value' => $body,
                        ],
                    ],
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Sendgrid Email error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
```

---

#### 3.4.2.6 File: `CustomHttpEmailGatewayService.php`
```php
<?php
// app/Services/EmailGateways/CustomHttpEmailGatewayService.php
// Last updated: 2025-02-06
// Sends email using a custom HTTP API endpoint.

namespace App\Services\EmailGateways;

use App\Interfaces\EmailGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class CustomHttpEmailGatewayService implements EmailGatewayInterface
{
    public function send(string $to, string $subject, string $body): array
    {
        $url = env('CUSTOM_HTTP_EMAIL_URL', 'https://example.com/api/email');
        $client = new Client();
        try {
            $response = $client->post($url, [
                'form_params' => [
                    'to' => $to,
                    'subject' => $subject,
                    'body' => $body,
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Custom HTTP Email error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
```

---

### 3.4.3 OTP Gateway Services  
*(All files in `app/Services/OtpGateways/`)*

#### 3.4.3.1 File: `MocitSmsOtpGatewayService.php`
```php
<?php
// app/Services/OtpGateways/MocitSmsOtpGatewayService.php
// Last updated: 2025-02-06
// Sends OTP via Mocit using SMS parameters (OTP mode).
// Required: secret, type="sms", message, phone, expire, mode="devices", device, sim.

namespace App\Services\OtpGateways;

use App\Interfaces\OtpGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class MocitSmsOtpGatewayService implements OtpGatewayInterface
{
    public function sendOtp(string $phone, string $message, int $expire, array $options = []): array
    {
        $client = new Client();
        $apiSecret = config('notificationgateways.mocit.api_secret');
        $device = config('notificationgateways.mocit.device');
        $sim = config('notificationgateways.mocit.sim', 1);
        $gatewayUrl = config('notificationgateways.mocit.gateway_url');
        $url = $gatewayUrl . '/send/otp';
        try {
            $response = $client->post($url, [
                'multipart' => [
                    ['name' => 'secret', 'contents' => $apiSecret],
                    ['name' => 'type', 'contents' => 'sms'],
                    ['name' => 'message', 'contents' => $message],
                    ['name' => 'phone', 'contents' => $phone],
                    ['name' => 'expire', 'contents' => $expire],
                    ['name' => 'mode', 'contents' => 'devices'],
                    ['name' => 'device', 'contents' => $device],
                    ['name' => 'sim', 'contents' => $sim],
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Mocit SMS OTP send error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
    public function verifyOtp(string $otp, array $options = []): array
    {
        $client = new Client();
        $apiSecret = config('notificationgateways.mocit.api_secret');
        $url = config('notificationgateways.mocit.gateway_url') . '/get/otp';
        try {
            $response = $client->get($url, ['query' => ['secret' => $apiSecret, 'otp' => $otp]]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Mocit SMS OTP verify error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
```

---

#### 3.4.3.2 File: `MocitWhatsappOtpGatewayService.php`
```php
<?php
// app/Services/OtpGateways/MocitWhatsappOtpGatewayService.php
// Last updated: 2025-02-06
// Sends OTP via Mocit using WhatsApp parameters (OTP mode).
// Required: secret, type="WhatsApp", message, phone, expire, account.

namespace App\Services\OtpGateways;

use App\Interfaces\OtpGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class MocitWhatsappOtpGatewayService implements OtpGatewayInterface
{
    public function sendOtp(string $phone, string $message, int $expire, array $options = []): array
    {
        $client = new Client();
        $apiSecret = config('notificationgateways.mocit.api_secret');
        $account = config('notificationgateways.mocit.whatsapp_account');
        $gatewayUrl = config('notificationgateways.mocit.gateway_url');
        $url = $gatewayUrl . '/send/otp';
        try {
            $response = $client->post($url, [
                'multipart' => [
                    ['name' => 'secret', 'contents' => $apiSecret],
                    ['name' => 'type', 'contents' => 'WhatsApp'],
                    ['name' => 'message', 'contents' => $message],
                    ['name' => 'phone', 'contents' => $phone],
                    ['name' => 'expire', 'contents' => $expire],
                    ['name' => 'account', 'contents' => $account],
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Mocit WhatsApp OTP send error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
    public function verifyOtp(string $otp, array $options = []): array
    {
        $client = new Client();
        $apiSecret = config('notificationgateways.mocit.api_secret');
        $url = config('notificationgateways.mocit.gateway_url') . '/get/otp';
        try {
            $response = $client->get($url, ['query' => ['secret' => $apiSecret, 'otp' => $otp]]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Mocit WhatsApp OTP verify error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
```

---

#### 3.4.3.3 File: `TermiiSmsOtpGatewayService.php`
```php
<?php
// app/Services/OtpGateways/TermiiSmsOtpGatewayService.php
// Last updated: 2025-02-06
// Sends OTP via Termii SMS using Termii's send token API (for OTP).

namespace App\Services\OtpGateways;

use App\Interfaces\OtpGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TermiiSmsOtpGatewayService implements OtpGatewayInterface
{
    public function sendOtp(string $phone, string $message, int $expire, array $options = []): array
    {
        $client = new Client();
        $apiKey = config('notificationgateways.termii.api_key');
        $senderId = config('notificationgateways.termii.sender_id');
        $url = 'https://api.ng.termii.com/api/sms/otp/send';
        $pinAttempts = $options['pin_attempts'] ?? 3;
        $pinLength = $options['pin_length'] ?? 4;
        $params = [
            'api_key' => $apiKey,
            'to' => $phone,
            'from' => $senderId,
            'sms' => $message,
            'pin_attempts' => $pinAttempts,
            'pin_time' => $expire,
            'pin_length' => $pinLength,
        ];
        try {
            $response = $client->post($url, ['form_params' => $params]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Termii SMS OTP send error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
    public function verifyOtp(string $otp, array $options = []): array
    {
        $client = new Client();
        $apiKey = config('notificationgateways.termii.api_key');
        $url = 'https://api.ng.termii.com/api/sms/otp/verify';
        if (!isset($options['phone'])) {
            $errorMsg = "Termii OTP verify error: 'phone' parameter is required.";
            Log::error($errorMsg);
            return ['status' => 'error', 'error' => $errorMsg];
        }
        $phone = $options['phone'];
        try {
            $response = $client->post($url, ['form_params' => [
                'api_key' => $apiKey,
                'otp' => $otp,
                'to' => $phone,
            ]]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Termii SMS OTP verify error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
```

---

#### 3.4.3.4 File: `TermiiWhatsappOtpGatewayService.php`
```php
<?php
// app/Services/OtpGateways/TermiiWhatsappOtpGatewayService.php
// Last updated: 2025-02-06
// Sends OTP via Termii WhatsApp using Termii's send token API (for OTP).

namespace App\Services\OtpGateways;

use App\Interfaces\OtpGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TermiiWhatsappOtpGatewayService implements OtpGatewayInterface
{
    public function sendOtp(string $phone, string $message, int $expire, array $options = []): array
    {
        $client = new Client();
        $apiKey = config('notificationgateways.termii.api_key');
        $senderId = config('notificationgateways.termii.sender_id');
        $url = 'https://api.ng.termii.com/api/whatsapp/otp/send';
        $pinAttempts = $options['pin_attempts'] ?? 3;
        $pinLength = $options['pin_length'] ?? 4;
        $params = [
            'api_key' => $apiKey,
            'to' => $phone,
            'from' => $senderId,
            'sms' => $message,
            'pin_attempts' => $pinAttempts,
            'pin_time' => $expire,
            'pin_length' => $pinLength,
        ];
        try {
            $response = $client->post($url, ['form_params' => $params]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Termii WhatsApp OTP send error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
    public function verifyOtp(string $otp, array $options = []): array
    {
        $client = new Client();
        $apiKey = config('notificationgateways.termii.api_key');
        $url = 'https://api.ng.termii.com/api/sms/otp/verify';
        if (!isset($options['phone'])) {
            $errorMsg = "Termii OTP verify error: 'phone' parameter is required.";
            Log::error($errorMsg);
            return ['status' => 'error', 'error' => $errorMsg];
        }
        $phone = $options['phone'];
        try {
            $response = $client->post($url, ['form_params' => [
                'api_key' => $apiKey,
                'otp' => $otp,
                'to' => $phone,
            ]]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Termii WhatsApp OTP verify error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
```

---

#### 3.4.3.5 File: `TermiiEmailOtpGatewayService.php`
```php
<?php
// app/Services/OtpGateways/TermiiEmailOtpGatewayService.php
// Last updated: 2025-02-06
// Sends OTP via Termii Email using Termii's send token API (for OTP).

namespace App\Services\OtpGateways;

use App\Interfaces\OtpGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TermiiEmailOtpGatewayService implements OtpGatewayInterface
{
    public function sendOtp(string $to, string $message, int $expire, array $options = []): array
    {
        $client = new Client();
        $apiKey = config('notificationgateways.termii.api_key');
        $senderId = config('notificationgateways.termii.sender_id');
        $url = 'https://api.ng.termii.com/api/email/otp/send';
        $pinAttempts = $options['pin_attempts'] ?? 3;
        $pinLength = $options['pin_length'] ?? 4;
        $params = [
            'api_key' => $apiKey,
            'to' => $to,
            'from' => $senderId,
            'email' => $message,
            'pin_attempts' => $pinAttempts,
            'pin_time' => $expire,
            'pin_length' => $pinLength,
        ];
        try {
            $response = $client->post($url, ['form_params' => $params]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Termii Email OTP send error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
    public function verifyOtp(string $otp, array $options = []): array
    {
        $client = new Client();
        $apiKey = config('notificationgateways.termii.api_key');
        $url = 'https://api.ng.termii.com/api/sms/otp/verify';
        if (!isset($options['phone'])) {
            $errorMsg = "Termii OTP verify error: 'phone' parameter is required.";
            Log::error($errorMsg);
            return ['status' => 'error', 'error' => $errorMsg];
        }
        $phone = $options['phone'];
        try {
            $response = $client->post($url, ['form_params' => [
                'api_key' => $apiKey,
                'otp' => $otp,
                'to' => $phone,
            ]]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Termii Email OTP verify error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
```

---

#### 3.4.3.6 File: `TermiiVoiceOtpGatewayService.php`
```php
<?php
// app/Services/OtpGateways/TermiiVoiceOtpGatewayService.php
// Last updated: 2025-02-06
// Sends OTP via Termii Voice using Termii's voice token API.

namespace App\Services\OtpGateways;

use App\Interfaces\OtpGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TermiiVoiceOtpGatewayService implements OtpGatewayInterface
{
    public function sendOtp(string $to, string $message, int $expire, array $options = []): array
    {
        $client = new Client();
        $apiKey = config('notificationgateways.termii.api_key');
        $senderId = config('notificationgateways.termii.sender_id');
        $url = 'https://api.ng.termii.com/api/voice/otp/send';
        $params = [
            'api_key' => $apiKey,
            'to' => $to,
            'from' => $senderId,
            'voice' => $message,
        ];
        try {
            $response = $client->post($url, ['form_params' => $params]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Termii Voice OTP send error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
    public function verifyOtp(string $otp, array $options = []): array
    {
        $client = new Client();
        $apiKey = config('notificationgateways.termii.api_key');
        $url = 'https://api.ng.termii.com/api/sms/otp/verify';
        if (!isset($options['phone'])) {
            $errorMsg = "Termii OTP verify error: 'phone' parameter is required.";
            Log::error($errorMsg);
            return ['status' => 'error', 'error' => $errorMsg];
        }
        $phone = $options['phone'];
        try {
            $response = $client->post($url, ['form_params' => [
                'api_key' => $apiKey,
                'otp' => $otp,
                'to' => $phone,
            ]]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Termii Voice OTP verify error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
```

---

### 3.4.4 Push Notification Gateway Services  
*(All files in `app/Services/PushGateways/`)*

#### 3.4.4.1 File: `GoogleFcmGatewayService.php`
```php
<?php
// app/Services/PushGateways/GoogleFcmGatewayService.php
// Last updated: 2025-02-06
// Sends push notifications using Google FCM.

namespace App\Services\PushGateways;

use App\Interfaces\PushGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GoogleFcmGatewayService implements PushGatewayInterface
{
    public function sendPush($to, array $payload): array
    {
        $serverKey = env('GOOGLE_FCM_SERVER_KEY');
        $client = new Client();
        $url = 'https://fcm.googleapis.com/fcm/send';
        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'key=' . $serverKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'to' => $to,
                    'notification' => $payload,
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Google FCM error: " . $e->getMessage());
            return ['status' => 'failed', 'response_code' => '09', 'error' => $e->getMessage()];
        }
    }
}
```

---

#### 3.4.4.2 File: `OneSignalGatewayService.php`
```php
<?php
// app/Services/PushGateways/OneSignalGatewayService.php
// Last updated: 2025-02-06
// Sends push notifications using OneSignal.

namespace App\Services\PushGateways;

use App\Interfaces\PushGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class OneSignalGatewayService implements PushGatewayInterface
{
    public function sendPush($to, array $payload): array
    {
        $appId = env('ONESIGNAL_APP_ID');
        $restApiKey = env('ONESIGNAL_REST_API_KEY');
        $client = new Client();
        $url = 'https://onesignal.com/api/v1/notifications';
        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Basic ' . $restApiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'app_id' => $appId,
                    'include_player_ids' => is_array($to) ? $to : [$to],
                    'headings' => ['en' => $payload['title'] ?? ''],
                    'contents' => ['en' => $payload['body'] ?? ''],
                    'data' => $payload['data'] ?? []
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("OneSignal error: " . $e->getMessage());
            return ['status' => 'failed', 'response_code' => '09', 'error' => $e->getMessage()];
        }
    }
}
```

---

#### 3.4.4.3 File: `WonderpushGatewayService.php`
```php
<?php
// app/Services/PushGateways/WonderpushGatewayService.php
// Last updated: 2025-02-06
// Sends push notifications using Wonderpush.

namespace App\Services\PushGateways;

use App\Interfaces\PushGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WonderpushGatewayService implements PushGatewayInterface
{
    public function sendPush($to, array $payload): array
    {
        $apiKey = env('WONDERPUSH_API_KEY');
        $client = new Client();
        $url = 'https://api.wonderpush.com/v1/notifications';
        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'to' => $to,
                    'notification' => $payload,
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Wonderpush error: " . $e->getMessage());
            return ['status' => 'failed', 'response_code' => '09', 'error' => $e->getMessage()];
        }
    }
}
```

---

#### 3.4.4.4 File: `AirshipGatewayService.php`
```php
<?php
// app/Services/PushGateways/AirshipGatewayService.php
// Last updated: 2025-02-06
// Sends push notifications using Airship.

namespace App\Services\PushGateways;

use App\Interfaces\PushGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class AirshipGatewayService implements PushGatewayInterface
{
    public function sendPush($to, array $payload): array
    {
        $appKey = env('AIRSHIP_APP_KEY');
        $masterSecret = env('AIRSHIP_MASTER_SECRET');
        $client = new Client();
        $url = 'https://go.urbanairship.com/api/push/';
        try {
            $response = $client->post($url, [
                'auth' => [$appKey, $masterSecret],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'audience' => $to,
                    'notification' => $payload,
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Airship error: " . $e->getMessage());
            return ['status' => 'failed', 'response_code' => '09', 'error' => $e->getMessage()];
        }
    }
}
```

---

#### 3.4.4.5 File: `PushEngageGatewayService.php`
```php
<?php
// app/Services/PushGateways/PushEngageGatewayService.php
// Last updated: 2025-02-06
// Sends push notifications using PushEngage.

namespace App\Services\PushGateways;

use App\Interfaces\PushGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PushEngageGatewayService implements PushGatewayInterface
{
    public function sendPush($to, array $payload): array
    {
        $apiKey = env('PUSHENGAGE_API_KEY');
        $client = new Client();
        $url = 'https://api.pushengage.com/v1/notification/send';
        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'recipient' => $to,
                    'notification' => $payload,
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("PushEngage error: " . $e->getMessage());
            return ['status' => 'failed', 'response_code' => '09', 'error' => $e->getMessage()];
        }
    }
}
```

---

### 3.4.5 Core Push Notification Service

#### 3.4.5.1 File: `PushNotificationService.php`
```php
<?php
// app/Services/PushNotificationService.php
// Last updated: 2025-02-06
// Provides a common interface for sending push notifications via the appropriate push gateway.

namespace App\Services;

use App\Interfaces\PushGatewayInterface;
use App\Services\PushGateways\GoogleFcmGatewayService;
use App\Services\PushGateways\OneSignalGatewayService;
use App\Services\PushGateways\WonderpushGatewayService;
use App\Services\PushGateways\AirshipGatewayService;
use App\Services\PushGateways\PushEngageGatewayService;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    // Sends a push notification using the specified gateway.
    public function sendPush($to, array $payload, ?string $gateway = null): array
    {
        $gateway = $gateway ?? config('notifications.default_push_gateway');
        $pushGateway = $this->resolvePushGateway($gateway);
        return $pushGateway->sendPush($to, $payload);
    }
    
    // Resolves the push gateway instance.
    protected function resolvePushGateway(string $gateway): PushGatewayInterface
    {
        switch (strtolower($gateway)) {
            case 'google_fcm':
                return new GoogleFcmGatewayService();
            case 'onesignal':
                return new OneSignalGatewayService();
            case 'wonderpush':
                return new WonderpushGatewayService();
            case 'airship':
                return new AirshipGatewayService();
            case 'pushengage':
                return new PushEngageGatewayService();
            default:
                return new GoogleFcmGatewayService();
        }
    }
}
```

---

### 3.5 Core Service Classes (Application Level)

#### 3.5.1 File: `NotificationService.php`
```php
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
```

---

#### 3.5.2 File: `OtpService.php`
```php
<?php
// app/Services/OtpService.php
// Last updated: 2025-02-06
// Handles sending and verifying OTPs via the appropriate OTP gateway.

namespace App\Services;

use App\Models\NotificationLog;
use App\Interfaces\OtpGatewayInterface;
use Illuminate\Support\Facades\Log;

class OtpService
{
    // Sends an OTP notification.
    public function sendOtp(string $phone, string $message, int $expire, ?string $gateway = null): array
    {
        $gateway = $gateway ?? config('notifications.default_otp_gateway', 'mocit_sms');
        $otpGateway = $this->resolveOtpGateway($gateway);
        $response = $otpGateway->sendOtp($phone, $message, $expire);
        NotificationLog::create([
            'type'      => 'otp',
            'gateway'   => $gateway,
            'recipient' => $phone,
            'message'   => $message,
            'response'  => json_encode($response),
        ]);
        return $response;
    }

    // Verifies an OTP.
    public function verifyOtp(string $otp, ?string $gateway = null, array $options = []): array
    {
        $gateway = $gateway ?? config('notifications.default_otp_gateway', 'mocit_sms');
        $otpGateway = $this->resolveOtpGateway($gateway);
        return $otpGateway->verifyOtp($otp, $options);
    }

    // Resolves the OTP gateway instance.
    protected function resolveOtpGateway(string $gateway): OtpGatewayInterface
    {
        switch (strtolower($gateway)) {
            case 'termii':
                return new \App\Services\OtpGateways\TermiiSmsOtpGatewayService();
            case 'termii_whatsapp':
                return new \App\Services\OtpGateways\TermiiWhatsappOtpGatewayService();
            case 'termii_email':
                return new \App\Services\OtpGateways\TermiiEmailOtpGatewayService();
            case 'termii_voice':
                return new \App\Services\OtpGateways\TermiiVoiceOtpGatewayService();
            case 'mocit_whatsapp':
                return new \App\Services\OtpGateways\MocitWhatsappOtpGatewayService();
            case 'mocit_sms':
            default:
                return new \App\Services\OtpGateways\MocitSmsOtpGatewayService();
        }
    }
}
```

---

#### 3.5.3 File: `PushNotificationService.php`
```php
<?php
// app/Services/PushNotificationService.php
// Last updated: 2025-02-06
// Provides a common interface for sending push notifications via the appropriate push gateway.

namespace App\Services;

use App\Interfaces\PushGatewayInterface;
use App\Services\PushGateways\GoogleFcmGatewayService;
use App\Services\PushGateways\OneSignalGatewayService;
use App\Services\PushGateways\WonderpushGatewayService;
use App\Services\PushGateways\AirshipGatewayService;
use App\Services\PushGateways\PushEngageGatewayService;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    // Sends a push notification using the specified gateway.
    public function sendPush($to, array $payload, ?string $gateway = null): array
    {
        $gateway = $gateway ?? config('notifications.default_push_gateway');
        $pushGateway = $this->resolvePushGateway($gateway);
        return $pushGateway->sendPush($to, $payload);
    }
    
    // Resolves the push gateway instance.
    protected function resolvePushGateway(string $gateway): PushGatewayInterface
    {
        switch (strtolower($gateway)) {
            case 'google_fcm':
                return new GoogleFcmGatewayService();
            case 'onesignal':
                return new OneSignalGatewayService();
            case 'wonderpush':
                return new WonderpushGatewayService();
            case 'airship':
                return new AirshipGatewayService();
            case 'pushengage':
                return new PushEngageGatewayService();
            default:
                return new GoogleFcmGatewayService();
        }
    }
}
```

---

## 3.6 Custom Channels  
*(All files in `app/Channels/`)*

### 3.6.1 File: `SmsGatewayChannel.php`
```php
<?php
// app/Channels/SmsGatewayChannel.php
// Last updated: 2025-02-06
// Custom channel for sending SMS notifications using NotificationService.

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\NotificationService;

class SmsGatewayChannel
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    // Called when a notification is sent via the SMS channel.
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toSms')) {
            return;
        }
        $data = $notification->toSms($notifiable);
        $to = $data['phone'] ?? $notifiable->routeNotificationFor('sms');
        $message = $data['message'] ?? '';
        $gateway = $data['gateway'] ?? null;
        return $this->notificationService->sendSms($to, $message, $gateway);
    }
}
```

---

### 3.6.2 File: `EmailGatewayChannel.php`
```php
<?php
// app/Channels/EmailGatewayChannel.php
// Last updated: 2025-02-06
// Custom channel for sending Email notifications using NotificationService.

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\NotificationService;

class EmailGatewayChannel
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    // Called when a notification is sent via the Email channel.
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toEmail')) {
            return;
        }
        $data = $notification->toEmail($notifiable);
        $to = $data['email'] ?? $notifiable->routeNotificationFor('email');
        $subject = $data['subject'] ?? '';
        $body = $data['body'] ?? '';
        $gateway = $data['gateway'] ?? null;
        return $this->notificationService->sendEmail($to, $subject, $body, $gateway);
    }
}
```

---

### 3.6.3 File: `OtpGatewayChannel.php`
```php
<?php
// app/Channels/OtpGatewayChannel.php
// Last updated: 2025-02-06
// Custom channel for sending OTP notifications using OtpService.

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\OtpService;

class OtpGatewayChannel
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    // Called when a notification is sent via the OTP channel.
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toOtp')) {
            return;
        }
        $data = $notification->toOtp($notifiable);
        $phone = $data['phone'] ?? $notifiable->routeNotificationFor('otp');
        $message = $data['message'] ?? '';
        $expire = $data['expire'] ?? 300;
        $gateway = $data['gateway'] ?? null;
        return $this->otpService->sendOtp($phone, $message, $expire, $gateway);
    }
}
```

---

### 3.6.4 File: `PushNotificationChannel.php`
```php
<?php
// app/Channels/PushNotificationChannel.php
// Last updated: 2025-02-06
// Custom channel for sending push notifications using PushNotificationService.

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\PushNotificationService;

class PushNotificationChannel
{
    protected PushNotificationService $pushNotificationService;

    public function __construct(PushNotificationService $pushNotificationService)
    {
        $this->pushNotificationService = $pushNotificationService;
    }

    // Called when a notification is sent via the Push channel.
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toPush')) {
            return;
        }
        $data = $notification->toPush($notifiable);
        $to = $data['to'] ?? $notifiable->routeNotificationFor('push');
        $payload = $data['payload'] ?? [];
        $gateway = $data['gateway'] ?? null;
        return $this->pushNotificationService->sendPush($to, $payload, $gateway);
    }
}
```

---

## 3.7 Notification Classes  
*(All files in `app/Notifications/`)*

### 3.7.1 File: `SmsNotification.php`
```php
<?php
// app/Notifications/SmsNotification.php
// Last updated: 2025-02-06
// Notification class for sending SMS messages.

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Channels\SmsGatewayChannel;

class SmsNotification extends Notification
{
    protected string $message;
    protected ?string $gateway;

    public function __construct(string $message, ?string $gateway = null)
    {
        $this->message = $message;
        $this->gateway = $gateway;
    }

    // Specifies the channels to use.
    public function via($notifiable): array
    {
        return [SmsGatewayChannel::class];
    }

    // Formats the data for the SMS channel.
    public function toSms($notifiable): array
    {
        return [
            'phone' => $notifiable->routeNotificationFor('sms'),
            'message' => $this->message,
            'gateway' => $this->gateway,
        ];
    }
}
```

---

### 3.7.2 File: `EmailNotification.php`
```php
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
```

---

### 3.7.3 File: `OtpNotification.php`
```php
<?php
// app/Notifications/OtpNotification.php
// Last updated: 2025-02-06
// Notification class for sending OTP messages.

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Channels\OtpGatewayChannel;

class OtpNotification extends Notification
{
    protected string $message;
    protected int $expire;
    protected ?string $gateway;

    public function __construct(string $message, int $expire = 300, ?string $gateway = null)
    {
        $this->message = $message;
        $this->expire = $expire;
        $this->gateway = $gateway;
    }

    // Specifies the channels to use.
    public function via($notifiable): array
    {
        return [OtpGatewayChannel::class];
    }

    // Formats the data for the OTP channel.
    public function toOtp($notifiable): array
    {
        return [
            'phone' => $notifiable->routeNotificationFor('otp'),
            'message' => $this->message,
            'expire' => $this->expire,
            'gateway' => $this->gateway,
        ];
    }
}
```

---

### 3.7.4 File: `PushNotification.php`
```php
<?php
// app/Notifications/PushNotification.php
// Last updated: 2025-02-06
// Notification class for sending push notifications.

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Channels\PushNotificationChannel;

class PushNotification extends Notification
{
    protected array $payload;
    protected ?string $gateway;

    public function __construct(array $payload, ?string $gateway = null)
    {
        $this->payload = $payload;
        $this->gateway = $gateway;
    }

    // Specifies the channels to use.
    public function via($notifiable): array
    {
        return [PushNotificationChannel::class];
    }

    // Formats the data for the Push channel.
    public function toPush($notifiable): array
    {
        return [
            'to' => $notifiable->routeNotificationFor('push'),
            'payload' => $this->payload,
            'gateway' => $this->gateway,
        ];
    }
}
```

---

## 3.8 Controllers  
*(All files in `app/Http/Controllers/`)*

### 3.8.1 File: `NotificationController.php`
```php
<?php
// app/Http/Controllers/NotificationController.php
// Last updated: 2025-02-06
// Controller for sending SMS, Email, and OTP notifications.

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SmsNotification;
use App\Notifications\EmailNotification;
use App\Notifications\OtpNotification;
use App\Services\OtpService;

class NotificationController extends Controller
{
    protected OtpService $otpService;
    
    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }
    
    // Sends an SMS notification.
    public function sendSms(Request $request)
    {
        $validated = $request->validate([
            'to'      => 'required',
            'message' => 'required',
            'gateway' => 'sometimes|string',
        ]);
        $response = Notification::route('sms', $validated['to'])
            ->notify(new SmsNotification($validated['message'], $validated['gateway'] ?? null));
        return response()->json([
            'status' => 'SMS notification sent',
            'response' => $response
        ]);
    }
    
    // Sends an Email notification.
    public function sendEmail(Request $request)
    {
        $validated = $request->validate([
            'to'      => 'required|email',
            'subject' => 'required|string',
            'body'    => 'required|string',
            'gateway' => 'sometimes|string',
        ]);
        $response = Notification::route('email', $validated['to'])
            ->notify(new EmailNotification($validated['subject'], $validated['body'], $validated['gateway'] ?? null));
        return response()->json([
            'status' => 'Email notification sent',
            'response' => $response
        ]);
    }
    
    // Sends an OTP notification.
    public function sendOtp(Request $request)
    {
        $validated = $request->validate([
            'to'      => 'required',
            'message' => 'required',  // Include the {{otp}} shortcode.
            'expire'  => 'required|integer',
            'gateway' => 'sometimes|string', // e.g., "mocit_sms", "mocit_whatsapp", "termii_sms", etc.
        ]);
        $response = Notification::route('otp', $validated['to'])
            ->notify(new OtpNotification($validated['message'], $validated['expire'], $validated['gateway'] ?? null));
        return response()->json([
            'status' => 'OTP notification sent',
            'response' => $response
        ]);
    }
    
    // Verifies an OTP.
    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'otp'     => 'required|string',
            'phone'   => 'required',
            'gateway' => 'sometimes|string',
        ]);
        $result = $this->otpService->verifyOtp($validated['otp'], $validated['gateway'] ?? null, ['phone' => $validated['phone']]);
        return response()->json($result);
    }
}
```

---

### 3.8.2 File: `PushNotificationController.php`
```php
<?php
// app/Http/Controllers/PushNotificationController.php
// Last updated: 2025-02-06
// Controller for sending push notifications.

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\PushNotification;
use Illuminate\Support\Facades\Notification;

class PushNotificationController extends Controller
{
    // Sends a push notification.
    public function sendPush(Request $request)
    {
        $validated = $request->validate([
            'to'      => 'required', // Device token or topic
            'payload' => 'required|array', // Must contain keys like title, body, data, etc.
            'gateway' => 'sometimes|string', // Optional: specify push gateway (e.g., google_fcm, onesignal, etc.)
        ]);

        $response = Notification::route('push', $validated['to'])
            ->notify(new PushNotification($validated['payload'], $validated['gateway'] ?? null));

        return response()->json([
            'status' => 'Push notification sent',
            'response' => $response
        ]);
    }
}
```

---

### 3.8.3 File: `NotificationLogController.php`
```php
<?php
// app/Http/Controllers/NotificationLogController.php
// Last updated: 2025-02-06
// Controller for managing NotificationLog records.

namespace App\Http\Controllers;

use App\Models\NotificationLog;
use Illuminate\Http\Request;

class NotificationLogController extends Controller
{
    // Retrieves all notification logs.
    public function index()
    {
        $logs = NotificationLog::orderBy('created_at', 'desc')->get();
        return response()->json($logs);
    }
    
    // Retrieves a single notification log by ID.
    public function show($id)
    {
        $log = NotificationLog::find($id);
        if (!$log) {
            return response()->json(['error' => 'Log not found'], 404);
        }
        return response()->json($log);
    }
    
    // Deletes a notification log by ID.
    public function destroy($id)
    {
        $log = NotificationLog::find($id);
        if (!$log) {
            return response()->json(['error' => 'Log not found'], 404);
        }
        $log->delete();
        return response()->json(['status' => 'Log deleted']);
    }
    
    // Empties the notification logs table.
    public function empty()
    {
        NotificationLog::truncate();
        return response()->json(['status' => 'Notification logs table emptied']);
    }
}
```

---

### 3.8.4 File: `NotificationMessageController.php`
```php
<?php
// app/Http/Controllers/NotificationMessageController.php
// Last updated: 2025-02-06
// Controller for managing NotificationMessage records, filtering, and retrying failed messages.

namespace App\Http\Controllers;

use App\Models\NotificationMessage;
use Illuminate\Http\Request;

class NotificationMessageController extends Controller
{
    // Lists all notification messages.
    public function index()
    {
        $messages = NotificationMessage::orderBy('created_at', 'desc')->get();
        return response()->json($messages);
    }
    
    // Retrieves a single notification message by ID.
    public function show($id)
    {
        $message = NotificationMessage::find($id);
        if (!$message) {
            return response()->json(['error' => 'Message not found'], 404);
        }
        return response()->json($message);
    }
    
    // Deletes a notification message by ID.
    public function destroy($id)
    {
        $message = NotificationMessage::find($id);
        if (!$message) {
            return response()->json(['error' => 'Message not found'], 404);
        }
        $message->delete();
        return response()->json(['status' => 'Message deleted']);
    }
    
    // Empties the notification messages table.
    public function empty()
    {
        NotificationMessage::truncate();
        return response()->json(['status' => 'Notification messages table emptied']);
    }
    
    // Filters notification messages based on query parameters.
    public function filter(Request $request)
    {
        $query = NotificationMessage::query();
        if ($request->has('gateway')) {
            $query->where('gateway', $request->gateway);
        }
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        if ($request->has('recipient')) {
            $query->where('recipient', $request->recipient);
        }
        if ($request->has('status')) {
            $query->where('response_status', $request->status);
        }
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }
        $messages = $query->orderBy('created_at', 'desc')->get();
        return response()->json($messages);
    }
    
    // Cron job endpoint to retry failed messages.
    public function retryFailed()
    {
        $failed = NotificationMessage::where('http_status_code', '<>', 200)
            ->orWhere('response_status', '<>', 'success')
            ->get();
        foreach ($failed as $message) {
            // For demonstration, mark as retried.
            $message->update(['response_status' => 'retried']);
        }
        return response()->json(['status' => 'Failed messages retried', 'count' => $failed->count()]);
    }
}
```

---

### 3.8.5 File: `AnotherController.php`
```php
<?php
// app/Http/Controllers/AnotherController.php
// Last updated: 2025-02-06
// Example controller demonstrating usage of NotificationService, OtpService, and PushNotificationService.

namespace App\Http\Controllers;

use App\Services\NotificationService;
use App\Services\OtpService;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;

class AnotherController extends Controller
{
    protected NotificationService $notificationService;
    protected OtpService $otpService;
    protected PushNotificationService $pushNotificationService;
    
    public function __construct(NotificationService $notificationService, OtpService $otpService, PushNotificationService $pushNotificationService)
    {
        $this->notificationService = $notificationService;
        $this->otpService = $otpService;
        $this->pushNotificationService = $pushNotificationService;
    }
    
    // Sends a custom SMS.
    public function customSms(Request $request)
    {
        $to = '+1234567890';
        $message = 'This is a custom SMS from AnotherController.';
        $response = $this->notificationService->sendSms($to, $message);
        return response()->json($response);
    }
    
    // Sends a custom Email.
    public function customEmail(Request $request)
    {
        $to = 'user@example.com';
        $subject = 'Custom Email';
        $body = 'This is a custom email body.';
        $response = $this->notificationService->sendEmail($to, $subject, $body);
        return response()->json($response);
    }
    
    // Sends a custom OTP.
    public function customOtp(Request $request)
    {
        $to = '+1234567890';
        $message = 'Your OTP is {{otp}}. It expires in 5 minutes.';
        $expire = 300;
        $response = $this->otpService->sendOtp($to, $message, $expire);
        return response()->json($response);
    }
    
    // Sends a custom push notification.
    public function customPush(Request $request)
    {
        $to = 'your_device_token_or_topic';
        $payload = [
            'title' => 'Test Push Notification',
            'body'  => 'This is a test push notification sent from AnotherController.',
            'data'  => ['key' => 'value'],
        ];
        $response = $this->pushNotificationService->sendPush($to, $payload);
        return response()->json($response);
    }
}
```

---

## 3.9 Routes

### 3.9.1 File: `routes/api.php`
```php
<?php
// routes/api.php
// Last updated: 2025-02-06
// API routes for sending notifications (SMS, Email, OTP, and Push) and managing logs/messages.

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PushNotificationController;
use App\Http\Controllers\NotificationLogController;
use App\Http\Controllers\NotificationMessageController;
use App\Http\Controllers\AnotherController;

Route::post('/sms/send', [NotificationController::class, 'sendSms']);
Route::post('/email/send', [NotificationController::class, 'sendEmail']);
Route::post('/otp/send', [NotificationController::class, 'sendOtp']);
Route::post('/otp/verify', [NotificationController::class, 'verifyOtp']);

// New endpoint for push notifications.
Route::post('/push/send', [PushNotificationController::class, 'sendPush']);

// Routes for managing notification logs.
Route::get('/logs', [NotificationLogController::class, 'index']);
Route::get('/logs/{id}', [NotificationLogController::class, 'show']);
Route::delete('/logs/{id}', [NotificationLogController::class, 'destroy']);
Route::delete('/logs/empty', [NotificationLogController::class, 'empty']);

// Routes for managing notification messages.
Route::get('/messages', [NotificationMessageController::class, 'index']);
Route::get('/messages/{id}', [NotificationMessageController::class, 'show']);
Route::delete('/messages/{id}', [NotificationMessageController::class, 'destroy']);
Route::delete('/messages/empty', [NotificationMessageController::class, 'empty']);
Route::get('/messages/filter', [NotificationMessageController::class, 'filter']);
Route::get('/messages/retry', [NotificationMessageController::class, 'retryFailed']);

// Example routes for custom usage.
Route::get('/another/sms', [AnotherController::class, 'customSms']);
Route::get('/another/email', [AnotherController::class, 'customEmail']);
Route::get('/another/otp', [AnotherController::class, 'customOtp']);
Route::get('/another/push', [AnotherController::class, 'customPush']);
```

---

## 3.10 Service Provider Bindings

### 3.10.1 File: `app/Providers/AppServiceProvider.php`
```php
<?php
// app/Providers/AppServiceProvider.php
// Last updated: 2025-02-06
// Binds custom channels and core services as singletons.

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Channels\SmsGatewayChannel;
use App\Channels\EmailGatewayChannel;
use App\Channels\OtpGatewayChannel;
use App\Channels\PushNotificationChannel;
use App\Services\NotificationService;
use App\Services\OtpService;
use App\Services\PushNotificationService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(NotificationService::class, function ($app) {
            return new NotificationService();
        });
        $this->app->singleton(OtpService::class, function ($app) {
            return new OtpService();
        });
        $this->app->singleton(PushNotificationService::class, function ($app) {
            return new PushNotificationService();
        });
        $this->app->singleton(SmsGatewayChannel::class, function ($app) {
            return new SmsGatewayChannel($app->make(NotificationService::class));
        });
        $this->app->singleton(EmailGatewayChannel::class, function ($app) {
            return new EmailGatewayChannel($app->make(NotificationService::class));
        });
        $this->app->singleton(OtpGatewayChannel::class, function ($app) {
            return new OtpGatewayChannel($app->make(OtpService::class));
        });
        $this->app->singleton(PushNotificationChannel::class, function ($app) {
            return new PushNotificationChannel($app->make(PushNotificationService::class));
        });
    }
    public function boot()
    {
        //
    }
}
```

---

## 3.11 Mailable & Email View

### 3.11.1 File: `app/Mail/GenericEmail.php`
```php
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
```

---

### 3.11.2 File: `resources/views/emails/generic.blade.php`
```html
<!-- resources/views/emails/generic.blade.php -->
<!-- Last updated: 2025-02-06 -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $subject ?? 'Notification' }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 3px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #007BFF;
            padding: 20px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
            font-size: 16px;
            line-height: 1.5;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        .btn {
            display: inline-block;
            background-color: #28a745;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>{{ $subject ?? 'Notification' }}</h1>
        </div>
        <div class="content">
            {!! $body !!}
            <p style="text-align: center;">
                <a href="#" class="btn">Call to Action</a>
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} My Company. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
```

---

# 4. Final Usage Instructions Recap

1. **Installation:**
   - Create a Laravel 11 project:
     ```bash
     composer create-project laravel/laravel my-notification-project
     ```
   - Copy the files above into their corresponding directories.
   - Update the **.env** file with your API keys, credentials, and default gateway settings.
   - Run database migrations:
     ```bash
     php artisan migrate
     ```
   - Install required packages:
     ```bash
     composer require guzzlehttp/guzzle
     composer require twilio/sdk
     ```
   - Start the Laravel server:
     ```bash
     php artisan serve
     ```

2. **Testing the API Endpoints:**  
   Use Postman or cURL to test endpoints:
   - **SMS:** POST `/api/sms/send`
   - **Email:** POST `/api/email/send`
   - **OTP:** POST `/api/otp/send` and `/api/otp/verify`
   - **Push:** POST `/api/push/send`
   - **Logs & Messages:** Use GET and DELETE endpoints as needed.
   - **Custom Usage:** Try GET `/api/another/sms`, `/api/another/email`, `/api/another/otp`, and `/api/another/push`.

3. **API Response Template:**  
   Use the response templates defined in `config/NotificationResponse.php` to standardize API responses.

4. **Customization:**  
   Adjust default gateways and credentials in **.env** and the configuration files as needed.

---

This complete code base and folder structure, along with these step-by-step instructions, should allow you to set up, use, and customize the multi‑gateway notification system in Laravel 11.

Happy coding!
