<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Course;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        Log::info('User ID: ' . $userId);
        
        $user = auth()->user();
        
        $tasks = Task::where('user_id', $userId)->get();
        Log::info('Tasks count: ' . $tasks->count());
        
        $projects = Project::where('user_id', $userId)->get();
        Log::info('Projects count: ' . $projects->count());
        
        // Obtener cursos: propios + asignados para estudiantes y profesores
        if ($user->isStudent()) {
            // Obtener materias propias del estudiante
            $ownCourses = Course::where('user_id', $userId)->get();
            
            // Obtener IDs de materias asignadas a través de TeacherStudentAssignment
            $assignedCourseIds = \App\Models\TeacherStudentAssignment::where('student_id', $userId)
                ->where('status', 'active')
                ->whereNotNull('course_id')
                ->pluck('course_id')
                ->unique()
                ->toArray();
            
            // Obtener las materias asignadas
            $assignedCourses = Course::whereIn('id', $assignedCourseIds)->get();
            
            // Combinar y eliminar duplicados
            $courses = $ownCourses->merge($assignedCourses)->unique('id');
        } elseif ($user->isTeacher()) {
            // Obtener materias propias del profesor
            $ownCourses = Course::where('user_id', $userId)->get();
            
            // Obtener IDs de materias asignadas a través de TeacherStudentAssignment
            $assignedCourseIds = \App\Models\TeacherStudentAssignment::where('teacher_id', $userId)
                ->where('status', 'active')
                ->whereNotNull('course_id')
                ->pluck('course_id')
                ->unique()
                ->toArray();
            
            // Obtener las materias asignadas
            $assignedCourses = Course::whereIn('id', $assignedCourseIds)->get();
            
            // Combinar y eliminar duplicados
            $courses = $ownCourses->merge($assignedCourses)->unique('id');
        } else {
            // Para admin, solo sus propias materias
            $courses = Course::where('user_id', $userId)->get();
        }
        
        Log::info('Courses count: ' . $courses->count());
        
        // Obtener actividades próximas (tareas y proyectos)
        $upcomingActivities = collect();
        
        // Agregar tareas pendientes con fecha válida
        foreach ($tasks->where('status', 'pending') as $task) {
            if ($task->due_date) {
                $task->type = 'task';
                $task->activity_date = $task->due_date;
                $upcomingActivities->push($task);
            }
        }
        
        // Agregar proyectos activos con fecha válida
        foreach ($projects->where('status', 'active') as $project) {
            if ($project->end_date) {
                $project->type = 'project';
                $project->activity_date = $project->end_date;
                $upcomingActivities->push($project);
            }
        }
        
        // Ordenar por fecha y tomar los primeros 5
        $upcomingActivities = $upcomingActivities->sortBy('activity_date')->take(5);

        // Obtener eventos próximos para el widget
        $upcomingEvents = $this->getUpcomingEventsForWidget($userId);

        return view('dashboard', compact(
            'tasks',
            'projects',
            'courses',
            'upcomingActivities',
            'upcomingEvents'
        ));
    }

    /**
     * Obtener eventos próximos formateados para el widget
     */
    private function getUpcomingEventsForWidget($userId)
    {
        $now = Carbon::now();
        $upcomingEvents = collect();

        // Tareas próximas (hoy, mañana, en 3 días)
        $tasks = Task::where('user_id', $userId)
            ->where('status', '!=', 'completed')
            ->whereDate('due_date', '>=', $now->toDateString())
            ->whereDate('due_date', '<=', $now->copy()->addDays(3)->toDateString())
            ->with('course')
            ->orderBy('due_date')
            ->take(5)
            ->get();

        foreach ($tasks as $task) {
            // Calcular días restantes correctamente (solo fechas futuras)
            if ($task->due_date->isPast()) {
                $daysUntilDue = 0;
            } else {
                $daysUntilDue = $now->copy()->startOfDay()->diffInDays($task->due_date->copy()->startOfDay(), false);
                $daysUntilDue = max(0, min(999, $daysUntilDue));
            }
            $upcomingEvents->push([
                'id' => 'task-' . $task->id,
                'title' => $task->title,
                'type' => 'task',
                'date' => $task->due_date->toDateTimeString(),
                'days_until_due' => $daysUntilDue,
                'priority' => $task->priority,
                'course' => $task->course ? $task->course->name : null,
                'url' => route('tasks.index')
            ]);
        }

        // Proyectos próximos (hoy, mañana, en 7 días)
        $projects = Project::where('user_id', $userId)
            ->where('status', '!=', 'completed')
            ->whereDate('end_date', '>=', $now->toDateString())
            ->whereDate('end_date', '<=', $now->copy()->addDays(7)->toDateString())
            ->with('course')
            ->orderBy('end_date')
            ->take(5)
            ->get();

        foreach ($projects as $project) {
            // Calcular días restantes correctamente (solo fechas futuras)
            if ($project->end_date->isPast()) {
                $daysUntilDue = 0;
            } else {
                $daysUntilDue = $now->copy()->startOfDay()->diffInDays($project->end_date->copy()->startOfDay(), false);
                $daysUntilDue = max(0, min(999, $daysUntilDue));
            }
            $upcomingEvents->push([
                'id' => 'project-' . $project->id,
                'title' => $project->name,
                'type' => 'project',
                'date' => $project->end_date->toDateTimeString(),
                'days_until_due' => $daysUntilDue,
                'course' => $project->course ? $project->course->name : null,
                'url' => route('projects.index')
            ]);
        }

        // Ordenar por fecha y tomar los primeros 5
        return $upcomingEvents->sortBy('date')->take(5)->values();
    }
} 