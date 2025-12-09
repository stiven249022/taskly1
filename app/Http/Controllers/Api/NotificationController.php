<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use App\Models\Reminder;
use App\Notifications\TaskDueSoonNotification;
use App\Notifications\ProjectDueSoonNotification;
use App\Notifications\ExamReminderNotification;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Enviar notificación de tarea próxima a vencer
     */
    public function sendTaskNotification(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'days_until_due' => 'required|integer|min:0'
        ]);

        $task = Task::findOrFail($request->task_id);
        $user = auth()->user();

        // Verificar que la tarea pertenece al usuario
        if ($task->user_id !== $user->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Enviar notificación
        $user->notify(new TaskDueSoonNotification($task, $request->days_until_due));

        return response()->json([
            'message' => 'Notificación enviada exitosamente',
            'task' => $task->title,
            'days_until_due' => $request->days_until_due
        ]);
    }

    /**
     * Enviar notificación de proyecto próximo a vencer
     */
    public function sendProjectNotification(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'days_until_due' => 'required|integer|min:0'
        ]);

        $project = Project::findOrFail($request->project_id);
        $user = auth()->user();

        // Verificar que el proyecto pertenece al usuario
        if ($project->user_id !== $user->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Enviar notificación
        $user->notify(new ProjectDueSoonNotification($project, $request->days_until_due));

        return response()->json([
            'message' => 'Notificación enviada exitosamente',
            'project' => $project->title,
            'days_until_due' => $request->days_until_due
        ]);
    }

    /**
     * Enviar notificación de recordatorio de examen
     */
    public function sendExamNotification(Request $request)
    {
        $request->validate([
            'reminder_id' => 'required|exists:reminders,id',
            'days_until_due' => 'required|integer|min:0'
        ]);

        $reminder = Reminder::findOrFail($request->reminder_id);
        $user = auth()->user();

        // Verificar que el recordatorio pertenece al usuario
        if ($reminder->user_id !== $user->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Enviar notificación
        $user->notify(new ExamReminderNotification($reminder, $request->days_until_due));

        return response()->json([
            'message' => 'Notificación enviada exitosamente',
            'reminder' => $reminder->title,
            'days_until_due' => $request->days_until_due
        ]);
    }

    /**
     * Obtener configuración de notificaciones del usuario
     */
    public function getNotificationSettings()
    {
        $user = auth()->user();

        return response()->json([
            'email_notifications' => $user->email_notifications,
            'push_notifications' => $user->push_notifications,
            'task_reminders' => $user->task_reminders,
            'project_deadlines' => $user->project_deadlines,
            'exam_reminders' => $user->exam_reminders,
            'reminder_frequency' => $user->reminder_frequency
        ]);
    }

    /**
     * Actualizar configuración de notificaciones del usuario
     */
    public function updateNotificationSettings(Request $request)
    {
        $request->validate([
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'task_reminders' => 'boolean',
            'project_deadlines' => 'boolean',
            'exam_reminders' => 'boolean',
            'reminder_frequency' => 'string|in:daily,weekly,monthly'
        ]);

        $user = auth()->user();
        $user->update($request->only([
            'email_notifications',
            'push_notifications',
            'task_reminders',
            'project_deadlines',
            'exam_reminders',
            'reminder_frequency'
        ]));

        return response()->json([
            'message' => 'Configuración de notificaciones actualizada',
            'settings' => $user->fresh()->only([
                'email_notifications',
                'push_notifications',
                'task_reminders',
                'project_deadlines',
                'exam_reminders',
                'reminder_frequency'
            ])
        ]);
    }

    /**
     * Enviar notificaciones automáticas (para testing)
     */
    public function sendAutomaticNotifications()
    {
        $user = auth()->user();

        // Verificar tareas próximas a vencer
        $this->checkTasksDueSoon($user);
        
        // Verificar proyectos próximos a vencer
        $this->checkProjectsDueSoon($user);
        
        // Verificar recordatorios de exámenes
        $this->checkExamReminders($user);

        return response()->json([
            'message' => 'Notificaciones automáticas enviadas',
            'user' => $user->name
        ]);
    }

    private function checkTasksDueSoon($user)
    {
        $now = Carbon::now();
        
        // Tareas que vencen hoy
        $tasksDueToday = Task::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->whereDate('due_date', $now->toDateString())
            ->get();
            
        foreach ($tasksDueToday as $task) {
            $user->notify(new TaskDueSoonNotification($task, 0));
        }
        
        // Tareas que vencen mañana
        $tasksDueTomorrow = Task::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->whereDate('due_date', $now->addDay()->toDateString())
            ->get();
            
        foreach ($tasksDueTomorrow as $task) {
            $user->notify(new TaskDueSoonNotification($task, 1));
        }
    }

    private function checkProjectsDueSoon($user)
    {
        $now = Carbon::now();
        
        // Proyectos que vencen hoy
        $projectsDueToday = Project::where('user_id', $user->id)
            ->where('email_notifications', true)
            ->where('status', '!=', 'completed')
            ->whereDate('end_date', $now->toDateString())
            ->get();
            
        foreach ($projectsDueToday as $project) {
            $user->notify(new ProjectDueSoonNotification($project, 0));
        }
        
        // Proyectos que vencen mañana
        $projectsDueTomorrow = Project::where('user_id', $user->id)
            ->where('email_notifications', true)
            ->where('status', '!=', 'completed')
            ->whereDate('end_date', $now->addDay()->toDateString())
            ->get();
            
        foreach ($projectsDueTomorrow as $project) {
            $user->notify(new ProjectDueSoonNotification($project, 1));
        }
    }

    private function checkExamReminders($user)
    {
        $now = Carbon::now();
        
        // Recordatorios que vencen hoy
        $remindersToday = Reminder::where('user_id', $user->id)
            ->whereDate('due_date', $now->toDateString())
            ->get();
            
        foreach ($remindersToday as $reminder) {
            $user->notify(new ExamReminderNotification($reminder, 0));
        }
        
        // Recordatorios que vencen mañana
        $remindersTomorrow = Reminder::where('user_id', $user->id)
            ->whereDate('due_date', $now->addDay()->toDateString())
            ->get();
            
        foreach ($remindersTomorrow as $reminder) {
            $user->notify(new ExamReminderNotification($reminder, 1));
        }
    }
}
