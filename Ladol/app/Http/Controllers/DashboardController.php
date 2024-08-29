<?php

namespace App\Http\Controllers;
use App\TrainingPlan;
use App\UserTrainingPlan;
use App\TrainingData;
use App\Designation;
use App\Department;
use App\TrainingProgress;
use Illuminate\Http\Request;
use App\UserGroup;
use App\User;
use App\UdemyCourses;
use App\TrainingPlanBudget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\TrainingFeedback;
use App\UdemyActivity;
use App\LearningPath;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TrainingsExport;
use Illuminate\Support\Facades\Schema;



class DashboardController extends Controller
{
    public function showManagerDashboard()
    {
        if(auth()->user()->role->id == '1' || auth()->user()->role->id == '2'){
            $ongoing_counter     = $this->fetchGraphRecords('ongoing');
            $pending_counter     = $this->fetchGraphRecords('pending');
            $completed_counter   = $this->fetchGraphRecords('completed');
            $overdue_counter     = $this->fetchGraphRecords('overdue');
            $ongoing_pie         = $this->fetchPieData('ongoing', 'gen');
            $pending_pie         = $this->fetchPieData('pending', 'gen');
            $overdue_pie         = $this->fetchPieData('overdue', 'gen');
            $completed_pie       = $this->fetchPieData('completed','gen');
            $max                 = max(array_merge($this->fetchGraphRecords('ongoing'), $this->fetchGraphRecords('pending'), $this->fetchGraphRecords('completed')));
            $total_trainings     = TrainingPlan::all()->count();
            $udemy_courses       = UdemyActivity::all()->count();
            $departments         = Department::all();
            $mandatory_trainings = TrainingPlan::where('mode', '=', 'mandatory')->count();
            $optional_trainings  = TrainingPlan::where('mode', '=', 'optional')->count();
            $completed_trainings = TrainingPlan::where('status', '=', 'completed')->count();
            $overdue_trainings   = TrainingPlan::where('status', '=', 'overdue')->count();
            $ongoing_trainings   = TrainingPlan::where('status', '=', 'ongoing')->count();
            $pending_trainings   = TrainingPlan::where('status', '=', 'pending')->count();
            return view('learningdev.manager', ['total_trainings' => $total_trainings, 'optional_trainings' => $optional_trainings, 'completed_trainings' => $completed_trainings, 'ongoing_trainings' => $ongoing_trainings, 'overdue_trainings' => $overdue_trainings, 'pending_trainings' => $pending_trainings, 'mandatory_trainings' => $mandatory_trainings, 'udemy_courses' => $udemy_courses, 'departments' => $departments, 'ongoing_counter' => $ongoing_counter, 'pending_counter' => $pending_counter, 'completed_counter' => $completed_counter, 'overdue_counter' => $overdue_counter, 'max' => $max, 'ongoing_pie' => $ongoing_pie, 'pending_pie' => $pending_pie, 'overdue_pie' => $overdue_pie, 'completed_pie' => $completed_pie]);
        }else if(auth()->user()->role->name == 'Employee'){
            $ongoing_counter     = $this->fetchGraphRecords('ongoing', 'user');
            $pending_counter     = $this->fetchGraphRecords('pending', 'user');
            $completed_counter   = $this->fetchGraphRecords('completed','user');
            $overdue_counter     = $this->fetchGraphRecords('overdue', 'user');
            $ongoing_pie         = $this->fetchPieData('ongoing', 'user');
            $pending_pie         = $this->fetchPieData('pending', 'user');
            $overdue_pie         = $this->fetchPieData('overdue', 'user');
            $completed_pie       = $this->fetchPieData('completed','user');
            $max                 =  max(array_merge($this->fetchGraphRecords('ongoing'), $this->fetchGraphRecords('pending'), $this->fetchGraphRecords('completed')));
            $udemy_courses       = UdemyActivity::where('user_email', auth()->user()->email)->count();
            $departments         = Department::all();
            $total_trainings     =  DB::table('user_training_plans')
                                        ->where('user_id', '=', auth()->user()->id)
                                        ->join('training_datas', 'user_training_plans.training_id', '=', 'training_datas.id')
                                        ->join('training_plans', 'user_training_plans.training_plan_id', '=', 'training_plans.id')
                                        ->count();
            $mandatory_trainings = $this->countData('user_training_plans', 'mandatory');
            $optional_trainings  = $this->countData('user_training_plans', 'optional');
            $completed_trainings = UserTrainingPlan::where('user_active', '2')->where('user_id', auth()->user()->id)->count();
            $overdue_trainings   = UserTrainingPlan::where('status', 'overdue')->where('user_id', auth()->user()->id)->count();
            $ongoing_trainings   = UserTrainingPlan::where('user_active', '1')->where('user_id', auth()->user()->id)->count();
            $pending_trainings   = UserTrainingPlan::where('user_active', '0')->where('user_id', auth()->user()->id)->count();
            return view('learningdev.manager', ['total_trainings' => $total_trainings, 'optional_trainings' => $optional_trainings, 'completed_trainings' => $completed_trainings, 'overdue_counter' => $overdue_counter, 'ongoing_trainings' => $ongoing_trainings, 'overdue_trainings' => $overdue_trainings, 'pending_trainings' => $pending_trainings, 'mandatory_trainings' => $mandatory_trainings, 'udemy_courses' => $udemy_courses, 'departments' => $departments, 'ongoing_counter' => $ongoing_counter, 'pending_counter' => $pending_counter, 'completed_counter' => $completed_counter, 'max' => $max, 'ongoing_pie' => $ongoing_pie, 'pending_pie' => $pending_pie, 'overdue_pie' => $overdue_pie, 'completed_pie' => $completed_pie]);
        }
    }
 
