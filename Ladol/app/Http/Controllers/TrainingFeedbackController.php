<?php

namespace App\Http\Controllers;
use App\Question;
use App\Category;
use App\Option;
use App\TrainingPlan;
use App\EvaluationFeedback;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ManagerFeedback;
use App\TrainingAssessment;

class TrainingFeedbackController extends Controller
{
    public function index(string $id)
    {
        $training  = TrainingPlan::find($id);
        $questions = Question::with('category')->get()->groupBy('category.category');
        $options   = Option::all();
        return view('learningdev.feedback',  ['questionsByCategory' => $questions, 'options' => $options, 'training' => $training, 'training_plan_id' => $training->id]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'response'    => "required|array|size:16", 
            'plan_id'     => "required"
        ]);

        if($validator->fails()){
            return redirect()->back()->with('message','Kindly fill all fields');
        }

        //INSERT 
        foreach($request['response'] as $key => $response){
            EvaluationFeedback::updateOrCreate([
                'question_id'      => $key,
                'user_id'          => auth()->user()->id,
                'training_plan_id' => $request['plan_id']
            ], [
                'response'    => $response,
                'plan_id'     => $request['training_plan_id']
            ]);
        }

        $user_score  = DB::table('evaluation_feedbacks')
                        ->where('user_id', auth()->user()->id)
                        ->where('training_plan_id', $request['plan_id'])
                        ->sum('response');

        $assessment = TrainingAssessment::where('user_id', auth()->user()->id)
                        ->where('training_plan_id', $request['plan_id'])
                        ->first();
        if($assessment){
            $assessment->update([
                'user_evaluation_score'    => $user_score,
                'is_filled'                => "1",
            ]);
        }else{
           TrainingAssessment::create([
                'user_id' => auth()->user()->id,
                'training_plan_id' => $request['plan_id'],
                'user_evaluation_score' => $user_score,
                'is_filled' => "1",
           ]);
        }
        
        return redirect()->back()->with('message','Successful');
    }

    public function showFilledReports()
    {
        $feedbacks = EvaluationFeedback::select('user_id', 'created_at', 'training_plan_id')->distinct()->with(['user', 'trainingPlan'])->get();
        return view('learningdev.filled-evaluations', ['feedbacks' => $feedbacks]);
    }

    public function viewFilledReport(string $id)
    {
        $feedback = EvaluationFeedback::findOrFail($id);
        return view('learningdev.manager-evaluations');
    }

    public function showUserReport(string $id, $plan)
    {
        $assessment       = TrainingAssessment::where('user_id', $id)->where('training_plan_id', $plan)->first();
        $user             = User::findOrFail($id);
        $options          = Option::all();
        $feedbacks        = EvaluationFeedback::with(['user', 'question'])->get();
        $total_score      = DB::table('evaluation_feedbacks')->sum('response');
        $questions        = Question::where('assign_method', 'manager')->get();
        $total_obtainable = DB::table('evaluation_feedbacks')->whereBetween('response', [1, 5])->count() * 5;
        $user_obtainable  =  DB::table('manager_feedbacks')->whereBetween('response', [1, 5])->count() * 5;
        return view('learningdev.manager-evaluations', ['feedbacks' => $feedbacks, 'user' => $user, 'total_score' => intval($total_score), 'total_obtainable' => $total_obtainable, 'percentage' => $this->calculatePercentage($total_score, $total_obtainable), 'questions' => $questions, 'options' => $options, 'plan_id' => $plan, 'assessment' => $assessment, 'obtainable' => $user_obtainable, 'user_percentage' => $this->calculatePercentage($assessment->manager_evaluation_score, $user_obtainable) ]);
    }

    public function calculatePercentage($value, $total)
    {
        return($value/$total) * 100;
    }

    public function submitManagerReport(Request $request)
    {
        $count = Question::where('assign_method', 'manager')->count();

        $validator = Validator::make($request->all(), [
            'response'    => "required|array|size:{$count}", 
            'plan_id'     => "required",
            'member_id'   => "required"
        ]);

        if($validator->fails()){
            return redirect()->back()->with('message','Kindly fill all fields');
        }

        //INSERT 
        foreach($request['response'] as $key => $response){
            ManagerFeedback::updateOrCreate([
                'question_id'      => $key,
                'user_id'          => $request['member_id'],
                'training_plan_id' => $request['plan_id']
            ], [
                'response'         => $response,
                'plan_id'          => $request['plan_id'],
                'question_id'      => $key,
                'user_id'          => $request['member_id'],
            ]);
        }

        $total_score = DB::table('manager_feedbacks')->sum('response');
        //INSERT into training_assessment::
        $assessment = TrainingAssessment::where('user_id', $request['member_id'])
                    ->where('training_plan_id', $request['plan_id'])
                    ->first();
    
        if($assessment){
            $assessment->update([
                'manager_evaluation_score' => $total_score,
                'is_assessed'              => "1",
            ]);
        }else{
           TrainingAssessment::create([
            'user_id'                  => $request['member_id'],
            'training_plan_id'         => $request['plan_id'],
            'manager_evaluation_score' => $total_score,
            'is_assessed'              => "1",
           ]);
        }
        
        return redirect()->back()->with('message','Successful');
    }
}
