<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;


class LoanRequest extends Model
{
    protected $table = 'loan_requests';
    protected $fillable = [
        'user_id', 'amount', 'netpay', 'monthly_deduction', 'deduction_count',
        'period', 'months_deducted', 'current_rate', 'repayment_starts', 'status', 'approved_by',
        'completed', 'created_at', 'updated_at', 'specific_salary_component_type_id', 'maximum_allowed', 'company_id', 'workflow_id',
        'total_repayments', 'total_interest'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }

    public function approver()
    {
        return $this->belongsTo('App\User', 'approved_by');
    }

    public function approvals()
    {
        return $this->hasMany('App\LoanApproval', 'loan_request_id');
    }
    public function payrolls()
    {
        return $this->belongsToMany('App\Payroll', 'payroll_loan_request', 'loan_request_id', 'payroll_id');
    }
    public function specific_salary_components()
    {
        return $this->hasMany('App\SpecificSalaryComponent', 'loan_id');
    }
    public function workflow()
    {
        return $this->belongsTo('App\Workflow', 'workflow_id');
    }
}
