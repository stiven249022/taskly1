<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExamReminderNotification extends Notification
{
    use Queueable;

    public $reminder;
    public $daysUntilDue;

    /**
     * Create a new notification instance.
     */
    public function __construct($reminder, $daysUntilDue = null)
    {
        $this->reminder = $reminder;
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
            ? '¡Examen HOY! - ' . $this->reminder->title
            : '¡Examen pronto! - ' . $this->reminder->title;

        $message = $this->daysUntilDue === 0
            ? 'Tu examen "' . $this->reminder->title . '" es HOY. ¡Buena suerte!'
            : 'Tu examen "' . $this->reminder->title . '" es en ' . $this->daysUntilDue . ' día(s).';

        return (new MailMessage)
            ->subject($subject)
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line($message)
            ->line('Detalles del examen:')
            ->line('• Título: ' . $this->reminder->title)
            ->line('• Descripción: ' . ($this->reminder->description ?: 'Sin descripción'))
            ->line('• Fecha: ' . $this->reminder->due_date->format('d/m/Y H:i'))
            ->line('• Curso: ' . ($this->reminder->course ? $this->reminder->course->name : 'Sin curso'))
            ->line('• Tipo: ' . $this->reminder->type)
            ->action('Ver Recordatorio', url('/reminders/' . $this->reminder->id))
            ->line('¡Prepárate bien para tu examen!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'reminder_id' => $this->reminder->id,
            'reminder_title' => $this->reminder->title,
            'due_date' => $this->reminder->due_date,
            'days_until_due' => $this->daysUntilDue,
            'type' => 'exam_reminder',
            'message' => $this->daysUntilDue === 0 
                ? 'Examen HOY: ' . $this->reminder->title
                : 'Examen en ' . $this->daysUntilDue . ' día(s): ' . $this->reminder->title
        ];
    }
}
