<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['question', 'category_id', 'type', 'status', 'assign_method','compulsory'];

    public function option()
    {
        return $this->hasMany('App\Option');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }
}
