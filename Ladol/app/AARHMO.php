<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AARHMO extends Model
{
    //
    protected $table = "hmo";
    protected $fillable = ['id','hmo', 'description'];
    protected $with = ["hmohospitals"];
    public function CountHospital(){
    	return $this->hasMany(AARHMOHospital::class, 'hmo');
    }
    public function hmohospitals()
    {
        return $this->belongsToMany(AARHMOHospital::class, 'hmo_hmohospitals', 'hmo_id', 'hmohospitals_id')
            ->withTimestamps();
    }


    
}
