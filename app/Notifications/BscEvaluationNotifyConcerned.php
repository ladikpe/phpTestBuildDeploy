<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class BscEvaluationNotifyConcerned extends Notification 
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $evaluation , $notiMessage,$urlToGo, $accepted;
    public function __construct($evaluation,$urlToGo, $message)
    {
        //
        $this->evaluation=$evaluation;
        $this->urlToGo=$urlToGo;
        $this->notiMessage=$message;
       

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
        // return ['database'];
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
                    ->subject('Evaluation Completed')
                    ->line($this->notiMessage)

                    ->action('Notification Action', url($this->urlToGo))
                    ->line('Thank you for using our application!');
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

    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'subject'=> 'Evaluation Completed',
            'message' => $this->notiMessage,
            'action' => url($this->urlToGo),
            'type'=>'Performance Evaluation'
            ]);
        }
}
