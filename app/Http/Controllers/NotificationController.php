<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        try {
            $notification = Auth::user()->notifications()->findOrFail($id);
            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notificación marcada como leída'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al marcar la notificación'
            ], 500);
        }
    }

    public function markAllAsRead()
    {
        try {
            Auth::user()->unreadNotifications->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Todas las notificaciones marcadas como leídas'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al marcar las notificaciones'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $notification = Auth::user()->notifications()->findOrFail($id);
            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notificación eliminada'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la notificación'
            ], 500);
        }
    }

    public function getUnread()
    {
        $unreadCount = Auth::user()->unreadNotifications()->count();
        
        return response()->json([
            'count' => $unreadCount,
            'notifications' => Auth::user()->unreadNotifications()->take(5)->get()
        ]);
    }

    /**
     * Obtener eventos próximos y crear notificaciones si es necesario
     */
    public function getUpcomingEvents()
    {
        $user = Auth::user();
        $upcomingEvents = $this->buildUpcomingEvents($user);
        return response()->json([
            'success' => true,
            'events' => $upcomingEvents,
            'count' => $upcomingEvents->count()
        ]);
    }

    private function buildUpcomingEvents(User $user)
    {
        $now = \Carbon\Carbon::now();
        $upcomingEvents = collect();

        $tasks = \App\Models\Task::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->whereDate('due_date', '>=', $now->toDateString())
            ->whereDate('due_date', '<=', $now->copy()->addDays(3)->toDateString())
            ->with('course')
            ->get();

        foreach ($tasks as $task) {
            if ($task->due_date->isPast()) {
                $daysUntilDue = 0;
            } else {
                $daysUntilDue = $now->copy()->startOfDay()->diffInDays($task->due_date->copy()->startOfDay(), false);
                $daysUntilDue = max(0, min(999, $daysUntilDue));
            }
            $this->createUpcomingEventNotification($user, $task, 'task', $daysUntilDue);
            $upcomingEvents->push([
                'id' => 'task-' . $task->id,
                'title' => $task->title,
                'type' => 'task',
                'date' => $task->due_date->toDateTimeString(),
                'days_until_due' => $daysUntilDue,
                'priority' => $task->priority,
                'course' => $task->course ? $task->course->name : null,
                'url' => url('/tasks/' . $task->id)
            ]);
        }

        $projects = \App\Models\Project::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->whereDate('end_date', '>=', $now->toDateString())
            ->whereDate('end_date', '<=', $now->copy()->addDays(7)->toDateString())
            ->with('course')
            ->get();

        foreach ($projects as $project) {
            if ($project->end_date->isPast()) {
                $daysUntilDue = 0;
            } else {
                $daysUntilDue = $now->copy()->startOfDay()->diffInDays($project->end_date->copy()->startOfDay(), false);
                $daysUntilDue = max(0, min(999, $daysUntilDue));
            }
            $this->createUpcomingEventNotification($user, $project, 'project', $daysUntilDue);
            $upcomingEvents->push([
                'id' => 'project-' . $project->id,
                'title' => $project->name,
                'type' => 'project',
                'date' => $project->end_date->toDateTimeString(),
                'days_until_due' => $daysUntilDue,
                'course' => $project->course ? $project->course->name : null,
                'url' => url('/projects/' . $project->id)
            ]);
        }

        $reminders = \App\Models\Reminder::where('user_id', $user->id)
            ->whereDate('due_date', '>=', $now->toDateString())
            ->whereDate('due_date', '<=', $now->copy()->addDays(2)->toDateString())
            ->with('course')
            ->get();

        foreach ($reminders as $reminder) {
            if ($reminder->due_date->isPast()) {
                $daysUntilDue = 0;
            } else {
                $daysUntilDue = $now->copy()->startOfDay()->diffInDays($reminder->due_date->copy()->startOfDay(), false);
                $daysUntilDue = max(0, min(999, $daysUntilDue));
            }
            $this->createUpcomingEventNotification($user, $reminder, 'reminder', $daysUntilDue);
            $upcomingEvents->push([
                'id' => 'reminder-' . $reminder->id,
                'title' => $reminder->title,
                'type' => 'reminder',
                'date' => $reminder->due_date->toDateTimeString(),
                'days_until_due' => $daysUntilDue,
                'course' => $reminder->course ? $reminder->course->name : null,
                'url' => url('/reminders/' . $reminder->id)
            ]);
        }

        return $upcomingEvents->sortBy('date')->values();
    }

    public function showUpcomingEvents()
    {
        $user = Auth::user();
        $events = $this->buildUpcomingEvents($user);
        return view('notifications.upcoming-events', compact('events'));
    }

    /**
     * Crear notificación de evento próximo si no existe ya
     */
    private function createUpcomingEventNotification($user, $event, $eventType, $daysUntilDue)
    {
        // Verificar si ya existe una notificación similar reciente (últimas 24 horas)
        $existingNotification = $user->notifications()
            ->where('type', \App\Notifications\UpcomingEventNotification::class)
            ->where('data->event_id', $event->id)
            ->where('data->event_type', $eventType)
            ->where('created_at', '>=', \Carbon\Carbon::now()->subDay())
            ->first();
        
        // Solo crear notificación si no existe una reciente
        if (!$existingNotification) {
            // Solo crear notificación para eventos que vencen hoy, mañana o en 3 días (para tareas/reminders) o 7 días (para proyectos)
            $shouldNotify = false;
            if ($eventType === 'project') {
                $shouldNotify = $daysUntilDue >= 0 && $daysUntilDue <= 7;
            } else {
                $shouldNotify = $daysUntilDue >= 0 && $daysUntilDue <= 3;
            }
            
            if ($shouldNotify) {
                $user->notify(new \App\Notifications\UpcomingEventNotification(
                    $event,
                    $eventType,
                    $daysUntilDue
                ));
            }
        }
    }

    public function settings()
    {
        return view('notifications.settings');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'dark_mode' => 'boolean',
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'task_reminders' => 'boolean',
            'project_deadlines' => 'boolean',
            'exam_reminders' => 'boolean',
            'reminder_frequency' => 'integer|in:15,30,60,120,1440'
        ]);

        $user = Auth::user();
        $user->update([
            'dark_mode' => $request->dark_mode ?? false,
            'email_notifications' => $request->email_notifications ?? false,
            'push_notifications' => $request->push_notifications ?? false,
            'task_reminders' => $request->task_reminders ?? false,
            'project_deadlines' => $request->project_deadlines ?? false,
            'exam_reminders' => $request->exam_reminders ?? false,
            'reminder_frequency' => $request->reminder_frequency ?? 60,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Configuración de notificaciones actualizada'
        ]);
    }
} 
