<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DelegateRole extends Model
{
    protected $fillable = ['approval_request_id', 'workflow_id', 'delegate_id', 'stage_id', 'end_date', 'message', 'delegated_by'];

    public function approval_request()
    {
        return $this->belongsTo('App\LeaveRequest', 'approval_request_id');
    }

    public function delegate()
    {
        return $this->belongsTo('App\User', 'delegate_id');
    }

    public function module()
    {
        return $this->belongsTo('App\Workflow', 'workflow_id');
    }

    public function stage()
    {
        return $this->belongsTo('App\Stage', 'stage_id');
    }

    public function delegator()
    {
        return $this->belongsTo('App\User', 'delegated_by');
    }


    public function payroll()
    {
        return $this->belongsTo('App\Payroll', 'approval_request_id');
    }

}
