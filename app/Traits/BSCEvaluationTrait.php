<?php

namespace App\Traits;

use App\BehavioralSubMetric;
use App\BscMetric;
use App\BscSubMetric;
use App\BscMeasurementPeriod;
use App\BscWeight;
use App\Company;
use App\PerformanceDiscussionDetail;
use App\User;
use Auth;
use App\Department;
use App\Grade;
use App\GradeCategory;
use App\BscEvaluation;
use App\BscEvaluationDetail;
use App\Notifications\KPIsCreated;
use App\Notifications\KPIsAccepted;
use App\Notifications\BscEvaluationNotifyConcerned;
use App\BehavioralEvaluationDetail;
use App\QueryThread;
use Excel;
use App\BscDet;
use App\Traits\Micellenous;
use App\BscDetDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Notifications\NotifyManagerApprovedPerformanceDiscussion;

trait BSCEvaluationTrait
{
    use Micellenous;
    public $performance_grades = ["Below Expectation" => [0, 49], "Satisfies Most Expectation" => [50, 59], "Meets Expectation" => [60, 79], "Exceeds Expectation" => [80, 99],"Distinguished"=>[100,101]];

    public function processGet($route, Request $request)
    {
        switch ($route) {
            case 'get_weight':
                # code...
                return $this->getWeight($request);
                break;
            case 'get_measurement_period':
                # code...
                return $this->getMeasurementPeriod($request);
                break;
            case 'get_evaluation_details':
                # code...
                return $this->getEvaluationDetails($request);
                break;
            case 'delete_evaluation_detail':
                # code...
                return $this->deleteEvaluationDetail($request);
                break;
            case 'get_evaluation_details_sum':
                # code...
                return $this->getEvaluationDetailsSum($request);
                break;
            case 'get_evaluation_wcp':
                # code...
                return $this->getEvaluationWcp($request);
                break;
            case 'get_behavioral_evaluation_wcp':
                # code...
                return $this->getBehavioralEvaluationWcp($request);
                break;
            case 'get_evaluation':

                return $this->getEvaluation($request);
                break;
            case 'set_employee_kpi':

                return $this->setupEmployeeKPI($request);
                break;

            case 'get_evaluation_user_list':

                return $this->getEvaluationUserList($request);
                break;
            case 'my_evaluations':

                return $this->getMyEvaluations($request);
                break;
            case 'get_my_evaluation':

                return $this->viewMyEvaluation($request);
                break;
            case 'use_dept_template':

                return $this->useDeptTemplate($request);
                break;
            case 'accept_kpis':

                return $this->acceptRejectKPIs($request);
                break;
            case 'submit_kpis_for_review':

                return $this->submitKPIsForReview($request);
                break;
            case 'manager_approve':

                return $this->managerApprove($request);
                break;
            case 'employee_approve':

                return $this->employeeApprove($request);
                break;
            case 'hr':

                return $this->hrIndex($request);
                break;
            case 'get_hr_department_list':

                return $this->departmentList($request);
                break;
            case  'performanceDiscussion':
                return $this->getPerformanceDiscussion($request);
                break;
            case  'approvePerformanceDiscussion':
                return $this->approvePerformanceDiscussion($request);
                break;
            case  'submitPerformanceDiscussion':
                return $this->submitPerformanceDiscussion($request);
                break;
            case  'dept_user_list':
                return $this->departmentUserList($request);
                break;
            case 'get_hr_evaluation':

                return $this->getHREvaluation($request);
                break;

            case 'get_behavioral_evaluation_details':

                return $this->getBehavioralEvaluationDetails($request);
                break;
            case 'graph_report':

                return $this->graphChart($request);
                break;
            case 'bsc_mp_report':

                return $this->getBscMPReport($request);
                break;
            case 'ba_mp_report':

                return $this->getBAMPReport($request);
                break;
            case 'avg_report':

                return $this->getAvgMPReport($request);
                break;
            case 'dept_report':

                return $this->getDeptAvgMPReport($request);
                break;
            case 'excel_report':

                return $this->exportForBSCExcelReport($request);
                break;
            case 'get_metric_weight':

                return $this->getMetricWeight($request);
                break;
            case 'get_approvals':

                return $this->get_approvals($request);
                break;
            case 'individual_report':

                return $this->individual_report($request);
                break;
            case 'getSingleDiscussionAPI':
                return $this->getSingleDiscussionAPI($request);
                break;
            case 'mp_report':

                return $this->mp_report($request);
                break;



            default:
                # code...
                break;
        }

    }


    public function processPost(Request $request)
    {
        // try{
        switch ($request->type) {
            case 'get_evaluation':

                return $this->getEvaluation($request);
                break;
            case 'save_evaluation_detail':
                # code...
                return $this->saveEvaluationDetail($request);
                break;
            case 'saveDiscussionDetailAPI':
                # code...
                return $this->saveDiscussionDetailAPI($request);
                break;
            case 'save_employee_kpi_detail':
                # code...
                return $this->saveEmployeeKpi($request);
                break;
            case 'measurementperiod':
                # code...
                return $this->saveMeasurementPeriod($request);
                break;
            case 'save_evaluation_comment':
                # code...
                return $this->saveEvaluationComment($request);
                break;
            case 'import_emeasures':
                # code...
                return $this->importTemplate($request);
                break;
            case 'saveDiscussion':
                return $this->saveDiscussion($request);
            case 'saveDiscussionAPI':
                return $this->saveDiscussionAPI($request);
      
            case 'saveDiscussionDetail':
                return $this->saveDiscussionDetail($request);
                break;
            case 'save_behavioral_evaluation_detail':
                # code...
                return $this->saveBehavioralEvaluationDetail($request);
                break;
            case 'save_evaluation_approval':
                # code...
                return $this->saveEvaluationApproval($request);
                break;
            default:
                # code...
                break;
        }
        // }
        // catch(\Exception $ex){
        // 	return response()->json(['status'=>'error','message'=>$ex->getMessage()]);
        // }
    }


