<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrainingPlan extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "training_name"   => ['required'],
            "training_description" => ['required'],
            "assign_mode"    => ["required"],
            "employee_email" => ["nullable"],
            "designation"    => ["nullable"],
            "cost_per_head"  => ["required"],
            "training_mode"  => ["required"],
            "resource_link"  => ["nullable"],
            "start_date"     => ["required"],
            "stop_date"      => ["required"],
            "duration"       => ["required"]
        ];
    }
}
