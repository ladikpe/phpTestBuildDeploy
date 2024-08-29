<?php

namespace App\Traits;

use App\LoanPolicy;
use App\Payroll;
use App\Bank;
use App\Company;
use App\CompanyAccountDetail;
use App\PayslipDetail;
use App\PayrollDetail;
use App\PayrollPolicy;
use App\SalaryComponent;
use App\SpecificSalaryComponent;
use App\Workflow;
use App\LatenessPolicy;
use App\Setting;
use App\Loan;
use App\User;
use App\Holiday;
use App\LoanRequest;
use App\LoanApproval;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Notifications\ApproveLoan;
use App\Notifications\LoanRequestApproved;
use App\Notifications\LoanRequestPassedStage;
use App\Notifications\LoanRequestRejected;
use App\Stage;
use Auth;
use Excel;

trait LoanTrait
{

    public function processGetLoan($route, Request $request)
    {
        // return $request->all();
        if ($request->query('gld')) {
        }
        switch ($route) {
            case 'loan_requests':

                return $this->getLoanList($request);
                break;
            case 'my_loan_requests':

                return $this->myLoanList($request);
                break;

            case 'approvals':
                return $this->approvals($request);
                break;

            case 'approve_loan_request':

                return $this->approveLoan($request);
                break;
            case 'loan_excel':

                return $this->loanExcel($request);
                break;
            case 'get_loan_details':

                return $this->getLoanRequest($request);
                break;


            default:
                # code...
                break;
        }
    }

    public function processPostLoan(Request $request)
    {
        switch ($request->type) {
            case 'loan_request':
                return $this->saveLoanRequest($request);
                break;

            case 'save_approval':
                return $this->saveApproval($request);
                break;

            default:
                # code...
                break;
        }
    }

    public function myLoanList(Request $request)
    {
        // $loan_requests = Auth::user()->loan_requests()->paginate(5);
        $loan_requests = LoanRequest::where('user_id', Auth::user()->id)->paginate(5);

        return view('loan.mylist', compact('loan_requests'));
    }

    public function getLoanList(Request $request)
    {
        $loan_requests = LoanRequest::paginate(5);

        return view('loan.list', compact('loan_requests'));
    }

    public function saveLoanRequest(Request $request)
    {
        $company_id = companyId();
        $lp =  LoanPolicy::where('company_id', $company_id)->first();

        $netpay = $request->netpay;
        $maximum_allowed = $request->maximum_allowed;
        $annual_interest = $lp->annual_interest_percentage;
        $monthly_rate = $annual_interest / 12 / 100;
        $start = 1;
        $monthly_payment = 0;
        $length = 1 + $monthly_rate;
        for ($i = 0; $i < $request->period; $i++) {
            $start = $start * $length;
        }
        $repayment_starts = date('Y-m-d', strtotime($request->starts . '-01'));
        // $payment=$monthly_payment=$request->amount*$monthly_rate/1;
        // return "Monthly Rate: {$monthly_rate} Amount: {$request->amount} Start: {$start} Period: {$request->period}";
        $payment = $monthly_payment = floatval($request->amount * $monthly_rate / (1 - (1 / $start)));
        $total_amount_repayable = $payment * $i;
        $total_interest = $total_amount_repayable - $request->amount;
        $loan_request = LoanRequest::create([
            'user_id' => Auth::user()->id, 'netpay' => $netpay, 'amount' => $request->amount,
            'monthly_deduction' => $payment, 'period' => $request->period, 'current_rate' => $annual_interest,
            'repayment_starts' => $repayment_starts, 'status' => 0, 'approved_by' => 0, 'completed' => 0, 'specific_salary_component_type_id' => $lp->specific_salary_component_type_id,
            'maximum_allowed' => $maximum_allowed, 'company_id' => $company_id, 'workflow_id' => $lp->workflow_id,
            'total_repayments' => $total_amount_repayable, 'total_interest' => $total_interest
        ]);

        $this->start_payroll_approval($loan_request, $lp);

        if ($loan_request) {
            return 'success';
        } else {
            return 'failed';
        }
    }
    public function approveLoan(Request $request)
    {
        $loan_request = loanRequest::find($request->loan_request_id);
        if ($loan_request) {
            $loan_request->update(['status' => 1, 'approved_by' => Auth::user()->id]);
        }
        return 'success';
    }

