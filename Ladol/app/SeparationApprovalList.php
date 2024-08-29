<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeparationApprovalList extends Model
{
    protected $fillable=['name','created_by','company_id', 'save_profile', 'save_'];
    public function separation_approvals()
    {
        return $this->belongsToMany('App\SeparationApproval','separation_approval_separation_approval_list');
    }
}
