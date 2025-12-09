<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;

class GoogleAuthController extends Controller
{
    /**
     * Obtener URL de redirección a Google
     */
    public function getGoogleAuthUrl(): JsonResponse
    {
        try {
            $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
            
            return response()->json([
                'auth_url' => $url,
                'message' => 'URL de autenticación con Google generada'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar URL de autenticación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manejar callback de autenticación con Google
     */
    public function handleGoogleCallback(Request $request): JsonResponse
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Validar datos del usuario de Google
            $validator = Validator::make([
                'email' => $googleUser->email,
                'name' => $googleUser->name,
                'google_id' => $googleUser->id
            ], [
                'email' => 'required|email',
                'name' => 'required|string|max:255',
                'google_id' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Datos de Google inválidos',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Buscar o crear usuario
            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => now(), // Google ya verifica el email
                    'status' => 'active'
                ]
            );

            // Generar token de acceso
            $token = $user->createToken('google-auth-token')->plainTextToken;

            return response()->json([
                'message' => 'Autenticación exitosa con Google',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'status' => $user->status,
                    'role' => $user->role
                ],
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error en la autenticación con Google',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar estado de autenticación con Google
     */
    public function checkGoogleAuthStatus(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        return response()->json([
            'authenticated' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->google_id,
                'email_verified_at' => $user->email_verified_at,
                'status' => $user->status
            ]
        ], 200);
    }

    /**
     * Desconectar cuenta de Google
     */
    public function disconnectGoogle(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        // Revocar token actual
        $user->tokens()->delete();
        
        // Limpiar google_id (opcional)
        $user->update(['google_id' => null]);

        return response()->json([
            'message' => 'Cuenta de Google desconectada exitosamente'
        ], 200);
    }
}