    public function get_approvals(Request $request)
    {
       

        $evaluations = BscEvaluation::where(function ($query) {
            $query->where(['manager_of_manager_id' => Auth::user()->id,
                'approval_status' => 'manager_of_manager']);
        })->orwhere(function ($query) {
            $query->where(['head_of_strategy_id' => Auth::user()->id,
                'approval_status' => 'head_of_strategy']);
        })->orwhere(function ($query) {
            $query->where(['head_of_strategy_id' => Auth::user()->id,
                'kpi_accepted' => 4]);
        })->orwhere(function ($query) {
            $query->where(['head_of_hr_id' => Auth::user()->id,
                'approval_status' => 'head_of_hr']);
        })->orwhere(function ($query) {
            $query->where(['head_of_hr_id' => Auth::user()->id,
            'kpi_accepted' => 3]);
        })->orWhere(function ($query) {
            $query->where([ 'kpi_submitted'=> 1, 'approval_status' => null,'manager_id' => Auth::user()->id ]);
    })->get();

        
        // dd($evaluations, Auth::user()->role->name);


        // the executive secetary should only be able to evaluate those under him/her
        // as a result the view of approvals returned should not contain any evaluations
        // as all his evals will be under performance management -evaluation
        if ( Auth::user()->role->name == 'ES'){

            return redirect('/bsc?operation=evaluation');


        }
       

        return view('bsc.approvals', compact('evaluations'));

    }

    public function saveEvaluationApproval(Request $request)
    {
        $evaluation = BscEvaluation::find($request->evaluation_id);
        $company = Company::find(companyId());
        switch ($request->approval_status) {
            // line manager begins process by changing kpi to employee
            // ...
            
            case 'employee':
//                change stage
                // if ($request->approval_type == 'approved') {
                    $evaluation->update(['approval_status' => 'appraisal', 'manager_approval_date' => date('Y-m-d'), 'manager_approval_comment' => $request->comment,
                        'manager_approval_approved'=>1
                    ]);
                     // notify line manager
                    $notificationMessage = $evaluation->user->name.' has completed evaluation';
                    $notificationUrl = "bsc/get_evaluation?employee=".$evaluation->user->id."&mp=".$evaluation->measurement_period->id ;

                    User::find($evaluation->manager_id)->notify((new BscEvaluationNotifyConcerned($evaluation, $notificationUrl, $notificationMessage)));
                    return 'success';
                // }
                //employee moves to appraisal
            case 'appraisal':
                $head_of_hr_id = $evaluation->measurement_period->head_of_hr_id;
//                change stage
              if ($request->approval_type == 'approved') {
                $hod_id = $request->hod_id;
                
                $evaluation->update(['approval_status' => 'hod', 'manager_approval_date' => date('Y-m-d'), 'manager_approval_comment' => $request->comment,
                    'manager_approval_approved'=>1, 'head_of_hr_id' => $evaluation->measurement_period->head_of_hr_id, 'manager_of_manager_id'=>$hod_id
                ]);
                // notify the hod
                $hod = User::find($hod_id);
                 // notify employee so employee moves to head of hr
                 $notificationMessage = User::find($evaluation->manager_id)->name.' (line manager) has evaluated you';
                 $notificationUrl = "bsc/get_evaluation?employee=".$evaluation->user->id."&mp=".$evaluation->measurement_period->id ;

                 $evaluation->user->notify((new BscEvaluationNotifyConcerned($evaluation, $notificationUrl, $notificationMessage)));
                 $hodMessage = 'Hello, '.$hod->name.'. '.$evaluation->user->name.' has been evaluated by their line manager.';
                
                //  notify head of hr
                User::find($evaluation->measurement_period->head_of_hr_id)->notify((new BscEvaluationNotifyConcerned($evaluation, $notificationUrl, $notificationMessage)));
                //  notify the hod
                $hod->email && filter_var($hod->email, FILTER_VALIDATE_EMAIL) !== false && $hod->notify((new BscEvaluationNotifyConcerned($evaluation, $notificationUrl, $hodMessage)));

                return 'to accepting';
            }else{
                $evaluation->update(['approval_status' => 'employee', 'manager_approval_comment' => $request->comment,
                    
                ]);
            
                 $notificationMessage = User::find($evaluation->manager_id)->name.' (line manager) has disapproved your evaluation';
                 $notificationUrl = "bsc/get_evaluation?employee=".$evaluation->user->id."&mp=".$evaluation->measurement_period->id ;

                 User::find($evaluation->manager_id)->notify((new BscEvaluationNotifyConcerned($evaluation, $notificationUrl, $notificationMessage)));
                return 'success';

            }

            case 'hod':
                $head_of_hr_id = $evaluation->measurement_period->head_of_hr_id;

              if ($request->approval_type == 'approved') {
                
                $evaluation->update(['approval_status' => 'head_of_hr', 'manager_approval_date' => date('Y-m-d'), 'manager_approval_comment' => $request->comment,
                    'manager_approval_approved'=>1, 'head_of_hr_id' => $evaluation->measurement_period->head_of_hr_id,
                ]);
                // notify the hod
                 // notify employee so employee moves to head of hr
                 $notificationMessage = User::find($evaluation->manager_id)->name.' (line manager) has evaluated you';
                 $notificationUrl = "bsc/get_evaluation?employee=".$evaluation->user->id."&mp=".$evaluation->measurement_period->id ;

                 $evaluation->user->notify((new BscEvaluationNotifyConcerned($evaluation, $notificationUrl, $notificationMessage)));
                
                
                //  notify head of hr
                User::find($evaluation->measurement_period->head_of_hr_id)->notify((new BscEvaluationNotifyConcerned($evaluation, $notificationUrl, $notificationMessage)));

   

                return 'to accepting';
            }else{
                $evaluation->update(['approval_status' => 'appraisal', 'manager_approval_comment' => $request->comment,
                    
                ]);
            
                 $notificationMessage = User::find($evaluation->manager_id)->name.' (hod) has disapproved your evaluation';
                 $notificationUrl = "bsc/get_evaluation?employee=".$evaluation->user->id."&mp=".$evaluation->measurement_period->id ;

                 User::find($evaluation->manager_id)->notify((new BscEvaluationNotifyConcerned($evaluation, $notificationUrl, $notificationMessage)));
                return 'success';

            }
            
       
        

            case 'head_of_hr':
                if ($request->approval_type == 'approved') {
                    $evaluation->update(['head_of_hr_approval_comment' => $request->comment,
                        'head_of_hr_approved_date' => date('Y-m-d'),
                        'approval_status' => 'approved', 'head_of_hr_approved' => 1]);
                    $notificationMessage = Auth::user()->name.' (head of hr) has approved your evaluation';
                    $notificationUrl = "bsc/get_evaluation?employee=".$evaluation->user->id."&mp=".$evaluation->measurement_period->id ;

                    $evaluation->user->notify((new BscEvaluationNotifyConcerned($evaluation, $notificationUrl, $notificationMessage)));
                    return 'to approved';
                } else {
                    $evaluation->update(['head_of_hr_approval_comment' => $request->comment,
                        'head_of_hr_approved_date' => date('Y-m-d'),
                        'approval_status' => 'appraisal', 'head_of_hr_approved' => 0]);

                        // notify manager manager
                    $notificationMessage = Auth::user()->name.' (head of hr) has disapproved the evaluation of  '.$evaluation->user->name;
                    $notificationUrl = "bsc/get_evaluation?employee=".$evaluation->user->id."&mp=".$evaluation->measurement_period->id ;

                    User::find($evaluation->measurement_period->head_of_strategy_id)->notify((new BscEvaluationNotifyConcerned($evaluation, $notificationUrl, $notificationMessage)));
                    return 'success';
                }

            default:
                return 1;
        }


    }

