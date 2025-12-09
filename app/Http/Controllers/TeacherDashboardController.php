<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Course;
use App\Notifications\GradeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeacherDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:teacher']);
    }

    public function index()
    {
        $teacherId = auth()->id();
        
        // Obtener solo los estudiantes asignados a este profesor
        $students = auth()->user()->students()->wherePivot('status', 'active')->get();
        
        // Obtener tareas de estudiantes asignados (temporalmente sin teacher_id)
        $studentIds = $students->pluck('id');
        $allTasks = Task::with(['user', 'course'])
            ->whereIn('user_id', $studentIds)
            ->get();
            
        // Obtener proyectos de estudiantes asignados (temporalmente sin teacher_id)
        $allProjects = Project::with(['user', 'course'])
            ->whereIn('user_id', $studentIds)
            ->get();
        
        // Estadísticas generales
        $stats = [
            'total_students' => $students->count(),
            'total_tasks' => $allTasks->count(),
            'total_projects' => $allProjects->count(),
            'pending_tasks' => $allTasks->where('status', 'pending')->count(),
            'completed_tasks' => $allTasks->where('status', 'completed')->count(),
            'active_projects' => $allProjects->where('status', 'active')->count(),
            'completed_projects' => $allProjects->where('status', 'completed')->count(),
        ];
        
        // Tareas pendientes de calificación
        $tasksToGrade = $allTasks->where('status', 'completed')
            ->whereNull('grade')
            ->take(10);
            
        // Proyectos pendientes de calificación
        $projectsToGrade = $allProjects->where('status', 'completed')
            ->whereNull('grade')
            ->take(10);
        
        // Actividades recientes de estudiantes
        $recentActivities = collect();
        
        // Agregar tareas recientes
        foreach ($allTasks->take(5) as $task) {
            $task->type = 'task';
            $task->activity_date = $task->updated_at;
            $recentActivities->push($task);
        }
        
        // Agregar proyectos recientes
        foreach ($allProjects->take(5) as $project) {
            $project->type = 'project';
            $project->activity_date = $project->updated_at;
            $recentActivities->push($project);
        }
        
        $recentActivities = $recentActivities->sortByDesc('activity_date')->take(10);

        // Obtener tareas para calificar para el widget
        $upcomingEvents = $this->getTasksToGradeForWidget($studentIds);

        return view('teacher.dashboard', compact(
            'stats',
            'students',
            'tasksToGrade',
            'projectsToGrade',
            'recentActivities',
            'upcomingEvents'
        ));
    }

    /**
     * Obtener tareas para calificar para el widget
     */
    private function getTasksToGradeForWidget($studentIds)
    {
        $tasksToGrade = collect();

        // Tareas completadas sin calificar
        $tasks = Task::whereIn('user_id', $studentIds)
            ->where('status', 'completed')
            ->whereNull('grade')
            ->with(['user', 'course'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        foreach ($tasks as $task) {
            $tasksToGrade->push([
                'id' => 'task-' . $task->id,
                'title' => $task->title,
                'type' => 'task',
                'date' => $task->updated_at->toDateTimeString(),
                'days_until_due' => null,
                'priority' => $task->priority,
                'course' => $task->course ? $task->course->name : null,
                'student_name' => $task->user->name . ' ' . $task->user->last_name,
                'url' => route('teacher.student-tasks'),
                'is_for_grade' => true
            ]);
        }

        // Proyectos completados sin calificar
        $projects = Project::whereIn('user_id', $studentIds)
            ->where('status', 'completed')
            ->whereNull('grade')
            ->with(['user', 'course'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        foreach ($projects as $project) {
            $tasksToGrade->push([
                'id' => 'project-' . $project->id,
                'title' => $project->name,
                'type' => 'project',
                'date' => $project->updated_at->toDateTimeString(),
                'days_until_due' => null,
                'course' => $project->course ? $project->course->name : null,
                'student_name' => $project->user->name . ' ' . $project->user->last_name,
                'url' => route('teacher.student-projects'),
                'is_for_grade' => true
            ]);
        }

        // Ordenar por fecha de actualización (más recientes primero)
        return $tasksToGrade->sortByDesc('date')->take(5)->values();
    }
    
    public function students()
    {
        $students = auth()->user()->students()
            ->wherePivot('status', 'active')
            ->with(['tasks', 'projects'])
            ->paginate(15);
            
        return view('teacher.students', compact('students'));
    }
    
    public function gradeTask(Request $request, $taskId)
    {
        $request->validate([
            'grade' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:1000'
        ]);
        
        $task = Task::findOrFail($taskId);
        
        // Verificar que la tarea pertenece a un estudiante
        if ($task->user->role !== 'student') {
            return redirect()->back()->with('error', 'Solo se pueden calificar tareas de estudiantes.');
        }
        
        $task->update([
            'grade' => $request->grade,
            'feedback' => $request->feedback,
            'graded_at' => now(),
            'graded_by' => auth()->id()
        ]);
        
        // Enviar notificación al estudiante
        $teacherName = auth()->user()->name . ' ' . (auth()->user()->last_name ?? '');
        $task->user->notify(new GradeNotification(
            $task,
            'task',
            $request->grade,
            $request->feedback,
            $teacherName
        ));
        
        return redirect()->back()->with('success', 'Tarea calificada exitosamente.');
    }
    
    public function gradeProject(Request $request, $projectId)
    {
        $request->validate([
            'grade' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:1000'
        ]);
        
        $project = Project::findOrFail($projectId);
        
        // Verificar que el proyecto pertenece a un estudiante
        if ($project->user->role !== 'student') {
            return redirect()->back()->with('error', 'Solo se pueden calificar proyectos de estudiantes.');
        }
        
        $project->update([
            'grade' => $request->grade,
            'feedback' => $request->feedback,
            'graded_at' => now(),
            'graded_by' => auth()->id()
        ]);
        
        // Enviar notificación al estudiante
        $teacherName = auth()->user()->name . ' ' . (auth()->user()->last_name ?? '');
        $project->user->notify(new GradeNotification(
            $project,
            'project',
            $request->grade,
            $request->feedback,
            $teacherName
        ));
        
        return redirect()->back()->with('success', 'Proyecto calificado exitosamente.');
    }
    
    public function tasks(Request $request)
    {
        $students = auth()->user()->students()->wherePivot('status', 'active')->get();
        $studentIds = $students->pluck('id');
        
        $tasksQuery = Task::with(['user', 'course'])
            ->whereIn('user_id', $studentIds);

        if ($request->filled('q')) {
            $q = trim($request->q);
            $tasksQuery->where(function($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $tasks = $tasksQuery->orderBy('created_at', 'desc')->paginate(15);
            
        return view('teacher.tasks', compact('tasks', 'students'));
    }
    
    public function projects(Request $request)
    {
        $students = auth()->user()->students()->wherePivot('status', 'active')->get();
        $studentIds = $students->pluck('id');
        
        $projectsQuery = Project::with(['user', 'course', 'projectTasks'])
            ->whereIn('user_id', $studentIds);

        if ($request->filled('q')) {
            $q = trim($request->q);
            $projectsQuery->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $projects = $projectsQuery->orderBy('created_at', 'desc')->paginate(15);
            
        return view('teacher.projects', compact('projects', 'students'));
    }
    
    public function grades()
    {
        $students = auth()->user()->students()->wherePivot('status', 'active')->get();
        $studentIds = $students->pluck('id');
        
        $gradedTasks = Task::with(['user', 'course'])
            ->whereIn('user_id', $studentIds)
            ->whereNotNull('grade')
            ->where('graded_by', auth()->id())
            ->paginate(15);
            
        $gradedProjects = Project::with(['user', 'course'])
            ->whereIn('user_id', $studentIds)
            ->whereNotNull('grade')
            ->where('graded_by', auth()->id())
            ->paginate(15);
            
        return view('teacher.grades', compact('gradedTasks', 'gradedProjects'));
    }
    
    public function analytics()
    {
        $students = auth()->user()->students()
            ->wherePivot('status', 'active')
            ->with(['tasks', 'projects'])
            ->get();
        $studentIds = $students->pluck('id');
        
        // Calcular promedio para cada estudiante
        $students = $students->map(function ($student) {
            // Obtener todas las calificaciones (tareas y proyectos)
            $taskGrades = $student->tasks()->whereNotNull('grade')->pluck('grade');
            $projectGrades = $student->projects()->whereNotNull('grade')->pluck('grade');
            
            // Combinar todas las calificaciones
            $allGrades = $taskGrades->merge($projectGrades);
            
            // Calcular promedio
            $student->average_grade = $allGrades->count() > 0 ? $allGrades->avg() : 0;
            
            return $student;
        });
        
        // Calcular promedio general
        $allTaskGrades = Task::whereIn('user_id', $studentIds)->whereNotNull('grade')->pluck('grade');
        $allProjectGrades = Project::whereIn('user_id', $studentIds)->whereNotNull('grade')->pluck('grade');
        $allGrades = $allTaskGrades->merge($allProjectGrades);
        $averageGrade = $allGrades->count() > 0 ? $allGrades->avg() : 0;
        
        $analytics = [
            'total_students' => $students->count(),
            'total_tasks' => Task::whereIn('user_id', $studentIds)->count(),
            'total_projects' => Project::whereIn('user_id', $studentIds)->count(),
            'graded_tasks' => Task::whereIn('user_id', $studentIds)->whereNotNull('grade')->count(),
            'graded_projects' => Project::whereIn('user_id', $studentIds)->whereNotNull('grade')->count(),
            'average_grade' => $averageGrade,
        ];
        
        return view('teacher.analytics', compact('analytics', 'students'));
    }
    
    /**
     * Generar y descargar informe de estudiantes y notas
     */
    public function downloadReport()
    {
        $teacher = auth()->user();
        $students = $teacher->students()
            ->wherePivot('status', 'active')
            ->with(['tasks', 'projects'])
            ->get();
        
        // Preparar datos para el informe
        $reportData = [];
        
        foreach ($students as $student) {
            // Obtener todas las calificaciones
            $taskGrades = $student->tasks()->whereNotNull('grade')->get();
            $projectGrades = $student->projects()->whereNotNull('grade')->get();
            
            // Calcular promedio
            $allGrades = $taskGrades->pluck('grade')->merge($projectGrades->pluck('grade'));
            $averageGrade = $allGrades->count() > 0 ? $allGrades->avg() : 0;
            
            // Determinar estado
            $status = 'Sin Calificar';
            if ($averageGrade >= 90) {
                $status = 'Excelente';
            } elseif ($averageGrade >= 70) {
                $status = 'Bueno';
            } elseif ($averageGrade >= 50) {
                $status = 'Regular';
            } elseif ($averageGrade > 0) {
                $status = 'Necesita Mejora';
            }
            
            // Detalles de tareas
            $taskDetails = [];
            foreach ($taskGrades as $task) {
                $taskDetails[] = [
                    'tipo' => 'Tarea',
                    'titulo' => $task->title,
                    'curso' => $task->course ? $task->course->name : 'N/A',
                    'calificacion' => $task->grade,
                    'fecha' => $task->graded_at ? $task->graded_at->format('d/m/Y') : 'N/A'
                ];
            }
            
            // Detalles de proyectos
            foreach ($projectGrades as $project) {
                $taskDetails[] = [
                    'tipo' => 'Proyecto',
                    'titulo' => $project->name,
                    'curso' => $project->course ? $project->course->name : 'N/A',
                    'calificacion' => $project->grade,
                    'fecha' => $project->graded_at ? $project->graded_at->format('d/m/Y') : 'N/A'
                ];
            }
            
            $reportData[] = [
                'estudiante' => $student->name . ' ' . $student->last_name,
                'email' => $student->email,
                'total_tareas' => $student->tasks->count(),
                'total_proyectos' => $student->projects->count(),
                'tareas_calificadas' => $taskGrades->count(),
                'proyectos_calificados' => $projectGrades->count(),
                'promedio' => number_format($averageGrade, 2),
                'estado' => $status,
                'detalles' => $taskDetails
            ];
        }
        
        // Generar CSV
        $filename = 'informe_estudiantes_' . $teacher->name . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($reportData, $teacher) {
            $file = fopen('php://output', 'w');
            
            // BOM para UTF-8 (Excel compatibility)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezado del informe
            fputcsv($file, ['INFORME DE ESTUDIANTES Y NOTAS']);
            fputcsv($file, ['Profesor: ' . $teacher->name . ' ' . $teacher->last_name]);
            fputcsv($file, ['Fecha: ' . date('d/m/Y H:i:s')]);
            fputcsv($file, []); // Línea vacía
            
            // Encabezados de la tabla principal
            fputcsv($file, [
                'Estudiante',
                'Email',
                'Total Tareas',
                'Total Proyectos',
                'Tareas Calificadas',
                'Proyectos Calificados',
                'Promedio',
                'Estado'
            ]);
            
            // Datos de estudiantes
            foreach ($reportData as $row) {
                fputcsv($file, [
                    $row['estudiante'],
                    $row['email'],
                    $row['total_tareas'],
                    $row['total_proyectos'],
                    $row['tareas_calificadas'],
                    $row['proyectos_calificados'],
                    $row['promedio'],
                    $row['estado']
                ]);
            }
            
            // Línea vacía
            fputcsv($file, []);
            fputcsv($file, ['DETALLE DE CALIFICACIONES']);
            fputcsv($file, []);
            
            // Detalles por estudiante
            foreach ($reportData as $row) {
                fputcsv($file, ['Estudiante: ' . $row['estudiante']]);
                fputcsv($file, ['Tipo', 'Título', 'Curso', 'Calificación', 'Fecha']);
                
                foreach ($row['detalles'] as $detail) {
                    fputcsv($file, [
                        $detail['tipo'],
                        $detail['titulo'],
                        $detail['curso'],
                        $detail['calificacion'],
                        $detail['fecha']
                    ]);
                }
                
                fputcsv($file, []); // Línea vacía entre estudiantes
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function assignStudents()
    {
        $assignments = \App\Models\TeacherStudentAssignment::where('teacher_id', auth()->id())
            ->where('status', 'active')
            ->with('student')
            ->get();
            
        $assignedStudentIds = $assignments->pluck('student_id');
        $allStudents = User::where('role', 'student')
            ->whereNotIn('id', $assignedStudentIds)
            ->get();
            
        return view('teacher.assign-students', compact('assignments', 'allStudents'));
    }
    
    public function storeStudentAssignment(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'class_name' => 'nullable|string|max:255',
        ]);
        
        // Verificar que el estudiante sea realmente un estudiante
        $student = User::findOrFail($request->student_id);
        if ($student->role !== 'student') {
            return back()->with('error', 'El usuario seleccionado no es un estudiante.');
        }
        
        // Verificar que no esté ya asignado a este profesor
        $existingAssignment = \App\Models\TeacherStudentAssignment::where([
            'teacher_id' => auth()->id(),
            'student_id' => $request->student_id,
        ])->first();
        
        if ($existingAssignment) {
            return back()->with('error', 'Este estudiante ya está asignado a tu clase.');
        }
        
        \App\Models\TeacherStudentAssignment::create([
            'teacher_id' => auth()->id(),
            'student_id' => $request->student_id,
            'class_name' => $request->class_name ?? 'Mi Clase',
            'status' => 'active',
        ]);
        
        return back()->with('success', 'Estudiante asignado exitosamente a tu clase.');
    }
    
    public function removeStudentAssignment(\App\Models\TeacherStudentAssignment $assignment)
    {
        // Verificar que la asignación pertenece al profesor actual
        if ($assignment->teacher_id !== auth()->id()) {
            return back()->with('error', 'No tienes permisos para realizar esta acción.');
        }
        
        $assignment->delete();
        return back()->with('success', 'Estudiante removido de tu clase exitosamente.');
    }
    
    public function updateClassName(Request $request, \App\Models\TeacherStudentAssignment $assignment)
    {
        // Verificar que la asignación pertenece al profesor actual
        if ($assignment->teacher_id !== auth()->id()) {
            return back()->with('error', 'No tienes permisos para realizar esta acción.');
        }
        
        $request->validate([
            'class_name' => 'required|string|max:255',
        ]);
        
        $assignment->update([
            'class_name' => $request->class_name,
        ]);
        
        return back()->with('success', 'Nombre de clase actualizado exitosamente.');
    }
    
    public function createTask()
    {
        $students = auth()->user()->students()->wherePivot('status', 'active')->get();
        return view('teacher.create-task', compact('students'));
    }
    
    public function storeTask(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:task,exam,project,reading',
            'due_date' => 'required|date|after:now',
            'priority' => 'required|in:low,medium,high',
            'support_file' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png,gif,zip,rar|max:10240'
        ]);
        
        // Verificar que el estudiante esté asignado a este profesor
        $assignment = \App\Models\TeacherStudentAssignment::where([
            'teacher_id' => auth()->id(),
            'student_id' => $request->student_id,
            'status' => 'active'
        ])->first();
        
        if (!$assignment) {
            return back()->with('error', 'No tienes permisos para asignar tareas a este estudiante.');
        }
        
        $task = Task::create([
            'user_id' => $request->student_id,
            'teacher_id' => auth()->id(),
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'start_date' => now(),
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'status' => 'pending',
        ]);

        // Material de apoyo
        if ($request->hasFile('support_file')) {
            $file = $request->file('support_file');
            $fileName = \Illuminate\Support\Str::uuid().'.'.$file->getClientOriginalExtension();
            $path = 'support-material/tasks/'.$fileName;
            $file->storeAs('support-material/tasks', $fileName, 'public');
            $task->update([
                'support_file_path' => $path,
                'support_file_name' => $file->getClientOriginalName(),
                'support_file_type' => $file->getMimeType(),
                'support_file_size' => $file->getSize(),
            ]);
        }
        
        return redirect()->route('teacher.tasks')->with('success', 'Tarea asignada exitosamente.');
    }
    
    public function createProject()
    {
        $students = auth()->user()->students()->wherePivot('status', 'active')->get();
        return view('teacher.create-project', compact('students'));
    }
    
    public function storeProject(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'end_date' => 'required|date|after:now',
            'priority' => 'required|in:low,medium,high',
            'support_file' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png,gif,zip,rar|max:10240'
        ]);
        
        // Verificar que el estudiante esté asignado a este profesor
        $assignment = \App\Models\TeacherStudentAssignment::where([
            'teacher_id' => auth()->id(),
            'student_id' => $request->student_id,
            'status' => 'active'
        ])->first();
        
        if (!$assignment) {
            return back()->with('error', 'No tienes permisos para asignar proyectos a este estudiante.');
        }
        
        $project = Project::create([
            'user_id' => $request->student_id,
            'teacher_id' => auth()->id(),
            'course_id' => $request->course_id,
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => now(),
            'end_date' => $request->end_date,
            'priority' => $request->priority,
            'status' => 'active',
            'progress' => 0,
        ]);

        // Material de apoyo
        if ($request->hasFile('support_file')) {
            $file = $request->file('support_file');
            $fileName = \Illuminate\Support\Str::uuid().'.'.$file->getClientOriginalExtension();
            $path = 'support-material/projects/'.$fileName;
            $file->storeAs('support-material/projects', $fileName, 'public');
            $project->update([
                'support_file_path' => $path,
                'support_file_name' => $file->getClientOriginalName(),
                'support_file_type' => $file->getMimeType(),
                'support_file_size' => $file->getSize(),
            ]);
        }
        
        if ($request->boolean('create_subtasks')) {
            return redirect()->route('project-tasks.index', $project)
                ->with('success', 'Proyecto asignado. Ahora puedes crear las subtareas.');
        }
        
        return redirect()->route('teacher.projects')->with('success', 'Proyecto asignado exitosamente.');
    }
    
    /**
     * Obtener los cursos de un estudiante específico (AJAX)
     */
    public function getStudentCourses($studentId)
    {
        // Verificar que el estudiante esté asignado a este profesor
        $assignment = \App\Models\TeacherStudentAssignment::where([
            'teacher_id' => auth()->id(),
            'student_id' => $studentId,
            'status' => 'active'
        ])->first();
        
        if (!$assignment) {
            return response()->json(['error' => 'No tienes permisos para ver los cursos de este estudiante.'], 403);
        }
        
        // Obtener los cursos asignados a este estudiante a través de TeacherStudentAssignment
        $assignedCourseIds = \App\Models\TeacherStudentAssignment::where('student_id', $studentId)
            ->where('status', 'active')
            ->whereNotNull('course_id')
            ->pluck('course_id')
            ->unique()
            ->toArray();
        
        // Obtener las materias asignadas
        $courses = \App\Models\Course::whereIn('id', $assignedCourseIds)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);
        
        return response()->json($courses);
    }

    public function getStudentDetails($studentId)
    {
        $student = User::findOrFail($studentId);
        if ($student->role !== 'student') {
            return response()->json(['error' => 'El usuario no es estudiante.'], 422);
        }
        $assignment = \App\Models\TeacherStudentAssignment::where([
            'teacher_id' => auth()->id(),
            'student_id' => $studentId,
            'status' => 'active'
        ])->first();
        if (!$assignment) {
            return response()->json(['error' => 'No tienes permisos para ver este estudiante.'], 403);
        }
        $tasksTotal = Task::where('user_id', $studentId)->count();
        $projectsTotal = Project::where('user_id', $studentId)->count();
        $tasksByTeacher = Task::where('user_id', $studentId)->where('teacher_id', auth()->id())->count();
        $projectsByTeacher = Project::where('user_id', $studentId)->where('teacher_id', auth()->id())->count();
        $gradedTasksByTeacher = Task::where('user_id', $studentId)->whereNotNull('grade')->where('graded_by', auth()->id())->count();
        $gradedProjectsByTeacher = Project::where('user_id', $studentId)->whereNotNull('grade')->where('graded_by', auth()->id())->count();
        $allGrades = collect();
        $allGrades = $allGrades->merge(Task::where('user_id', $studentId)->whereNotNull('grade')->pluck('grade'));
        $allGrades = $allGrades->merge(Project::where('user_id', $studentId)->whereNotNull('grade')->pluck('grade'));
        $average = $allGrades->count() > 0 ? round($allGrades->avg(), 1) : 0;
        $courseIds = \App\Models\TeacherStudentAssignment::where('student_id', $studentId)
            ->where('status', 'active')
            ->whereNotNull('course_id')
            ->pluck('course_id')
            ->unique()
            ->toArray();
        $courses = Course::whereIn('id', $courseIds)->get(['id','name','code','professor','semester','schedule','credits','description']);
        return response()->json([
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'last_name' => $student->last_name,
                'email' => $student->email,
                'profile_photo_url' => $student->profile_photo_url,
                'class_name' => $assignment->class_name,
            ],
            'stats' => [
                'tasks_total' => $tasksTotal,
                'projects_total' => $projectsTotal,
                'tasks_by_teacher' => $tasksByTeacher,
                'projects_by_teacher' => $projectsByTeacher,
                'graded_tasks_by_teacher' => $gradedTasksByTeacher,
                'graded_projects_by_teacher' => $gradedProjectsByTeacher,
                'average' => $average,
            ],
            'courses' => $courses,
        ]);
    }
}
