<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\GoogleAuthController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas de autenticación (públicas)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])
        ->name('api.auth.register');
    
    Route::post('/login', [AuthController::class, 'login'])
        ->name('api.auth.login');
});

// Rutas de verificación de email
Route::prefix('email')->middleware('auth:sanctum')->group(function () {
    Route::post('/send-verification', [EmailVerificationController::class, 'sendVerificationEmail'])
        ->name('api.email.send-verification');
    
    Route::get('/verification-status', [EmailVerificationController::class, 'checkVerificationStatus'])
        ->name('api.email.verification-status');
    
    Route::post('/resend-verification', [EmailVerificationController::class, 'resendVerificationEmail'])
        ->name('api.email.resend-verification');
});

// Ruta pública para verificar email (sin autenticación)
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->name('api.email.verify');

// Rutas de autenticación con Google
Route::prefix('auth/google')->group(function () {
    Route::get('/url', [GoogleAuthController::class, 'getGoogleAuthUrl'])
        ->name('api.google.auth-url');
    
    Route::get('/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
        ->name('api.google.callback');
});

// Rutas protegidas para usuarios autenticados con Google
Route::prefix('auth/google')->middleware('auth:sanctum')->group(function () {
    Route::get('/status', [GoogleAuthController::class, 'checkGoogleAuthStatus'])
        ->name('api.google.status');
    
    Route::post('/disconnect', [GoogleAuthController::class, 'disconnectGoogle'])
        ->name('api.google.disconnect');
});

// Rutas protegidas para usuarios autenticados
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])
        ->name('api.auth.logout');
    
    Route::get('/auth/me', [AuthController::class, 'me'])
        ->name('api.auth.me');
    
    Route::post('/auth/profile', [AuthController::class, 'updateProfile'])
        ->name('api.auth.profile');
    
    // Rutas de notificaciones
    Route::prefix('notifications')->group(function () {
        Route::post('/task', [NotificationController::class, 'sendTaskNotification'])
            ->name('api.notifications.task');
        
        Route::post('/project', [NotificationController::class, 'sendProjectNotification'])
            ->name('api.notifications.project');
        
        Route::post('/exam', [NotificationController::class, 'sendExamNotification'])
            ->name('api.notifications.exam');
        
        Route::get('/settings', [NotificationController::class, 'getNotificationSettings'])
            ->name('api.notifications.settings');
        
        Route::post('/settings', [NotificationController::class, 'updateNotificationSettings'])
            ->name('api.notifications.settings.update');
        
        Route::post('/send-automatic', [NotificationController::class, 'sendAutomaticNotifications'])
            ->name('api.notifications.automatic');
    });
}); 