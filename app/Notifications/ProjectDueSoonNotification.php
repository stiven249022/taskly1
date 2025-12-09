<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectDueSoonNotification extends Notification
{
    use Queueable;

    public $project;
    public $daysUntilDue;

    /**
     * Create a new notification instance.
     */
    public function __construct($project, $daysUntilDue = null)
    {
        $this->project = $project;
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
        $projectName = $this->project->name ?? $this->project->title ?? 'Proyecto';
        $subject = $this->daysUntilDue === 0 
            ? '¡Proyecto vence HOY! - ' . $projectName
            : '¡Proyecto vence pronto! - ' . $projectName;

        $message = $this->daysUntilDue === 0
            ? 'Tu proyecto "' . $projectName . '" vence HOY. ¡No te olvides de completarlo!'
            : 'Tu proyecto "' . $projectName . '" vence en ' . $this->daysUntilDue . ' día(s).';

        return (new MailMessage)
            ->subject($subject)
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line($message)
            ->line('Detalles del proyecto:')
            ->line('• Título: ' . $projectName)
            ->line('• Descripción: ' . ($this->project->description ?: 'Sin descripción'))
            ->line('• Fecha de vencimiento: ' . $this->project->end_date->format('d/m/Y H:i'))
            ->line('• Curso: ' . ($this->project->course ? $this->project->course->name : 'Sin curso'))
            ->line('• Estado: ' . $this->project->status)
            ->action('Ver Proyecto', url('/projects/' . $this->project->id))
            ->line('¡Mantente al día con tus proyectos!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'project_id' => $this->project->id,
            'project_title' => $projectName,
            'end_date' => $this->project->end_date,
            'days_until_due' => $this->daysUntilDue,
            'type' => 'project_due_soon',
            'message' => $this->daysUntilDue === 0 
                ? 'Proyecto vence HOY: ' . $projectName
                : 'Proyecto vence en ' . $this->daysUntilDue . ' día(s): ' . $projectName
        ];
    }
}
