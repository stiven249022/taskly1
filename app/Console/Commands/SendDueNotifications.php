<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use App\Models\Reminder;
use App\Notifications\TaskDueSoonNotification;
use App\Notifications\ProjectDueSoonNotification;
use App\Notifications\ExamReminderNotification;
use App\Notifications\UpcomingEventNotification;
use Carbon\Carbon;

class SendDueNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-due';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar notificaciones por email para tareas y proyectos próximos a vencer';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando envío de notificaciones...');
        
        // Obtener usuarios con notificaciones habilitadas
        $users = User::where('email_notifications', true)->get();
        
        $this->info('Usuarios encontrados: ' . $users->count());
        
        foreach ($users as $user) {
            $this->info("Procesando usuario: {$user->name}");
            
            // Verificar tareas próximas a vencer
            $this->checkTasksDueSoon($user);
            
            // Verificar proyectos próximos a vencer
            $this->checkProjectsDueSoon($user);
            
            // Verificar recordatorios de exámenes
            $this->checkExamReminders($user);
            
            // Crear notificaciones de eventos próximos en la base de datos
            $this->createUpcomingEventNotifications($user);
        }
        
        $this->info('Envío de notificaciones completado.');
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
            $this->info("  - Notificación enviada: Tarea '{$task->title}' vence HOY");
        }
        
        // Tareas que vencen mañana
        $tasksDueTomorrow = Task::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->whereDate('due_date', $now->addDay()->toDateString())
            ->get();
            
        foreach ($tasksDueTomorrow as $task) {
            $user->notify(new TaskDueSoonNotification($task, 1));
            $this->info("  - Notificación enviada: Tarea '{$task->title}' vence mañana");
        }
        
        // Tareas que vencen en 3 días
        $tasksDueIn3Days = Task::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->whereDate('due_date', $now->addDays(3)->toDateString())
            ->get();
            
        foreach ($tasksDueIn3Days as $task) {
            $user->notify(new TaskDueSoonNotification($task, 3));
            $this->info("  - Notificación enviada: Tarea '{$task->title}' vence en 3 días");
        }
    }
    
    private function checkProjectsDueSoon($user)
    {
        $now = Carbon::now();
        
        // Proyectos que vencen hoy
        $projectsDueToday = Project::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->whereDate('end_date', $now->toDateString())
            ->get();
            
        foreach ($projectsDueToday as $project) {
            $user->notify(new ProjectDueSoonNotification($project, 0));
            $this->info("  - Notificación enviada: Proyecto '{$project->name}' vence HOY");
        }
        
        // Proyectos que vencen mañana
        $projectsDueTomorrow = Project::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->whereDate('end_date', $now->addDay()->toDateString())
            ->get();
            
        foreach ($projectsDueTomorrow as $project) {
            $user->notify(new ProjectDueSoonNotification($project, 1));
            $this->info("  - Notificación enviada: Proyecto '{$project->name}' vence mañana");
        }
        
        // Proyectos que vencen en 7 días
        $projectsDueIn7Days = Project::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->whereDate('end_date', $now->addDays(7)->toDateString())
            ->get();
            
        foreach ($projectsDueIn7Days as $project) {
            $user->notify(new ProjectDueSoonNotification($project, 7));
            $this->info("  - Notificación enviada: Proyecto '{$project->name}' vence en 7 días");
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
            $this->info("  - Notificación enviada: Recordatorio '{$reminder->title}' es HOY");
        }
        
        // Recordatorios que vencen mañana
        $remindersTomorrow = Reminder::where('user_id', $user->id)
            ->whereDate('due_date', $now->addDay()->toDateString())
            ->get();
            
        foreach ($remindersTomorrow as $reminder) {
            $user->notify(new ExamReminderNotification($reminder, 1));
            $this->info("  - Notificación enviada: Recordatorio '{$reminder->title}' es mañana");
        }
    }

    /**
     * Crear notificaciones de eventos próximos en la base de datos
     */
    private function createUpcomingEventNotifications($user)
    {
        $now = Carbon::now();
        
        // Tareas próximas (hoy, mañana, en 3 días)
        $tasks = Task::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->whereDate('due_date', '>=', $now->toDateString())
            ->whereDate('due_date', '<=', $now->copy()->addDays(3)->toDateString())
            ->get();
        
        foreach ($tasks as $task) {
            // Calcular días restantes correctamente
            if ($task->due_date->isPast()) {
                $daysUntilDue = 0;
            } else {
                $daysUntilDue = $now->copy()->startOfDay()->diffInDays($task->due_date->copy()->startOfDay(), false);
                $daysUntilDue = max(0, min(999, $daysUntilDue));
            }
            
            // Verificar si ya existe una notificación similar reciente
            $existingNotification = $user->notifications()
                ->where('type', UpcomingEventNotification::class)
                ->where('data->event_id', $task->id)
                ->where('data->event_type', 'task')
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->first();
            
            if (!$existingNotification && $daysUntilDue >= 0 && $daysUntilDue <= 3) {
                $user->notify(new UpcomingEventNotification($task, 'task', $daysUntilDue));
                $this->info("  - Notificación de evento próximo creada: Tarea '{$task->title}'");
            }
        }
        
        // Proyectos próximos (hoy, mañana, en 7 días)
        $projects = Project::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->whereDate('end_date', '>=', $now->toDateString())
            ->whereDate('end_date', '<=', $now->copy()->addDays(7)->toDateString())
            ->get();
        
        foreach ($projects as $project) {
            // Calcular días restantes correctamente
            if ($project->end_date->isPast()) {
                $daysUntilDue = 0;
            } else {
                $daysUntilDue = $now->copy()->startOfDay()->diffInDays($project->end_date->copy()->startOfDay(), false);
                $daysUntilDue = max(0, min(999, $daysUntilDue));
            }
            
            // Verificar si ya existe una notificación similar reciente
            $existingNotification = $user->notifications()
                ->where('type', UpcomingEventNotification::class)
                ->where('data->event_id', $project->id)
                ->where('data->event_type', 'project')
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->first();
            
            if (!$existingNotification && $daysUntilDue >= 0 && $daysUntilDue <= 7) {
                $user->notify(new UpcomingEventNotification($project, 'project', $daysUntilDue));
                $this->info("  - Notificación de evento próximo creada: Proyecto '{$project->name}'");
            }
        }
        
        // Recordatorios próximos (hoy, mañana, en 2 días)
        $reminders = Reminder::where('user_id', $user->id)
            ->whereDate('due_date', '>=', $now->toDateString())
            ->whereDate('due_date', '<=', $now->copy()->addDays(2)->toDateString())
            ->get();
        
        foreach ($reminders as $reminder) {
            // Calcular días restantes correctamente
            if ($reminder->due_date->isPast()) {
                $daysUntilDue = 0;
            } else {
                $daysUntilDue = $now->copy()->startOfDay()->diffInDays($reminder->due_date->copy()->startOfDay(), false);
                $daysUntilDue = max(0, min(999, $daysUntilDue));
            }
            
            // Verificar si ya existe una notificación similar reciente
            $existingNotification = $user->notifications()
                ->where('type', UpcomingEventNotification::class)
                ->where('data->event_id', $reminder->id)
                ->where('data->event_type', 'reminder')
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->first();
            
            if (!$existingNotification && $daysUntilDue >= 0 && $daysUntilDue <= 2) {
                $user->notify(new UpcomingEventNotification($reminder, 'reminder', $daysUntilDue));
                $this->info("  - Notificación de evento próximo creada: Recordatorio '{$reminder->title}'");
            }
        }
    }
}
