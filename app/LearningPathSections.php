<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LearningPathSections extends Model
{
    protected $fillable = ['learning_path_id', 'title', 'url', 'type', 'duration', 'no_of_items', 'order', 'thumbnail', 'resource_url'];

    public function learning_path()
    {
        return $this->belongsTo('App\LearningPath');
    }
}
