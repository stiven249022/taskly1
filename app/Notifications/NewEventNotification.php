<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEventNotification extends Notification
{
    use Queueable;

    protected $event;
    protected $eventType;

    /**
     * Create a new notification instance.
     */
    public function __construct($event, $eventType)
    {
        $this->event = $event;
        $this->eventType = $eventType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $eventTypeText = $this->getEventTypeText($this->eventType);

        return (new MailMessage)
            ->subject("Nueva {$eventTypeText} creada")
            ->greeting("¡Hola {$notifiable->name}!")
            ->line("Has creado una nueva {$eventTypeText}: " . $this->getEventTitle())
            ->line("Fecha límite: " . ($this->event->due_date ? $this->event->due_date->format('d/m/Y H:i') : 'Sin fecha límite'))
            ->action('Ver detalles', url('/dashboard'))
            ->line('¡Gracias por usar Taskly!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        $eventTypeText = $this->getEventTypeText($this->eventType);
        $eventTitle = $this->getEventTitle();
        
        return [
            'title' => "Nueva {$eventTypeText} creada",
            'message' => "Has creado: {$eventTitle}",
            'event_id' => $this->event->id,
            'event_type' => $this->eventType,
            'due_date' => $this->event->due_date ? $this->event->due_date->toISOString() : null,
        ];
    }

    /**
     * Get event type text
     */
    private function getEventTypeText($type)
    {
        switch ($type) {
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
     * Get event title
     */
    private function getEventTitle()
    {
        if (isset($this->event->title)) {
            return $this->event->title;
        }
        if (isset($this->event->name)) {
            return $this->event->name;
        }
        return 'Sin título';
    }
} 