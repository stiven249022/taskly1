<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Notifications\GradeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectFileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Subir archivo a un proyecto
     */
    public function upload(Request $request, Project $project)
    {
        $this->authorize('submit', $project);

        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png,gif,zip,rar|max:10240' // 10MB máximo
        ]);

        try {
            // Eliminar archivo anterior si existe
            if ($project->file_path && Storage::disk('public')->exists($project->file_path)) {
                Storage::disk('public')->delete($project->file_path);
            }

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::uuid() . '.' . $extension;
            $filePath = 'project-files/' . $fileName;

            // Guardar archivo
            $file->storeAs('project-files', $fileName, 'public');

            // Actualizar proyecto
            $project->update([
                'file_path' => $filePath,
                'file_name' => $originalName,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'submitted_at' => now(),
                'progress' => 100,
                'status' => 'completed'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Archivo subido exitosamente',
                'file' => [
                    'name' => $originalName,
                    'size' => $project->getFormattedFileSize(),
                    'type' => $file->getMimeType(),
                    'url' => $project->getFileUrl()
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
     * Descargar archivo de un proyecto
     */
    public function download(Project $project)
    {
        $this->authorize('view', $project);

        if (!$project->hasFile()) {
            abort(404, 'Archivo no encontrado');
        }

        if (!Storage::disk('public')->exists($project->file_path)) {
            abort(404, 'Archivo no encontrado en el servidor');
        }

        return Storage::disk('public')->download($project->file_path, $project->file_name);
    }

    /**
     * Ver archivo de un proyecto
     */
    public function view(Project $project)
    {
        $this->authorize('view', $project);

        if (!$project->hasFile()) {
            abort(404, 'Archivo no encontrado');
        }

        if (!Storage::disk('public')->exists($project->file_path)) {
            abort(404, 'Archivo no encontrado en el servidor');
        }

        $fileUrl = Storage::disk('public')->url($project->file_path);
        $downloadUrl = route('project-files.download', $project);
        $extension = strtolower(pathinfo($project->file_name, PATHINFO_EXTENSION));
        
        // Para archivos de texto, leer el contenido
        $fileContent = null;
        if (in_array($extension, ['txt', 'md', 'csv'])) {
            try {
                $fileContent = Storage::disk('public')->get($project->file_path);
            } catch (\Exception $e) {
                // Si no se puede leer, dejar null
            }
        }

        return view('files.viewer', [
            'fileName' => $project->file_name,
            'fileSize' => $project->getFormattedFileSize(),
            'fileUrl' => $fileUrl,
            'downloadUrl' => $downloadUrl,
            'fileContent' => $fileContent
        ]);
    }

    /**
     * Ver material de apoyo de un proyecto
     */
    public function viewSupport(Project $project)
    {
        $this->authorize('view', $project);

        if (!$project->hasSupportFile()) {
            abort(404, 'Archivo no encontrado');
        }

        if (!Storage::disk('public')->exists($project->support_file_path)) {
            abort(404, 'Archivo no encontrado en el servidor');
        }

        $filePath = Storage::disk('public')->path($project->support_file_path);
        $mimeType = Storage::disk('public')->mimeType($project->support_file_path);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $project->support_file_name . '"'
        ]);
    }

    /**
     * Descargar material de apoyo de un proyecto
     */
    public function downloadSupport(Project $project)
    {
        $this->authorize('view', $project);

        if (!$project->hasSupportFile()) {
            abort(404, 'Archivo no encontrado');
        }

        if (!Storage::disk('public')->exists($project->support_file_path)) {
            abort(404, 'Archivo no encontrado en el servidor');
        }

        return Storage::disk('public')->download($project->support_file_path, $project->support_file_name);
    }

    /**
     * Eliminar archivo de un proyecto
     */
    public function delete(Project $project)
    {
        $this->authorize('submit', $project);

        try {
            if ($project->file_path && Storage::disk('public')->exists($project->file_path)) {
                Storage::disk('public')->delete($project->file_path);
            }

            $project->update([
                'file_path' => null,
                'file_name' => null,
                'file_type' => null,
                'file_size' => null,
                'submitted_at' => null,
                'status' => 'active'
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
     * Calificar proyecto (solo para profesores y administradores)
     */
    public function grade(Request $request, Project $project)
    {
        // Verificar que el usuario sea profesor o administrador
        if (!Auth::user()->hasAnyRole(['teacher', 'admin'])) {
            abort(403, 'No tienes permisos para calificar proyectos');
        }

        $request->validate([
            'grade' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:1000'
        ]);

        try {
            $isUpdate = $project->grade !== null;
            
            $project->update([
                'grade' => $request->grade,
                'feedback' => $request->feedback,
                'graded_at' => now(),
                'graded_by' => Auth::id()
            ]);
            
            // Enviar notificación al estudiante si el proyecto pertenece a un estudiante
            // Solo enviar notificación si es una nueva calificación, no si se está actualizando
            if (!$isUpdate && $project->user && $project->user->role === 'student') {
                $teacherName = Auth::user()->name . ' ' . (Auth::user()->last_name ?? '');
                $project->user->notify(new GradeNotification(
                    $project,
                    'project',
                    $request->grade,
                    $request->feedback,
                    $teacherName
                ));
            }

            return response()->json([
                'success' => true,
                'message' => $isUpdate ? 'Calificación actualizada exitosamente' : 'Proyecto calificado exitosamente',
                'grade' => $project->grade,
                'feedback' => $project->feedback
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al calificar el proyecto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar progreso del proyecto
     */
    public function updateProgress(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $request->validate([
            'progress' => 'required|integer|min:0|max:100'
        ]);

        try {
            $project->updateProgress($request->progress);

            return response()->json([
                'success' => true,
                'message' => 'Progreso actualizado exitosamente',
                'progress' => $project->progress,
                'status' => $project->status,
                'progress_text' => $project->getProgressText(),
                'progress_color' => $project->getProgressColor()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el progreso: ' . $e->getMessage()
            ], 500);
        }
    }
}
