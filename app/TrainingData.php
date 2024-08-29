<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingData extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['name', 'description', 'duration', 'type_of_training', 'category_id', 'training_url', 'category',  'training_location', 'class_size', 'cost_per_head', 'is_certification_required', 'mode_of_training'];

    public function category()
    {
        return $this->belongsTo('App\TrainingCategory');
    }
}
