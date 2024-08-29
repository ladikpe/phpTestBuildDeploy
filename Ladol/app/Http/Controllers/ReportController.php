<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
// use App\Traits\Micellenous;


class ReportController extends Controller
{
    // use Micellenous;
    //
    public function __construct()
    {
        $this->middleware('bi_report', ['except' => ['getReport']]);

        $this->middleware('auth')->only(['getReport']);
        $this->middleware('microsoft')->only(['getReport']);
        
    }


    //UPSTREAM REPORT
    public function index()
    {
        return view('report.upstream.index');
    }
    // LEAVE

    // leave-request-approvals
    public function leaveRequestApprovals()
    {
        $data = \App\LeaveApproval::all();
        return response()->json([
            'message'=> 'Leave request approvals retrieved successfully!',
            'data'=> $data
        ]);
    }
    // leaves
    public function leaves()
    {
        $data = \App\Leave::all();
        return response()->json([
            'message'=> 'Leaves retrieved successfully!',
            'data'=> $data
        ]);
    }
    // leave requests
    public function leaveRequests()
    {
        $data = \App\LeaveRequest::all();
        return response()->json([
            'message'=> 'Leave requests retrieved successfully!',
            'data'=> $data
        ]);
    }
    // leave requests dates
    public function leaveRequestDates()
    {
        $data = \App\LeaveRequestDate::all();
        return response()->json([
            'message'=> 'Leave request dates retrieved successfully!',
            'data'=> $data
        ]);
    }

