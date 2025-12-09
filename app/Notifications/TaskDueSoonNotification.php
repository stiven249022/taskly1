<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskDueSoonNotification extends Notification
{
    use Queueable;

    public $task;
    public $daysUntilDue;

    /**
     * Create a new notification instance.
     */
    public function __construct($task, $daysUntilDue = null)
    {
        $this->task = $task;
        $this->daysUntilDue = $daysUntilDue;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->daysUntilDue === 0 
            ? '¡Tarea vence HOY! - ' . $this->task->title
            : '¡Tarea vence pronto! - ' . $this->task->title;

        $message = $this->daysUntilDue === 0
            ? 'Tu tarea "' . $this->task->title . '" vence HOY. ¡No te olvides de completarla!'
            : 'Tu tarea "' . $this->task->title . '" vence en ' . $this->daysUntilDue . ' día(s).';

        return (new MailMessage)
            ->subject($subject)
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line($message)
            ->line('Detalles de la tarea:')
            ->line('• Título: ' . $this->task->title)
            ->line('• Descripción: ' . ($this->task->description ?: 'Sin descripción'))
            ->line('• Fecha de vencimiento: ' . $this->task->due_date->format('d/m/Y H:i'))
            ->line('• Curso: ' . ($this->task->course ? $this->task->course->name : 'Sin curso'))
            ->line('• Prioridad: ' . $this->task->priority)
            ->action('Ver Tarea', url('/tasks/' . $this->task->id))
            ->line('¡Mantente al día con tus tareas!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'due_date' => $this->task->due_date,
            'days_until_due' => $this->daysUntilDue,
            'type' => 'task_due_soon',
            'message' => $this->daysUntilDue === 0 
                ? 'Tarea vence HOY: ' . $this->task->title
                : 'Tarea vence en ' . $this->daysUntilDue . ' día(s): ' . $this->task->title
        ];
    }
}
