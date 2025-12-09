<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->bio = $request->bio;

            // Manejar foto de perfil
            if ($request->hasFile('profile_photo')) {
                // Eliminar foto anterior si existe
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }

                // Guardar nueva foto con nombre único
                $file = $request->file('profile_photo');
                $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('profile-photos', $filename, 'public');
                $user->profile_photo_path = $path;
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Perfil actualizado exitosamente',
                'profile_photo_url' => $user->profile_photo_url,
                'profile_photo_path' => $user->profile_photo_path,
                'user' => [
                    'name' => $user->name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'bio' => $user->bio
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el perfil: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.current_password' => 'La contraseña actual es incorrecta.',
            'new_password.confirmed' => 'La confirmación de la nueva contraseña no coincide.',
        ]);

        try {
            $user = Auth::user();
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Contraseña cambiada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar la contraseña: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePreferences(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Obtener datos del request (pueden venir como JSON)
            $data = $request->all();
            
            // Convertir valores a booleanos explícitamente
            $updateData = [];
            
            if (isset($data['dark_mode'])) {
                $updateData['dark_mode'] = filter_var($data['dark_mode'], FILTER_VALIDATE_BOOLEAN);
            }
            if (isset($data['email_notifications'])) {
                $updateData['email_notifications'] = filter_var($data['email_notifications'], FILTER_VALIDATE_BOOLEAN);
            }
            if (isset($data['push_notifications'])) {
                $updateData['push_notifications'] = filter_var($data['push_notifications'], FILTER_VALIDATE_BOOLEAN);
            }
            if (isset($data['task_reminders'])) {
                $updateData['task_reminders'] = filter_var($data['task_reminders'], FILTER_VALIDATE_BOOLEAN);
            }
            if (isset($data['project_deadlines'])) {
                $updateData['project_deadlines'] = filter_var($data['project_deadlines'], FILTER_VALIDATE_BOOLEAN);
            }
            if (isset($data['exam_reminders'])) {
                $updateData['exam_reminders'] = filter_var($data['exam_reminders'], FILTER_VALIDATE_BOOLEAN);
            }
            
            // Actualizar solo los campos que se enviaron
            if (!empty($updateData)) {
                $user->update($updateData);
            }

            // Recargar el usuario para obtener los valores actualizados
            $user->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Preferencias actualizadas exitosamente',
                'user' => [
                    'dark_mode' => (bool) $user->dark_mode,
                    'email_notifications' => (bool) $user->email_notifications,
                    'push_notifications' => (bool) $user->push_notifications,
                    'task_reminders' => (bool) $user->task_reminders,
                    'project_deadlines' => (bool) $user->project_deadlines,
                    'exam_reminders' => (bool) $user->exam_reminders
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar preferencias: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar las preferencias: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteProfilePhoto(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Eliminar foto de perfil si existe
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
                $user->profile_photo_path = null;
                $user->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Foto de perfil eliminada exitosamente',
                    'profile_photo_url' => $user->profile_photo_url
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No hay foto de perfil para eliminar'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la foto de perfil: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        try {
            $user = Auth::user();
            
            // Eliminar foto de perfil si existe
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Eliminar cuenta
            $user->delete();

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'success' => true,
                'message' => 'Cuenta eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la cuenta: ' . $e->getMessage()
            ], 500);
        }
    }
}