    public function getEvaluationUserList(Request $request)
    {

        $mp = BscMeasurementPeriod::find($request->mp);
        $manager = Auth::user();
        $operation=request()->operation;
        $employeeStatus = null;
        if($request->mptype == 'confirmed'){
            $employeeStatus = 1;
        }
        if($request->mptype == 'probation'){
            $employeeStatus = 0;
        }
        $bsms = \App\BehavioralSubMetric::where(['status' => 1, 'company_id' => companyId()])->get();
        $users = User::whereHas('managers', function ($query) use ($manager) {
            $query->where('manager_id', $manager->id);
        })->where('hiredate', '<=', $mp->to)->where('status', '=', $employeeStatus)->get();
        foreach($users as $user){
            $evaluation = BscEvaluation::where(['user_id' => $user->id, 'bsc_measurement_period_id' => $mp->id])->with(['behavioral_evaluation_details'])->first();

            if ($evaluation) {
                foreach ($bsms as $bsm) {
                    $evaluation_detail = BehavioralEvaluationDetail::firstOrCreate(['bsc_evaluation_id' => $evaluation->id, 'behavioral_sub_metric_id' => $bsm->id]);
                }
                $metrics = BscMetric::all();
                foreach ($bsms as $bsm) {

                    $evaluation_detail = BehavioralEvaluationDetail::firstOrCreate(['bsc_evaluation_id' => $evaluation->id, 'behavioral_sub_metric_id' => $bsm->id]);
                }




            } else {


                $evaluation = BscEvaluation::create(['user_id' => $user->id, 'bsc_measurement_period_id' => $mp->id, 'department_id' => $user->job->department_id, 'company_id' => companyId(), 'performance_category_id' => 0]);
                foreach ($bsms as $bsm) {

                    $evaluation_detail = BehavioralEvaluationDetail::firstOrCreate(['bsc_evaluation_id' => $evaluation->id, 'behavioral_sub_metric_id' => $bsm->id]);
                }
                $metrics = BscMetric::all();




            }

        }
        // dd($users);
        return view('bsc.users_list', compact('users', 'mp','operation'));

    }

    public function getMyEvaluations(Request $request)
    {

        // $evaluations = BscEvaluation::where(['user_id' => Auth::user()->id])->get();
        // return view('bsc.user_index', compact('evaluations'));

        $mps=BscMeasurementPeriod::all();
        // the latest measuremnt period
        $mp=$mps[count($mps) -1];


        $evaluations=BscEvaluation::where(['user_id'=>Auth::user()->id])->get();
        if((count($evaluations) > 0)){
        

            
        }else{
            // dd($request, companyId(),$mp);
            $evaluation=BscEvaluation::create(['user_id'=>Auth::user()->id,'bsc_measurement_period_id'=>$mp->id,'department_id'=>Auth::user()->job->department_id,'performance_category_id'=>0,'company_id'=>companyId()]);
            $evaluations = array($evaluation);
        }
        
        return view('bsc.user_index',compact('evaluations'));
    }

    public function viewMyEvaluation(Request $request)
    {
        $evaluation = BscEvaluation::find($request->evaluation);
        $metrics = BscMetric::all();
        $user = $evaluation->user;
        if ($evaluation->user_id == Auth::user()->id) {
            if ($evaluation) {

                return view('bsc.view_evaluation', compact('evaluation', 'metrics', 'user'));
            } else {
                $request->session()->flash('error', 'User does not have a grade category or a department');
                return redirect()->back();
            }
        } else {
            $request->session()->flash('error', 'You cannot view this evaluation');
            return redirect()->back();
        }

    }

    public function acceptRejectKPIs(Request $request)
    {
        $evaluation = BscEvaluation::find($request->bsc_evaluation_id);
        $notificationVal =$request->action == 1 ? 'accepted' : 'rejected';

        //employeee = 5
        // line manager = null
        // make manager capable instead of user
        if ($evaluation && $request->user_id == Auth::user()->id) {
            $evaluation->update(['kpi_accepted' => $request->action , 'kpi_submitted' => $request->action == 1 ? 1 : 0, 'date_kpi_accepted' => date('Y-m-d'),'head_of_strategy_id' => $evaluation->measurement_period->head_of_strategy_id,
                'approval_status' =>$request->action == 1 ? 'employee' : null, 'manager_id' => $request->line_manager_id]);
            
            $notificationMessage = 'Your Kpis have been '.$notificationVal.' by your line manager.';
            $notificationUrl = $request->action == 1 ? "bsc/get_evaluation?employee=".$evaluation->user->id."&mp=".$evaluation->measurement_period->id : "/bsc/set_employee_kpi?employee=".$evaluation->user->id."&mp=".$evaluation->measurement_period->id;

            $evaluation->user->notify((new KPIsAccepted($evaluation, $notificationUrl, $notificationMessage,$request->action)));
      
            return 'success';
        }
      
        return 'error';

    }

    public function submitKPIsForReview(Request $request)
    {
        $evaluation = BscEvaluation::find($request->bsc_evaluation_id);

        if ($evaluation->user_id == Auth::user()->id) {
            // kpi_submitted => 1 => the employee has to be able to submit
            // nd not accept kpi => was removed  => 'kpi_accepted' => 1,
            // $evaluation->update(['kpi_submitted' => 1, 'kpi_submitted_date' => date('Y-m-d'), 'manager_id' => Auth::user()->id]);
            
            $evaluation->update([ 'date_kpi_accepted' => date('Y-m-d'), 'kpi_submitted' => 1, 'kpi_submitted_date' => date('Y-m-d'), 'evaluator_id' => Auth::user()->line_manager_id, 'manager_id' => Auth::user()->line_manager_id]);
            $evaluation->user->plmanager->notify((new KPIsCreated($evaluation, "bsc/get_evaluation?employee=".$evaluation->user->id."&mp=".$evaluation->measurement_period->id)));
            return 'success';
        } else {
            $evaluation->update(['kpi_submitted' => 1, 'kpi_submitted_date' => date('Y-m-d'), 'manager_id' => Auth::user()->id]);
            $evaluation->user->notify((new KPIsCreated($evaluation, "bsc/get_my_evaluation?evaluation=$evaluation->id")));
            return 'success';
        }
    }

