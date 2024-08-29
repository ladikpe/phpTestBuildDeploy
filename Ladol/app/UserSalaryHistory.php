<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSalaryHistory extends Model
{
    protected $fillable =['user_id','salary','effective_date','created_by','company_id','pay_grade_code'];

    public function user()
    {
        return $this->belongsTo('\App\User','user_id');
    }
    public function approver()
    {
        return $this->belongsTo('\App\User','created_by');
    }
}
