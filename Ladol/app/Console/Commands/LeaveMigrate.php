<?php

namespace App\Console\Commands;

use App\LeavePolicy;
use App\LeaveRequest;
use App\LeaveRequestDate;
use Illuminate\Console\Command;

class LeaveMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $leave_requests = LeaveRequest::all();
        foreach ($leave_requests as $leave_request):
            if ($leave_request->dates->count() > 0):
                $start_date = date('Y-m-d', strtotime($leave_request->start_date));
                $end_date = date('Y-m-d', strtotime($leave_request->end_date));
                $dates_and_days = $this->LeaveDaysRange($leave_request->start_date, $leave_request->end_date, $leave_request->company_id);
                $length = $dates_and_days['days'];
                foreach ($dates_and_days['dates'] as $dd) {
                    LeaveRequestDate::create(['leave_request_id' => $leave_request->id, 'date' => date('Y-m-d', strtotime($dd))]);
                }
            endif;
        endforeach;

    }

    public function LeaveDaysRange($start_date, $end_date, $company_id)
    {

        $lp = LeavePolicy::where('company_id', $company_id)->first();
        $dates = [];
        $start = new \DateTime($start_date);
        $end = new \DateTime($end_date);
        // otherwise the  end date is excluded (bug?)
        $end->modify('+1 day');

        $interval = $end->diff($start);

        // total days
        $days = $interval->days;

        // create an iterateable period of date (P1D equates to 1 day)
        $period = new \DatePeriod($start, new \DateInterval('P1D'), $end);

        // best stored as array, so you can add more than one
        $holidays = \App\Holiday::where(['status' => 1, 'company_id' => $company_id])->whereYear('date', date('Y'))->pluck('date');//array('2012-09-07');

        foreach ($period as $dt) {
            $curr = $dt->format('D');
            $is_weekend = 0;
            $is_holiday = 0;

            // substract if Saturday or Sunday
            if (($curr == 'Sat' || $curr == 'Sun') && $lp->includes_weekend == 0) {
                $days--;
                $is_weekend = 1;

            } elseif ($holidays->count() > 0 && $lp->includes_holiday == 0) {
                foreach ($holidays as $holiday) {
                    if ($dt->format('m/d/Y') == $holiday) {
                        $days--;
                        $is_holiday = 1;
                    }
                }


            } else {

            }
            if ($is_weekend == 0 && $is_holiday == 0) {
                $dates[] = $dt->format('Y-m-d');
            }
            // $dates[]=$dt->format('Y-m-d');
        }


        return ['days' => $days, 'dates' => $dates];
    }
}
