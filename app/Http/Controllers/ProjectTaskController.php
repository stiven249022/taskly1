<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar las subtareas de un proyecto
     */
    public function index(Project $project)
    {
        $this->authorize('view', $project);
        
        $project->load(['projectTasks' => function($query) {
            $query->orderBy('order');
        }]);
        
        return view('projects.tasks.index', compact('project'));
    }

    /**
     * Crear una nueva subtarea
     */
    public function create(Project $project)
    {
        $this->authorize('manageSubtasks', $project);
        
        return view('projects.tasks.create', compact('project'));
    }

    /**
     * Guardar una nueva subtarea
     */
    public function store(Request $request, Project $project)
    {
        $this->authorize('manageSubtasks', $project);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after_or_equal:today',
            'priority' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string'
        ]);

        try {
            // Obtener el siguiente orden
            $nextOrder = $project->projectTasks()->max('order') + 1;

            $projectTask = ProjectTask::create([
                'project_id' => $project->id,
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'priority' => $request->priority,
                'notes' => $request->notes,
                'order' => $nextOrder,
                'status' => 'pending'
            ]);

            // Recalcular el progreso del proyecto
            $project->calculateProgress();

            return response()->json([
                'success' => true,
                'message' => 'Subtarea creada exitosamente',
                'task' => $projectTask
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la subtarea: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar una subtarea específica
     */
    public function show(Project $project, ProjectTask $projectTask)
    {
        $this->authorize('view', $project);
        
        return view('projects.tasks.show', compact('project', 'projectTask'));
    }

    /**
     * Editar una subtarea
     */
    public function edit(Project $project, ProjectTask $projectTask)
    {
        $this->authorize('manageSubtasks', $project);
        
        return view('projects.tasks.edit', compact('project', 'projectTask'));
    }

    /**
     * Actualizar una subtarea
     */
    public function update(Request $request, Project $project, ProjectTask $projectTask)
    {
        $this->authorize('manageSubtasks', $project);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        try {
            $oldStatus = $projectTask->status;
            
            $projectTask->update([
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'priority' => $request->priority,
                'notes' => $request->notes,
                'status' => $request->status
            ]);

            // Si el estado cambió, recalcular el progreso del proyecto
            if ($oldStatus !== $request->status) {
                $project->calculateProgress();
            }

            return response()->json([
                'success' => true,
                'message' => 'Subtarea actualizada exitosamente',
                'task' => $projectTask
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la subtarea: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una subtarea
     */
    public function destroy(Project $project, ProjectTask $projectTask)
    {
        $this->authorize('manageSubtasks', $project);

        try {
            // Eliminar archivo si existe
            if ($projectTask->file_path && Storage::disk('public')->exists($projectTask->file_path)) {
                Storage::disk('public')->delete($projectTask->file_path);
            }

            $projectTask->delete();

            // Recalcular el progreso del proyecto
            $project->calculateProgress();

            return response()->json([
                'success' => true,
                'message' => 'Subtarea eliminada exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la subtarea: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cambiar el estado de una subtarea
     */
    public function toggleStatus(Project $project, ProjectTask $projectTask)
    {
        $this->authorize('manageSubtasks', $project);

        try {
            $oldStatus = $projectTask->status;
            
            // Cambiar estado
            switch ($projectTask->status) {
                case 'pending':
                    $projectTask->status = 'in_progress';
                    break;
                case 'in_progress':
                    $projectTask->status = 'completed';
                    break;
                case 'completed':
                    $projectTask->status = 'pending';
                    break;
            }
            
            $projectTask->save();

            // Recalcular el progreso del proyecto
            $project->calculateProgress();

            return response()->json([
                'success' => true,
                'message' => 'Estado de subtarea actualizado',
                'status' => $projectTask->status,
                'project_progress' => $project->fresh()->progress
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Subir archivo a una subtarea
     */
    public function uploadFile(Request $request, Project $project, ProjectTask $projectTask)
    {
        $this->authorize('submit', $project);

        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png,gif,zip,rar|max:10240'
        ]);

        try {
            // Eliminar archivo anterior si existe
            if ($projectTask->file_path && Storage::disk('public')->exists($projectTask->file_path)) {
                Storage::disk('public')->delete($projectTask->file_path);
            }

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::uuid() . '.' . $extension;
            $filePath = 'project-task-files/' . $fileName;

            // Guardar archivo
            $file->storeAs('project-task-files', $fileName, 'public');

            // Actualizar subtarea
            $projectTask->update([
                'file_path' => $filePath,
                'file_name' => $originalName,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'submitted_at' => now(),
                'status' => 'completed'
            ]);

            // Recalcular el progreso del proyecto
            $project->calculateProgress();

            return response()->json([
                'success' => true,
                'message' => 'Archivo subido exitosamente',
                'file' => [
                    'name' => $originalName,
                    'size' => $projectTask->getFormattedFileSize(),
                    'type' => $file->getMimeType(),
                    'url' => $projectTask->getFileUrl()
                ],
                'project_progress' => $project->fresh()->progress
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar archivo de una subtarea
     */
    public function deleteFile(Project $project, ProjectTask $projectTask)
    {
        $this->authorize('submit', $project);

        try {
            if ($projectTask->file_path && Storage::disk('public')->exists($projectTask->file_path)) {
                Storage::disk('public')->delete($projectTask->file_path);
            }

            $projectTask->update([
                'file_path' => null,
                'file_name' => null,
                'file_type' => null,
                'file_size' => null,
                'submitted_at' => null,
                'status' => 'pending'
            ]);

            // Recalcular el progreso del proyecto
            $project->calculateProgress();

            return response()->json([
                'success' => true,
                'message' => 'Archivo eliminado exitosamente',
                'project_progress' => $project->fresh()->progress
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Descargar archivo de una subtarea
     */
    public function downloadFile(Project $project, ProjectTask $projectTask)
    {
        $this->authorize('view', $project);

        if (!$projectTask->hasFile()) {
            abort(404, 'Archivo no encontrado');
        }

        if (!Storage::disk('public')->exists($projectTask->file_path)) {
            abort(404, 'Archivo no encontrado en el servidor');
        }

        return Storage::disk('public')->download($projectTask->file_path, $projectTask->file_name);
    }

    /**
     * Ver archivo de una subtarea
     */
    public function viewFile(Project $project, ProjectTask $projectTask)
    {
        $this->authorize('view', $project);

        if (!$projectTask->hasFile()) {
            abort(404, 'Archivo no encontrado');
        }

        if (!Storage::disk('public')->exists($projectTask->file_path)) {
            abort(404, 'Archivo no encontrado en el servidor');
        }

        $filePath = Storage::disk('public')->path($projectTask->file_path);
        $mimeType = Storage::disk('public')->mimeType($projectTask->file_path);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $projectTask->file_name . '"'
        ]);
    }

    /**
     * Calificar una subtarea (solo profesor/admin)
     */
    public function grade(Request $request, Project $project, ProjectTask $projectTask)
    {
        // Solo profesores o administradores
        if (!Auth::user()->hasAnyRole(['teacher', 'admin'])) {
            abort(403, 'No tienes permisos para calificar');
        }

        $request->validate([
            'grade' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:1000'
        ]);

        try {
            $projectTask->update([
                'grade' => $request->grade,
                'feedback' => $request->feedback,
                'graded_at' => now(),
                'graded_by' => Auth::id()
            ]);

            $avg = $project->projectTasks()
                ->whereNotNull('submitted_at')
                ->whereNotNull('grade')
                ->avg('grade');
            $project->grade = $avg !== null ? round($avg, 2) : null;
            $project->graded_at = now();
            $project->graded_by = Auth::id();
            $project->save();

            return response()->json([
                'success' => true,
                'message' => 'Subtarea calificada exitosamente',
                'grade' => $projectTask->grade,
                'feedback' => $projectTask->feedback
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al calificar la subtarea: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reordenar subtareas
     */
    public function reorder(Request $request, Project $project)
    {
        $this->authorize('manageSubtasks', $project);

        $request->validate([
            'tasks' => 'required|array',
            'tasks.*.id' => 'required|exists:project_tasks,id',
            'tasks.*.order' => 'required|integer|min:0'
        ]);

        try {
            foreach ($request->tasks as $taskData) {
                ProjectTask::where('id', $taskData['id'])
                    ->where('project_id', $project->id)
                    ->update(['order' => $taskData['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Orden de subtareas actualizado'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al reordenar las subtareas: ' . $e->getMessage()
            ], 500);
        }
    }
}
