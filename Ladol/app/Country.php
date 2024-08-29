<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
   protected $table='countries';
   protected $guarded = ['id', 'created_at', 'updated_at'];

   public function states()
   {
   return $this->hasMany('App\State','country_id');
   }
}
