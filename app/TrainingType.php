<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingType extends Model
{
    protected $fillable = ['type', 'description'];

    public function training()
    {
        return $this->belongsTo('App\TrainingData');
    }
}
