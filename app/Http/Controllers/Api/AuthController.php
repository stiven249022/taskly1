<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Registrar nuevo usuario
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'sometimes|string|in:student'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos de validación incorrectos',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student', // Siempre forzar rol de estudiante
            'status' => 'pending'
        ]);

        // Enviar email de verificación
        $user->notify(new VerifyEmailNotification());

        // Generar token de acceso
        $token = $user->createToken('auth-token')->plainTextToken;

        event(new Registered($user));

        return response()->json([
            'message' => 'Usuario registrado exitosamente. Se ha enviado un email de verificación.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
                'email_verified_at' => $user->email_verified_at
            ],
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }

    /**
     * Iniciar sesión
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos de validación incorrectos',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        // Verificar si el usuario está activo
        if ($user->status !== 'active') {
            return response()->json([
                'message' => 'Tu cuenta está pendiente de aprobación o ha sido desactivada'
            ], 403);
        }

        // Generar token de acceso
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
                'email_verified_at' => $user->email_verified_at
            ],
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente'
        ], 200);
    }

    /**
     * Obtener información del usuario autenticado
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
                'email_verified_at' => $user->email_verified_at,
                'profile_photo_url' => $user->profile_photo_url,
                'dark_mode' => $user->dark_mode,
                'created_at' => $user->created_at
            ]
        ], 200);
    }

    /**
     * Actualizar perfil del usuario
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'bio' => 'sometimes|string|max:500',
            'phone' => 'sometimes|string|max:20',
            'location' => 'sometimes|string|max:255',
            'timezone' => 'sometimes|string|max:50',
            'language' => 'sometimes|string|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos de validación incorrectos',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($request->only([
            'name', 'last_name', 'bio', 'phone', 
            'location', 'timezone', 'language'
        ]));

        return response()->json([
            'message' => 'Perfil actualizado exitosamente',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'bio' => $user->bio,
                'phone' => $user->phone,
                'location' => $user->location,
                'timezone' => $user->timezone,
                'language' => $user->language
            ]
        ], 200);
    }
}
