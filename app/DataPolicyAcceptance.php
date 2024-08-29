<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataPolicyAcceptance extends Model
{
    protected $table = 'data_policy_acceptances';

    protected $fillable =['user_id','accepted'];

    
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }

}
