<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LearningPath extends Model
{
    protected $fillable = ['title', 'description', 'editor_name', 'editor_email', 'estimated_content_length', 'number_of_content_items','is_pro_path', 'created', 'last_modified', 'url', 'path_id'];

    public function learning_path_sections()
    {
        return $this->hasMany('App\LearningPathSections', 'learning_path_id');
    }
}

