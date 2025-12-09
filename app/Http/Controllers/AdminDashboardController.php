<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        // Estadísticas generales del sistema
        $stats = [
            'total_users' => User::count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_tasks' => Task::count(),
            'total_projects' => Project::count(),
            'total_courses' => Course::count(),
            'pending_users' => User::where('status', 'pending')->count(),
        ];
        
        // Usuarios recientes
        $recentUsers = User::latest()->take(10)->get();
        
        // Usuarios pendientes de aprobación
        $pendingUsers = User::where('status', 'pending')->get();
        
        // Actividad reciente
        $recentTasks = Task::with('user')->latest()->take(5)->get();
        $recentProjects = Project::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'pendingUsers',
            'recentTasks',
            'recentProjects'
        ));
    }
    
    public function users(Request $request)
    {
        $query = User::with(['tasks', 'projects']);

        // Filtrar por búsqueda de texto
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Filtrar por rol
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filtrar por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(15);
        $roles = ['student', 'teacher', 'admin'];
        $assignableRoles = ['student', 'teacher'];
        $adminCount = User::where('role', 'admin')->count();
        if ($adminCount === 0) {
            $assignableRoles[] = 'admin';
        }

        return view('admin.users', compact('users', 'roles', 'assignableRoles'));
    }
    
    public function teachers()
    {
        $teachers = User::where('role', 'teacher')
            ->with(['tasks', 'projects'])
            ->paginate(15);
            
        return view('admin.teachers', compact('teachers'));
    }

    public function showTeacher($teacherId)
    {
        $teacher = User::with(['tasks', 'projects'])->findOrFail($teacherId);
        if ($teacher->role !== 'teacher') {
            abort(404);
        }

        $totalAssignedTasks = Task::where('teacher_id', $teacher->id)->count();
        $totalAssignedProjects = Project::where('teacher_id', $teacher->id)->count();
        $gradedTasks = Task::where('graded_by', $teacher->id)->count();
        $gradedProjects = Project::where('graded_by', $teacher->id)->count();

        return response()->json([
            'id' => $teacher->id,
            'name' => $teacher->name,
            'last_name' => $teacher->last_name,
            'email' => $teacher->email,
            'status' => $teacher->status,
            'role' => $teacher->role,
            'profile_photo_url' => $teacher->profile_photo_url,
            'created_at' => $teacher->created_at ? $teacher->created_at->format('d/m/Y H:i') : null,
            'counts' => [
                'tasks' => $totalAssignedTasks,
                'projects' => $totalAssignedProjects,
                'graded_tasks' => $gradedTasks,
                'graded_projects' => $gradedProjects,
            ],
        ]);
    }
    
    public function assignRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|in:student,teacher,admin'
        ]);

        $user = User::findOrFail($userId);

        if ($request->role === 'admin') {
            $otherAdmins = User::where('role', 'admin')->where('id', '!=', $user->id)->count();
            if ($otherAdmins > 0) {
                return redirect()->back()->with('error', 'Solo puede existir un Administrador en el sistema.');
            }
        }

        $user->update([
            'role' => $request->role,
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Rol asignado exitosamente.');
    }
    
    public function approveUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['status' => 'active']);
        
        return redirect()->back()->with('success', 'Usuario aprobado exitosamente.');
    }
    
    public function rejectUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['status' => 'rejected']);
        
        return redirect()->back()->with('success', 'Usuario rechazado.');
    }
    
    public function createTeacher()
    {
        return view('admin.create-teacher');
    }
    
    public function storeTeacher(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        
        User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'status' => 'active'
        ]);
        
        return redirect()->route('admin.teachers')
            ->with('success', 'Profesor creado exitosamente.');
    }
    
    public function assignments()
    {
        // Obtener todas las asignaciones agrupadas por profesor y estudiante
        $allAssignments = \App\Models\TeacherStudentAssignment::with(['teacher', 'student', 'course'])
            ->get()
            ->groupBy(function($assignment) {
                return $assignment->teacher_id . '-' . $assignment->student_id;
            });
        
        // Crear una colección de asignaciones agrupadas para la vista
        $groupedAssignments = collect();
        foreach ($allAssignments as $key => $assignments) {
            $firstAssignment = $assignments->first();
            $groupedAssignments->push([
                'teacher' => $firstAssignment->teacher,
                'student' => $firstAssignment->student,
                'courses' => $assignments->pluck('course')->filter(),
                'assignments' => $assignments,
                'class_names' => $assignments->pluck('class_name')->filter()->unique(),
            ]);
        }
        
        // Paginar los resultados agrupados
        $currentPage = request()->get('page', 1);
        $perPage = 15;
        $items = $groupedAssignments->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $assignments = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $groupedAssignments->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        $teachers = User::where('role', 'teacher')->get();
        $students = User::where('role', 'student')->get();
        
        return view('admin.assign-students', compact('assignments', 'teachers', 'students'));
    }
    
    public function storeAssignment(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'student_id' => 'required|exists:users,id',
            'course_name' => 'required|string|max:255',
            'course_code' => 'nullable|string|max:50',
            'schedule' => 'nullable|string|max:255',
            'class_name' => 'nullable|string|max:255',
        ]);
        
        // Verificar que el profesor sea realmente un profesor
        $teacher = User::findOrFail($request->teacher_id);
        if ($teacher->role !== 'teacher') {
            return back()->with('error', 'El usuario seleccionado no es un profesor.');
        }
        
        // Verificar que el estudiante sea realmente un estudiante
        $student = User::findOrFail($request->student_id);
        if ($student->role !== 'student') {
            return back()->with('error', 'El usuario seleccionado no es un estudiante.');
        }
        
        // Crear o buscar el curso
        $courseName = trim($request->course_name);
        
        // Generar código único si no se proporciona
        if ($request->filled('course_code')) {
            $courseCode = trim($request->course_code);
        } else {
            $baseCode = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $courseName), 0, 3));
            $courseCode = $baseCode;
            $counter = 1;
            // Asegurar que el código sea único
            while (\App\Models\Course::where('code', $courseCode)->exists()) {
                $courseCode = $baseCode . $counter;
                $counter++;
            }
        }
        
        // Buscar si ya existe un curso con ese nombre creado por un admin
        $course = \App\Models\Course::whereHas('user', function($query) {
            $query->where('role', 'admin');
        })
        ->where('name', $courseName)
        ->first();
        
        // Si no existe, crear el curso
        if (!$course) {
            $course = \App\Models\Course::create([
                'user_id' => auth()->id(),
                'name' => $courseName,
                'code' => $courseCode,
                'description' => 'Materia asignada por administrador',
                'color' => '#4f46e5',
                'semester' => date('Y') . '-' . (date('Y') + 1),
                'professor' => $teacher->name . ' ' . ($teacher->last_name ?? ''),
                'schedule' => $request->filled('schedule') ? trim($request->schedule) : 'Por asignar',
                'credits' => 3
            ]);
        }
        
        // Verificar que no exista ya la asignación con el mismo curso
        $existingAssignment = \App\Models\TeacherStudentAssignment::where([
            'teacher_id' => $request->teacher_id,
            'student_id' => $request->student_id,
            'course_id' => $course->id,
        ])->first();
        
        if ($existingAssignment) {
            return back()->with('error', 'Esta asignación ya existe para esta materia.');
        }
        
        \App\Models\TeacherStudentAssignment::create([
            'teacher_id' => $request->teacher_id,
            'student_id' => $request->student_id,
            'course_id' => $course->id,
            'class_name' => $request->class_name,
            'status' => 'active',
        ]);
        
        return back()->with('success', 'Asignación creada exitosamente. La materia "' . $course->name . '" ha sido asignada al profesor y al estudiante.');
    }
    
    public function toggleAssignment(\App\Models\TeacherStudentAssignment $assignment)
    {
        $assignment->update([
            'status' => $assignment->status === 'active' ? 'inactive' : 'active'
        ]);
        
        $status = $assignment->status === 'active' ? 'activada' : 'desactivada';
        return back()->with('success', "Asignación {$status} exitosamente.");
    }
    
    public function destroyAssignment(\App\Models\TeacherStudentAssignment $assignment)
    {
        $assignment->delete();
        return back()->with('success', 'Asignación eliminada exitosamente.');
    }
    
    /**
     * Descargar informe de estudiantes de un profesor específico
     */
    public function downloadTeacherReport($teacherId)
    {
        $teacher = User::findOrFail($teacherId);
        
        if ($teacher->role !== 'teacher') {
            return back()->with('error', 'El usuario seleccionado no es un profesor.');
        }
        
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
            fputcsv($file, ['Email: ' . $teacher->email]);
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
    
    /**
     * Descargar reporte de usuarios (PDF/CSV)
     */
    public function downloadUsersReport(Request $request)
    {
        $month = (int) ($request->get('month'));
        $year = (int) ($request->get('year'));
        $users = User::with(['tasks', 'projects', 'courses'])->get();
        
        $filenameSuffix = ($month && $year) ? sprintf('%04d-%02d', $year, $month) : date('Y-m-d');
        $filename = 'reporte_usuarios_' . $filenameSuffix . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($users, $month, $year) {
            $file = fopen('php://output', 'w');
            
            // BOM para UTF-8 (Excel compatibility)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezado del informe
            fputcsv($file, ['REPORTE DE USUARIOS DEL SISTEMA']);
            fputcsv($file, ['Fecha: ' . date('d/m/Y H:i:s')]);
            if ($month && $year) {
                fputcsv($file, ['Periodo: ' . sprintf('%02d/%04d', $month, $year)]);
            }
            fputcsv($file, []); // Línea vacía
            
            // Encabezados
            fputcsv($file, [
                'ID',
                'Nombre',
                'Apellido',
                'Email',
                'Rol',
                'Estado',
                'Total Tareas',
                'Total Proyectos',
                'Total Cursos',
                'Tareas Completadas',
                'Proyectos Completados',
                'Fecha de Registro',
                'Email Verificado'
            ]);
            
            // Datos de usuarios
            foreach ($users as $user) {
                $taskQuery = $user->tasks();
                $projectQuery = $user->projects();
                if ($month && $year) {
                    $taskQuery = $taskQuery->whereYear('created_at', $year)->whereMonth('created_at', $month);
                    $projectQuery = $projectQuery->whereYear('created_at', $year)->whereMonth('created_at', $month);
                }
                $completedTasks = (clone $taskQuery)->where('status', 'completed')->count();
                $completedProjects = (clone $projectQuery)->where('status', 'completed')->count();
                
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->last_name ?? 'N/A',
                    $user->email,
                    ucfirst($user->role),
                    ucfirst($user->status),
                    $taskQuery->count(),
                    $projectQuery->count(),
                    $user->courses->count(),
                    $completedTasks,
                    $completedProjects,
                    $user->created_at->format('d/m/Y H:i:s'),
                    $user->email_verified_at ? 'Sí' : 'No'
                ]);
            }
            
            // Estadísticas generales
            fputcsv($file, []);
            fputcsv($file, ['ESTADÍSTICAS GENERALES']);
            fputcsv($file, [
                'Total Usuarios: ' . $users->count(),
                'Estudiantes: ' . $users->where('role', 'student')->count(),
                'Profesores: ' . $users->where('role', 'teacher')->count(),
                'Administradores: ' . $users->where('role', 'admin')->count(),
                'Usuarios Activos: ' . $users->where('status', 'active')->count(),
                'Usuarios Pendientes: ' . $users->where('status', 'pending')->count(),
            ]);
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Descargar reporte de rendimiento de profesores (Excel/CSV)
     */
    public function downloadTeachersPerformanceReport(Request $request)
    {
        $month = (int) ($request->get('month'));
        $year = (int) ($request->get('year'));
        $teachers = User::where('role', 'teacher')
            ->with(['students', 'students.tasks', 'students.projects'])
            ->get();
        
        $filenameSuffix = ($month && $year) ? sprintf('%04d-%02d', $year, $month) : date('Y-m-d');
        $filename = 'reporte_rendimiento_profesores_' . $filenameSuffix . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($teachers, $month, $year) {
            $file = fopen('php://output', 'w');
            
            // BOM para UTF-8 (Excel compatibility)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezado del informe
            fputcsv($file, ['REPORTE DE RENDIMIENTO DE PROFESORES']);
            fputcsv($file, ['Fecha: ' . date('d/m/Y H:i:s')]);
            if ($month && $year) {
                fputcsv($file, ['Periodo: ' . sprintf('%02d/%04d', $month, $year)]);
            }
            fputcsv($file, []); // Línea vacía
            
            foreach ($teachers as $teacher) {
                $students = $teacher->students()->wherePivot('status', 'active')->get();
                $studentIds = $students->pluck('id');
                
                $taskQuery = Task::whereIn('user_id', $studentIds);
                $projectQuery = Project::whereIn('user_id', $studentIds);
                if ($month && $year) {
                    $taskQuery = $taskQuery->whereYear('created_at', $year)->whereMonth('created_at', $month);
                    $projectQuery = $projectQuery->whereYear('created_at', $year)->whereMonth('created_at', $month);
                }
                $totalTasks = $taskQuery->count();
                $totalProjects = $projectQuery->count();
                $gradedTasks = (clone $taskQuery)->whereNotNull('grade')->count();
                $gradedProjects = (clone $projectQuery)->whereNotNull('grade')->count();
                
                $allTaskGrades = (clone $taskQuery)->whereNotNull('grade')->pluck('grade');
                $allProjectGrades = (clone $projectQuery)->whereNotNull('grade')->pluck('grade');
                $allGrades = $allTaskGrades->merge($allProjectGrades);
                $averageGrade = $allGrades->count() > 0 ? $allGrades->avg() : 0;
                
                // Información del profesor
                fputcsv($file, ['PROFESOR: ' . $teacher->name . ' ' . ($teacher->last_name ?? '')]);
                fputcsv($file, ['Email: ' . $teacher->email]);
                fputcsv($file, [
                    'Total Estudiantes Asignados: ' . $students->count(),
                    'Total Tareas: ' . $totalTasks,
                    'Total Proyectos: ' . $totalProjects,
                    'Tareas Calificadas: ' . $gradedTasks,
                    'Proyectos Calificados: ' . $gradedProjects,
                    'Promedio General: ' . number_format($averageGrade, 2),
                    'Tasa de Calificación: ' . ($totalTasks + $totalProjects > 0 ? number_format((($gradedTasks + $gradedProjects) / ($totalTasks + $totalProjects)) * 100, 2) . '%' : '0%')
                ]);
                fputcsv($file, []); // Línea vacía
                
                // Detalles por estudiante
                if ($students->count() > 0) {
                    fputcsv($file, ['ESTUDIANTES ASIGNADOS']);
                    fputcsv($file, ['Estudiante', 'Email', 'Tareas', 'Proyectos', 'Tareas Calificadas', 'Proyectos Calificados', 'Promedio']);
                    
                    foreach ($students as $student) {
                        $studentTaskQuery = $student->tasks();
                        $studentProjectQuery = $student->projects();
                        if ($month && $year) {
                            $studentTaskQuery = $studentTaskQuery->whereYear('created_at', $year)->whereMonth('created_at', $month);
                            $studentProjectQuery = $studentProjectQuery->whereYear('created_at', $year)->whereMonth('created_at', $month);
                        }
                        $studentTaskGrades = (clone $studentTaskQuery)->whereNotNull('grade')->pluck('grade');
                        $studentProjectGrades = (clone $studentProjectQuery)->whereNotNull('grade')->pluck('grade');
                        $studentAllGrades = $studentTaskGrades->merge($studentProjectGrades);
                        $studentAverage = $studentAllGrades->count() > 0 ? $studentAllGrades->avg() : 0;
                        
                        fputcsv($file, [
                            $student->name . ' ' . ($student->last_name ?? ''),
                            $student->email,
                            $studentTaskQuery->count(),
                            $studentProjectQuery->count(),
                            (clone $studentTaskQuery)->whereNotNull('grade')->count(),
                            (clone $studentProjectQuery)->whereNotNull('grade')->count(),
                            number_format($studentAverage, 2)
                        ]);
                    }
                }
                
                fputcsv($file, []); // Línea vacía entre profesores
                fputcsv($file, []); // Línea vacía
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Descargar reporte de uso del sistema (CSV)
     */
    public function downloadSystemUsageReport()
    {
        $stats = [
            'total_users' => User::count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_tasks' => Task::count(),
            'total_projects' => Project::count(),
            'total_courses' => Course::count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
            'completed_projects' => Project::where('status', 'completed')->count(),
            'pending_tasks' => Task::where('status', 'pending')->count(),
            'in_progress_tasks' => Task::where('status', 'in_progress')->count(),
            'pending_projects' => Project::where('status', 'pending')->count(),
            'in_progress_projects' => Project::where('status', 'in_progress')->count(),
            'active_users' => User::where('status', 'active')->count(),
            'pending_users' => User::where('status', 'pending')->count(),
        ];
        
        // Actividad por mes (últimos 6 meses)
        $monthlyActivity = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyActivity[] = [
                'mes' => $date->format('F Y'),
                'tareas_creadas' => Task::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'proyectos_creados' => Project::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'usuarios_registrados' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        }
        
        $filename = 'reporte_uso_sistema_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($stats, $monthlyActivity) {
            $file = fopen('php://output', 'w');
            
            // BOM para UTF-8 (Excel compatibility)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezado del informe
            fputcsv($file, ['REPORTE DE USO DEL SISTEMA']);
            fputcsv($file, ['Fecha: ' . date('d/m/Y H:i:s')]);
            fputcsv($file, []); // Línea vacía
            
            // Estadísticas generales
            fputcsv($file, ['ESTADÍSTICAS GENERALES']);
            fputcsv($file, ['Métrica', 'Valor']);
            fputcsv($file, ['Total Usuarios', $stats['total_users']]);
            fputcsv($file, ['Estudiantes', $stats['total_students']]);
            fputcsv($file, ['Profesores', $stats['total_teachers']]);
            fputcsv($file, ['Administradores', $stats['total_admins']]);
            fputcsv($file, ['Usuarios Activos', $stats['active_users']]);
            fputcsv($file, ['Usuarios Pendientes', $stats['pending_users']]);
            fputcsv($file, []); // Línea vacía
            
            fputcsv($file, ['TAREAS']);
            fputcsv($file, ['Total Tareas', $stats['total_tasks']]);
            fputcsv($file, ['Tareas Completadas', $stats['completed_tasks']]);
            fputcsv($file, ['Tareas Pendientes', $stats['pending_tasks']]);
            fputcsv($file, ['Tareas En Progreso', $stats['in_progress_tasks']]);
            fputcsv($file, ['Tasa de Completación', $stats['total_tasks'] > 0 ? number_format(($stats['completed_tasks'] / $stats['total_tasks']) * 100, 2) . '%' : '0%']);
            fputcsv($file, []); // Línea vacía
            
            fputcsv($file, ['PROYECTOS']);
            fputcsv($file, ['Total Proyectos', $stats['total_projects']]);
            fputcsv($file, ['Proyectos Completados', $stats['completed_projects']]);
            fputcsv($file, ['Proyectos Pendientes', $stats['pending_projects']]);
            fputcsv($file, ['Proyectos En Progreso', $stats['in_progress_projects']]);
            fputcsv($file, ['Tasa de Completación', $stats['total_projects'] > 0 ? number_format(($stats['completed_projects'] / $stats['total_projects']) * 100, 2) . '%' : '0%']);
            fputcsv($file, []); // Línea vacía
            
            fputcsv($file, ['CURSOS']);
            fputcsv($file, ['Total Cursos', $stats['total_courses']]);
            fputcsv($file, []); // Línea vacía
            
            // Actividad mensual
            fputcsv($file, ['ACTIVIDAD MENSUAL (ÚLTIMOS 6 MESES)']);
            fputcsv($file, ['Mes', 'Tareas Creadas', 'Proyectos Creados', 'Usuarios Registrados']);
            
            foreach ($monthlyActivity as $activity) {
                fputcsv($file, [
                    $activity['mes'],
                    $activity['tareas_creadas'],
                    $activity['proyectos_creados'],
                    $activity['usuarios_registrados']
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
