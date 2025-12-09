<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class UpcomingEventNotification extends Notification
{
    use Queueable;

    public $event;
    public $eventType;
    public $daysUntilDue;
    public $eventDate;

    /**
     * Create a new notification instance.
     */
    public function __construct($event, $eventType, $daysUntilDue = null, $eventDate = null)
    {
        $this->event = $event;
        $this->eventType = $eventType; // 'task', 'project', 'reminder'
        $this->daysUntilDue = $daysUntilDue;
        $this->eventDate = $eventDate ?? ($eventType === 'project' ? $event->end_date : $event->due_date);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];
        
        // Agregar email si el usuario tiene notificaciones por email habilitadas
        if ($notifiable->email_notifications ?? false) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $title = $this->eventType === 'project' 
            ? ($this->event->name ?? $this->event->title ?? 'Evento')
            : ($this->event->title ?? 'Evento');
        
        $eventTypeLabel = $this->getEventTypeLabel();
        
        $subject = $this->daysUntilDue === 0 
            ? "¡{$eventTypeLabel} HOY! - {$title}"
            : "{$eventTypeLabel} próxim{$this->getEventTypeGender()} - {$title}";

        $message = $this->getMessage();

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line($message)
            ->line('Detalles del evento:')
            ->line('• Título: ' . $title)
            ->line('• Descripción: ' . ($this->getEventDescription() ?: 'Sin descripción'))
            ->line('• Fecha: ' . $this->eventDate->format('d/m/Y H:i'))
            ->line('• Curso: ' . ($this->getCourseName() ?: 'Sin curso'));

        // Agregar información específica según el tipo
        if ($this->eventType === 'task') {
            $mail->line('• Prioridad: ' . ($this->event->priority ?? 'media'));
        } elseif ($this->eventType === 'project') {
            $mail->line('• Estado: ' . ($this->event->status ?? 'activo'));
        }

        $actionUrl = $this->getActionUrl();
        if ($actionUrl) {
            $mail->action('Ver ' . $eventTypeLabel, $actionUrl);
        }

        return $mail->line('¡Mantente al día con tus eventos!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $title = $this->eventType === 'project' 
            ? ($this->event->name ?? $this->event->title ?? 'Evento')
            : ($this->event->title ?? 'Evento');
        
        $eventTypeLabel = $this->getEventTypeLabel();
        
        return [
            'event_id' => $this->event->id,
            'event_type' => $this->eventType,
            'event_title' => $title,
            'event_date' => $this->eventDate ? $this->eventDate->toDateTimeString() : null,
            'days_until_due' => $this->daysUntilDue,
            'type' => 'upcoming_event',
            'title' => $this->getNotificationTitle(),
            'message' => $this->getMessage(),
            'icon' => $this->getIcon(),
            'color' => $this->getColor(),
            'url' => $this->getActionUrl()
        ];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }

    /**
     * Get event type label in Spanish
     */
    private function getEventTypeLabel(): string
    {
        switch ($this->eventType) {
            case 'task':
                return 'Tarea';
            case 'project':
                return 'Proyecto';
            case 'reminder':
                return 'Recordatorio';
            default:
                return 'Evento';
        }
    }

    /**
     * Get event type gender for Spanish grammar
     */
    private function getEventTypeGender(): string
    {
        return $this->eventType === 'project' ? 'o' : 'a';
    }

    /**
     * Get notification title
     */
    private function getNotificationTitle(): string
    {
        $title = $this->eventType === 'project' 
            ? ($this->event->name ?? $this->event->title ?? 'Evento')
            : ($this->event->title ?? 'Evento');
        
        $eventTypeLabel = $this->getEventTypeLabel();
        
        if ($this->daysUntilDue === 0) {
            return "¡{$eventTypeLabel} vence HOY!";
        } elseif ($this->daysUntilDue === 1) {
            return "{$eventTypeLabel} vence mañana";
        } else {
            return "{$eventTypeLabel} vence en {$this->daysUntilDue} días";
        }
    }

    /**
     * Get notification message
     */
    private function getMessage(): string
    {
        $title = $this->eventType === 'project' 
            ? ($this->event->name ?? $this->event->title ?? 'Evento')
            : ($this->event->title ?? 'Evento');
        
        $eventTypeLabel = $this->getEventTypeLabel();
        
        if ($this->daysUntilDue === 0) {
            return "Tu {$eventTypeLabel} \"{$title}\" vence HOY. ¡No te olvides!";
        } elseif ($this->daysUntilDue === 1) {
            return "Tu {$eventTypeLabel} \"{$title}\" vence mañana.";
        } else {
            return "Tu {$eventTypeLabel} \"{$title}\" vence en {$this->daysUntilDue} días.";
        }
    }

    /**
     * Get event description
     */
    private function getEventDescription(): ?string
    {
        return $this->event->description ?? null;
    }

    /**
     * Get course name
     */
    private function getCourseName(): ?string
    {
        if (isset($this->event->course)) {
            return $this->event->course->name ?? null;
        }
        
        if (isset($this->event->course_id) && method_exists($this->event, 'course')) {
            $course = $this->event->course;
            return $course ? $course->name : null;
        }
        
        return null;
    }

    /**
     * Get action URL
     */
    private function getActionUrl(): ?string
    {
        switch ($this->eventType) {
            case 'task':
                return url('/tasks/' . $this->event->id);
            case 'project':
                return url('/projects/' . $this->event->id);
            case 'reminder':
                return url('/reminders/' . $this->event->id);
            default:
                return null;
        }
    }

    /**
     * Get icon for notification
     */
    private function getIcon(): string
    {
        switch ($this->eventType) {
            case 'task':
                return 'fas fa-tasks';
            case 'project':
                return 'fas fa-project-diagram';
            case 'reminder':
                return 'fas fa-bell';
            default:
                return 'fas fa-calendar';
        }
    }

    /**
     * Get color for notification
     */
    private function getColor(): string
    {
        switch ($this->eventType) {
            case 'task':
                return '#3B82F6'; // blue
            case 'project':
                return '#10B981'; // green
            case 'reminder':
                return '#F59E0B'; // amber
            default:
                return '#6B7280'; // gray
        }
    }
}


