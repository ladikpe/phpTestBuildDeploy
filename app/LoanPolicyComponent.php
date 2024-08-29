<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanPolicyComponent extends Model
{
    protected $fillable=['payroll_policy_id','name','source','salary_component_constant','payroll_constant','amount','percentage'];

    public function policy(){
        return $this->belongsTo('App\LoanPolicy','payroll_policy_id');
    }
}
