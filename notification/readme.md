Below is the complete updated `README.md` file in Markdown format. This file includes all installation, usage, and configuration instructions along with the complete list of required composer dependencies.

---

```markdown
# Laravel Multi-Gateway Notification System

**Last Updated: 2025-02-06**

This project is a multi-gateway notification system built with Laravel 11. It provides a unified API for sending notifications via SMS, Email, OTP, and Push using various service providers.

## Features

- **SMS Gateways:**  
  - Twilio  
  - Africa's Talking  
  - SMPP (Simulated)  
  - AWS SNS  
  - Mocit SMS  
  - Mocit WhatsApp  
  - Termii SMS

- **Email Gateways:**  
  - SMTP (using Laravel’s Mail)  
  - AWS SES  
  - Mailgun  
  - Gmail (simulated)  
  - Sendgrid  
  - Custom HTTP Email

- **OTP Gateways:**  
  - Mocit SMS OTP  
  - Mocit WhatsApp OTP  
  - Termii SMS OTP  
  - Termii WhatsApp OTP  
  - Termii Email OTP  
  - Termii Voice OTP

- **Push Notification Gateways:**  
  - Google FCM  
  - OneSignal  
  - Wonderpush  
  - Airship  
  - PushEngage

- **Response Logging:**  
  - All notification requests are logged in the database.

- **Standardized API Responses:**  
  - Uses a common response template (configured in `config/NotificationResponse.php`) that includes a status, response code, and message.

## Requirements

- **PHP:** 8.0 or later  
- **Laravel:** 11.x  
- **Composer:** 2.x  
- **Database:** MySQL, PostgreSQL, or SQLite

## Composer Dependencies

This project requires the following composer packages:

- **twilio/sdk** – For Twilio SMS integration.
- **aws/aws-sdk-php** – Provides AWS SNS and AWS SES functionality.
- **sendgrid/sendgrid** – For Sendgrid Email integration.
- **mailgun/mailgun-php** – For Mailgun Email integration.
- **guzzlehttp/guzzle** – For making HTTP requests.

To install these dependencies, run the following commands in your project root:

```bash
composer require twilio/sdk
composer require aws/aws-sdk-php
composer require sendgrid/sendgrid
composer require mailgun/mailgun-php
composer require guzzlehttp/guzzle
```

## Installation

1. **Create a New Laravel Project:**

   Open your terminal and run:
   ```bash
   composer create-project laravel/laravel my-notification-project
   ```

2. **File Structure:**

   Copy the project files into your Laravel project according to the following structure:

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

3. **Update Environment Variables:**

   Edit the `.env` file with your specific API keys, credentials, and default settings.

4. **Run Migrations:**

   ```bash
   php artisan migrate
   ```

5. **Install Composer Dependencies:**

   ```bash
   composer require twilio/sdk
   composer require aws/aws-sdk-php
   composer require sendgrid/sendgrid
   composer require mailgun/mailgun-php
   composer require guzzlehttp/guzzle
   ```

6. **Start the Laravel Server:**

   ```bash
   php artisan serve
   ```

---

## API Endpoints

### SMS Notifications

- **Endpoint:** `POST /api/sms/send`  
- **Example Payload:**
  ```json
  {
    "to": "+1234567890",
    "message": "Your SMS message goes here.",
    "gateway": "twilio"
  }
  ```

### Email Notifications

- **Endpoint:** `POST /api/email/send`  
- **Example Payload:**
  ```json
  {
    "to": "user@example.com",
    "subject": "Test Email",
    "body": "This is a test email message.",
    "gateway": "smtp"
  }
  ```

### OTP Notifications

- **Endpoint:** `POST /api/otp/send`  
- **Example Payload:**
  ```json
  {
    "to": "+1234567890",
    "message": "Your OTP is {{otp}}. It expires in 5 minutes.",
    "expire": 300,
    "gateway": "mocit_sms"
  }
  ```
- **Verify OTP:**  
  **Endpoint:** `POST /api/otp/verify`  
  **Example Payload:**
  ```json
  {
    "otp": "123456",
    "phone": "+1234567890",
    "gateway": "mocit_sms"
  }
  ```

### Push Notifications

- **Endpoint:** `POST /api/push/send`  
- **Example Payload:**
  ```json
  {
    "to": "your_device_token_or_topic",
    "payload": {
       "title": "Test Push",
       "body": "This is a test push notification.",
       "data": {"key": "value"}
    },
    "gateway": "google_fcm"
  }
  ```

### Logs & Messages Management

- **Logs:**
  - `GET /api/logs` – List all logs
  - `GET /api/logs/{id}` – Retrieve a specific log
  - `DELETE /api/logs/{id}` – Delete a specific log
  - `DELETE /api/logs/empty` – Empty the logs table

- **Messages:**
  - `GET /api/messages` – List all notification messages
  - `GET /api/messages/{id}` – Retrieve a specific message
  - `DELETE /api/messages/{id}` – Delete a specific message
  - `DELETE /api/messages/empty` – Empty the messages table
  - `GET /api/messages/filter?gateway=twilio&type=sms` – Filter messages
  - `GET /api/messages/retry` – Retry failed messages

### Custom Endpoints (Example Usage)

- `GET /api/another/sms`
- `GET /api/another/email`
- `GET /api/another/otp`
- `GET /api/another/push`

---

## API Response Template

All responses are standardized using the templates defined in `config/NotificationResponse.php`. For example, if required parameters are missing, you can return:

```php
$responseTemplate = config('NotificationResponse.missing_parameters');
return response()->json($responseTemplate, $responseTemplate['http_code']);
```

---

## Customization & Testing

- **Testing:**  
  Use Postman or cURL to send requests to the API endpoints.
- **Error Handling:**  
  Each service class logs errors using Laravel’s logging system.
- **Customization:**  
  Adjust default gateways and credentials by editing the `.env` file and the configuration files in `config/`.

---

## Composer Dependencies Recap

Ensure you have installed the following dependencies:

```bash
composer require twilio/sdk
composer require aws/aws-sdk-php
composer require sendgrid/sendgrid
composer require mailgun/mailgun-php
composer require guzzlehttp/guzzle
```

This installs the Twilio SDK, AWS SDK for PHP (which supports AWS SNS and SES), Sendgrid, Mailgun, and Guzzle for HTTP requests.

---

## License

This project is open-sourced under the [MIT License](LICENSE).

---

## Conclusion

This Laravel multi-gateway notification system provides a unified API for sending notifications via SMS, Email, OTP, and Push. Its modular design and configurable settings make it easy to integrate with multiple service providers and extend as needed.

Happy Coding!
```

---

Simply copy the above content into a file named `README.md` in the root directory of your project. This comprehensive README includes all implementation and usage instructions as well as the required composer dependencies.

Happy coding!
