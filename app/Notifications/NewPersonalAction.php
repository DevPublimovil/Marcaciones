<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPersonalAction extends Notification
{
    use Queueable;

    private $user;
    private $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->url = '/actions';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nueva acción de personal')
            ->greeting('¡Hola '. $notifiable->name .'!')
            ->line('Tienes una nueva acción de personal.')
            ->line(($this->user->role->id != 2) ? 'de tu empleado ' . $this->user->name .', la cual requiere tu aprobación' : 'de tu jefe ' . $this->user->name)
            ->action('Revisar', url($this->url))
            ->line('¡Gracias por usar nuestra Aplicación!')
            ->salutation('¡Saludos!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
