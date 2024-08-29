<?php

namespace App\Http\Controllers;

use App\Branch;
use App\BscEvaluation;
use App\LoanPolicy;
use App\PaceSalaryCategory;
use App\PaceSalaryComponent;
use Illuminate\Http\Request;
use App\Traits\LoanTrait;
use App\User;
use App\LoanRequest;
use App\Setting;
use Auth;
use function Sodium\crypto_box_publickey_from_secretkey;

class LoanController extends Controller
{
    use LoanTrait;

    public function index()
    {
        $status = Auth::user()->status;
        $last_performance = BscEvaluation::where('user_id', Auth::user()->id)->latest()->first();
        $score = 0;
        $length_of_stay = Auth::user()->months_of_service;
        if ($last_performance) {
            $score = $last_performance->behavioral_percentage + $last_performance->scorecard_percentage;
        }
        $branch=Branch::find(Auth::user()->branch_id);
        // if($branch and $branch->name=='LASG'){
        //     return back()->with('success','You are a secondary staff hence, you are ineligible');
        // }

        $lp = LoanPolicy::where('company_id', companyId())->first();
        if ($lp->uses_confirmation and $status != 1) {
            return back()->with('success','You are not confirmed hence, you are ineligible');
        }
        if ($lp->uses_performance and $score < $lp->minimum_performance_mark) {
            return back()->with('success','You last appraisal was less than '.$lp->minimum_performance_mark.' hence, you are ineligible');
        }
        if ($length_of_stay < $lp->minimum_length_of_stay) {
            return back()->with('success','You have been in the organization for less than '.$lp->minimum_length_of_stay.' hence, you are ineligible');
        }
        if ($lp->concurrent_loans==0 and $this->hasActiveLoan(Auth::user())) {
            return back()->with('success','You have a running loan repayment hence, you are ineligible');
        }
        $maximum_allowed = $this->getLoanObligorLimit(Auth::user(), $lp);
        $annual_interest = $lp->annual_interest_percentage;

        return view('loan.index', compact('maximum_allowed', 'annual_interest','lp'));
    }


    public function store(Request $request)
    {
        //
        return $this->processPostLoan($request);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {

        return $this->processGetLoan($id, $request);
    }

    public function getLoanObligorLimit($user, $lp)
    {
        $amount = 0;
        if($user->payroll_type=='project'){
            if ($lp->policy_components) {
                foreach ($lp->policy_components as $policy_component) {
                    $component_amount = 0;
                    if ($policy_component->source == 'payroll_constant') {
                        //check if employee has a project payroll category
                        $project_payroll_category = PaceSalaryCategory::find($user->project_salary_category_id);
                        $component_amount += $project_payroll_category->basic_salary * 12;
                    }
                    if ($policy_component->source == 'salary_component') {
                        //check if employee has a project payroll category
                        $project_payroll_category = PaceSalaryCategory::find($user->project_salary_category_id);
                        $payroll_salary_component = PaceSalaryComponent::where(['pace_salary_category_id' => $project_payroll_category->id,
                            'constant' => $policy_component->salary_component_constant])->first();
                        $component_amount += $payroll_salary_component->amount * 12;
                    }
                    if ($policy_component->source == 'amount') {
                        //check if employee has a project payroll category

                        $component_amount += $policy_component->amount;
                    }

                    if ($policy_component->percentage > 0) {
                        $component_amount = ($component_amount * $policy_component->percentage) / 100;
                    }
                    $amount += $component_amount;
                }
            }
        }elseif ($user->payroll_type=='office'){
            return 0;
        }


        return $amount;

    }

    public function hasActiveLoan($user)
    {
        $loan=LoanRequest::whereHas('specific_salary_components',function ($query) use($user){
            $query->where('specific_salary_components.emp_id',$user->id)
                ->where('completed',0);
        })->first();
        if($loan){
            return true;
        }
        return false;

    }
}
