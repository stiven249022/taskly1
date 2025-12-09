<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GradeNotification extends Notification
{
    use Queueable;

    protected $item;
    protected $itemType; // 'task' o 'project'
    protected $grade;
    protected $feedback;
    protected $teacherName;

    /**
     * Create a new notification instance.
     */
    public function __construct($item, $itemType, $grade, $feedback = null, $teacherName = null)
    {
        $this->item = $item;
        $this->itemType = $itemType;
        $this->grade = $grade;
        $this->feedback = $feedback;
        $this->teacherName = $teacherName;
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
        $itemTitle = $this->itemType === 'project' 
            ? ($this->item->name ?? 'Proyecto')
            : ($this->item->title ?? 'Tarea');
        
        $itemTypeLabel = $this->itemType === 'project' ? 'proyecto' : 'tarea';
        
        $message = (new MailMessage)
            ->subject("Tu {$itemTypeLabel} ha sido calificada")
            ->greeting("¡Hola {$notifiable->name}!")
            ->line("Tu {$itemTypeLabel} \"{$itemTitle}\" ha sido calificada con una nota de {$this->grade}.")
            ->action('Ver detalles', $this->itemType === 'project' 
                ? url("/projects/{$this->item->id}")
                : url("/tasks/{$this->item->id}"));
        
        if ($this->feedback) {
            $message->line("Comentarios del profesor: {$this->feedback}");
        }
        
        if ($this->teacherName) {
            $message->line("Calificado por: {$this->teacherName}");
        }
        
        $message->line('¡Gracias por usar Taskly!');
        
        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $itemTitle = $this->itemType === 'project' 
            ? ($this->item->name ?? 'Proyecto')
            : ($this->item->title ?? 'Tarea');
        
        $itemTypeLabel = $this->itemType === 'project' ? 'proyecto' : 'tarea';
        
        return [
            'title' => "Tu {$itemTypeLabel} ha sido calificada",
            'message' => "Tu {$itemTypeLabel} \"{$itemTitle}\" ha sido calificada con una nota de {$this->grade}." . ($this->feedback ? " Comentarios: {$this->feedback}" : ''),
            'type' => 'grade',
            'item_type' => $this->itemType,
            'item_id' => $this->item->id,
            'grade' => $this->grade,
            'feedback' => $this->feedback,
            'teacher_name' => $this->teacherName,
            'url' => $this->itemType === 'project' 
                ? route('projects.show', $this->item->id)
                : route('tasks.show', $this->item->id),
            'icon' => 'fas fa-check-circle',
            'color' => '#10B981',
        ];
    }
}

