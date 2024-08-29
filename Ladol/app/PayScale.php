<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayScale extends Model
{
   protected $fillable = ['level_code', 'min_val', 'max_val'];
   protected $table="pay_scales";
   protected $with=['grades'];

   public function grades()
    {
       return $this->hasMany('App\Grade','level', 'level_code');
    }
}