    public function loanExcel(Request $request)
    {
        $loan_request_id = $request->loan_request_id;
        $loan_request = LoanRequest::find($loan_request_id);
        $netpay = 333245;
        $view = 'loan.loan_schedule';
        $this->exportToExcel($loan_request, $netpay, $view);
    }
    private function exportToExcel($datas, $netpay, $view)
    {

        return     \Excel::create("$view", function ($excel) use ($datas, $view, $netpay) {

            $excel->sheet("$view", function ($sheet) use ($datas, $view, $netpay) {
                $sheet->loadView("$view", compact("datas", "netpay"))
                    ->setOrientation('landscape');
            });
        })->export('xlsx');
    }

    public function monthly_repayment()
    {
    }
    public function bulk_repayment($loan_request)
    {
        $existing = SpecificSalaryComponent::where('loan_id', $loan_request->id)->first();
    }
    public function start_payroll_approval($loan_request, $lp)
    {
        if ($lp->workflow_id > 0) {
            $loan_request->approved = 3;
            $loan_request->save();
            $stage = Workflow::find($lp->workflow_id)->stages->first();
            if ($stage->type == 1) {
                $loan_request->approvals()->create([
                    'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => $stage->user_id
                ]);
                if ($stage->user) {
                    $stage->user->notify(new ApproveLoan($loan_request));
                }
            } elseif ($stage->type == 2) {
                $loan_request->approvals()->create([
                    'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => 0
                ]);
                if ($stage->role->manages == 'dr') {
                    if ($loan_request->user->managers) {
                        foreach ($loan_request->user->managers as $manager) {
                            $manager->notify(new ApproveLoan($loan_request));
                        }
                    }
                } elseif ($stage->role->manage == 'all') {
                    foreach ($stage->role->users as $user) {
                        $user->notify(new ApproveLoan($loan_request));
                    }
                } elseif ($stage->role->manage == 'none') {
                    foreach ($stage->role->users as $user) {
                        $user->notify(new ApproveLoan($loan_request));
                    }
                }
            } elseif ($stage->type == 3) {
                $loan_request->approvals()->create([
                    'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => 0
                ]);
                if ($stage->group) {
                    foreach ($stage->group->users as $user) {
                        $user->notify(new ApproveLoan($loan_request));
                    }
                }
            }
            return 'success';
        }
        return 'error';
    }
    public function saveApproval(Request $request)
    {
        $loan_approval = LoanApproval::find($request->loan_approval_id);
        $company_id = companyId();
        $loanRequester = $loan_approval->loan_request->user;
        $lp = LoanPolicy::where('company_id', $company_id)->first();
        $loan_approval->comments = $request->comment;
        if ($request->approval == 1) {
            $loan_approval->status = 1;
            $loan_approval->approver_id = Auth::user()->id;
            $loan_approval->save();
            // $logmsg=$leave_approval->document->filename.' was approved in the '.$leave_approval->stage->name.' in the '.$leave_approval->stage->workflow->name;
            // $this->saveLog('info','App\Review',$leave_approval->id,'leave_approvals',$logmsg,Auth::user()->id);
            $newposition = $loan_approval->stage->position + 1;
            $nextstage = Stage::where(['workflow_id' => $loan_approval->stage->workflow->id, 'position' => $newposition])->first();
            // return $review->stage->position+1;
            // return $nextstage;

            if ($nextstage) {

                $newloan_approval = new LoanApproval();
                $newloan_approval->stage_id = $nextstage->id;
                $newloan_approval->loan_request_id = $loan_approval->loan_request->id;
                $newloan_approval->status = 0;
                $newloan_approval->save();
                // $logmsg='New review process started for '.$newleave_approval->document->filename.' in the '.$newleave_approval->stage->workflow->name;
                // $this->saveLog('info','App\Review',$leave_approval->id,'reviews',$logmsg,Auth::user()->id);
                if ($nextstage->type == 1) {

                    // return $nextstage->type . '-2--' . 'all';

                    $nextstage->user->notify(new ApproveLoan($newloan_approval->loan_request));
                } elseif ($nextstage->type == 2) {
                    // return $nextstage->role->manages . '1---' . 'all' . json_encode($leave_approval->payroll->user->managers);
                    if ($nextstage->role->manages == 'dr') {

                        // return $nextstage->role->manage . '---' . json_encode($nextstage->role->users);

                        foreach ($loan_approval->payroll->user->managers as $manager) {
                            $manager->notify(new ApproveLoan($newloan_approval->loan_request));
                        }
                    } elseif ($nextstage->role->manages == 'all') {
                        // return 'all.';

                        // return $nextstage->role->manage . '---' . json_encode($nextstage->role->users);

                        foreach ($nextstage->role->users as $user) {
                            $user->notify(new ApproveLoan($newloan_approval->loan_request));
                        }
                    } elseif ($nextstage->role->manage == 'none') {
                        foreach ($nextstage->role->users as $user) {
                            $user->notify(new ApproveLoan($newloan_approval->loan_request));
                        }
                    }
                } elseif ($nextstage->type == 3) {
                    //1-user
                    //2-role
                    //3-groups
                    // return 'not_blank';

                    foreach ($nextstage->group->users as $user) {
                        $user->notify(new ApproveLoan($newloan_approval->loan_request));
                    }
                } else {
                    // return 'blank';
                }
                $loan_approval->loan_request->user->notify(new LoanRequestPassedStage($loan_approval, $loan_approval->stage, $newloan_approval->stage));
            } else {
                // return 'blank2';
                $loan_approval->loan_request->approved = 1;
                $loan_approval->loan_request->status = 1;
                $loan_approval->loan_request->approved_by = Auth::user()->id;
                $loan_approval->loan_request->completed = 1;
                $loan_approval->loan_request->save();
                $this->prepareLoanDeduction($loanRequester, $loan_approval->loan_request, $lp);
                $loanRequester->notify(new LoanRequestApproved($loan_approval->stage, $loan_approval));
            }
        } elseif ($request->approval == 2) {
            // return 'blank3';
            $loan_approval->status = 2;
            $loan_approval->comments = $request->comment;
            $loan_approval->approver_id = Auth::user()->id;
            $loan_approval->save();
            // $logmsg=$leave_approval->document->filename.' was rejected in the '.$leave_approval->stage->name.' in the '.$leave_approval->stage->workflow->name;
            // $this->saveLog('info','App\Review',$leave_approval->id,'leave_approvals',$logmsg,Auth::user()->id);
            $loan_approval->loan_request->status = 2;
            $loan_approval->loan_request->save();
            $loan_approval->loan_request->user->notify(new LoanRequestRejected($loan_approval->stage, $loan_approval));
            // return redirect()->route('documents.mypendingleave_approvals')->with(['success'=>'Document Reviewed Successfully']);
        }

        return 'success';


        // return redirect()->route('documents.mypendingreviews')->with(['success'=>'Leave Request Approved Successfully']);
    }

