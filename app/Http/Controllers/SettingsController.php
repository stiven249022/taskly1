<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar página principal de configuración
     */
    public function index()
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    /**
     * Mostrar configuración general
     */
    public function general()
    {
        $user = Auth::user();
        return view('settings.general', compact('user'));
    }

    /**
     * Actualizar configuración general
     */
    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'theme' => 'required|in:light,dark,system',
            'timezone' => 'required|string',
            'language' => 'required|in:es,en'
        ]);

        $user = Auth::user();
        $user->update($validated);

        return redirect()->back()->with('success', 'Configuración general actualizada exitosamente.');
    }

    /**
     * Mostrar configuración de notificaciones
     */
    public function notifications()
    {
        $user = Auth::user();
        return view('settings.notifications', compact('user'));
    }

    /**
     * Actualizar configuración de notificaciones
     */
    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'task_reminders' => 'boolean',
            'project_deadlines' => 'boolean',
            'exam_reminders' => 'boolean',
            'reminder_frequency' => 'in:15,30,60,120,1440'
        ]);

        $user = Auth::user();
        $user->update($validated);

        return redirect()->back()->with('success', 'Configuración de notificaciones actualizada.');
    }

    /**
     * Mostrar configuración de seguridad
     */
    public function security()
    {
        $user = Auth::user();
        return view('settings.security', compact('user'));
    }

    /**
     * Actualizar contraseña
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->back()->with('success', 'Contraseña actualizada exitosamente.');
    }

    /**
     * Mostrar configuración de perfil
     */
    public function profile()
    {
        $user = Auth::user();
        return view('settings.profile', compact('user'));
    }

    /**
     * Actualizar información del perfil
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'bio' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255'
        ]);

        $user = Auth::user();
        $user->update($validated);

        return redirect()->back()->with('success', 'Perfil actualizado exitosamente.');
    }

    /**
     * Actualizar preferencias del usuario via AJAX
     */
    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'nullable|in:light,dark,system',
            'dark_mode' => 'nullable|boolean',
            'email_notifications' => 'nullable|boolean',
            'push_notifications' => 'nullable|boolean',
            'task_reminders' => 'nullable|boolean',
            'project_deadlines' => 'nullable|boolean',
            'exam_reminders' => 'nullable|boolean',
        ]);

        $user = Auth::user();
        
        // Si se envía dark_mode, convertir a theme
        if (isset($validated['dark_mode'])) {
            $validated['theme'] = $validated['dark_mode'] ? 'dark' : 'light';
            unset($validated['dark_mode']);
        }
        
        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Preferencias actualizadas correctamente',
            'theme' => $user->theme
        ]);
    }

    /**
     * Exportar datos del usuario
     */
    public function exportData()
    {
        $user = Auth::user();
        
        $data = [
            'user' => $user->toArray(),
            'tasks' => $user->tasks()->get()->toArray(),
            'projects' => $user->projects()->get()->toArray(),
            'courses' => $user->courses()->get()->toArray(),
            'reminders' => $user->reminders()->get()->toArray(),
        ];

        return response()->json($data);
    }

    /**
     * Eliminar cuenta del usuario
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = Auth::user();
        
        // Eliminar todos los datos del usuario
        $user->tasks()->delete();
        $user->projects()->delete();
        $user->courses()->delete();
        $user->reminders()->delete();
        $user->notifications()->delete();
        
        // Eliminar el usuario
        $user->delete();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Tu cuenta ha sido eliminada exitosamente.');
    }
} 