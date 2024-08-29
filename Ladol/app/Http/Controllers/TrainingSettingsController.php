<?php

namespace App\Http\Controllers;
use App\Http\Requests\TrainingDataRequest;
use Illuminate\Http\Request;
use App\TrainingData;
use App\Job;
use App\TrainingCategory;
use App\Category;
use App\TrainingType;
use App\Question;
use App\Option;
use Illuminate\Support\Facades\Validator;

class TrainingSettingsController extends Controller
{
    public function index()
    {
        $trainings           = TrainingData::all();
        $categories          = TrainingCategory::all();
        $types               = TrainingType::all();
        $question_categories = Category::all();
        $questions           = Question::with('category')->get();
        $options             = Option::all();
        return view('settings.trainingsettings.index', ['trainings' => $trainings, 'categories' => $categories, 'types' => $types, 'question_categories' => $question_categories, 'questions' => $questions, 'options' => $options]);
    }

    public function saveTraining(TrainingDataRequest $request)
    {
        TrainingData::updateOrCreate(['id' => $request['training_id']], [
            'name'              => $request['name'],
            'description'       => $request['description'],
            'duration'          => $request['training_duration'],
            'type_of_training'  => $request['type_of_training'],
            'category_id'       => $request['category'],
            'training_url'      => $request['training_url'],
            'training_location' => $request['training_location'],
            'class_size'        => $request['class_size'],
            'cost_per_head'     => $request['cost_per_head'],
            'is_certification_required' => $request['is_certification'],
            'mode_of_training'   => $request['mode_of_training'],
            'class_size'         => $request['class_size'],
            'cost_per_head'      => $request['cost_per_head'],
        ]);

        return  response()->json('success',200);
        
    }


    public function getTraining(string $id)
    {
        $training = TrainingData::find($id);
        return  response()->json(['status' => 200, 'data' => $training],200);
    }

    public function deleteTraining(string $id)
    {
        $training = TrainingData::find($id);
        if ($training) {
			$training->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
    }

    public function getAllTrainings(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'course_type' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status'  => "401",
                'errors'  => $validator->errors()
            ]);
        }
        if($request['course_type'] == 'non-udemy'){
            $data = TrainingData::where('course_type', 'non-udemy')->get();
            return  response()->json([
                'data'   => $data,
                'status' => 200
            ],200);
        }else{
            return  response()->json([
                'data'   =>  [],
                'status' => 200
            ],200);
        }
        
    }

    
    public function getJobRoles(string $id){
        $jobs = Job::where('department_id', '=', $id)->get();
        return  response()->json(['status' => 200, 'data' => $jobs],200);
    }
}
