<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = trim((string) $request->get('q', ''));

        if ($query === '') {
            return view('search.results', [
                'query' => $query,
                'tasks' => collect(),
                'projects' => collect(),
                'courses' => collect(),
                'users' => collect(),
            ]);
        }

        $user = Auth::user();
        $isTeacher = $user->hasRole('teacher');
        $isAdmin = $user->hasRole('admin');

        // Base empty collections
        $tasks = collect();
        $projects = collect();
        $courses = collect();
        $users = collect();

        if ($isAdmin) {
            // Admin: puede ver todo
            $tasks = Task::with(['user', 'teacher', 'course'])
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->latest('updated_at')
                ->limit(50)
                ->get();

            $projects = Project::with(['user', 'teacher', 'course'])
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->latest('updated_at')
                ->limit(50)
                ->get();

            $courses = Course::where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->latest('updated_at')
                ->limit(50)
                ->get();

            $users = User::where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('last_name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%");
                })
                ->latest('updated_at')
                ->limit(50)
                ->get();
        } elseif ($isTeacher) {
            // Profesor: ver tareas/proyectos de sus estudiantes o asignadas por él
            $tasks = Task::with(['user', 'course'])
                ->where(function ($q) use ($user) {
                    $q->where('teacher_id', $user->id);
                })
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->latest('updated_at')
                ->limit(50)
                ->get();

            $projects = Project::with(['user', 'course'])
                ->where(function ($q) use ($user) {
                    $q->where('teacher_id', $user->id);
                })
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->latest('updated_at')
                ->limit(50)
                ->get();

            $courses = Course::where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->latest('updated_at')
                ->limit(50)
                ->get();
        } else {
            // Estudiante: ver propios
            $tasks = Task::with(['course'])
                ->where('user_id', $user->id)
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->latest('updated_at')
                ->limit(50)
                ->get();

            $projects = Project::with(['course'])
                ->where('user_id', $user->id)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->latest('updated_at')
                ->limit(50)
                ->get();

            $courses = Course::where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->latest('updated_at')
                ->limit(50)
                ->get();
        }

        return view('search.results', [
            'query' => $query,
            'tasks' => $tasks,
            'projects' => $projects,
            'courses' => $courses,
            'users' => $users,
        ]);
    }
}