    public function showTrainings($searchData = null)
    {
        $trainings     = $searchData == null ? TrainingData::all() : $searchData;
        $users         = User::all();
        $designations  = Designation::all();
        $departments   = Department::all();
        $groups        = UserGroup::all();
        $udemy_courses = UdemyCourses::all();

        if(auth()->user()->role->id == '1' || auth()->user()->role->id == '2'){
            $plans         = $searchData ?  $searchData : TrainingPlan::withTrashed()->get(); 
            return view('learningdev.trainings', ['plans' => $plans, 'groups' => $groups, 'designations' => $designations, 'trainings' => $trainings, 'departments' => $departments,'users' => $users, 'udemy_courses' => $udemy_courses]);
        }else if(auth()->user()->role->name == 'Employee'){
            $trainings   =   DB::table('user_training_plans')
                            ->where('user_id', '=', auth()->user()->id)
                            ->join('training_datas', 'user_training_plans.training_id', '=', 'training_datas.id')
                            ->join('training_plans', 'user_training_plans.training_plan_id', '=', 'training_plans.id')
                            ->select('user_training_plans.id as main_id','user_training_plans.active as plan_active', 'user_active', 'training_datas.*', 'training_plans.*')
                            ->paginate(7);

            return view('learningdev.employeetrainings', ['trainings' => $trainings, 'groups' => $groups, 'designations' => $designations, 'trainings' => $trainings, 'departments' => $departments,'users' => $users, 'udemy_courses' => $udemy_courses]);

        }
    }

    public function viewTraining($id)
    {
      $plan = TrainingPlan::findOrFail($id);
      $rate = $this->getCompletionPercentage($plan->start_date, $plan->end_date);
      return view('learningdev.show', ['plan' => $plan, 'rate' => $rate]);
    }

    public function deleteTraining($id)
    {
      $plan = TrainingPlan::findOrFail($id);
      $plan->delete();
      return redirect()->back()->with('success', 'Training Plan deleted successfully');
    }

    public function activateTraining(Request $request)
    {
      $plan = TrainingPlan::findOrFail($request['plan_id']);
      $plan->restore();
      return redirect()->route('activatetrainingplan')->with('success', 'Training Plan restored successfully');
    }


