<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\LeaveApproval;
use App\Stage;

class LeaveRequestApprovedOthers extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $stage;
    public $leave_approval;
    public function __construct(LeaveApproval $leave_approval)
    {
        //

        $this->leave_approval=$leave_approval;
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
        if ($this->leave_approval->leave_request->leave_id==0) {
            $leave_name="Annual_leave";
        }else{
            $leave_name=$this->leave_approval->leave_request->leave->name;
        }


        return (new MailMessage)
            ->subject('Leave Request Approved')
            ->line('The leave request, '.$leave_name.' which was submitted by '.$this->leave_approval->leave_request->user->name.' for approval on the '.date('Y-m-d',strtotime($this->leave_approval->leave_request->created_at)).'('.\Carbon\Carbon::parse($this->leave_approval->leave_request->created_at)->diffForHumans().') has been approved at the final approval stage')
            ->line('They are to start the leave on '.date('F j,Y',strtotime($this->leave_approval->leave_request->start_date)).' and end on '.date('F j,Y',strtotime($this->leave_approval->leave_request->end_date)))

            // ->action('View Leave Request', url("leave/myrequests"))
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
        if ($this->leave_approval->leave_request->leave_id==0) {
            $leave_name="Annual_leave";
        }else{
            $leave_name=$this->leave_approval->leave_request->leave->name;
        }
        return new DatabaseMessage([
            'subject'=>$leave_name.' -Leave Request Approved',
            'message'=>'The leave request, '.$leave_name.' which was submitted by '.$this->leave_approval->leave_request->user->name.' for approval on the '.date('Y-m-d',strtotime($this->leave_approval->leave_request->created_at)).'('.\Carbon\Carbon::parse($this->leave_approval->leave_request->created_at)->diffForHumans().') has been approved at the final approval stage
            <br> They are to start the leave on '.date('F j,Y',strtotime($this->leave_approval->leave_request->start_date)).' and end on '.date('F j,Y',strtotime($this->leave_approval->leave_request->end_date)),
            'action'=>'#',
            'type'=>'LeaveRequest',
            'icon'=>'md-calendar'
        ]);

    }
}
