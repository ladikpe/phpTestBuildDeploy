<?php

namespace App\Http\Controllers;

use App\AttendanceReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Company;
use App\User;
use App\WorkingPeriod;
use App\Attendance;
use App\Leave;
use App\LeaveRequest;
use App\Job;
use App\UserTemp;
use App\EducationHistory;
use App\Dependant;
use App\NokTemp;
use App\EmploymentHistory;
use App\DataPolicyAcceptance;
use Auth;

class DataPolicyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function data_policy_acceptances()
    {
        $user =  Auth::user();
        $dpas = DataPolicyAcceptance::where([ 'accepted'=> 1])->get();
        
            return view('data_policy.data_policy_acceptances', compact('dpas'));

            
        

        
        
    }
    public function index()
    {
        $user =  Auth::user();
        $dpa = DataPolicyAcceptance::where(['user_id'=>$user->id, 'accepted'=> 1])->first();
        if(!$dpa){
            return view('data_policy.index');

            
        }
        return redirect('/');

        
        
    }
    public function store(Request $request)
    {
        $user =  Auth::user();
        // return $user;
        $dpa = DataPolicyAcceptance::updateOrCreate(['user_id'=>$user->id],['accepted'=>$request->accepted ? 1 : 0]);
        // $dpa = DataPolicyAcceptance::find(1);
        return redirect('/');
        // return 'dog';
        // return view('data_policy.index');
    }
  

}
