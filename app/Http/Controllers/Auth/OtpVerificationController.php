<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use App\Notifications\OtpVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OtpVerificationController extends Controller
{
    /**
     * Mostrar la página de verificación OTP
     */
    public function show(): View
    {
        return view('auth.verify-otp');
    }

    /**
     * Verificar el código OTP
     */
    public function verify(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'otp_code' => 'required|string|size:6|regex:/^[0-9]{6}$/'
        ], [
            'otp_code.required' => 'El código OTP es obligatorio.',
            'otp_code.size' => 'El código OTP debe tener exactamente 6 dígitos.',
            'otp_code.regex' => 'El código OTP debe contener solo números.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $email = session('verification_email');
        
        if (!$email) {
            return redirect()->route('register')
                ->with('error', 'Sesión expirada. Por favor, regístrate nuevamente.');
        }

        $otpCode = OtpCode::where('email', $email)
            ->where('code', $request->otp_code)
            ->where('type', 'email_verification')
            ->where('used', false)
            ->first();

        if (!$otpCode || !$otpCode->isValid()) {
        return redirect()->back()
            ->with('error', 'Código de verificación inválido o expirado. Por favor, intenta nuevamente.');
        }

        // Marcar el código como usado
        $otpCode->markAsUsed();

        // Buscar el usuario y verificar su email
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return redirect()->route('register')
                ->with('error', 'Usuario no encontrado. Por favor, regístrate nuevamente.');
        }

        // Marcar el email como verificado
        $user->markEmailAsVerified();
        $user->update(['status' => 'active']);

        // Limpiar la sesión
        session()->forget('verification_email');

        // Autenticar al usuario
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', '¡Email verificado exitosamente! Bienvenido a Taskly.');
    }

    /**
     * Reenviar código OTP
     */
    public function resend(Request $request): RedirectResponse
    {
        $email = session('verification_email');
        
        if (!$email) {
            return redirect()->route('register')
                ->with('error', 'Sesión expirada. Por favor, regístrate nuevamente.');
        }

        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return redirect()->route('register')
                ->with('error', 'Usuario no encontrado. Por favor, regístrate nuevamente.');
        }

        // Crear nuevo código OTP
        $otpCode = OtpCode::createCode($email, 'email_verification', 10);

        // Enviar notificación
        $user->notify(new OtpVerificationNotification($otpCode->code));

        return redirect()->back()
            ->with('success', 'Código de verificación reenviado exitosamente. Revisa tu email.');
    }
}
