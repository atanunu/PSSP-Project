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

// New endpoints for sending to multiple recipients:
Route::post('/multi/sms/send', [MultiRecipientController::class, 'sendSmsMulti']);
Route::post('/multi/email/send', [MultiRecipientController::class, 'sendEmailMulti']);
Route::post('/multi/push/send', [MultiRecipientController::class, 'sendPushMulti']);

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
