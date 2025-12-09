<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        );

        return (new MailMessage)
            ->subject('Verifica tu dirección de correo electrónico - Taskly')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Gracias por registrarte en Taskly. Para comenzar a usar tu cuenta, necesitamos verificar tu dirección de correo electrónico.')
            ->action('Verificar Correo Electrónico', $verificationUrl)
            ->line('Este enlace de verificación expirará en 60 minutos.')
            ->line('Si no creaste una cuenta, no es necesario que hagas nada.')
            ->salutation('Saludos, El equipo de Taskly');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Verificación de correo electrónico',
            'message' => 'Se ha enviado un enlace de verificación a tu correo electrónico.',
            'type' => 'email_verification'
        ];
    }
} 