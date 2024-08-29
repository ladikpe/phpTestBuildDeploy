<?php

namespace App\Notifications;

use App\LoanRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;


class ApproveLoanRequest extends Notification //implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
      public $loan_request;
    public function __construct(LoanRequest $loan_request)
    {
        //
        
        $this->loan_request=$loan_request;
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
                    ->subject('Approve Loan Request')
                    ->line('You are to review and approve a loan request applied for by '.$this->loan_request->user->name)
                     ->action('View Loan Request', url('/loan/approvals'))
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
            'subject'=>'Approve Loan Request',
            'message'=>'You are to review and approve a loan request applied for by '.$this->loan_request->user->name,
             'action'=>url('/loan/approvals'),
            'type'=>'Loan Request'
        ]);

    }
}
