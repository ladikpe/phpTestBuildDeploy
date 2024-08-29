<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingPlan extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['name', 'description', 'cost_per_head', 'grand_total',  'assign_mode', 'type', 'resource_link', 'start_date', 'end_date', 'duration', 'employee_email' , 'designation_id', 'user_ids', 'group_id', 'department_id', 'mode', 'location', 'jobroles_id', 'course_type'];

    protected $guarded = ['id'];

    protected $casts = [
        'user_ids'    => 'array',
        'jobroles_id' => 'array'
    ];

    public function users()
    {
        return $this->belongsToMany('App\User')->using('App\UserTrainingPlan');
    }

    public function user_training_plans()
    {
        return $this->hasMany('App\UserTrainingPlan');
    }

    public function training_data()
    {
      return $this->hasOne('App\TrainingData');
    }
    
    public function getCompletionPercentage($start_date, $end_date)
    {
        $start_stamp = strtotime($start_date);
        $end_stamp   = strtotime($end_date);
        $today_stamp = strtotime("now");

        if($today_stamp <= $start_stamp){
            return 0;
        }else if($today_stamp >= $end_stamp){
            return 100;
        }else{
            $total_duration = $end_stamp - $start_stamp;
            $completed_duration = $today_stamp - $start_stamp;
            $completion_rate = ($completed_duration/$total_duration) * 100;
            return round($completion_rate, 2);
        }
    }

    public function evaluationFeedbacks()
    {
        return $this->hasMany('App\EvaluationFeedback', 'training_plan_id');
    }
 
}
