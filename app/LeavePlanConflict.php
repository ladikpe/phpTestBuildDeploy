<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeavePlanConflict extends Model
{
    protected $fillable=['user_id', 'conflict_with_user_id', 'department_id', 'date', 'message'];

    public function user()
    {
    	return $this->belongsTo('App\User', 'user_id');
    }

    public function conflict_user()
    {
        return $this->belongsTo('App\User', 'conflict_with_user_id');
    }

    public function department()
    {
        return $this->belongsTo('App\Department', 'department_id');
    }
}
