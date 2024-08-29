<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\LoanApproval;
use App\Stage;

class LoanRequestPassedStage extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

   public $loan_approval;
      public $stage;
      public $nextstage;
    public function __construct(LoanApproval $loan_approval,Stage $stage,Stage $nextstage)
    {
        //
        $this->loan_approval=$loan_approval;
        $this->stage=$stage;
        $this->nextstage=$nextstage;
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
        ->subject('Loan request Passed an Approval Stage')
        ->line('The loan request, which you submitted for approval has been approved at the '.$this->stage->name.' by '.$this->loan_approval->approver->name)
        ->line('It has been moved to the'.$this->nextstage->name)
        ->action('View Loan Request',  url("loan/myrequests"))
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
            'subject'=>'Loan Request Passed an Approval Stage',
            'message'=>'The loan request which you submitted for approval has been approved at the '.$this->stage->name.' by '.$this->loan_approval->approver->name,
            'action'=>url("loan/myrequests"),
            'type'=>'LoanRequest',
            'icon'=>'md-money'
        ]);

    }
}
