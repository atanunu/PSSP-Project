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
