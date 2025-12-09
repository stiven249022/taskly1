<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            // Cerrar sesión del usuario
            Auth::guard('web')->logout();

            // Invalidar la sesión
            $request->session()->invalidate();

            // Regenerar el token CSRF
            $request->session()->regenerateToken();
            
            // Forzar eliminación de cookies de sesión
            $cookie = \Illuminate\Support\Facades\Cookie::forget(config('session.cookie'));
            \Illuminate\Support\Facades\Cookie::queue($cookie);
        } catch (\Exception $e) {
            // Si hay un error, intentar limpiar la sesión de otra manera
            try {
                \Illuminate\Support\Facades\Session::flush();
                \Illuminate\Support\Facades\Session::regenerate();
                Auth::guard('web')->logout();
                
                // Intentar eliminar cookie manualmente
                $cookie = \Illuminate\Support\Facades\Cookie::forget(config('session.cookie'));
                \Illuminate\Support\Facades\Cookie::queue($cookie);
            } catch (\Exception $e2) {
                // Si aún falla, simplemente redirigir
                \Log::error('Error al cerrar sesión: ' . $e2->getMessage());
            }
        }

        return redirect('/')->with('status', 'Sesión cerrada exitosamente');
    }
}