    public function showMandantaryTrainings()
    {
        $trainings     = TrainingData::all();
        $users         = User::all();
        $designations  = Designation::all();
        $departments   = Department::all();
        $groups        = UserGroup::all();
        $udemy_courses = UdemyCourses::all();
        if(auth()->user()->role->id == '1'){
            $plans = TrainingPlan::where('mode', '=', 'mandatory')->get();
        }else if(auth()->user()->role->name == 'Employee'){
            $trainings   =   DB::table('user_training_plans')
                            ->where('user_id', '=', auth()->user()->id)
                            ->join('training_datas', 'user_training_plans.training_id', '=', 'training_datas.id')
                            ->join('training_plans', 'user_training_plans.training_plan_id', '=', 'training_plans.id')
                            ->select('user_training_plans.id as main_id','user_training_plans.active as plan_active', 'user_active', 'training_datas.*', 'training_plans.*')
                            ->paginate(7);
        }

        $holder = auth()->user()->role->id == '1' ?   $plans : $trainings;
        return view('learningdev.mandatory', [ 'plans' => $holder, 'groups' => $groups, 'designations' => $designations, 'trainings' => $trainings, 'departments' => $departments,'users' => $users, 'udemy_courses' => $udemy_courses]);
    }

    public function showOngoingTrainings()
    {
        if((auth()->user()->job_id == "1") || (auth()->user()->job_id == "3")){
            $plans = TrainingPlan::where('status', '=', 'ongoing')->get();
            return view('learningdev.ongoing', ['plans' => $plans]);
        }else{
            $plans   =   DB::table('user_training_plans')
                ->where('user_id', '=', auth()->user()->id)
                ->where('user_training_plans.user_active', '=', "1")
                ->join('training_datas', 'user_training_plans.training_id', '=', 'training_datas.id')
                ->join('training_plans', 'user_training_plans.training_plan_id', '=', 'training_plans.id')
                ->get();
            return view('learningdev.ongoing', ['plans' => $plans]);
        }
       
    }

    public function showOptionalTrainings()
    {
        $trainings     = TrainingData::all();
        $users         = User::all();
        $designations  = Designation::all();
        $departments   = Department::all();
        $groups        = UserGroup::all();
        $udemy_courses = UdemyCourses::all();

        if(auth()->user()->role->id == '1'){
            $plans = TrainingPlan::where('mode', '=', 'optional')->get();
        }else if(auth()->user()->role->name == 'Employee'){
            $plans  =   DB::table('user_training_plans')
                        ->where('user_id', '=', auth()->user()->id)
                        ->join('training_datas', 'user_training_plans.training_id', '=', 'training_datas.id')
                        ->join('training_plans', 'user_training_plans.training_plan_id', '=', 'training_plans.id')
                        ->select('user_training_plans.id as main_id','user_training_plans.active as plan_active', 'user_active', 'training_datas.*', 'training_plans.*')
                        ->paginate(7);
        }
        return view('learningdev.optional', ['plans' => $plans, 'groups' => $groups, 'designations' => $designations, 'trainings' => $trainings, 'departments' => $departments,'users' => $users, 'udemy_courses' => $udemy_courses]);
    }

    public function showOverdueTrainings()
    {
        if((auth()->user()->job_id == "1") || (auth()->user()->job_id == "3")){
            $plans = TrainingPlan::where('status', '=', 'overdue')->get();
        }else{
            $plans   =   DB::table('user_training_plans')
                ->where('user_id', '=', auth()->user()->id)
                ->where('user_training_plans.status', '=', "overdue")
                ->join('training_datas', 'user_training_plans.training_id', '=', 'training_datas.id')
                ->join('training_plans', 'user_training_plans.training_plan_id', '=', 'training_plans.id')
                ->get();
        }
        return view('learningdev.overdue', ['plans' => $plans]);
    }