    public function managerApprove(Request $request)
    {
        $evaluation = BscEvaluation::find($request->bsc_evaluation_id);
        if ($evaluation) {
            $evaluation->update(['manager_approved' => 1, 'date_manager_approved' => date('Y-m-d'), 'evaluator_id' => Auth::user()->id]);
            return 'success';
        }
        return 'error';

    }

    public function employeeApprove(Request $request)
    {
        $evaluation = BscEvaluation::find($request->bsc_evaluation_id);
        if ($evaluation && $evaluation->user_id == Auth::user()->id) {
            $evaluation->update(['employee_approved' => 1, 'date_employee_approved' => date('Y-m-d')]);
            return 'success';
        }
        return 'error';

    }

    public function setupEmployeeKPI(Request $request)
    {
        $user = User::find($request->employee);
        $mp = BscMeasurementPeriod::find($request->mp);
        $bsms = \App\BehavioralSubMetric::where(['status' => 1, 'company_id' => companyId()])->get();
        $templates = BscDet::where('company_id', companyId())->get();
        $operation = 'evaluate';
        $grade = Grade::find($user->grade_id);
        if ($grade && $user->job) {
            if ($user->job) {

                $evaluation = BscEvaluation::where(['user_id' => $user->id, 'bsc_measurement_period_id' => $mp->id])->with(['behavioral_evaluation_details'])->first();

                if ($evaluation) {
                    foreach ($bsms as $bsm) {
                        $evaluation_detail = BehavioralEvaluationDetail::firstOrCreate(['bsc_evaluation_id' => $evaluation->id, 'behavioral_sub_metric_id' => $bsm->id]);
                    }
                    $metrics = BscMetric::all();


                    return view('bsc.setup_employee_kpi', compact('user', 'operation', 'evaluation', 'metrics', 'bsms', 'templates'));

                } else {


                    $evaluation = BscEvaluation::create(['user_id' => $user->id, 'bsc_measurement_period_id' => $mp->id, 'department_id' => $user->job->department_id, 'company_id' => companyId(), 'performance_category_id' => 0]);
                    foreach ($bsms as $bsm) {

                        $evaluation_detail = BehavioralEvaluationDetail::firstOrCreate(['bsc_evaluation_id' => $evaluation->id, 'behavioral_sub_metric_id' => $bsm->id]);
                    }
                    $metrics = BscMetric::all();

                    return view('bsc.setup_employee_kpi', compact('user', 'operation', 'evaluation', 'metrics', 'bsms', 'templates'));


                }

            } else {
                $request->session()->flash('error', 'User does not have a department ');
            }


        } elseif (!$grade) {
            $request->session()->flash('error', 'User does not have a grade  or job ');
            return redirect()->back();
        } else {
            return redirect()->back();

        }


    }

    public function saveEmployeeKpi(Request $request)
    {
        $metric = BscMetric::find($request->metric_id);
        if ($metric->has_penalties == 1) {
            $is_penalty = 1;
            if ($request->achievement > 0) {
                $score = $request->weight;
                $final_score = $request->weight;

            } else {
                $score = 0;
                $final_score = 0;
            }
        } else {
            $is_penalty = 0;

        }

        $evaluation_detail = BscEvaluationDetail::updateOrCreate(['id' => $request->id], ['bsc_evaluation_id' => $request->bsc_evaluation_id, 'metric_id' => $request->metric_id,
            'focus' => $request->focus, 'objective' => $request->objective,
            'key_deliverable' => $request->key_deliverable, 'measure_of_success' => $request->measure_of_success,
            'means_of_verification' => $request->means_of_verification, 'weight' => $request->weight, 'is_penalty' => $is_penalty]);

        $weightSum = BscEvaluationDetail::where(['bsc_evaluation_id' => $evaluation_detail->bsc_evaluation_id, 'is_penalty' => 0])->sum('weight');
        $evaluation = BscEvaluation::updateorCreate(['id' => $evaluation_detail->bsc_evaluation_id], ['weight_sum' => $weightSum,
            'kpi_submitted_date' => date('Y-m-d'), 'kpi_submitted' => 0,
            'manager_approval_approved' => 0, 'manager_approval_date' => date('Y-m-d'),
            'kpi_accepted' => 0, 'kpi_accepted_date' => date('Y-m-d'), 'company_id' => companyId()]);

//        $this->notifyUserKpiChange($evaluation->user_id, $evaluation_detail->bsc_evaluation_id);

        return $evaluation_detail;


    }

