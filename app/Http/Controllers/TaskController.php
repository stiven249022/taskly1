<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Course;
use App\Models\Tag;
use App\Http\Requests\TaskRequest;
use App\Jobs\ProcessTaskCompletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Task::with(['course', 'teacher'])
            ->where('user_id', auth()->id());

        // Filtrar por curso si se especifica
        if ($request->has('course') && $request->course !== '') {
            $query->where('course_id', $request->course);
        }

        // Filtrar por estado si se especifica
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filtrar por prioridad si se especifica
        if ($request->has('priority') && $request->priority !== '') {
            $query->where('priority', $request->priority);
        }

        // Búsqueda por texto
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $tasks = $query->orderBy('due_date')->paginate(10);
        $courses = Course::where('user_id', auth()->id())->get();
            
        return view('tasks.index', compact('tasks', 'courses'));
    }

    public function create()
    {
        $courses = Course::where('user_id', auth()->id())->get();
        
        if ($courses->isEmpty()) {
            return redirect()->route('courses.create')
                ->with('warning', 'Debe crear al menos una materia antes de crear tareas.');
        }
        
        return view('tasks.create', compact('courses'));
    }

    public function store(TaskRequest $request)
    {
        try {
            $validated = $request->validated();

            $task = Task::create([
                'user_id' => auth()->id(),
                'course_id' => $validated['course_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'type' => $validated['type'] ?? 'task',
                'start_date' => $validated['start_date'],
                'due_date' => $validated['due_date'],
                'priority' => $validated['priority'],
                'status' => 'pending'
            ]);

            return redirect()->route('tasks.index')
                ->with('success', '¡Tarea creada exitosamente! La tarea "' . $task->title . '" ha sido agregada a tu lista.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la tarea. Por favor, inténtalo de nuevo.');
        }
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        $task->load(['course', 'teacher', 'grader']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $courses = Course::where('user_id', auth()->id())->get();
        return view('tasks.edit', compact('task', 'courses'));
    }

    public function update(TaskRequest $request, Task $task)
    {
        try {
            $this->authorize('update', $task);

            $validated = $request->validated();
            $oldTitle = $task->title;
            $oldStatus = $task->status;
            
            $task->update($validated);

            // Si la tarea se marcó como completada, procesar en segundo plano
            if ($oldStatus !== 'completed' && $validated['status'] === 'completed') {
                ProcessTaskCompletion::dispatch($task);
            }

            return redirect()->route('tasks.index')
                ->with('success', '¡Tarea actualizada exitosamente! La tarea "' . $oldTitle . '" ha sido modificada.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la tarea. Por favor, inténtalo de nuevo.');
        }
    }

    public function destroy(Task $task)
    {
        try {
            $this->authorize('delete', $task);
            $taskTitle = $task->title;
            $task->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '¡Tarea eliminada exitosamente! La tarea "' . $taskTitle . '" ha sido removida de tu lista.'
                ]);
            }

            return redirect()->route('tasks.index')
                ->with('success', '¡Tarea eliminada exitosamente! La tarea "' . $taskTitle . '" ha sido removida de tu lista.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la tarea. Por favor, inténtalo de nuevo.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error al eliminar la tarea. Por favor, inténtalo de nuevo.');
        }
    }

    public function toggleStatus(Task $task)
    {
        try {
            $this->authorize('update', $task);

            $oldStatus = $task->status;
            $task->status = $task->status === 'completed' ? 'pending' : 'completed';
            $task->save();

            // Si la tarea se marcó como completada, procesar en segundo plano
            if ($oldStatus !== 'completed' && $task->status === 'completed') {
                ProcessTaskCompletion::dispatch($task);
            }

            $message = $task->status === 'completed' 
                ? '¡Excelente! La tarea "' . $task->title . '" ha sido marcada como completada.'
                : 'La tarea "' . $task->title . '" ha sido marcada como pendiente.';

            return response()->json([
                'success' => true,
                'status' => $task->status,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado de la tarea. Por favor, inténtalo de nuevo.'
            ], 500);
        }
    }

    /**
     * Mostrar tareas para profesores (todas las tareas de sus estudiantes)
     */
    public function teacherIndex(Request $request)
    {
        // Obtener estudiantes asignados al profesor
        $students = auth()->user()->students()->wherePivot('status', 'active')->get();
        $studentIds = $students->pluck('id');
        
        if ($studentIds->isEmpty()) {
            $tasks = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            $courses = collect([]);
            $stats = [
                'total' => 0,
                'submitted' => 0,
                'pending' => 0,
                'graded' => 0
            ];
            return view('tasks.teacher-index', compact('tasks', 'courses', 'students', 'stats'));
        }
        
        $baseQuery = Task::with(['user', 'course'])
            ->whereIn('user_id', $studentIds);

        // Filtrar por curso si se especifica
        if ($request->filled('course')) {
            $baseQuery->where('course_id', $request->input('course'));
        }

        // Filtrar por estado si se especifica
        if ($request->filled('status')) {
            $baseQuery->where('status', $request->input('status'));
        }

        // Filtrar por tareas con archivos
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
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Calcular estadísticas con los filtros aplicados
        $allTasks = (clone $baseQuery)->get();
        $stats = [
            'total' => $allTasks->count(),
            'submitted' => $allTasks->filter(function($task) {
                return $task->file_path !== null && $task->submitted_at !== null;
            })->count(),
            'pending' => $allTasks->filter(function($task) {
                return $task->file_path === null || $task->submitted_at === null;
            })->count(),
            'graded' => $allTasks->whereNotNull('grade')->count()
        ];

        // Obtener todos los cursos únicos de las tareas de los estudiantes
        $courseIds = Task::whereIn('user_id', $studentIds)
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
        $tasks = $baseQuery->orderBy('due_date', 'desc')->paginate(15)->withQueryString();
            
        return view('tasks.teacher-index', compact('tasks', 'courses', 'students', 'stats'));
    }
} 