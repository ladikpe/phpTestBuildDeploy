<?php

namespace App;
use App\TrainingData;
use Illuminate\Database\Eloquent\Model;

class TrainingCategory extends Model
{
    protected $fillable = ['name', 'description'];

    public function trainings()
    {
        return $this->hasMany('App\TrainingData');
    }
}
