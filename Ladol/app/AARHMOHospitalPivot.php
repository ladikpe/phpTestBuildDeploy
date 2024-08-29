<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class AARHMOHospitalPivot extends Model
{
    //
    protected $table = "hmo_hmohospitals";
    protected $appends = ['hmo'];
    public function getHmoAttribute(){
        $hmo = DB::select('SELECT * FROM hmo WHERE id = ?', [$this->hmo_id]);
    	return $hmo[0];
    }
    


    
}
