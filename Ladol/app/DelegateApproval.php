<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DelegateApproval extends Model
{
    //
    protected $fillable = ['module_type', 'approval_request_id', 'stage_id', 'status', 'first', 'approved_by'];

    public function approval_request()
    {
        return $this->belongsTo('App\LeaveRequest', 'approval_request_id');
    }

    public function stage()
    {
        return $this->belongsTo('App\Stage', 'stage_id');
    }

    public function approver()
    {
        return $this->belongsTo('App\User', 'approved_by');
    }
}
