<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class LoanPolicy extends Model
{
    protected $table='loan_policies';
    protected $fillable=['annual_interest_percentage','uses_confirmation','minimum_length_of_stay',
        'uses_performance','minimum_performance_mark','dsr_percentage','user_id','workflow_id','company_id','specific_salary_component_type_id',
        'concurrent_loans','repayment_length'];

    public function editor()
    {
    	return $this->belongsTo('App\User','user_id');
    }
    public function workflow()
    {
    	return $this->belongsTo('App\Workflow','workflow_id');
    }
    public function specific_salary_component_type()
    {
        return $this->belongsTo('App\SpecificSalaryComponentType','specific_salary_component_type_id');
    }

    public function policy_components()
    {
        return $this->hasMany('App\LoanPolicyComponent','payroll_policy_id');
    }
     protected static function boot()
    {
        parent::boot();
      
        static::addGlobalScope(new CompanyScope);
    }
}
