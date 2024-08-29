<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingAssessment extends Model
{
    protected $fillable = ['user_id', 'training_plan_id', 'is_filled', 'is_assessed', 'user_evaluation_score', 'manager_evaluation_score'];


    public function trainingplan()
    {
        return $this->belongsTo('App\TrainingPlan');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
