<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\LeaveRequest;

class RelieveLeaveRequestRejection extends Notification 
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    
      public $leave_request;
    public function __construct(LeaveRequest $leave_request)
    {
        //
       
        $this->leave_request=$leave_request;
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
        if ($this->leave_request->leave_id==0) {
            $leave_name="Annual_leave";
        }else{
            $leave_name=$this->leave_request->leave->name;
        }
         return (new MailMessage)
        ->subject('Leave Request Rejected')
        ->line('The leave request, '.$leave_name.' which you submitted for approval on the '.date('Y-m-d',strtotime($this->leave_request->created_at)).'('.\Carbon\Carbon::parse($this->leave_request->created_at)->diffForHumans().') has been rejected by your suggested relieve')
        ->line('Your leave balance is '.$this->leave_request->balance)
        ->action('View Leave Request', url("leave/myrequests"))
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
         if ($this->leave_request->leave_id==0) {
            $leave_name="Annual_leave";
        }else{
            $leave_name=$this->leave_request->leave->name;
        }
          return new DatabaseMessage([
            'subject'=>$leave_name.' -Leave Request Rejected',
            'message'=>'The leave request, '.$leave_name.' which you submitted for approval on the '.date('Y-m-d',strtotime($this->leave_request->created_at)).'('.\Carbon\Carbon::parse($this->leave_request->created_at)->diffForHumans().') has been rejected.
<br> Your leave balance is '.$this->leave_request->balance+$this->leave_request->length,
            'action'=>url("leave/myrequests"),
            'type'=>'LeaveRequest',
            'icon'=>'md-calendar'
        ]);

    }
}
