<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;

class EmailVerificationController extends Controller
{
    /**
     * Enviar notificación de verificación de email
     */
    public function sendVerificationEmail(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'El email ya ha sido verificado',
                'verified' => true
            ], 200);
        }

        $user->notify(new VerifyEmailNotification());

        return response()->json([
            'message' => 'Se ha enviado un enlace de verificación a tu correo electrónico',
            'verified' => false
        ], 200);
    }

    /**
     * Verificar email con token
     */
    public function verify(Request $request): JsonResponse
    {
        $user = User::find($request->route('id'));

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        if (!hash_equals(sha1($user->getEmailForVerification()), $request->route('hash'))) {
            return response()->json([
                'message' => 'Enlace de verificación inválido'
            ], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'El email ya ha sido verificado',
                'verified' => true
            ], 200);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json([
            'message' => 'Email verificado exitosamente',
            'verified' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at
            ]
        ], 200);
    }

    /**
     * Verificar estado de verificación del email
     */
    public function checkVerificationStatus(): JsonResponse
    {
        $user = Auth::user();
        
        return response()->json([
            'verified' => $user->hasVerifiedEmail(),
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at
        ], 200);
    }

    /**
     * Reenviar email de verificación
     */
    public function resendVerificationEmail(): JsonResponse
    {
        $user = Auth::user();
        
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'El email ya ha sido verificado',
                'verified' => true
            ], 200);
        }

        $user->notify(new VerifyEmailNotification());

        return response()->json([
            'message' => 'Se ha reenviado el email de verificación',
            'verified' => false
        ], 200);
    }
}
