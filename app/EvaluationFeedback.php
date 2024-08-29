<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvaluationFeedback extends Model
{
    protected $fillable = ['question_id', 'response', 'name', 'designation','training_plan_id', 'user_id', 'score', 'department', 'comment'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function question()
    {
        return $this->belongsTo('App\Question', 'question_id');
    }

    public function trainingPlan()
    {
        return $this->belongsTo('App\TrainingPlan', 'training_plan_id');
    }
}
