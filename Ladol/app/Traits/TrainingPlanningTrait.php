<?php

namespace App\Traits;
use App\User;
use Illuminate\Support\Facades\DB;
use App\TrainingPlan;

trait TrainingPlanningTrait{

    public function getTrainingUsers($type, $data)
    {
        switch($type){
            case "jobrole":
                return DB::table('users')->whereIn('job_id', $data)->get();
            break;
            case "group":
                $user_ids =  DB::table('user_group_user')->where('user_group_id', $data)->pluck('user_id')->toArray();
                return DB::table('users')->whereIn('id', $user_ids)->get();
            break;
            case "employee":
                $users = DB::table('users')->whereIn('id', $data)->get();
                return $users;
            break;
            default:
                return [];
            break;
        }
    }


    public function assignTraining($type, $data, $plan_id, $training_id){
        if($type != "group"){
            $data = array_values(json_decode($data, true));
        }
       
        $plan = TrainingPlan::find($plan_id);
        $users = $this->getTrainingUsers($type, $data);
        if(!empty($users)){
            foreach($users as $user){
                DB::table('user_training_plans')->updateOrInsert(
                    [
                        'user_id' => $user->id,
                        'training_id' => $training_id,
                    ],
                    [
                        'user_id' => $user->id,
                        'training_id' => $training_id,
                        'status' => $plan->status,
                        'active' => '0',
                        'created_at' => now(),
                        'updated_at' => now(),
                        'type' => $plan->mode,
                        'training_plan_id' => $plan->id
                    ]
                );
                
            }
        }
       
    }
 
}