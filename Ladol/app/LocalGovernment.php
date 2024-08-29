<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocalGovernment extends Model
{
    protected $table='l_g_a_s';
    protected $fillable=['name','state_id'];
    public function state()
    {
    	return $this->belongsTo('App\State','state_id');
    }
}
