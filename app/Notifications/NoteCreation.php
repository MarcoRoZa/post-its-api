<?php

namespace App\Notifications;

use App\Models\Note;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NoteCreation extends Notification implements ShouldQueue
{
    use Queueable;

    protected $note;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Note $note)
    {
        $this->note = $note;
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
        $group = $this->note->group;

        return (new MailMessage)
                    ->subject("Creación de Notas")
                    ->greeting("Hola $notifiable->name!")
                    ->line("Le notificamos que en el grupo \"$group->name\", se ha creado la nota: {$this->note->title}.");
    }
}