    public function getEvaluation(Request $request)
    {

        $user = User::find($request->employee);
        $mp = BscMeasurementPeriod::find($request->mp);
        $bsms = \App\BehavioralSubMetric::where(['status' => 1, 'company_id' => companyId()])->get();
        $templates = BscDet::where('company_id', companyId())->get();
        $userQuery = QueryThread::where('queried_user_id',$request->employee)->get();
        $operation = 'evaluate';
        $grade = Grade::find($user->grade_id);
        $departments = Department::all();
        $departments = $departments->filter(function (Department $value, int $key) {
            return $value->manager !== null;
        });

        if ($grade && $user->job) {
            if ($user->job) {

                $evaluation = BscEvaluation::where(['user_id' => $user->id, 'bsc_measurement_period_id' => $mp->id])->with(['behavioral_evaluation_details'])->first();

                if ($evaluation) {
                    foreach ($bsms as $bsm) {
                        $evaluation_detail = BehavioralEvaluationDetail::firstOrCreate(['bsc_evaluation_id' => $evaluation->id, 'behavioral_sub_metric_id' => $bsm->id]);
                    }
                    $metrics = BscMetric::all();
                    foreach ($bsms as $bsm) {

                        $evaluation_detail = BehavioralEvaluationDetail::firstOrCreate(['bsc_evaluation_id' => $evaluation->id, 'behavioral_sub_metric_id' => $bsm->id]);
                    }
                    // probation fix
                    // $user->underProbation = $user->status === '0' ? 'true' : 'false';
                    // in view instead
                    // dd($evaluation, $user->underProbation, $user, $evaluation->measurement_period->head_of_strategy_id);
                    // dd($evaluation);

                    

                    return view('bsc.evaluation', compact('user','departments', 'operation', 'evaluation', 'metrics', 'bsms', 'templates','userQuery'));


                } else {


                    $evaluation = BscEvaluation::create(['user_id' => $user->id, 'bsc_measurement_period_id' => $mp->id, 'department_id' => $user->job->department_id, 'company_id' => companyId(), 'performance_category_id' => 0]);
                    foreach ($bsms as $bsm) {

                        $evaluation_detail = BehavioralEvaluationDetail::firstOrCreate(['bsc_evaluation_id' => $evaluation->id, 'behavioral_sub_metric_id' => $bsm->id]);
                    }
                    $metrics = BscMetric::all();
                    // dd($evaluation);


                    return view('bsc.evaluation', compact('user','departments', 'operation', 'evaluation', 'metrics', 'bsms', 'templates','userQuery'));


                }

            } else {
                $request->session()->flash('error', 'User does not have a department ');
            }


        } elseif (!$grade) {
            $request->session()->flash('error', 'User does not have a grade  or job ');
            return redirect()->back();
        } else {
            return redirect()->back();

        }


    }

