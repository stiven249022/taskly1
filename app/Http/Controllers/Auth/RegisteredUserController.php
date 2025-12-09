<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OtpCode;
use App\Notifications\OtpVerificationNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['sometimes', 'in:student'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'student', // Siempre forzar rol de estudiante
            'status' => 'pending', // Cambiar a pending hasta verificar OTP
            'password' => Hash::make($request->password),
        ]);

        // Generar código OTP
        $otpCode = OtpCode::createCode($user->email, 'email_verification', 10);

        // Enviar notificación OTP
        $user->notify(new OtpVerificationNotification($otpCode->code));

        // Guardar email en sesión para verificación
        session(['verification_email' => $user->email]);

        return redirect()->route('otp.verify')
            ->with('success', '¡Registro exitoso! Hemos enviado un código de verificación a tu email.');
    }
}