    public function showCompletedTrainings()
    {
        if((auth()->user()->job_id == "1") || (auth()->user()->job_id == "3")){
            $plans = TrainingPlan::where('status', '=', 'completed')->get();
        }else{
            $plans   =   DB::table('user_training_plans')
                ->where('user_id', '=', auth()->user()->id)
                ->where('user_training_plans.user_active', '=', "2")
                ->join('training_datas', 'user_training_plans.training_id', '=', 'training_datas.id')
                ->join('training_plans', 'user_training_plans.training_plan_id', '=', 'training_plans.id')
                ->get();
        } 
        return view('learningdev.completed', ['plans' => $plans]);
    }

    public function showPendingTrainings()
    {
        if((auth()->user()->job_id == "1") || (auth()->user()->job_id == "3")){
            $plans = TrainingPlan::where('status', '=', 'pending')->get();
        }else{
            $plans   =   DB::table('user_training_plans')
            ->where('user_id', '=', auth()->user()->id)
            ->where('user_training_plans.user_active', '=', "0")
            ->join('training_datas', 'user_training_plans.training_id', '=', 'training_datas.id')
            ->join('training_plans', 'user_training_plans.training_plan_id', '=', 'training_plans.id')
            ->get();
        }
        return view('learningdev.pending ', ['plans' => $plans]);
    }

    public function startTraining(Request $request)
    {
        $request->validate([
            'plan_id' => ['required'],
            'auth_id' => ['required']
        ]);

        $plan_id = $request['plan_id'];
        $user_id = $request['auth_id'];

        //CHECK if training is not pending or overdue 

        $training_plan = TrainingPlan::find($plan_id);

        if($training_plan->status == 'pending' || $training_plan->status == 'overdue'){
            return redirect()->back()->with('message', "You cannot start a {$training_plan->status} training !.");
        }

        TrainingProgress::create([
            'user_id' => $user_id,
            'plan_id' => $plan_id
        ]);

        return redirect()->back()->with('message', 'Training started successfully');
    }

    public function cancelTraining(Request $request)
    {
        $request->validate([
            'plan_id' => ['required'],
            'auth_id' => ['required']
        ]);

        $plan_id = $request['plan_id'];
        $user_id = $request['auth_id'];

        //CHECK if training is not pending or overdue 

        $training_plan = TrainingPlan::find($plan_id);

        if($training_plan->status !== 'ongoing'){
            return redirect()->back()->with('message', "You cannot cancel a {$training_plan->status} training !.");
        }

    

    }
    
    public function countData($tablename, $condition){
        return  DB::table($tablename)
            ->where('user_id', '=', auth()->user()->id)
            ->where('user_training_plans.type', '=', $condition)
            ->join('training_datas', 'user_training_plans.training_id', '=', 'training_datas.id')
            ->join('training_plans', 'user_training_plans.training_plan_id', '=', 'training_plans.id')
            ->count();
    }

    public function saveBudget(Request $request)
    {
        try{
            $budget = TrainingPlanBudget::updateOrCreate(['id' => $request['budget_id']], [
                'department_id' => $request['department'],
                'allocation'    => $request['allocation'],
                'stop_date'     => $request['stop_date']
            ]);
            return response()->json([
                'message'   => 'Training buddet added successfully.',
                'status'    =>  200
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message'   =>  $e->getMessage(),
                'status'    =>  500
            ], 500);
        }
       
    }

    public function listBudget()
    {
        try{
            $budgets = TrainingPlanBudget::all();
            $departments = Department::all();
            return view('learningdev.budgets', ['budgets' => $budgets, 'departments' => $departments]);
        }catch(\Exception $e){
            abort(403);
        }
    }

    public function getBudget(string $id)
    {
        $data = TrainingPlanBudget::find($id);
        return  response()->json([
            'data'  => $data,
            'status'=> 200
        ]);
    }

    public function deleteBudget(string $id)
    {
        $budget = TrainingPlanBudget::find($id);
        $budget->delete();
        return redirect()->back()->with('message', 'Budget deleted successfully');
    }

    public function approveTrainingPlan(string $id)
    {
        $plan = TrainingPlan::findOrFail($id);
        $plan->is_approved = "1";
        $plan->update();
        return redirect()->back()->with('message', 'Training plan approved successfully');
    }

