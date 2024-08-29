<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'code','start_date','end_est_date','actual_ending_date','remark','client_id','created_by','project_manager_id','status'];

    public function tasks()
    {
    	return $this->hasMany('App\ProjectTask');
    }
    public function project_manager()
    {
    	return $this->belongsTo('App\User','project_manager_id');
    }
    public function project_members()
    {
    	return $this->belongsToMany('App\User','project_member')->withTimestamps();
    }
    public function client()
    {
    	return $this->belongsTo('App\Client');
    }
}
