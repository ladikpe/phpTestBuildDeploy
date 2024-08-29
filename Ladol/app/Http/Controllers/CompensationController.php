<?php

namespace App\Http\Controllers;

use App\PayrollPolicy;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Traits\PayrollTrait;
use App\User;
use App\Payroll;
use App\PayrollJournal;
use App\Company;

class CompensationController extends Controller
{
    use PayrollTrait;
    public function index()
    {
        $company_id = companyId();
        // $sections=\App\UserSection::where('company_id',$company_id)->get();
        if ($company_id == 0) {
            $payroll = Payroll::where(['month' => date('m'), 'year' => date('Y'), 'company_id' => $company_id])->first();
            if ($payroll) {
                $date = date('Y-m-d');
                $allowances = 0;
                $deductions = 0;
                $income_tax = 0;
                $salary = 0;
                $has_been_run = 1;
                foreach ($payroll->payroll_details as $detail) {
                    $salary += $detail->basic_pay;
                    $allowances += $detail->allowances;
                    $deductions += $detail->deductions;
                    $income_tax += $detail->paye;
                    
                }
               

                return view('compensation.payroll', compact('payroll', 'allowances', 'deductions', 'income_tax', 'salary', 'date', 'has_been_run'));
            } else {
                $has_been_run = 0;
                $employees = User::whereHas('promotionHistories.grade')->get();
                $date = date('Y-m-d');

                return view('compensation.payroll', compact('date', 'employees', 'has_been_run'));
            }
        }

        $payroll = Payroll::where(['month' => date('m'), 'year' => date('Y'), 'company_id' => $company_id])->first();
        if ($payroll) {
            $last_month_date = Carbon::parse($payroll->for)->subMonths(1)->toDateString();
            $last_month_payroll = Payroll::where('for', $last_month_date)->first();
            $date = date('Y-m-d');
            $allowances = 0;
            $deductions = 0;
            $income_tax = 0;
            $salary = 0;
            $has_been_run = 1;
            foreach ($payroll->payroll_details as $detail) {
                $variance = 'No Previous Payroll';
                $current_net_pay = $detail->basic_pay + $detail->allowances - ($detail->deductions + $detail->paye);
                if($last_month_payroll){
                    $variance = 'Previous Payroll exists';
                    $last_month_employee_payroll = $last_month_payroll->payroll_details->first(function ( $value) use($detail) {
                        return $value->emp_num == $detail->emp_num;
                    });
                    if($last_month_employee_payroll){
                        $previous_net_pay = $last_month_employee_payroll->basic_pay + $last_month_employee_payroll->allowances - ($last_month_employee_payroll->deductions - $last_month_employee_payroll->paye);
                        $variance = 'Employee exists in previous payroll';
                        if($current_net_pay > $previous_net_pay){
                            $variance = 'Greater';
                            
                        }
                        if($current_net_pay < $previous_net_pay){
                            $variance = 'Lesser';
                            
                        }
                        if($current_net_pay == $previous_net_pay){
                            $variance = 'Equal';
                            
                        }
                        
                    }else{
                        $variance = 'Employee does not exists in previous payroll';

                    }
                };
                $salary += $detail->basic_pay;
                $allowances += $detail->allowances;
                $deductions += $detail->deductions;
                $income_tax += $detail->paye;
                $detail->variance = $variance;
                $detail->net_pay = $current_net_pay;
                
            }
         

            return view('compensation.payroll', compact('payroll', 'last_month_payroll', 'allowances', 'deductions', 'income_tax', 'salary', 'date', 'has_been_run'));
        } else {
            $has_been_run = 0;
            $employees = User::where('status', '!=', 2)->whereHas('user_grade')->get();
            //            $employees=Company::where('id',$company_id)->first()->users()->has('promotionHistories.grade')->get();
            $date = date('Y-m-d');

            return view('compensation.payroll', compact('date', 'employees', 'has_been_run'));
        }
    }
    public function store(Request $request)
    {
        //
        return $this->processPost($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {

        return $this->processGet($id, $request);
    }
    public function nav_int(Request $request)
    {
        //        if($request->key== env('APP_KEY')) {
        $months = ['jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4, 'may' => 5, 'jun' => 6, 'jul' => 7, 'aug' => 8, 'sep' => 9, 'oct' => 10, 'nov' => 11, 'dec' => 12];
        if (!array_key_exists(($request->month), $months)) {

            return response()->json(['status' => 'error', 'message' => 'enter the month like jan or feb'], 400);
        }
        // $company = Company::where('bc_id', $request->company_GUID)->first();
        $company = Company::find($request->company_id);
        if ($company) {
            $company_id = $company->id;
        }
        if (!$company) {
            return response()->json(['status' => 'error', 'message' => 'company not found on Hcmatrix'], 400);
        }
        $payroll = Payroll::where(['month' => $months[($request->month)], 'year' => $request->year, 'company_id' => $company_id])->first();
        if (!$payroll) {
            return response()->json(['status' => 'error', 'message' => 'payroll has not been processed for this month'], 400);
        }
        if ($payroll->approved == 0) {
            return response()->json(['status' => 'error', 'message' => 'payroll journal is not ready'], 400);
        }
        if ($payroll->has_been_read == 1) {
            return response()->json(['status' => 'error', 'message' => 'payroll journal has already been read'], 400);
        }


        $sections = \App\UserSection::where('company_id', $company_id)->with('users.user_groups')->get();
        $allusers = \App\User::where('company_id', $company_id)->with('user_groups')->get();
        // return ($allusers);

        $chart_of_accounts = \App\ChartOfAccount::where(['company_id' => $company_id, 'status' => 1,])->orderBy('position')->get();
        $payroll_details = $payroll->payroll_details;
        $specific_salary_component_types = \App\SpecificSalaryComponentType::where('company_id', $company_id)->get();
        $users = \App\User::where('company_id', $company_id)->with('user_groups')->get();
        $lsas = \App\LongServiceAward::where('company_id', $company_id)->orderBy('max_year', 'ASC')->get();
        $pp = PayrollPolicy::where('company_id', $company_id)->first();

        if ($payroll) {

            $days = cal_days_in_month(CAL_GREGORIAN, $payroll->month, $payroll->year);
            $date = date('Y-m-d', strtotime($payroll->year . '-' . $payroll->month . '-' . $days));
            //                $payroll->has_been_read=1;
            //                $payroll->save();
            return view('compensation.partials.newnavpayroll', compact('sections', 'payroll', 'chart_of_accounts', 'payroll_details', 'date', 'specific_salary_component_types', 'allusers', 'pp', 'lsas'));
        }

        //        }
        //        else{
        //            return response()->json(['status'=>'error','message'=>'Invalid Key'],400);
        //        }
    }

    public function payrollDetails($payroll_id)
    {
        $payroll = Payroll::with(['payroll_details'])->find($payroll_id);


        if ($payroll) {
            $currentDate = Carbon::parse($payroll->for)->toDateString();
            $last_month_date = Carbon::parse($payroll->for)->subMonths(1)->toDateString();
            $last_two_months_date = Carbon::parse($payroll->for)->subMonths(2)->toDateString();
            $last_month_payroll = Payroll::where('for', $last_month_date)->first();
            $last_two_months_payroll = Payroll::where('for', $last_two_months_date)->first();
            $payroll_data = [];
            $payroll_data['current']['name'] = date('M-Y', strtotime($currentDate));
            $payroll_data['current']['basic_pay'] = $payroll->payroll_details->sum('basic_pay');
            $payroll_data['current']['allowances'] = $payroll->payroll_details->sum('allowances');
            $payroll_data['current']['deductions'] = $payroll->payroll_details->sum('deductions');
            $payroll_data['current']['income_tax'] = $payroll->payroll_details->sum('paye');
            $payroll_data['current']['net_pay'] = $payroll_data['current']['basic_pay'] + $payroll_data['current']['allowances'] - ($payroll_data['current']['deductions'] - $payroll_data['current']['income_tax']);

            $salaryComponents = \App\SalaryComponent::where([ 'company_id' => $payroll->company_id])->get();

    

            



            if ($last_month_payroll) {
                $payroll_data['last_month_payroll']['name'] = date('M-Y', strtotime($last_month_date));
                $payroll_data['last_month_payroll']['basic_pay'] = $last_month_payroll->payroll_details->sum('basic_pay');
                $payroll_data['last_month_payroll']['allowances'] = $last_month_payroll->payroll_details->sum('allowances');
                $payroll_data['last_month_payroll']['deductions'] = $last_month_payroll->payroll_details->sum('deductions');
                $payroll_data['last_month_payroll']['income_tax'] = $last_month_payroll->payroll_details->sum('paye');
                $payroll_data['last_month_payroll']['net_pay'] = $payroll_data['last_month_payroll']['basic_pay'] + $payroll_data['last_month_payroll']['allowances'] - ($payroll_data['last_month_payroll']['deductions'] - $payroll_data['last_month_payroll']['income_tax']);
            } else {
                $payroll_data['last_month_payroll']['name'] = date('M-Y', strtotime($last_month_date));
                $payroll_data['last_month_payroll']['basic_pay'] = 0;
                $payroll_data['last_month_payroll']['allowances'] = 0;
                $payroll_data['last_month_payroll']['deductions'] = 0;
                $payroll_data['last_month_payroll']['income_tax'] = 0;
                $payroll_data['last_month_payroll']['net_pay'] = 0;
            }
            if ($last_two_months_payroll) {
                $payroll_data['last_two_months_payroll']['name'] = date('M-Y', strtotime($last_two_months_date));
                $payroll_data['last_two_months_payroll']['basic_pay'] = $last_month_payroll->payroll_details->sum('basic_pay');
                $payroll_data['last_two_months_payroll']['allowances'] = $last_month_payroll->payroll_details->sum('allowances');
                $payroll_data['last_two_months_payroll']['deductions'] = $last_month_payroll->payroll_details->sum('deductions');
                $payroll_data['last_two_months_payroll']['income_tax'] = $last_month_payroll->payroll_details->sum('paye');
                $payroll_data['last_two_months_payroll']['net_pay'] = $payroll_data['last_two_months_payroll']['basic_pay'] + $payroll_data['last_two_months_payroll']['allowances'] - ($payroll_data['last_two_months_payroll']['deductions'] - $payroll_data['last_two_months_payroll']['income_tax']);
            } else {
                $payroll_data['last_two_months_payroll']['name'] = date('M-Y', strtotime($last_two_months_date));
                $payroll_data['last_two_months_payroll']['basic_pay'] = 0;
                $payroll_data['last_two_months_payroll']['allowances'] = 0;
                $payroll_data['last_two_months_payroll']['deductions'] = 0;
                $payroll_data['last_two_months_payroll']['income_tax'] = 0;
                $payroll_data['last_two_months_payroll']['net_pay'] = 0;
            }
            $payroll_data['current']['detailed_breakdown'] = $this->generate_payroll_detail_breakdown($payroll, $salaryComponents);
            $payroll_data['last_month_payroll']['detailed_breakdown'] = $last_month_payroll ?$this->generate_payroll_detail_breakdown($last_month_payroll, $salaryComponents) : null;
            $payroll_data['last_two_months_payroll']['detailed_breakdown'] = $last_two_months_payroll ? $this->generate_payroll_detail_breakdown($last_two_months_payroll , $salaryComponents) : null;


            return view('compensation.partials.payrollDetails', compact('payroll_data', 'payroll', 'salaryComponents'));
        }
    }

    private function generate_payroll_detail_breakdown(Payroll $payroll, $salaryComponents){
        // component breakdown details 
        $data = $payroll->payroll_details;
        $parsedData =$data->map(function ($item, $key) use ($payroll) {
            $scAllowances =unserialize($item['sc_details'])["sc_allowances"];
            $scDeductions =unserialize($item['sc_details'])["sc_deductions"];
            // For the sake of consistency, overtime has to always match the first item in ssc_allowances array, it should match ssc_component_names
            $sscAllowances =unserialize($item['ssc_details'])["ssc_allowances"];
            // TODO: account for specific salary components
            $employeeOvertime =  $sscAllowances ? $sscAllowances[0] : null;
            return array_merge([
                
                'paye' => $item['paye'],
                'employee_overtime_amount' => $employeeOvertime,
                
                // 'sc_details'=> unserialize($item['sc_details']),
                // 'ssc_details'=> unserialize($item['ssc_details']),
            ], $scAllowances, $scDeductions);
        });
        

        $_breakdown = [];

        foreach ($salaryComponents as $item) {
            $constant = $item->constant;
            $_breakdown[$constant] = collect($parsedData)->sum($constant);
        }
        return $_breakdown;

    }
    public function bc_int(Request $request)
    {
        $months =
            ['jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4, 'may' => 5, 'jun' => 6, 'jul' => 7, 'aug' => 8, 'sep' => 9, 'oct' => 10, 'nov' => 11, 'dec' => 12];
        if (!array_key_exists(($request->month), $months)) {

            return response()->json(['status' => 'error', 'message' => 'enter the month like jan or feb'], 400);
        }
        // $company = Company::where('bc_id', $request->company_GUID)->first();
        // if ($company) {
        //     $company_id = $company->id;
        // }
        // if (!$company) {
        //     return response()->json(['status' => 'error', 'message' => 'company not found on Hcmatrix'], 400);
        // }
        $payroll = Payroll::where(['month' => $months[($request->month)], 'year' => $request->year])->first();
        if (!$payroll) {
            return response()->json(['status' => 'error', 'message' => 'payroll has not been processed for this month'], 400);
        }
        //        if ($payroll->approved == 0 || $payroll->can_be_read == 0) {
        //            return response()->json(['status' => 'error', 'message' => 'payroll journal is not ready'], 400);
        //        }
        //        if ($payroll->has_been_read == 1) {
        //            return response()->json(['status' => 'error', 'message' => 'payroll journal has already been read'], 400);
        //        }

        return $journal = PayrollJournal::where('payroll_id', $payroll->id)->get();
    }
}