    public function rejectTrainingPlan(string $id)
    {
        $plan = TrainingPlan::findOrFail($id);
        $plan->active = "-1";
        $plan->update();
        return redirect()->back()->with('message', 'Training plan rejected successfully');
    }

    public function fetchGraphRecords($filter, $type = "gen"){
        if($type !== "user"){
            $trainings = UserTrainingPlan::whereYear('created_at', date('Y'))
                        ->where('status', $filter)
                        ->selectRaw('month(created_at) as month')
                        ->selectRaw('count(*) as count')
                        ->groupBy('month')
                        ->pluck('count', 'month')
                        ->toArray();
        }else{
            $trainings = UserTrainingPlan::whereYear('created_at', date('Y'))
                    ->where('status', $filter)
                    ->where('user_id', auth()->user()->id)
                    ->selectRaw('month(created_at) as month')
                    ->selectRaw('count(*) as count')
                    ->groupBy('month')
                    ->pluck('count', 'month')
                    ->toArray();
        }
        
        for($i = 1; $i <= 12; $i++){
            if(!isset($trainings[$i])){
                $trainings[$i] = 0;
            }
        } 
        ksort($trainings);
        return array_values($trainings);
    }

    public function fetchPieData($filter, $type = "gen"){
        if($type == "user"){
            return UserTrainingPlan::where('status', '=', $filter)
                            ->where('user_id', auth()->user()->id)
                            ->count();
        }else if($type == "gen"){
            return TrainingPlan::where('status', '=', $filter)->count();
        }else{
            return 0;
        }
    }

    public function search(Request $request)
    {
        $status    = $request->status;
        $mode      = $request->mode;
        $type      = $request->type;
        $toDate    = $request->to == null ? '2023-12-30' : $request->to;
        $fromDate  = $request->from  == null ? '2023-01-01' : $request->to;
        $trainings = null;
        
        if($status || $mode || $type  || $toDate || $fromDate){
            $trainings = TrainingPlan::when($mode && $type && $status, function ($query) use ($mode, $type, $status, $toDate, $fromDate) {
                return $query->where('mode', $mode)
                    ->where('type', $type)
                    ->where('status', $status)
                    ->whereBetween('start_date', [$fromDate, $toDate]);
            })
            ->when($mode && $type && !$status, function ($query) use ($mode, $type, $toDate, $fromDate) {
                return $query->where('mode', $mode)
                    ->where('type', $type)
                    ->whereBetween('start_date', [$fromDate, $toDate]);
            })
            ->when(!$mode && $type && $status, function ($query) use ($type, $status, $toDate, $fromDate) {
                return $query->where('type', $type)
                    ->where('status', $status)
                    ->whereBetween('start_date', [$fromDate, $toDate]);
            })
            ->when($mode && !$type && $status, function ($query) use ($mode, $status, $toDate, $fromDate) {
                return $query->where('status', $status)
                    ->where('mode', $mode)
                    ->whereBetween('start_date', [$fromDate, $toDate]);
            })
            ->when($type && !$mode && !$status, function ($query) use ($type, $toDate, $fromDate) {
                return $query->where('type', $type)
                ->whereBetween('start_date', [$fromDate, $toDate]);
            })
            ->when(!$type && !$mode && $status, function ($query) use ($status, $toDate, $fromDate) {
                return $query->where('status', $status)
                ->whereBetween('start_date', [$fromDate, $toDate]);
            })
            ->when(!$type && $mode && !$status, function ($query) use ($mode, $fromDate, $toDate) {
                return $query->where('mode', $mode)
                ->whereBetween('start_date', [$fromDate, $toDate]);
            })
            ->get();
        }   

        return $this->showTrainings($trainings);
    }

    public function evaluateUser(string $id)
    {
        return view('learningdev.userevaluation', ['id_user' => $id]);
    }

