<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrainingDataRequest extends FormRequest
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
            'name'              => ['required', 'string'],
            'description'       => ['required'],
            'category'          => ['required'],
            'type_of_training'  => ['required', 'string'],
            'training_location' => ['nullable'],
            'training_url'      => ['nullable'],
            'training_duration' => ['required', 'string'],
            'mode_of_training'  => ['required', 'string'],
            'cost_per_head'     => ['required'],
            'is_certification'  => ['required']
        ]; 
    }
} 
