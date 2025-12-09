<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Reminder;
use App\Models\Course;
use App\Models\Tag;
use App\Notifications\NewEventNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $courses = Course::where('user_id', Auth::id())->get();
        $tags = Tag::where('user_id', Auth::id())->get();

        // Get upcoming events for the upcoming events section
        $upcomingEvents = collect();
        
        // Get upcoming tasks
        $upcomingTasks = Task::where('user_id', auth()->id())
            ->where('due_date', '>=', Carbon::now())
            ->orderBy('due_date')
            ->take(5)
            ->get()
            ->map(function ($task) {
                return [
                    'id' => 'task-' . $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'due_date' => $task->due_date,
                    'type' => 'task',
                    'priority' => $task->priority,
                    'course' => $task->course,
                    'icon' => 'fas fa-tasks',
                    'color' => 'blue'
                ];
            });

        // Get upcoming projects
        $upcomingProjects = Project::where('user_id', auth()->id())
            ->where('end_date', '>=', Carbon::now())
            ->orderBy('end_date')
            ->take(3)
            ->get()
            ->map(function ($project) {
                return [
                    'id' => 'project-' . $project->id,
                    'title' => $project->name,
                    'description' => $project->description,
                    'due_date' => $project->end_date,
                    'type' => 'project',
                    'priority' => $project->priority,
                    'course' => $project->course,
                    'icon' => 'fas fa-project-diagram',
                    'color' => 'green'
                ];
            });

        $upcomingEvents = $upcomingEvents->concat($upcomingTasks)->concat($upcomingProjects);

        // Get event summary statistics
        $currentMonth = Carbon::now()->startOfMonth();
        $nextMonth = Carbon::now()->addMonth()->startOfMonth();
        
        $monthlyStats = [
            'exams' => Task::where('user_id', auth()->id())
                ->where('type', 'exam')
                ->whereBetween('due_date', [$currentMonth, $nextMonth])
                ->count(),
            'tasks' => Task::where('user_id', auth()->id())
                ->where('type', 'task')
                ->whereBetween('due_date', [$currentMonth, $nextMonth])
                ->count(),
            'projects' => Project::where('user_id', auth()->id())
                ->whereBetween('end_date', [$currentMonth, $nextMonth])
                ->count(),
            'readings' => Task::where('user_id', auth()->id())
                ->where('type', 'reading')
                ->whereBetween('due_date', [$currentMonth, $nextMonth])
                ->count(),
            'completed' => Task::where('user_id', auth()->id())
                ->where('status', 'completed')
                ->whereBetween('due_date', [$currentMonth, $nextMonth])
                ->count() + Project::where('user_id', auth()->id())
                ->where('status', 'completed')
                ->whereBetween('end_date', [$currentMonth, $nextMonth])
                ->count()
        ];

        $totalEvents = $monthlyStats['exams'] + $monthlyStats['tasks'] + $monthlyStats['projects'] + $monthlyStats['readings'];
        
        if ($totalEvents > 0) {
            $monthlyStats['exams_percent'] = round(($monthlyStats['exams'] / $totalEvents) * 100, 1);
            $monthlyStats['tasks_percent'] = round(($monthlyStats['tasks'] / $totalEvents) * 100, 1);
            $monthlyStats['projects_percent'] = round(($monthlyStats['projects'] / $totalEvents) * 100, 1);
            $monthlyStats['readings_percent'] = round(($monthlyStats['readings'] / $totalEvents) * 100, 1);
            $monthlyStats['completed_percent'] = round(($monthlyStats['completed'] / $totalEvents) * 100, 1);
        } else {
            $monthlyStats['exams_percent'] = 0;
            $monthlyStats['tasks_percent'] = 0;
            $monthlyStats['projects_percent'] = 0;
            $monthlyStats['readings_percent'] = 0;
            $monthlyStats['completed_percent'] = 0;
        }

        return view('calendario.index', compact('courses', 'tags', 'upcomingEvents', 'monthlyStats'));
    }

    public function events()
    {
        $events = collect();

        // Obtener tareas del usuario actual
        $tasks = Task::where('user_id', auth()->id())
            ->get()
            ->map(function ($task) {
                return [
                    'id' => 'task-' . $task->id,
                    'title' => $task->title,
                    'start' => $task->due_date,
                    'end' => $task->due_date,
                    'color' => $this->getEventColor('task'),
                    'type' => 'task',
                    'description' => $task->description,
                    'status' => $task->status,
                    'priority' => $task->priority,
                    'course' => $task->course ? $task->course->name : null
                ];
            });

        // Obtener proyectos del usuario actual
        $projects = Project::where('user_id', auth()->id())
            ->get()
            ->map(function ($project) {
                return [
                    'id' => 'project-' . $project->id,
                    'title' => $project->name,
                    'start' => $project->end_date,
                    'end' => $project->end_date,
                    'color' => $this->getEventColor('project'),
                    'type' => 'project',
                    'description' => $project->description,
                    'status' => $project->status,
                    'progress' => $project->progress,
                    'course' => $project->course ? $project->course->name : null
                ];
            });

        // Obtener recordatorios del usuario actual
        $reminders = Reminder::where('user_id', auth()->id())
            ->get()
            ->map(function ($reminder) {
                return [
                    'id' => 'reminder-' . $reminder->id,
                    'title' => $reminder->title,
                    'start' => $reminder->due_date,
                    'end' => $reminder->due_date,
                    'color' => $this->getEventColor('reminder'),
                    'type' => 'reminder',
                    'description' => $reminder->description,
                    'status' => $reminder->status,
                    'course' => $reminder->course ? $reminder->course->name : null
                ];
            });

        $events = $events->concat($tasks)->concat($projects)->concat($reminders);

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'required|date',
            'type' => 'required|in:task,project,reminder',
            'course_id' => 'nullable|exists:courses,id',
            'priority' => 'nullable|in:low,medium,high'
        ]);

        switch ($validated['type']) {
            case 'task':
                $event = Task::create([
                    'user_id' => auth()->id(),
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'due_date' => $validated['start'],
                    'status' => 'pending',
                    'priority' => $validated['priority'] ?? 'medium',
                    'course_id' => $validated['course_id'] ?? null
                ]);
                
                // Enviar notificación
                auth()->user()->notify(new NewEventNotification($event, 'task'));
                break;
            case 'project':
                $event = Project::create([
                    'user_id' => auth()->id(),
                    'name' => $validated['title'],
                    'description' => $validated['description'],
                    'end_date' => $validated['start'],
                    'status' => 'active',
                    'progress' => 0,
                    'course_id' => $validated['course_id'] ?? null
                ]);
                
                // Enviar notificación
                auth()->user()->notify(new NewEventNotification($event, 'project'));
                break;
            case 'reminder':
                $event = Reminder::create([
                    'user_id' => auth()->id(),
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'due_date' => $validated['start'],
                    'status' => 'pending',
                    'course_id' => $validated['course_id'] ?? null
                ]);
                
                // Enviar notificación
                auth()->user()->notify(new NewEventNotification($event, 'reminder'));
                break;
        }

        return response()->json([
            'id' => $validated['type'] . '-' . $event->id,
            'title' => $validated['type'] === 'project' ? $event->name : $event->title,
            'start' => $validated['type'] === 'project' ? $event->end_date : $event->due_date,
            'end' => $validated['type'] === 'project' ? $event->end_date : $event->due_date,
            'color' => $this->getEventColor($validated['type']),
            'type' => $validated['type']
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'required|date'
        ]);

        list($type, $id) = explode('-', $id);

        switch ($type) {
            case 'task':
                $event = Task::findOrFail($id);
                break;
            case 'project':
                $event = Project::findOrFail($id);
                break;
            case 'reminder':
                $event = Reminder::findOrFail($id);
                break;
        }

        $this->authorize('update', $event);

        // Actualizar campos según el tipo de evento
        if ($type === 'project') {
            $event->update([
                'name' => $validated['title'],
                'description' => $validated['description'],
                'end_date' => $validated['start']
            ]);
        } else {
            $event->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'due_date' => $validated['start']
            ]);
        }

        return response()->json([
            'id' => $type . '-' . $event->id,
            'title' => $type === 'project' ? $event->name : $event->title,
            'start' => $type === 'project' ? $event->end_date : $event->due_date,
            'end' => $type === 'project' ? $event->end_date : $event->due_date,
            'color' => $this->getEventColor($type),
            'type' => $type
        ]);
    }

    public function destroy($id)
    {
        list($type, $id) = explode('-', $id);

        switch ($type) {
            case 'task':
                $event = Task::findOrFail($id);
                break;
            case 'project':
                $event = Project::findOrFail($id);
                break;
            case 'reminder':
                $event = Reminder::findOrFail($id);
                break;
        }

        $this->authorize('delete', $event);
        $event->delete();

        return response()->json(['success' => true]);
    }

    private function getEventColor($type)
    {
        switch ($type) {
            case 'task':
                return '#3B82F6';
            case 'project':
                return '#10B981';
            case 'reminder':
                return '#F59E0B';
            default:
                return '#6B7280';
        }
    }
} 