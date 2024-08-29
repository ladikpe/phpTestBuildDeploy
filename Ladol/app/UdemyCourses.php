<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UdemyCourses extends Model
{
    protected $fillable = ['name', 'description', 'url', 'duration', 'categories','instructor', 'images', 'headline','levels'];
}
