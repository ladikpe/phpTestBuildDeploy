<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTrainingPlan extends Model
{
  protected $fillable = ['user_id', 'training_plan_id', 'active', 'status', 'type'];

  public function training_plan()
  {
    return $this->belongsTo('App\TrainingPlan');
  }

  public function user()
  {
    return $this->belongsTo('App\User');
  }

  public function training_data()
  {
    return $this->hasOne('App\TrainingData', 'id', 'training_id');
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
  
}
