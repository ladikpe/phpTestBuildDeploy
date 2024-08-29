<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table='states';
    protected $fillable=['name','country_id'];


    public function lgas()
   {
   return $this->hasMany('App\LocalGovernment','state_id');
   }

	public function country()
   {
   return $this->belongsTo('App\Country','country_id');
   }
}
