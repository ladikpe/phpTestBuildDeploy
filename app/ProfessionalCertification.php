<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ProfessionalCertification extends Model
{
    use LogsActivity;
    protected $fillable=['title','credential_id','expires','issued_on','issuer_name','expires_on','file','emp_id','company_id','last_change_approved','last_change_approved_by','last_change_approved_on'];

    protected static $logUnguarded = true;
    protected static $logOnlyDirty = true;

    protected static $logAttributes =['title','credential_id','expires','issued_on','issuer_name','expires_on','emp_id','company_id','file'];
    protected static $ignoreChangedAttributes=['created_at','updated_at','last_change_approved','last_change_approved_by','last_change_approved_on'];

    public function user()
    {
        return $this->belongsTo('App\User','emp_id');
    }


    public function approver()
    {
        return $this->belongsTo('App\User','approved_by');
    }

}
