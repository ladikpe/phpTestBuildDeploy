<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\LoanApproval;
use App\Stage;

class LoanRequestRejected extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $stage;
      public $leave_approval;
    public function __construct(Stage $stage,LoanApproval $loan_approval)
    {
        //
        $this->stage=$stage;
        $this->loan_approval=$loan_approval;
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
        ->subject('Loan Request Rejected')
             ->line('The loan request which ' . $this->loan_approval->loan_request->user->name . ' submitted for approval on the ' . date('Y-m-d', strtotime($this->loan_approval->loan_request->created_at)) . '(' . \Carbon\Carbon::parse($this->loan_approval->loan_request->created_at)->diffForHumans() . ') has been rejected')
        ->action('View Loan Request', url("loan/myrequests"))
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
            'subject'=>'Loan Request Rejected',
            'message'=>'The loan request which ' . $this->loan_approval->loan_request->user->name . ' submitted for approval on the ' . date('Y-m-d', strtotime($this->loan_approval->loan_request->created_at)) . '(' . \Carbon\Carbon::parse($this->loan_approval->loan_request->created_at)->diffForHumans() . ') has been rejected',
            'action'=>url("loan/myrequests"),
            'type'=>'LoanRequest',
            'icon'=>'md-money'
        ]);

    }
}
