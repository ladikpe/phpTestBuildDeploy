<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class AARHMOHospital extends Model
{
    //
    protected $table = "hmohospitals";
    protected $fillable = ['hmo', 'hospital', 'category', 'band', 'category', 'address', 'contact'];
    protected $appends = ['hmos', 'user_count'];

    public function users(){
    	return $this->hasMany(AARHMOSelfService::class, 'primary_hospital');
    }
    public function FindHMO(){
    	return $this->belongsTo(AARHMO::class,'hmo');
    }
    public function hmohospitals()
    {
        return $this->belongsToMany(AARHMO::class, 'hmo_hmohospitals', 'hmohospitals_id', 'hmo_id' )
            ->withTimestamps();
    }
    public function getUserCountAttribute()
    {
        
        return count($this->users);
    }
    public function getHmosAttribute()
    {
        $pivots = AARHMOHospitalPivot::where('hmohospitals_id', $this->id)->get();
        $hmoIds = $pivots->map(function ($item, int $key) {
            return $item;
        });
        // $hmos = AARHMO::whereIn('id',$hmoIds)->get();
        return $hmoIds;
    }




}

