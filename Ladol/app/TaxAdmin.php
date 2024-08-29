<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxAdmin extends Model
{
    protected $table='tax_admins';
    protected $fillable=['name'];
    protected $with=['users'];


    public function users()
    {
        return $this->hasMany('App\User','tax_authority', 'name');
    }

	
}
