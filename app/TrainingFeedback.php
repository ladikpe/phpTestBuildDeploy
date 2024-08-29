<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingFeedback extends Model
{
    protected $fillable = ['training_plan_id', 'user_id', 'is_support_learning', 'is_length_sufficient', 'is_participation_encouraged', 'is_opportunities_provided', 'is_appropriate_level', 'is_quiz_helped', 'is_learning_aids', 'is_equipment_working', 'is_instructor_knowledgeable', 'is_instructor_responsive', 'is_content_presented', 'is_instructor_interesting', 'is_instructor_participatory', 'is_faccilities_suitable', 'is_location_easy', 'is_training_relevant', 'is_practical_exercises_good', 'is_training_style_more', 'is_course_satisifying', 'is_instructor_satisfying', 'is_environment_satisfying', 'comments'];
}
