<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = ['name', 'description', 'department_id'];

    public function trainingplan()
    {
        return $this->belongsTo('App\Department', 'department_id');
    }
}
