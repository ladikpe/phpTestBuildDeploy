<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UdemyActivity extends Model
{
    protected $fillable = ['username', 'user_email', 'user_role', 'user_joined_date', 'user_is_deactivated', 'num_new_enrolled_courses', 'num_new_assigned_courses', 'num_new_started_courses', 'num_completed_courses', 'num_completed_lectures', 'num_completed_quizzes', 'num_video_consumed_minutes', 'num_web_visited_days', 'last_date_visit'];
}
