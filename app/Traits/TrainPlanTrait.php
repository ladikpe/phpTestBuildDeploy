<?php

namespace App\Traits;
use App\TrainingPlan;
use Carbon\Carbon;
use App\UserTrainingPlan;

trait TrainPlanTrait
{
    //returns status as defined

    private function getDefinedStatus()
    {
        return [
            'status' => [
                0 => 'pending',
                1 => 'ongoing',
                2 => 'overdue'
            ]
        ];
    }



    public function getTimeStatus($start_date, $end_date)
    {
        $status = 0;
        $statusText = 'pending';
        $currentDate = Carbon::now()->toDateString();
        if($currentDate >= $start_date && $currentDate <= $end_date){
            $status = 3;
            $statusText = 'ongoing';
        }elseif($currentDate < $start_date){
            $status = 0;
            $statusText = 'pending';
        }elseif($currentDate > $end_date){
            $status = 2;
            $statusText = 'overdue';
        }

        return [$status, $statusText];
    }


    //creon function to run once daily::

    public function changeTrainingStatus()
    {
       $trainigplans = TrainingPlan::all();
        foreach($trainigplans as $plan){
            if($plan->is_approved == "1"){
                $active =  $this->getTimeStatus($plan->start_date, $plan->end_date)[0];
                $status =  $this->getTimeStatus($plan->start_date, $plan->end_date)[1];
                //update user_training_plans::
                UserTrainingPlan::where('training_plan_id', $plan->id)->update(['status' =>  $status, 'active' => $active]);
                $plan->active = $active;
                $plan->status = $status;
                $plan->save();
            }
        }
    }
}