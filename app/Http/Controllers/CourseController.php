<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Requests\CourseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        
        // Autorizar acceso (viewAny siempre devuelve true, pero es buena práctica)
        $this->authorize('viewAny', Course::class);
        
        // Si es estudiante, obtener también las materias asignadas por profesores
        if ($user->isStudent()) {
            // Obtener materias propias del estudiante
            $ownCourses = Course::where('user_id', $user->id)->get();
            
            // Obtener IDs de materias asignadas a través de TeacherStudentAssignment
            $assignedCourseIds = \App\Models\TeacherStudentAssignment::where('student_id', $user->id)
                ->where('status', 'active')
                ->whereNotNull('course_id')
                ->pluck('course_id')
                ->unique()
                ->toArray();
            
            // Obtener las materias asignadas
            $assignedCourses = Course::whereIn('id', $assignedCourseIds)->get();
            
            // Combinar y eliminar duplicados
            $allCourses = $ownCourses->merge($assignedCourses)->unique('id');
            $courses = $allCourses->sortBy('name')->values();
        } elseif ($user->isTeacher()) {
            // Para profesores: sus propias materias + materias asignadas a través de TeacherStudentAssignment
            $ownCourses = Course::where('user_id', $user->id)->get();
            
            // Obtener IDs de materias asignadas a través de TeacherStudentAssignment
            $assignedCourseIds = \App\Models\TeacherStudentAssignment::where('teacher_id', $user->id)
                ->where('status', 'active')
                ->whereNotNull('course_id')
                ->pluck('course_id')
                ->unique()
                ->toArray();
            
            // Obtener las materias asignadas
            $assignedCourses = Course::whereIn('id', $assignedCourseIds)->get();
            
            // Combinar y eliminar duplicados
            $allCourses = $ownCourses->merge($assignedCourses)->unique('id');
            $courses = $allCourses->sortBy('name')->values();
        } else {
            // Para otros roles (admin), solo sus propias materias
            $courses = Course::where('user_id', $user->id)
                ->orderBy('name')
                ->paginate(10);
        }
            
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        $this->authorize('create', Course::class);
        
        return view('courses.create');
    }

    public function store(CourseRequest $request)
    {
        $this->authorize('create', Course::class);
        
        try {
            $validated = $request->validated();

            $course = Course::create([
                'user_id' => auth()->id(),
                'name' => $validated['name'],
                'code' => $validated['code'],
                'description' => $validated['description'],
                'color' => $validated['color'],
                'semester' => $validated['semester'],
                'professor' => $validated['professor'],
                'schedule' => $validated['schedule'],
                'credits' => $validated['credits']
            ]);

            return redirect()->route('courses.index')
                ->with('success', '¡Materia creada exitosamente! "' . $course->name . '" ha sido agregada a tu lista.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la materia. Por favor, inténtalo de nuevo.');
        }
    }

    public function show(Course $course)
    {
        $this->authorize('view', $course);
        
        // Cargar tareas, proyectos y recordatorios del curso
        $course->load(['tasks', 'projects', 'reminders']);
        
        $className = null;
        $user = auth()->user();
        if ($user->isStudent()) {
            $className = \App\Models\TeacherStudentAssignment::where('student_id', $user->id)
                ->where('course_id', $course->id)
                ->where('status', 'active')
                ->value('class_name');
        } elseif ($user->isTeacher()) {
            $className = \App\Models\TeacherStudentAssignment::where('teacher_id', $user->id)
                ->where('course_id', $course->id)
                ->where('status', 'active')
                ->value('class_name');
        }
        
        return view('courses.show', compact('course', 'className'));
    }

    public function edit(Course $course)
    {
        $this->authorize('update', $course);
        return view('courses.edit', compact('course'));
    }

    public function update(CourseRequest $request, Course $course)
    {
        try {
            $this->authorize('update', $course);

            $validated = $request->validated();
            $oldName = $course->name;
            
            $course->update($validated);

            return redirect()->route('courses.index')
                ->with('success', '¡Materia actualizada exitosamente! "' . $oldName . '" ha sido modificada.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la materia. Por favor, inténtalo de nuevo.');
        }
    }

    public function destroy(Course $course)
    {
        try {
            $this->authorize('delete', $course);
            
            // Verificar si hay tareas, proyectos o recordatorios asociados
            if ($course->tasks()->count() > 0 || $course->projects()->count() > 0 || $course->reminders()->count() > 0) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede eliminar la materia porque tiene tareas, proyectos o recordatorios asociados.'
                    ], 422);
                }
                
                return redirect()->route('courses.index')
                    ->with('error', 'No se puede eliminar la materia porque tiene tareas, proyectos o recordatorios asociados.');
            }
            
            $courseName = $course->name;
            $course->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '¡Materia eliminada exitosamente! La materia "' . $courseName . '" ha sido removida de tu lista.'
                ]);
            }

            return redirect()->route('courses.index')
                ->with('success', '¡Materia eliminada exitosamente! La materia "' . $courseName . '" ha sido removida de tu lista.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la materia. Por favor, inténtalo de nuevo.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error al eliminar la materia. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Obtener cursos para formularios (AJAX)
     */
    public function getCourses()
    {
        $courses = Course::where('user_id', auth()->id())
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'color']);
            
        return response()->json($courses);
    }
} 