    //payroll
    public function payroll(Request $request, $companyId)
    {
        $validated = $request->validate([
            'month' => 'required|alpha_dash',
            
        ]);
      
        $company_id = $companyId;
        if ($request->filled('month')) {
            $date = date('Y-m-d', strtotime('01-' . $request->month));
        } else {
            $date = date('Y-m-d');
        }
        $pmonth = date('m', strtotime($date));
        $pyear = date('Y', strtotime($date));
        
        $payroll = \App\Payroll::where(['month' => $pmonth, 'year' => $pyear, 'company_id' => $company_id])->first();
        if(!$payroll){
            return response()->json([
                'message'=> 'Payroll does not exist!',
                'data'=> $payroll
            ], 400);
        }
        $date = date('Y-m-d', strtotime($payroll->for));
        $allowances = 0;
        $deductions = 0;
        $income_tax = 0;
        $salary = 0;
        $has_been_run = 1;
        $details = $payroll->payroll_details;
        if($request->filled('department_id')){
            $filtered = $details->filter(function ($item, $key) use($request) {
                return $item['user']['department_id'] == $request->department_id;
                
            });
            $details = $filtered->all();

           
             
        }
        if($request->filled('branch_id')){
            $filtered = $details->filter(function ($item, $key) use($request) {
                return $item['user']['branch_id'] == $request->branch_id;
                
            });
            $details = $filtered->all();

            
             
        }
        foreach ($details as $detail) {
            $payroll['salary'] += $detail->basic_pay;
            $payroll['allowances'] += $detail->allowances;
            $payroll['deductions'] += $detail->deductions;
            $payroll['income_tax'] += $detail->paye;
        }
        $payroll = json_encode($payroll);
        $payroll = json_decode($payroll);
        $payroll->payroll_details = $details; //so as to overide the default val to show what was filtered instead
        
        return response()->json([
            'message'=> 'Payroll retrieved successfully!',
            'data'=> $payroll
        ], 200);
    }
    // payrollTable
    public function payrollTable(Request $request, $companyId)
    {
        
        $company_id = $companyId;
        if (!$companyId) {
            return response()->json([
                'message'=> 'Please specify a company id!',
                'data'=> null,
             
            ], 400);
        }
       
        // $payrolls = \App\Payroll::with(['payroll_details'])->where(['company_id' => $company_id, 'approved'=> 1])->get();
        $payrolls = \App\Payroll::with(['payroll_details'])->where(['company_id' => $company_id])->get();
        // below is done to resemble a table as requested from the data team
        $combinedPayroll = null;
        for ($i=0; $i < count($payrolls) ; $i++) { 
            $payroll =  $payrolls[$i];
            $data = $payroll->payroll_details;
            $parsedData =$data->map(function ($item, $key) use ($payroll) {
                $scAllowances =unserialize($item['sc_details'])["sc_allowances"];
                $scDeductions =unserialize($item['sc_details'])["sc_deductions"];
                // For the sake of consistency, overtime has to always match the first item in ssc_allowances array, it should match ssc_component_names
                $sscAllowances =unserialize($item['ssc_details'])["ssc_allowances"];
                $employeeOvertime =  $sscAllowances ? $sscAllowances[0] : null;
                return array_merge([
                    'month'=>$payroll['month'],
                    'year'=>$payroll['year'],
                    'section_id'=>$payroll['section_id'],
                    'user_id' => $item['user_id'],
                    'gross_pay' => $item['gross_pay'],
                    'basic_pay' => $item['basic_pay'],
                    'allowances' => $item['allowances'],
                    'payroll_type' => $item['payroll_type'],
                    'netpay' => $item['netpay'],
                    'paye' => $item['paye'],
                    'taxable_income' => $item['taxable_income'],
                    'employee_overtime_amount' => $employeeOvertime,
                   
                    // 'sc_details'=> unserialize($item['sc_details']),
                    // 'ssc_details'=> unserialize($item['ssc_details']),
                ], $scAllowances, $scDeductions);
            });
            
            if($i === 0){
                $combinedPayroll = collect($parsedData);

                
            }else {
                $combinedPayroll = $combinedPayroll->concat(collect($parsedData));

            }
        }

        return response()->json([
            'message'=> 'Entire Payroll records retrieved successfully!',
            'data'=> $combinedPayroll,
        ], 200);
      
   
    }
    //payroll end
    public function companies()
    {
        $companies = \App\Company::all();
        return response()->json([
            'message'=> 'Companies retrieved successfully!',
            'data'=> $companies
        ]);
    }
    public function employees(Request $request, $companyId)
    {
        $employees = \App\User::where('company_id',$companyId)->get();
        if($request->filled('status')){
            $employees = \App\User::where(['company_id'=>$companyId, 'status'=>$request->status])->get();


        }
        return response()->json([
            'message'=> 'Employees retrieved successfully!',
            'data'=> $employees
        ]);
    }
    public function jobs()
    {
        $data = \App\Job::all();
        return response()->json([
            'message'=> 'Jobs retrieved successfully!',
            'data'=> $data
        ]);
    }
    public function branches($companyId)
    {
        $branches = \App\Branch::where('company_id',$companyId)->get();
        return response()->json([
            'message'=> 'Branches retrieved successfully!',
            'data'=> $branches
        ]);
    }
    public function countries()
    {
        $countries = \App\Country::all();
        return response()->json([
            'message'=> 'Countries retrieved successfully!',
            'data'=> $countries
        ]);
    }
    public function grades($companyId)
    {
        $grades = \App\Grade::where('company_id',$companyId)->get();
        return response()->json([
            'message'=> 'Grades retrieved successfully!',
            'data'=> $grades
        ]);
    }
    public function banks()
    {
        $employees = \App\Bank::all();
        return response()->json([
            'message'=> 'Banks retrieved successfully!',
            'data'=> $employees
        ]);
    }
    public function lgas($stateId)
    {
        $data = \App\LocalGovernment::where('state_id',$stateId)->get();
        return response()->json([
            'message'=> 'LGAs retrieved successfully!',
            'data'=> $data
        ]);
    }
    public function lineManager($companyId, $lineManagerId)
    {
        $user = \App\User::find($lineManagerId);
        $data = \App\User::where('company_id',$companyId)->where('line_manager_id',$lineManagerId)->get();
        return response()->json([
            'message'=> $user->name.' subordinates retrieved successfully!',
            'data'=> $data
        ]);
    }
    public function roles()
    {
        $roles = \App\Role::all();
        return response()->json([
            'message'=> 'Roles retrieved successfully!',
            'data'=> $roles
        ]);
    }
    public function sections($companyId)
    {
        $employees = \App\UserSection::where('company_id',$companyId)->get();
        return response()->json([
            'message'=> 'Sections retrieved successfully!',
            'data'=> $employees
        ]);
    }
    public function staffCategories()
    {
        $data = \App\StaffCategory::all();
        return response()->json([
            'message'=> 'Staff Categories retrieved successfully!',
            'data'=> $data
        ]);
    }
   
    public function states($countryId)
    {
        $states = \App\State::where('country_id',$countryId)->get();
        return response()->json([
            'message'=> 'states retrieved successfully!',
            'data'=> $states
        ]);
    }
    public function projectSalaryCategories($companyId)
    {
        $data = \App\PaceSalaryCategory::where('company_id',$companyId)->get();
        return response()->json([
            'message'=> 'Project Salary Categories retrieved successfully!',
            'data'=> $data
        ]);
    }
    public function pfas()
    {
        $pfas=\App\pfa::all();
        return response()->json([
            'message'=> 'PFAs retrieved successfully!',
            'data'=> $pfas
        ]);
    }
    public function performanceCategories()
    {
        $data = \App\BscGradePerformanceCategory::all();
        return response()->json([
            'message'=> 'Performance categories retrieved successfully!',
            'data'=> $data
        ]);
    }