    public function submitEvaluation(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'training_plan_id'     => ['required'],
            'user_id'              => ['required'],
            'is_support_learning'  => ['required'],
            'is_length_sufficient' => ['required'],
            'is_participation_encouraged' => ['required'],
            'is_opportunities_provided' => ['required'],
            'is_appropriate_level' => ['required'],
            'is_quiz_helped' => ['required'],
            'is_learning_aids' => ['required'],
            'is_equipment_working' => ['required'],
            'is_instructor_knowledgeable' => ['required'],
            'is_instructor_responsive' => ['required'],
            'is_instructor_interesting' => ['required'],
            'is_instructor_participatory' => ['required'],
            'is_faccilities_suitable' => ['required'],
            'is_location_easy' => ['required'],
            'is_training_relevant' => ['required'],
            'is_practical_exercises_good' => ['required'],
            'is_training_style_more' => ['required'],
            'is_course_satisifying' => ['required'],
            'is_instructor_satisfying' => ['required'],
            'is_environment_satisfying' => ['required'],
            'comments' => ['nullable']
        ]);

        if($validator->fails()){
            return redirect()->back()->with('message', 'kindly fill all fields');
        }

        TrainingFeedback::updateOrCreate(['training_plan_id' => $request['training_plan_id']], $request->all());
        return redirect()->back()->with('message', 'Evaluation submitted successfully.');
    }

    public function getCompletionPercentage($start_date, $end_date)
    {
        $start_stamp = strtotime($start_date);
        $end_stamp   = strtotime($end_date);
        $today_stamp = strtotime("now");

        if($today_stamp <= $start_stamp){
            return 0;
        }else if($today_stamp >= $end_stamp){
            return 100;
        }else{
            $total_duration = $end_stamp - $start_stamp;
            $completed_duration = $today_stamp - $start_stamp;
            $completion_rate = ($completed_duration/$total_duration) * 100;
            return round($completion_rate, 2);
        }
    }

    public function showReportData(string $id)
    {
        $results                     = $this->extractPieData($id);
        $is_support_learning         = array_values($results["is_support_learning"]);
        $is_length_sufficient        = array_values($results["is_length_sufficient"]);
        $is_participation_encouraged = array_values($results["is_participation_encouraged"]);
        $is_opportunities_provided   = array_values($results["is_opportunities_provided"]);
        $is_appropriate_level        = array_values($results["is_appropriate_level"]);
        $is_quiz_helped              = array_values($results["is_quiz_helped"]);
        $is_learning_aids            = array_values($results["is_learning_aids"]);
        $is_equipment_working        = array_values($results["is_equipment_working"]);
        $is_instructor_knowledgeable = array_values($results["is_instructor_knowledgeable"]);
        $is_instructor_responsive    = array_values($results["is_instructor_responsive"]);
        $is_content_presented        = array_values($results["is_content_presented"]);
        $is_instructor_interesting   = array_values($results["is_instructor_interesting"]);
        $is_instructor_participatory = array_values($results["is_instructor_participatory"]);
        $is_faccilities_suitable     = array_values($results["is_faccilities_suitable"]);
        $is_location_easy            = array_values($results["is_location_easy"]);
        $is_training_relevant        = array_values($results["is_training_relevant"]);
        $is_practical_exercises_good  = array_values($results["is_practical_exercises_good"]);
        $is_training_style_more      = array_values($results["is_training_style_more"]);
        $is_course_satisfying        = array_values($results["is_course_satisifying"]);
        $is_instructor_satisfying    = array_values($results["is_instructor_satisfying"]);
        $is_environment_satisfying   = array_values($results["is_environment_satisfying"]);
        return view('learningdev.reports', ['is_support_learning' => $is_support_learning, 'is_length_sufficient' => $is_length_sufficient, 'is_participation_encouraged' => $is_participation_encouraged, 'is_opportunities_provided' => $is_opportunities_provided, 'is_appropriate_level' => $is_appropriate_level, 'is_quiz_helped' => $is_quiz_helped, 'is_learning_aids' => $is_learning_aids, 'is_equipment_working' => $is_equipment_working, 'is_instructor_knowledgeable' => $is_instructor_knowledgeable, 'is_instructor_responsive' => $is_instructor_responsive, 'is_content_presented' => $is_content_presented, 'is_instructor_interesting' => $is_instructor_interesting, 'is_instructor_participatory' => $is_instructor_participatory, 'is_faccilities_suitable' => $is_faccilities_suitable, 'is_location_easy' => $is_location_easy, 'is_training_relevant' => $is_training_relevant, 'is_practical_exercises_good' => $is_practical_exercises_good, 'is_training_style_more' => $is_training_style_more, 'is_course_satisfying' => $is_course_satisfying, 'is_instructor_satisfying' => $is_instructor_satisfying, 'is_environment_satisfying' => $is_environment_satisfying]);
        
    }

  
    public function extractPieData($plan_id)
    {
        $feedbacks = TrainingFeedback::where('training_plan_id', '=', $plan_id)->get();
        $data = [];
        $filters = Schema::getColumnListing('training_feedbacks');

        foreach ($filters as $filter) {
            if ($filter !== "id" && $filter !== "training_plan_id" && $filter !== "user_id" && $filter !== "comments" && $filter !== "created_at" && $filter !== "updated_at") {
                $data[$filter] = [
                    'excellent' => 0,
                    'good' => 0,
                    'average' => 0,
                    'poor' => 0,
                    'unsatisfactory' => 0
                ];
            }
        }

        foreach ($feedbacks as $feedback) {
            foreach ($data as $filter => &$filterData) {
                $score = $feedback->$filter;

                if ($score >= 0 && $score <= 40) {
                    $filterData['unsatisfactory']++;
                } elseif ($score >= 41 && $score <= 49) {
                    $filterData['poor']++;
                } elseif ($score >= 50 && $score <= 59) {
                    $filterData['average']++;
                } elseif ($score >= 60 && $score <= 84) {
                    $filterData['good']++;
                } elseif ($score >= 85 && $score <= 100) {
                    $filterData['excellent']++;
                }
            }
        }

        return $data;
    }

    public function showUdemyDashboard()
    {
        if(auth()->user()->role->id == '1' || auth()->user()->role->id == '2'){
            $user_count    = UdemyActivity::count();
            $learning_path = LearningPath::count();
        }else if(auth()->user()->role->name == 'Employee'){
            $user_count    = UdemyActivity::where('user_email', auth()->user()->email)->count();
            $learning_path = LearningPath::where('editor_email', auth()->user()->email)->count();
        }
        return view('learningdev.udemy.index', ['users' => $user_count, 'path_count' => $learning_path]);
    }

    public function showUdemyUsers()
    {
        if(auth()->user()->role->id == '1' || auth()->user()->role->id == '2'){
            $users = UdemyActivity::all();
        }else if(auth()->user()->role->name == 'Employee'){
            $users = UdemyActivity::where('user_email', auth()->user()->email)->get();
        }
        return view('learningdev.udemy.users', ['users' => $users]);
    }

    public function showUdemyActivities()
    {
        $users = UdemyActivity::all();
        return view('learningdev.udemy.users', ['users' => $users]);
    }

    public function showUdemyPaths()
    {
        if(auth()->user()->role->id == '1' || auth()->user()->role->id == '2'){
            $paths = LearningPath::with('learning_path_sections')->get();
        }else if(auth()->user()->role->name == 'Employee'){
            $paths = LearningPath::where('editor_email', auth()->user()->email)->with('learning_path_sections')->get();
        }
        return view('learningdev.udemy.path', ['paths' => $paths]);
    }

    public function exportTrainingData(Request $request)
    {
        return Excel::download(new TrainingsExport($request->arg), 'trainings.xlsx');
    }

    public function fetchEvaluationPie($filter){
        $data =  TrainingFeedback::where('created_at', date('Y'))
        ->where('status', $filter)
        ->where('user_id', auth()->user()->id)
        ->selectRaw('month(created_at) as month')
        ->selectRaw('count(*) as count')
        ->groupBy('month')
        ->pluck('count', 'month')
        ->toArray();
    }   

 
}