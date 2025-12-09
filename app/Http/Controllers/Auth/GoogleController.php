<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

class GoogleController extends Controller
{
    /**
     * Redirige al usuario a Google para autenticación
     */
    public function redirectToGoogle()
    {
        try {
            $url = \Laravel\Socialite\Facades\Socialite::driver('google')
                ->redirect()
                ->getTargetUrl();
                
            // Agregar parámetro para forzar selección de cuenta
            $url .= '&prompt=select_account';
                
            return redirect($url);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al redirigir a Google: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Error al conectar con Google. Inténtalo de nuevo.');
        }
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Verificar si el usuario ya existe
            $existingUser = User::where('email', $googleUser->email)->first();
            
            if ($existingUser) {
                // Si el usuario existe, actualizar su google_id si no lo tiene
                if (!$existingUser->google_id) {
                    $existingUser->update(['google_id' => $googleUser->id]);
                }
                $user = $existingUser;
            } else {
                // Crear nuevo usuario
                $user = User::create([
                    'name' => $googleUser->name,
                    'last_name' => $googleUser->name, // Usar el nombre completo como apellido también
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt(Str::random(24)),
                    'email_verified_at' => now(),
                    'status' => 'active',
                    'role' => 'student' // Rol por defecto
                ]);
            }

            // Verificar que el usuario esté activo
            if ($user->status !== 'active') {
                return redirect()->route('login')->with('error', 'Tu cuenta está pendiente de aprobación o ha sido desactivada.');
            }

            Auth::login($user);

            return redirect()->intended('dashboard');
        } catch (\Exception $e) {
            Log::error('Error en Google OAuth: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Error al autenticar con Google. Por favor, intenta de nuevo.');
        }
    }
} 