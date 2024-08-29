<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerformanceDiscussion extends Model
{
    //
    protected $fillable = ['participant_id','evaluation_id','title','discussion','employee_submitted','line_manager_approved','employee_submission_date','line_manager_approval_date','rejection_reason'];
    protected $with = ['discussion_details'];
    public function bscevaluation(){
        return $this->belongsTo('App\BscEvaluation','evaluation_id')->withDefault();
    }
    public function discussion_details(){
        return $this->hasMany('App\PerformanceDiscussionDetail','performance_discussion_id');
    }
}