    public function saveEvaluationDetail(Request $request)
    {
        $metric = BscMetric::find($request->metric_id);
        if ($metric->has_penalties == 1) {
            $is_penalty = 1;


        } else {
            $is_penalty = 0;


        }
        $evaluation = BscEvaluation::find($request->bsc_evaluation_id);
        if($evaluation->status=='appraisal'){
            $score=$request->manager_assessment;
        }else{
            $score=$request->score;
        }
      

        $evaluation_detail = BscEvaluationDetail::updateOrCreate(['id' => $request->id], ['bsc_evaluation_id' => $request->bsc_evaluation_id, 'metric_id' => $request->metric_id,
            'self_assessment' => $request->self_assessment,'employee_comment'=>$request->employee_comment, 'manager_assessment' => $request->manager_assessment,
            'score'=>$score,'justification_of_rating' => $request->justification_of_rating,
            'accept_reject' => $request->accept_reject, 'manager_of_manager_assessment' => $request->manager_of_manager_assessment, 'is_penalty' => $is_penalty]);

        $scorecardSum = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'is_penalty' => 0])->sum('score');
        $selfScorecardSum = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'is_penalty' => 0])->sum('self_assessment');
        $penaltyScoreSum = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'is_penalty' => 1])->sum('score');
        $weightSum = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'is_penalty' => 0])->sum('weight');

      
        $scorecard_percentage = $evaluation->measurement_period->scorecard_percentage;
        $hasDispute = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'accept_reject' => 'reject'])->count();
        $is_disputed=$hasDispute>0?1 : 0;
        $evaluation->update(['scorecard_score' => $scorecardSum, 'scorecard_percentage' => (($scorecardSum / $weightSum) * ($scorecard_percentage)),
    'scorecard_self_score'=>(($selfScorecardSum / $weightSum) * ($scorecard_percentage)),
            'weight_sum' => $weightSum, 'penalty_score' => $penaltyScoreSum,'is_disputed'=>$is_disputed]);


        return $evaluation_detail;


    }

    public function importTemplate(Request $request)
    {
        $document = $request->file('template');
        $evaluation = BscEvaluation::find($request->evaluation_id);


        if ($request->hasFile('template')) {

            $datas = \Excel::load($request->file('template')->getrealPath(), function ($reader) {
                $reader->noHeading()->skipRows(1);
            })->get();

            foreach ($datas[0] as $data) {

                if ($data[0]) {
                    $metric = BscMetric::where('name', $data[0])->first();
                    $is_penalty = 0;
                    if ($metric->has_penalties == 1) {
                        $is_penalty = 1;
                    }
                    $det_detail = BscEvaluationDetail::create(['bsc_evaluation_id' => $request->evaluation_id, 'metric_id' => $metric->id,
                        'focus' => $data[1],
                        'objective' => $data[2], 'key_deliverable' => $data[3], 'measure_of_success' => $data[4],
                        'means_of_verification' => $data[5], 'weight' => $data[6] * 100, 'is_penalty' => $is_penalty,]);


                }

            }
            $weightSum = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->evaluation_id, 'is_penalty' => 0])->sum('weight');
            $evaluation = BscEvaluation::find($request->evaluation_id);
            $evaluation->update(['weight_sum' => $weightSum]);


            return 'success';
        }

    }

    public function getEvaluationDetails(Request $request)
    {
        return $evaluation_details = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'metric_id' => $request->metric_id])->get();

    }

    public function useDeptTemplate(Request $request)
    {
        $evaluation = BscEvaluation::find($request->bsc_evaluation_id);
        $det = BscDet::find($request->det_id);
        if ($evaluation && $det) {

            foreach ($det->details as $detail) {
                $evaluation->evaluation_details()->create(['metric_id' => $detail->metric_id, 'business_goal' => $detail->business_goal, 'is_penalty' => $detail->is_penalty, 'performance_metric_description' => $detail->performance_metric_description, 'target' => $detail->target, 'weight' => $detail->weight]);
            }
            return 'success';
        }

    }
    public function getBehavioralEvaluationWcp(Request $request)
    {
        $evaluation = BscEvaluation::find($request->bsc_evaluation_id);
        $behavioral_percentage = intval($evaluation->measurement_period->behavioral_percentage);
        $behavioralSum = BehavioralEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id])->sum('manager_assessment');
        $weightSum=BehavioralSubMetric::where(['status'=>1])->sum('weighting');
        $evaluation->update(['behavioral_score' => $behavioralSum, 'behavioral_percentage' => (($behavioralSum / $weightSum) * ($behavioral_percentage)),
            'weight_sum' => $weightSum]);

        return ['evaluation' => $evaluation];

    }

    public function getEvaluationWcp(Request $request)
    {
        $evaluation = BscEvaluation::find($request->bsc_evaluation_id);
        $scorecard_percentage = intval($evaluation->measurement_period->scorecard_percentage);
        $scorecardSum = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'is_penalty' => 0])->sum('manager_assessment');
        $penaltyScoreSum = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'is_penalty' => 1])->sum('manager_assessment');
        $weightSum = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'is_penalty' => 0])->sum('weight');
        $metricWeight = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'metric_id' => $request->metric_id])->sum('weight');
        $evaluation->update(['scorecard_score' => $scorecardSum, 'scorecard_percentage' => (($scorecardSum / $weightSum) * ($scorecard_percentage)),
            'weight_sum' => $weightSum, 'penalty_score' => $penaltyScoreSum]);

        return ['evaluation' => $evaluation, 'remark' => $this->calc_Performance($evaluation->score), 'metric_weight' => $metricWeight];

    }

    public function getMetricWeight(Request $request)
    {
        $evaluation = BscEvaluation::find($request->evaluation_id);
        return $weightSum = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->evaluation_id, 'metric_id' => $request->metric_id])->sum('weight');


    }

    public function saveEvaluationComment(Request $request)
    {
        $evaluation = BscEvaluation::find($request->bsc_evaluation_id);
        $evaluation->update(['employee_strength' => $request->employee_strength,
            'employee_developmental_area' => $request->employee_developmental_area,
            'special_achievement' => $request->special_achievement,
            'manager_approval_comment' => $request->manager_approval_comment]);
        return 'success';
    }

    public function deleteEvaluationDetail(Request $request)
    {
        $evaluation_detail = BscEvaluationDetail::find($request->id);
        $evaluation_detail->delete();

    }

    public function getEvaluationDetailsSum(Request $request)
    {
        return $sum = BscEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'metric_id' => $request->metric_id])->sum('weighting');
    }

    public function calc_Performance($summed_performance)
    {
        if ($summed_performance <= 1.95) {
            return "Poor Performance";
        } elseif ($summed_performance <= 2.45) {
            return "Below Expectation";
        } elseif ($summed_performance >= 3.5) {
            return "Exceeds Expectation";
        } elseif ($summed_performance <= 3.45) {
            return "Meets Expectation";
        } else {
            return "";
        }
    }

    public function weighted_contribution(Request $request)
    {
        $weighing = $request->weighing;
        $calc_result_achieved = $this->calc_result_achieved($request);
        return $weighing * $calc_result_achieved;
    }

    public function final_score(Request $request)
    {

        if ($request->achievement > $request->target) {
            return $request->weight;
        }
        return $final_score = ($request->achievement / $request->target) * $request->weight;
    }

    public function calc_result_achieved(Request $request)
    {


        if ($request->achievement > $request->target) {
            return 100;
        }

        return $score = ($request->achievement / $request->target) * 100;


    }

    public function departmentList(Request $request)
    {
        $company_id = companyId();
        $mp = BscMeasurementPeriod::find($request->mp_id);
        if ($company_id == 0) {
            $departments = Department::paginate(10);
        } else {
            $departments = Department::where('company_id', $company_id)->get();
        }

        return view('bsc.department_list', compact('departments', 'mp'));
    }

    public function measurementPeriodDepartmentUsers(Request $request)
    {
        $company_id = companyId();
        if ($company_id == 0) {
            $departments = Department::paginate(10);
        } else {
            $departments = Department::where('company_id', $company_id)->get();
        }

        return view('bsc.department_list', compact('departments'));
    }


    public function getPerformanceDiscussion(Request $request)
    {
        $discussions = \App\PerformanceDiscussion::where('evaluation_id', $request->evaluation_id)->get();

        return view('bsc.ajax.performanceDiscussion', compact('discussions'));
    }

    public function getPerformanceDiscussionDetail(Request $request)
    {
        return $discussion_detail = PerformanceDiscussionDetail::where('id', $request->performance_discussion_detail_id)->get()->with('evaluation_detail');
//        $discussions=\App\PerformanceDiscussion::where('evaluation_id',$request->evaluation_id)->get();
        return view('bsc.ajax.performanceDiscussion', compact('discussions'));
    }
    public function approvePerformanceDiscussion(Request $request){
        $discussion=\App\PerformanceDiscussion::find($request->discussion_id);
        $reason=$request->type==2?$request->reason:'';
        $discussion->update(['line_manager_approved'=>$request->type,'line_manager_approval_date'=>date('Y-m-d'),'rejection_reason'=>$reason]);

//        $discussion->bscevaluation->evaluator->notify( new NotifyManagerApprovedPerformanceDiscussion("bsc/get_evaluation?employee={$discussion->bscevaluation->user->id}&mp={$discussion->bscevaluation->bsc_measurement_period_id}",$discussion->bscevaluation->user->name,$request->type));
//        if($request->type==1){
//            $this->nofityHrAdminSaveDiscussion($discussion);
//        }

        return 'success';
    }
    public function submitPerformanceDiscussion(Request $request){
        $discussion=\App\PerformanceDiscussion::find($request->discussion_id);

        $discussion->update(['employee_submitted'=>1,'employee_submission_date'=>date('Y-m-d')]);

        $discussion->bscevaluation->evaluator->notify( new NotifyManagerApprovedPerformanceDiscussion("bsc/get_evaluation?employee={$discussion->bscevaluation->user->id}&mp={$discussion->bscevaluation->bsc_measurement_period_id}",$discussion->bscevaluation->user->name,$request->type));
//        if($request->type==1){
//            $this->nofityHrAdminSaveDiscussion($discussion);
//        }

        return 'success';
    }

    public function getSingleDiscussionAPI(Request $request)
    {
        
        
        $saveDiscussion = \App\PerformanceDiscussion::where(['participant_id'=> $request->participantId, 'evaluation_id'=>$request->evaluationId])->first();
        
      
        
        return response()->json(['success' => true, 'message' => 'Performance Discussion Succsessfully retrieved!', 'data'=> $saveDiscussion]);

    }
    public function saveDiscussionAPI(Request $request)
    {
        
        
        $saveDiscussion = \App\PerformanceDiscussion::updateOrCreate(['participant_id'=> $request->participantId, 'evaluation_id'=>$request->evaluationId],['title' => $request->title, 'discussion' => $request->discussion ]);
        $evaluation = BscEvaluation::find($request->evaluationId);
      

        foreach ($evaluation->evaluation_details as $detail) {
           $detail= PerformanceDiscussionDetail::firstOrCreate(['performance_discussion_id' => $saveDiscussion->id, 'evaluation_detail_id' => $detail->id,
            ]);
        }
        
        return response()->json(['success' => true, 'message' => 'Performance Discussion Succsessfully Saved', 'data'=> $saveDiscussion]);

    }
    // public function saveDiscussion(Request $request)
    // {
    //     // could become a potential problem better to use $request->id in the where clause, but this has its own importance
    //     $saveDiscussion = \App\PerformanceDiscussion::updateOrCreate(['title' => $request->title, 'discussion' => $request->discussion], $request->except('_token'));
    //     $evaluation = BscEvaluation::find($request->evaluation_id);
    //     foreach ($evaluation->evaluation_details as $detail) {
    //        $detail= PerformanceDiscussionDetail::firstOrCreate(['performance_discussion_id' => $saveDiscussion->id, 'evaluation_detail_id' => $detail->id,
    //         ], []);
    //     }
    //     $saveDiscussion->update(['employee_submitted'=>0,'line_manager_approved'=>0]);
    //     $this->nofityHrAdminSaveDiscussion($saveDiscussion);
    //     return $this->getPerformanceDiscussion($request);
    //     response()->json(['status' => 'success', 'message' => 'Performance Discussion Succsessfully Saved']);

    // }

    public function saveDiscussionDetailAPI(Request $request)
    {

        
        $saveDiscussion = \App\PerformanceDiscussionDetail::updateOrCreate(['id' => $request->detailId], ['action_update' =>$request->action_update,'challenges'=>$request->challenges, 'comment'=>$request->comment]);
      

        return response()->json(['success'=>true,'message'=>'Discussion Detail Succsessfully Saved', 'data'=>$saveDiscussion]);

    }
    public function saveDiscussionDetail(Request $request)
    {
        // could become a potential problem better to use $request->id in the where clause, but this has its own importance

        $saveDiscussion = \App\PerformanceDiscussionDetail::updateOrCreate(['id' => $request->discussion_detail_id], $request->except(['_token', 'type', 'discussion_detail_id', 'evaluation_id']));
        $saveDiscussion->performance_discussion->update(['employee_submitted'=>0,'line_manager_approved'=>0]);
        //        $this->nofityHrAdminSaveDiscussion($saveDiscussion);
        return $this->getPerformanceDiscussion($request);
        // response()->json(['status'=>'success','message'=>'Pefromance Discussion Succsessfully Saved']);

    }

    // incase it was requested
    public function deletePerformanceDiscussion(Request $request)
    {
        \App\PefromanceDiscussion::where('id', $request->id)->delete();
        return getPerformanceDiscussion($request);
    }

    public function hrIndex(Request $request)
    {
        $company_id = companyId();
        $company = \App\Company::find($company_id);
        $metrics = \App\BscMetric::all();
        $measurement_periods = BscMeasurementPeriod::all();
        $weights = \App\BscWeight::all();
        $departments = Department::where('company_id', $company_id)->get();
        $grade_categories = GradeCategory::all();
        $user = new User();
        $operation = 'select';

        return view('bsc.hr_index', compact('metrics', 'measurement_periods', 'weights', 'departments', 'grade_categories', 'user', 'operation'));//

    }

    public function departmentUserList(Request $request)
    {
        $company_id = companyId();
        $mp = BscMeasurementPeriod::find($request->mp);
        $department = Department::find($request->department);
        $employeeStatus = null;
        if($request->mptype == 'confirmed'){
            $employeeStatus = 1;
        }
        if($request->mptype == 'probation'){
            $employeeStatus = 0;
        }
        if (isset($mp) & isset($department)) {

            $users = $department->users()->where('hiredate', '<=', $mp->to)->where('status', '=', $employeeStatus)->get();

            return view('bsc.hr_users_list', compact('department', 'users', 'mp'));
        } else {
            return redirect()->back();
        }

    }

    public function getHREvaluation(Request $request)
    {

        $user = User::find($request->employee);
        $mp = BscMeasurementPeriod::find($request->mp);
        $operation = 'evaluate';
        if ($user->grade && $user->job) {
            if ($user->job) {
                if (!$user->grade->performance_category) {
                    $request->session()->flash('error', 'Employee does not have a grade category or a department');
                    return redirect()->back();
                } else {
                    $evaluation = BscEvaluation::where(['user_id' => $user->id, 'bsc_measurement_period_id' => $mp->id])->first();
                    if ($evaluation) {
                        $metrics = BscMetric::all();

                        return view('bsc.hr_view_evaluation', compact('user', 'operation', 'evaluation', 'metrics'));

                    } else {


                        $request->session()->flash('error', 'Employee has not been evaluated.');


                    }
                }
            } else {
                $request->session()->flash('error', 'Employee does not have a department ');
            }


        } elseif (!$user->grade) {
            $request->session()->flash('error', 'Employee does not have a grade  or job ');
            return redirect()->back();
        } else {
            return redirect()->back();

        }


    }


    public function saveBehavioralEvaluationDetail(Request $request)
    {

        $evaluation=BscEvaluation::find($request->bsc_evaluation_id);

        if($evaluation->status=='appraisal'){
            $score=$request->manager_assessment;
        }else{
            $score=$request->score;
        }

        $evaluation_detail=BehavioralEvaluationDetail::find($request->id);
        $evaluation_detail->update(['bsc_evaluation_id' => $request->bsc_evaluation_id, 'self_assessment' => $request->self_assessment,
            'manager_assessment' => $request->manager_assessment,'score'=>$score, 'employee_comment'=> $request->employee_comment,
            'manager_of_manager_assessment' => $request->manager_of_manager_assessment, 'head_of_strategy' => $request->head_of_strategy,
            'head_of_hr' => $request->head_of_hr,'accept_reject'=>$request->accept_reject]);
       
        $behavioral_percentage = intval($evaluation->measurement_period->behavioral_percentage);
        $behavioralSum = BehavioralEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id])->sum('score');
        $behavioralSelfSum = BehavioralEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id])->sum('self_assessment');
        $weightSum=BehavioralSubMetric::where(['status'=>1])->sum('weighting');

        $evaluation->update(['behavioral_score' => $behavioralSum, 'behavioral_percentage' => (($behavioralSum / $weightSum) * ($behavioral_percentage)),
        'behavioral_self_score' => (($behavioralSelfSum / $weightSum) * ($behavioral_percentage)),
        ]);
