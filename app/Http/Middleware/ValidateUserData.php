<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateUserData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Validar que el usuario tenga datos básicos completos
        $user = auth()->user();
        
        if ($user && !$user->email_verified_at) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Por favor, verifica tu dirección de correo electrónico para continuar.');
        }

        return $next($request);
    }
} 