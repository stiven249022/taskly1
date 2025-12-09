<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Notifications\GradeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TaskFileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Subir archivo a una tarea
     */
    public function upload(Request $request, Task $task)
    {
        $this->authorize('submit', $task);

        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png,gif|max:10240' // 10MB máximo
        ]);

        try {
            // Eliminar archivo anterior si existe
            if ($task->file_path && Storage::disk('public')->exists($task->file_path)) {
                Storage::disk('public')->delete($task->file_path);
            }

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::uuid() . '.' . $extension;
            $filePath = 'task-files/' . $fileName;

            // Guardar archivo
            $file->storeAs('task-files', $fileName, 'public');

            // Actualizar tarea
            $task->update([
                'file_path' => $filePath,
                'file_name' => $originalName,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'submitted_at' => now(),
                'status' => 'completed'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Archivo subido exitosamente',
                'file' => [
                    'name' => $originalName,
                    'size' => $task->getFormattedFileSize(),
                    'type' => $file->getMimeType(),
                    'url' => $task->getFileUrl()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Descargar archivo de una tarea
     */
    public function download(Task $task)
    {
        $this->authorize('view', $task);

        if (!$task->hasFile()) {
            abort(404, 'Archivo no encontrado');
        }

        if (!Storage::disk('public')->exists($task->file_path)) {
            abort(404, 'Archivo no encontrado en el servidor');
        }

        return Storage::disk('public')->download($task->file_path, $task->file_name);
    }

    /**
     * Ver archivo de una tarea
     */
    public function view(Task $task)
    {
        $this->authorize('view', $task);

        if (!$task->hasFile()) {
            abort(404, 'Archivo no encontrado');
        }

        if (!Storage::disk('public')->exists($task->file_path)) {
            abort(404, 'Archivo no encontrado en el servidor');
        }

        $fileUrl = Storage::disk('public')->url($task->file_path);
        $downloadUrl = route('task-files.download', $task);
        $extension = strtolower(pathinfo($task->file_name, PATHINFO_EXTENSION));
        
        // Para archivos de texto, leer el contenido
        $fileContent = null;
        if (in_array($extension, ['txt', 'md', 'csv'])) {
            try {
                $fileContent = Storage::disk('public')->get($task->file_path);
            } catch (\Exception $e) {
                // Si no se puede leer, dejar null
            }
        }

        return view('files.viewer', [
            'fileName' => $task->file_name,
            'fileSize' => $task->getFormattedFileSize(),
            'fileUrl' => $fileUrl,
            'downloadUrl' => $downloadUrl,
            'fileContent' => $fileContent
        ]);
    }

    /**
     * Ver material de apoyo de una tarea
     */
    public function viewSupport(Task $task)
    {
        $this->authorize('view', $task);

        if (!$task->hasSupportFile()) {
            abort(404, 'Archivo no encontrado');
        }

        if (!Storage::disk('public')->exists($task->support_file_path)) {
            abort(404, 'Archivo no encontrado en el servidor');
        }

        $filePath = Storage::disk('public')->path($task->support_file_path);
        $mimeType = Storage::disk('public')->mimeType($task->support_file_path);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $task->support_file_name . '"'
        ]);
    }

    /**
     * Descargar material de apoyo de una tarea
     */
    public function downloadSupport(Task $task)
    {
        $this->authorize('view', $task);

        if (!$task->hasSupportFile()) {
            abort(404, 'Archivo no encontrado');
        }

        if (!Storage::disk('public')->exists($task->support_file_path)) {
            abort(404, 'Archivo no encontrado en el servidor');
        }

        return Storage::disk('public')->download($task->support_file_path, $task->support_file_name);
    }

    /**
     * Eliminar archivo de una tarea
     */
    public function delete(Task $task)
    {
        $this->authorize('submit', $task);

        try {
            if ($task->file_path && Storage::disk('public')->exists($task->file_path)) {
                Storage::disk('public')->delete($task->file_path);
            }

            $task->update([
                'file_path' => null,
                'file_name' => null,
                'file_type' => null,
                'file_size' => null,
                'submitted_at' => null,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Archivo eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calificar tarea (solo para profesores y administradores)
     */
    public function grade(Request $request, Task $task)
    {
        // Verificar que el usuario sea profesor o administrador
        if (!Auth::user()->hasAnyRole(['teacher', 'admin'])) {
            abort(403, 'No tienes permisos para calificar tareas');
        }

        $request->validate([
            'grade' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:1000'
        ]);

        try {
            $isUpdate = $task->grade !== null;
            
            $task->update([
                'grade' => $request->grade,
                'feedback' => $request->feedback,
                'graded_at' => now(),
                'graded_by' => Auth::id()
            ]);
            
            // Enviar notificación al estudiante si la tarea pertenece a un estudiante
            // Solo enviar notificación si es una nueva calificación, no si se está actualizando
            if (!$isUpdate && $task->user && $task->user->role === 'student') {
                $teacherName = Auth::user()->name . ' ' . (Auth::user()->last_name ?? '');
                $task->user->notify(new GradeNotification(
                    $task,
                    'task',
                    $request->grade,
                    $request->feedback,
                    $teacherName
                ));
            }

            return response()->json([
                'success' => true,
                'message' => $isUpdate ? 'Calificación actualizada exitosamente' : 'Tarea calificada exitosamente',
                'grade' => $task->grade,
                'feedback' => $task->feedback
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al calificar la tarea: ' . $e->getMessage()
            ], 500);
        }
    }
}