//        $maSum = BehavioralEvaluationDetail::where('bsc_evaluation_id', $evaluation_detail->bsc_evaluation_id)->sum('manager_assessment');
//        $evaluation = BscEvaluation::updateorCreate(['id' => $evaluation_detail->bsc_evaluation_id], ['behavioral_score' => $maSum]);

//        $this->notifyUserKpiChange($evaluation->user_id, $evaluation_detail->bsc_evaluation_id);

        return $evaluation_detail;


    }

    public function getBehavioralEvaluationDetails(Request $request)
    {
        return $evaluation_details = \App\BehavioralEvaluationDetail::where(['bsc_evaluation_id' => $request->bsc_evaluation_id])->get();

    }


    public function graphChart(Request $request)
    {
        $company_id = companyId();
        $mp = BscMeasurementPeriod::find($request->mp_id);
        return view('bsc.graph_report', compact('mp'));
    }

    public function getBscMPReport(Request $request)
    {

        $data = [];


        // $leaves=Leave::where('company_id',comapnyId())->get();


        foreach ($this->performance_grades as $key => $performance_grade) {
            $data['labels'][] = $key;

            $data['data'][] = BscEvaluation::where(['company_id' => companyId(), 'bsc_measurement_period_id' => $request->mp_id])->whereBetween('score', $performance_grade)->count();

        }

        return $data;


    }

    public function getBAMPReport(Request $request)
    {

        $data = [];


        // $leaves=Leave::where('company_id',comapnyId())->get();


        foreach ($this->performance_grades as $key => $performance_grade) {
            $data['labels'][] = $key;

            $data['data'][] = BscEvaluation::where(['company_id' => companyId(), 'bsc_measurement_period_id' => $request->mp_id])->whereBetween('behavioral_score', $performance_grade)->count();


        }
        return $data;


    }

    public function getAvgMPReport(Request $request)
    {

        $data = [];


        // $leaves=Leave::where('company_id',comapnyId())->get();


        foreach ($this->performance_grades as $key => $performance_grade) {
            $data['labels'][] = $key;
            $data['data'][] = BscEvaluation::selectRaw(' (behavioral_percentage+scorecard_percentage) as avgs')->where(['company_id' => companyId(), 'bsc_measurement_period_id' => $request->mp_id])->get()->filter(function ($value, $key) use ($performance_grade) {
                if ($value->avgs >= $performance_grade[0] and $value->avgs <= $performance_grade[1]) {
                    return $value->avgs;
                };
            })->count();
        }
        return $data;


    }

    public function getDeptAvgMPReport(Request $request)
    {

        $data = [];


        // $leaves=Leave::where('company_id',comapnyId())->get();
        $departments = \App\Department::where('company_id', companyId())->get();
        foreach ($departments as $department) {
            $data['labels'][] = $department->name;
        }
        $pk = 0;
        foreach ($this->performance_grades as $key => $performance_grade) {
            $data['datasets'][$pk]['label'] = $key;
            foreach ($departments as $department) {
                $user_ids = $department->users->pluck('id');

                $data['datasets'][$pk]['data'][] = BscEvaluation::selectRaw(' (behavioral_percentage+scorecard_percentage) as avgs')->whereIn('user_id', $user_ids)->where(['company_id' => companyId(), 'bsc_measurement_period_id' => $request->mp_id])->get()->filter(function ($value, $key) use ($performance_grade) {
                    if ($value->avgs >= $performance_grade[0] and $value->avgs <= $performance_grade[1]) {
                        return $value->avgs;
                    };
                })->count();
            }
            $pk++;

        }
        return $data;


    }

    public function exportForBSCExcelReport(Request $request)
    {

        $data = [];
        $mp = BscMeasurementPeriod::find($request->mp_id);

        return \Excel::create("BSC export for " . date('F-Y', strtotime($mp->from)) . " to " . date('F-Y', strtotime($mp->to)), function ($excel) use ($request) {

            $type = $request->type;
            $evaluations = BscEvaluation::where(['company_id' => companyId(), 'bsc_measurement_period_id' => $request->mp_id])->get();
            if ($type == 'score') {
                $excel->sheet('performance report', function ($sheet) use ($evaluations) {

                    $sheet->loadView('bsc.partials.bsc_report', compact('evaluations'))->setOrientation('landscape');
                });
            } elseif ($type == 'behavioral_score') {
                $excel->sheet('performance report', function ($sheet) use ($evaluations) {

                    $sheet->loadView('bsc.partials.ba_report', compact('evaluations'))->setOrientation('landscape');
                });

            } elseif ($type == 'average') {
                $excel->sheet('performance report', function ($sheet) use ($evaluations) {

                    $sheet->loadView('bsc.partials.avg_report', compact('evaluations'))->setOrientation('landscape');
                });

            }
        })->export('xlsx');
    }

    public function individual_report(Request $request)
    {
        $evaluation=BscEvaluation::find($request->evaluation_id);
        $metrics = BscMetric::all();
        return \Excel::create("BSC export for {$evaluation->user->name} in " . date('F-Y', strtotime($evaluation->measurement_period->from)) . " to " . date('F-Y', strtotime($evaluation->measurement_period->to)), function ($excel) use ($evaluation,$metrics) {
            $excel->sheet('performance report', function ($sheet) use ($evaluation,$metrics) {

                $sheet->loadView('bsc.partials.individual_report', compact('evaluation','metrics'))->setOrientation('landscape');
            });
        })->export('xlsx');
    }

    public function mp_report(Request $request)
    {
        $measurement_period=BscMeasurementPeriod::find($request->mp);
        $evaluations=BscEvaluation::where('bsc_measurement_period_id',$request->mp)->get();
        $parsedEvaluations = [];
        $metricNames = ["DESIGNATION"];
        forEach($evaluations as $evaluation){
            $groupedDetails = [];
            $groupedDetails = $evaluation->evaluation_details->groupBy("metric_id");
            $parsedGroupedDetails = null;
            forEach($groupedDetails as $detail){
                $totalWeight = $detail->sum('weight');
                $totalScore = ($detail->sum('manager_assessment')/$totalWeight) * $detail[0]->metric->description;//convert to percentage of metric && description serves as the assigned percentage
                $metricName = $detail[0]->metric->name;
                $detail = ['totalWeight'=>$totalWeight, 'totalScore'=>$totalScore, 'metricName'=>$metricName];
                array_push($metricNames, $metricName);
                $parsedGroupedDetails[$metricName] = $detail;
            }
            $evaluation['grouped'] =  $parsedGroupedDetails;

            array_push($parsedEvaluations, $evaluation);

        }
      

        $evaluations = $parsedEvaluations;
        $metricNames= array_unique($metricNames); //remove all duplicate names
        return view('bsc.partials.group_report',compact('measurement_period','evaluations','metricNames'));
        
    }


}