    public function department_reports($companyId)
    {

        $departments = \App\Department::where('company_id',$companyId)->get();
        return response()->json([
            'message'=> 'Departments retrieved successfully!',
            'data'=> $departments
        ]);
    }
    public function departments()
    {
        return view('report.upstream.index');
    }




    //POWER BI Quick insight
    public function getReport(Request $request)
    {




        $page=$request->page;
        $accessToken=$this->plugPowerBI();

        $dataSetId=env('QI_DATASETID','');
        $reportId=env('QI_REPORTID','');
        $group_ids=env('QI_GROUPID',''); // does not change

       

        //UPSTREAM
        if($request->page=='demographics')
        { //employee reports

            $groupId = $group_ids;            $reportId=env('EMPLOYEE_QI_REPORTID','');
            $dataSetId=env('EMPLOYEE_QI_DATASETID','');
            
        }
        if($request->page=='leave')
        { // leave

            $groupId = $group_ids;            $reportId=env('LEAVE_QI_REPORTID','');
            $dataSetId=env('LEAVE_QI_DATASETID','');

        }
        if($request->page=='payroll')
        { // payroll

            $groupId = $group_ids;            $reportId=env('PAYROLL_QI_REPORTID','');
            $dataSetId=env('PAYROLL_QI_DATASETID','');

        }
        elseif($request->page=='hr')
        {
            $groupId = $group_ids;           $reportId="163e6140-d20a-4e93-a7ab-8fff78ec0200";
        }

        elseif($request->page=='job_roles')
        {
            $groupId= $group_ids;            $reportId="14c8852a-9873-4fe0-b9a8-efe9b0850a3a";
        }

        elseif($request->page=='loan_request')
        {
            $groupId= $group_ids;            $reportId="a45f0d58-c96c-409f-9b32-25e8558848cd";
        }

        elseif($request->page=='payroll')
        {
            $groupId= $group_ids;            $reportId="010043d3-f7a4-4b06-a670-37b8e5ccddf1";
        }





        elseif($request->page=='quickinsight')
        {
            $accessToken=$this->QuickInsight();            $groupId=env('QI_GROUPID','');
        }

                        $user=\Auth::user();
                                if(\Auth::user()->role->permissions->contains('constant', 'group_access')){
                                    $companies=companies();
                                    $filtered = collect($companies)->map(function ($company, $key)  use($user){
                                        if($company->id==8 && $user->company_id==8){
                                            return $company->id;
                                        }elseif($company->id!=8){
                                            return  $company->id;
                                        }
                                    });
                                    $filtered=$filtered->all();
                                }else{
                                    $filtered=[$user->company_id];
                                }


        return view('executiveview.index', compact('accessToken', 'groupId', 'reportId', 'dataSetId', 'page','filtered'));
    }

    public function QuickInsight(){
        $groupId=env('QI_GROUPID','');
        $dataSetId=env('QI_DATASETID','');
        $response = \Curl::to("https://api.powerbi.com/v1.0/myorg/groups/$groupId/datasets/$dataSetId/GenerateToken")
            ->withHeader('Authorization:Bearer '.$this->plugPowerBI())
            ->withData(['accessLevel'=>'view'])
            ->asJson()
            ->post();
// 572f20f0-947c-42b4-a2e4-2faa5a18a786/datasets/d8340c85-6c25-43c6-b1fd-9198f48b9403
        // dd($response);
        return $response->token;


    }

    public function plugPowerBI(){

        $auth_data= [ 'grant_type'=>'password',
            'client_id'=>env('POW_CLIENT_ID','') ,
            'client_secret'=> env('POW_CLIENT_SECRET',''),
            'resource'=>'https://analysis.windows.net/powerbi/api',
            'username'=> env('POW_USERNAME',''),
            'password'=> env('POW_PASSWORD',''),
            'scope'=>'openid'

        ];
        // if(session()->has('access_token') && session('access_token')!=''){
        //         return session('access_token');
        //     }
        $response = \Curl::to('https://login.microsoftonline.com/snapnet.com.ng/oauth2/token')
            ->withData($auth_data)
            ->post();
        $response= json_decode($response);
        // dd($response);
        if(!isset($response->access_token)){
            \Auth::logout();
            return 'Error';
        }
        session(['access_token'=>$response->access_token]);
        return $response->access_token;
    }



}


