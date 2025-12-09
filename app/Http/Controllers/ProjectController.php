<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Course;
use App\Models\Tag;
use App\Http\Requests\ProjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $projectsQuery = $user->projects()->with(['course', 'tags', 'user']);

        // Filtro de búsqueda
        if ($request->filled('q')) {
            $q = trim($request->q);
            $projectsQuery->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Filtros existentes
        if ($request->has('course') && $request->course !== '') {
            $projectsQuery->where('course_id', $request->course);
        }
        if ($request->has('status') && $request->status !== '') {
            $projectsQuery->where('status', $request->status);
        }
        if ($request->has('progress') && $request->progress !== '') {
            [$min, $max] = array_pad(explode('-', $request->progress), 2, null);
            if ($min !== null && $max !== null) {
                $projectsQuery->whereBetween('progress', [(int)$min, (int)$max]);
            }
        }

        $projects = $projectsQuery->paginate(10);
        $courses = $user->courses;

        // Obtener estadísticas de todos los proyectos (sin paginación)
        $allProjects = $user->projects;
        $activeProjects = $allProjects->where('status', 'active')->count();
        $completedProjects = $allProjects->where('status', 'completed')->count();
        $overdueProjects = $allProjects->where('status', 'active')->where('end_date', '<', now())->count();
        $averageProgress = $allProjects->count() > 0 ? round($allProjects->avg('progress')) : 0;

        return view('projects.index', compact(
            'projects',
            'courses',
            'activeProjects',
            'completedProjects',
            'overdueProjects',
            'averageProgress'
        ));
    }

    public function create()
    {
        $user = auth()->user();
        $courses = $user->courses;
        
        if ($courses->isEmpty()) {
            return redirect()->route('courses.create')
                ->with('warning', 'Debe crear al menos una materia antes de crear proyectos.');
        }
        
        return view('projects.create', compact('courses'));
    }

    public function store(ProjectRequest $request)
    {
        try {
            $validated = $request->validated();

            $project = Project::create([
                'user_id' => auth()->id(),
                'teacher_id' => (auth()->user()->hasRole('teacher') ? auth()->id() : null),
                'course_id' => $validated['course_id'],
                'name' => $validated['name'],
                'description' => $validated['description'] ?? '',
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'priority' => $validated['priority'],
                'status' => 'active',
                'progress' => 0,
                'reminder' => !empty($validated['enable_reminder']),
                'reminder_days' => $validated['reminder_days'] ?? null,
                'reminder_time' => $validated['reminder_time'] ?? null,
            ]);

            // Procesar etiquetas si se proporcionan
            if (!empty($validated['tags'])) {
                $tags = array_map('trim', explode(',', $validated['tags']));
                foreach ($tags as $tagName) {
                    if (!empty($tagName)) {
                        $tag = Tag::firstOrCreate(
                            ['name' => $tagName, 'user_id' => auth()->id()],
                            [
                                'name' => $tagName, 
                                'user_id' => auth()->id(),
                                'color' => '#6b7280' // Color gris por defecto
                            ]
                        );
                        $project->tags()->attach($tag->id);
                    }
                }
            }

            // Crear recordatorio si está habilitado
            if (!empty($validated['enable_reminder']) && !empty($validated['end_date'])) {
                $reminderDate = \Carbon\Carbon::parse($validated['end_date'])
                    ->subDays($validated['reminder_days'] ?? 1)
                    ->setTimeFromTimeString($validated['reminder_time'] ?? '09:00');

                \App\Models\Reminder::create([
                    'user_id' => auth()->id(),
                    'course_id' => $validated['course_id'],
                    'title' => 'Recordatorio: ' . $project->name,
                    'description' => 'El proyecto "' . $project->name . '" vence en ' . ($validated['reminder_days'] ?? 1) . ' día(s)',
                    'due_date' => $reminderDate,
                    'status' => 'pending'
                ]);
            }

            if ($request->boolean('create_subtasks')) {
                return redirect()->route('project-tasks.index', $project)
                    ->with('success', '¡Proyecto creado! Ahora puedes crear las subtareas.');
            }

            return redirect()->route('projects.index')
                ->with('success', '¡Proyecto creado exitosamente! El proyecto "' . $project->name . '" ha sido agregado a tu lista.');
        } catch (\Exception $e) {
            \Log::error('Error al crear proyecto: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el proyecto: ' . $e->getMessage());
        }
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        $project->load(['course', 'teacher', 'grader', 'tags', 'projectTasks']);
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        $courses = Course::where('user_id', auth()->id())->get();
        return view('projects.edit', compact('project', 'courses'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        try {
            $this->authorize('update', $project);

            $validated = $request->validated();
            $oldName = $project->name;
            
            // Actualizar el proyecto
            $project->update([
                'name' => $validated['name'],
                'course_id' => $validated['course_id'],
                'description' => $validated['description'] ?? '',
                'priority' => $validated['priority'],
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'reminder' => !empty($validated['enable_reminder']),
                'reminder_days' => $validated['reminder_days'] ?? null,
                'reminder_time' => $validated['reminder_time'] ?? null,
            ]);

            // Procesar etiquetas si se proporcionan
            if (!empty($validated['tags'])) {
                $tags = array_map('trim', explode(',', $validated['tags']));
                $project->tags()->detach(); // Eliminar etiquetas existentes
                foreach ($tags as $tagName) {
                    if (!empty($tagName)) {
                        $tag = Tag::firstOrCreate(
                            ['name' => $tagName, 'user_id' => auth()->id()],
                            [
                                'name' => $tagName, 
                                'user_id' => auth()->id(),
                                'color' => '#6b7280' // Color gris por defecto
                            ]
                        );
                        $project->tags()->attach($tag->id);
                    }
                }
            }

            // Actualizar o crear recordatorio si está habilitado
            if (!empty($validated['enable_reminder']) && !empty($validated['end_date'])) {
                $reminderDate = \Carbon\Carbon::parse($validated['end_date'])
                    ->subDays($validated['reminder_days'] ?? 1)
                    ->setTimeFromTimeString($validated['reminder_time'] ?? '09:00');

                // Buscar recordatorio existente o crear uno nuevo
                $reminder = \App\Models\Reminder::where('title', 'like', '%Recordatorio: ' . $project->name . '%')
                    ->first();

                if ($reminder) {
                    $reminder->update([
                        'title' => 'Recordatorio: ' . $project->name,
                        'description' => 'El proyecto "' . $project->name . '" vence en ' . ($validated['reminder_days'] ?? 1) . ' día(s)',
                        'due_date' => $reminderDate,
                    ]);
                } else {
                    \App\Models\Reminder::create([
                        'user_id' => auth()->id(),
                        'course_id' => $validated['course_id'],
                        'title' => 'Recordatorio: ' . $project->name,
                        'description' => 'El proyecto "' . $project->name . '" vence en ' . ($validated['reminder_days'] ?? 1) . ' día(s)',
                        'due_date' => $reminderDate,
                        'status' => 'pending'
                    ]);
                }
            } else {
                // Eliminar recordatorio si se deshabilitó
                \App\Models\Reminder::where('title', 'like', '%Recordatorio: ' . $project->name . '%')
                    ->delete();
            }

            return redirect()->route('projects.index')
                ->with('success', '¡Proyecto actualizado exitosamente! El proyecto "' . $oldName . '" ha sido modificado.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el proyecto. Por favor, inténtalo de nuevo.');
        }
    }

    public function destroy(Project $project)
    {
        try {
            $this->authorize('delete', $project);
            $projectName = $project->name;
            $project->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '¡Proyecto eliminado exitosamente! El proyecto "' . $projectName . '" ha sido removido de tu lista.'
                ]);
            }

            return redirect()->route('projects.index')
                ->with('success', '¡Proyecto eliminado exitosamente! El proyecto "' . $projectName . '" ha sido removido de tu lista.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el proyecto. Por favor, inténtalo de nuevo.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error al eliminar el proyecto. Por favor, inténtalo de nuevo.');
        }
    }

    public function updateProgress(Request $request, Project $project)
    {
        try {
            $this->authorize('update', $project);
            
            $request->validate([
                'progress' => 'required|integer|min:0|max:100'
            ]);

            $project->updateProgress($request->progress);

            return response()->json([
                'success' => true,
                'message' => 'Progreso actualizado correctamente',
                'progress' => $project->progress
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el progreso'
            ], 500);
        }
    }

    public function toggleStatus(Project $project)
    {
        try {
            $this->authorize('update', $project);

            $oldStatus = $project->status;
            $project->status = $project->status === 'completed' ? 'active' : 'completed';
            $project->save();

            $message = $project->status === 'completed' 
                ? '¡Excelente! El proyecto "' . $project->name . '" ha sido marcado como completado.'
                : 'El proyecto "' . $project->name . '" ha sido marcado como activo.';

            return response()->json([
                'success' => true,
                'status' => $project->status,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado del proyecto. Por favor, inténtalo de nuevo.'
            ], 500);
        }
    }

    /**
     * Mostrar proyectos para profesores (todos los proyectos de sus estudiantes)
     */
    public function teacherIndex(Request $request)
    {
        // Obtener estudiantes asignados al profesor
        $students = auth()->user()->students()->wherePivot('status', 'active')->get();
        $studentIds = $students->pluck('id');
        
        if ($studentIds->isEmpty()) {
            $projects = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            $courses = collect([]);
            $stats = [
                'total' => 0,
                'submitted' => 0,
                'active' => 0,
                'graded' => 0
            ];
            return view('projects.teacher-index', compact('projects', 'courses', 'students', 'stats'));
        }
        
        $baseQuery = Project::with(['user', 'course'])
            ->whereIn('user_id', $studentIds);

        // Filtrar por curso si se especifica
        if ($request->filled('course')) {
            $baseQuery->where('course_id', $request->input('course'));
        }

        // Filtrar por estado si se especifica
        if ($request->filled('status')) {
            $baseQuery->where('status', $request->input('status'));
        }

        // Filtrar por proyectos con archivos
        if ($request->filled('with_files')) {
            $withFiles = $request->input('with_files');
            if ($withFiles == '1' || $withFiles === 1) {
                $baseQuery->whereNotNull('file_path');
            } elseif ($withFiles == '0' || $withFiles === 0) {
                $baseQuery->whereNull('file_path');
            }
        }

        // Filtrar por calificación
        if ($request->filled('graded')) {
            $graded = $request->input('graded');
            if ($graded == '1' || $graded === 1) {
                $baseQuery->whereNotNull('grade');
            } elseif ($graded == '0' || $graded === 0) {
                $baseQuery->whereNull('grade');
            }
        }

        // Búsqueda por texto
        if ($request->filled('q')) {
            $q = trim($request->q);
            $baseQuery->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Calcular estadísticas con los filtros aplicados
        $allProjects = (clone $baseQuery)->get();
        $stats = [
            'total' => $allProjects->count(),
            'submitted' => $allProjects->filter(function($project) {
                return $project->file_path !== null && $project->submitted_at !== null;
            })->count(),
            'active' => $allProjects->where('status', 'active')->count(),
            'graded' => $allProjects->whereNotNull('grade')->count()
        ];

        // Obtener todos los cursos únicos de los proyectos de los estudiantes
        $courseIds = Project::whereIn('user_id', $studentIds)
            ->whereNotNull('course_id')
            ->distinct()
            ->pluck('course_id');
        
        // Obtener los cursos del profesor y de los estudiantes
        $courses = Course::where(function($query) use ($studentIds, $courseIds) {
            $query->whereIn('user_id', $studentIds)
                  ->orWhere('user_id', auth()->id())
                  ->orWhereIn('id', $courseIds);
        })->get()->unique('id')->values();

        // Paginar los resultados manteniendo los parámetros de filtro
        $projects = $baseQuery->orderBy('end_date', 'desc')->paginate(15)->withQueryString();
            
        return view('projects.teacher-index', compact('projects', 'courses', 'students', 'stats'));
    }
} 
