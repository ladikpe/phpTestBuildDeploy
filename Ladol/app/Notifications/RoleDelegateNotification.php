<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\DelegateRole;

class RoleDelegateNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public $message;
    public $sender;
    public $name;
    public $url;
    public $role_type;
    public $module_name;

    public function __construct($message, $sender, $name, $url, $role_type, $module_name)
    {
        $this->message = $message;
        $this->sender = $sender;
        $this->name = $name;
        $this->url = $url;
        $this->role_type = $role_type;
        $this->module_name = $module_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
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
            ->subject('Role Delegation Notification')
            // ->from($this->sender)
            ->line(' ' . $this->name)
            ->line(' ' . $this->message)
            ->action('Click here to view more', $this->url);
        //->line('Have A Lovely Day!');
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
            'subject' => 'Role Delegate Notification-' . $this->module_name,
            'message' => 'You have been delegated to the role of ' . $this->role_type . ' for ' . $this->module_name,
            'action' => url('leave/approvals'),
            'type' => 'Role Delegation',
            'icon' => 'md-calendar'
        ]);
    }
}
