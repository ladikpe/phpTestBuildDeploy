<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PensionFundAdmin extends Model
{
    protected $table='pension_fund_admins';
    protected $fillable=['name'];
    protected $with=['users'];


    public function users()
   {
   return $this->hasMany('App\User','pension_administrator', 'name');
   }

	
}