    public function approvals(Request $request)
    {
        $user = Auth::user();

        $user_approvals = $this->userApprovals($user) ?  $this->userApprovals($user) : [] ;
        $dr_approvals = $this->getDRLoanApprovals($user) ? $this->getDRLoanApprovals($user) : [];
        $ss_approvals = $this->getSSLoanApprovals($user) ? $this->getSSLoanApprovals($user) : [];
        $role_approvals = $this->roleApprovals($user) ? $this->roleApprovals($user) : [];
        $group_approvals = $this->groupApprovals($user)? $this->groupApprovals($user)  : [];
        // dd($group_approvals);

        return view('loan.approvals', compact('user_approvals', 'role_approvals', 'group_approvals', 'dr_approvals', 'ss_approvals'));
    }
    public function userApprovals(User $user)
    {
        return $las = LoanApproval::whereHas('stage.user', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })
            ->where('status', 0)->orderBy('id', 'asc')->get();
    }

    public function getDRLoanApprovals(User $user)
    {
        // return Auth::user()->getDRLoanApprovals();
        return $las = LoanApproval::whereHas('stage.role.users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })

            ->where('status', 0)->orderBy('id', 'desc')->get();
    }
    public function getSSLoanApprovals(User $user)
    {
        // return Auth::user()->getSSLoanApprovals();
        return $las = LoanApproval::whereHas('stage.role.users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })

            ->where('status', 0)->orderBy('id', 'desc')->get();
    }

    public function roleApprovals(User $user)
    {
        return $las = LoanApproval::whereHas('stage.role', function ($query) use ($user) {
            $query->where('manages', '!=', 'dr')
                ->where('roles.id', $user->role_id);
        })->where('status', 0)->orderBy('id', 'asc')->get();
    }

    public function groupApprovals(User $user)
    {
        return $las = LoanApproval::whereHas('stage.group.users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })->where('status', 0)->orderBy('id', 'asc')->get();
    }

    public function getLoanRequest(Request $request)
    {
        $loan = LoanRequest::find($request->loan_request_id);
        return view('loan.partials.loanDetails', compact('loan'));
    }


    public function prepareLoanDeduction($user, $loan_request, $lp)
    {
        $loanEndDate = date('y-m-d', strtotime($loan_request->repayment_starts . ' + ' . $loan_request->period . ' months'));

        if ($user->payroll_type == 'project') {

            $amount = $user->project_salary_category->basic_salary * 12 * 0.06;
            if (date('Y', strtotime($user->hiredate)) == date('Y')) {
                //porate for staff employed this year
                $amount = $amount / 12 * (12 - intval(date('m', strtotime($user->hiredate))) + 1);
            }


            SpecificSalaryComponent::create([
                'specific_salary_component_type_id' => $lp->specific_salary_component_type_id,
                'amount' => round($loan_request->amount, 2),
                'name' => 'Staff Loan Deduction',
                'type' => 0,
                'duration' => $loan_request->period,
                'grants' => 0,
                'completed' => 0,
                'starts' => $loan_request->repayment_starts,
                'ends' => $loan_request->period > 1 ? $loanEndDate : $loan_request->repayment_starts,
                'emp_id' => $user->id,
                'company_id' => $user->company_id,
                'one_off' => $loan_request->period > 1 ? 0 : 1,
                'is_relief' => 0,
                'taxable' => 0,
                'taxable_type' => 1,
                'status' => 1,
                'loan_id' => $loan_request->id,
            ]);
        } elseif ($user->payroll_type == 'office' && $user->user_grade) {

            $amount = $user->user_grade->basic_pay * 12 * 0.10;

            SpecificSalaryComponent::create([
                'specific_salary_component_type_id' => $lp->specific_salary_component_type_id,
                'amount' => round($loan_request->amount, 2),
                'name' => 'Staff Loan Deduction',
                'type' => 0,
                'duration' => $loan_request->period,
                'grants' => 0,
                'completed' => 0,
                'starts' => $loan_request->repayment_starts,
                'ends' => $loan_request->period > 1 ? $loanEndDate : $loan_request->repayment_starts,
                'emp_id' => $user->id,
                'company_id' => $user->company_id,
                'one_off' => $loan_request->period > 1 ? 0 : 1,
                'is_relief' => 0,
                'taxable' => 0,
                'taxable_type' => 1,
                'status' => 1,
                'loan_id' => $loan_request->id,
            ]);
        }
    }
}
