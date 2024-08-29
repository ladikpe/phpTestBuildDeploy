<?php

namespace App;
use App\Department;
use Illuminate\Database\Eloquent\Model;

class TrainingPlanBudget extends Model
{
    protected $fillable = ['department_id', 'allocation', 'stop_date', 'active'];

    public function department()
    {
        return $this->belongsTo('App\Department');
    }
}
