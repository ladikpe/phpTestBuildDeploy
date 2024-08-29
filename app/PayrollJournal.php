<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollJournal extends Model
{
    protected $fillable=['payroll_id','code','date','gl_code','description','project_code','debit','credit'];
    protected $appends=['datenew'];

    public function getDatenewAttribute()
    {
        return date('m/d/Y',strtotime($this->date));
    }

}
