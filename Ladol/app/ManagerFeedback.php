<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagerFeedback extends Model
{
    protected $table    = "manager_feedbacks";
    protected $fillable = ['question_id', 'user_id', 'training_plan_id', 'response'];
}
