<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\RoleDelegateNotification;
use Auth;

class DelegateRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function __construct()
    {
        $this->middleware('auth');
    }




    //function for sending email
    public function notify_delegate($delegate_id, $end_date, $module_name, $role_type, $message, $approval_request_id)
    {
        //user deoartment head notification
        $delegate = \App\User::where('id', $delegate_id)->first();

        //sending email to User
        $sender = \Auth::user()->email;
        $name = $delegate->name;
        $url = url('delegate-approvals', $approval_request_id);
        $message = "You have been delegated an approval role for " . $module_name . " by " . \Auth::user()->name . " with the following message : " . $message . ". Please this is a temporary role that will expire on " . $end_date . " Click the link below to do so";

        $delegate->notify(new RoleDelegateNotification($message, $sender, $name, $url, $module_name, $role_type));
    }





    public function index()
    {
        $delegates = \App\DelegateRole::where('workflow_id', 3)->orderBy('id', 'desc')->get();

        $users = \App\User::where('employment_status', 1)->orderBy('name', 'asc')->get();
        $workflows = \App\Workflow::where('id', 3)->orderBy('name', 'asc')->get();
        $approval_requests = \App\LeaveRequest::where('status', 0)->orderBy('user_id', 'asc')->get();

        $controllerName = new DelegateRoleController;

        return view('delegates.index', compact('delegates', 'users', 'workflows', 'approval_requests', 'controllerName'));
    }


    public function delegatePayrollRole()
    {
        $delegates = \App\DelegateRole::where('workflow_id', 5)->orderBy('id', 'desc')->get();

        $users = \App\User::where('employment_status', 1)->orderBy('name', 'asc')->get();
        $workflows = \App\Workflow::where('id', 5)->orderBy('name', 'asc')->get();
        $approval_requests = \App\Payroll::orderBy('month', 'desc')->get();

        $controllerName = new DelegateRoleController;

        return view('delegates.delegate-payroll-role', compact('delegates', 'users', 'workflows', 'approval_requests', 'controllerName'));
    }


    public function resolveMonth($month)
    {
        switch ($month) 
        {
            case 1:
                return 'January';
            break;
            case 2:
                return 'February';
            break;
            case 3:
                return 'March';
            break;
            case 4:
                return 'April';
            break;
            case 5:
                return 'May';
            break;
            case 6:
                return 'June';
            break;
            case 7:
                return 'July';
            break;
            case 8:
                return 'August';
            break;
            case 9:
                return 'September';
            break;
            case 10:
                return 'October';
            break;
            case 11:
                return 'November';
            break;
            case 12:
                return 'December';
            break;
            
            default:
                return null;
            break;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // return $request->all();

            $module = \App\Workflow::where('id', $request->workflow_id)->first();
            $stage = \App\Stage::where('id', $request->stage_id)->first();

            $add = \App\DelegateRole::updateOrCreate(
                ['id' => $request->id],
                [
                    'approval_request_id' => $request->approval_request_id,
                    'workflow_id' => $request->workflow_id,
                    'stage_id' => $request->stage_id,
                    'delegate_id' => $request->delegate_id,
                    'end_date' => $request->end_date,
                    'message' => $request->message,
                    'delegated_by' => \Auth::user()->id,
                ]
            );

            if($request->workflow_id == 3){ $module_type = 'Leave Approval'; }
            elseif($request->workflow_id == 5){ $module_type = 'Payroll Approval'; }
            //APPROVAL TABLE RECORD
            $save = \App\DelegateApproval::create(
                [
                    'module_type' => $module_type,
                    'approval_request_id' => $request->approval_request_id,
                    'stage_id' => $request->stage_id,
                    'status' => 0,
                    'first' => 0,
                    'approved_by' => Auth::user()->id,
                ]
            );

            //email notification
            $this->notify_delegate($request->delegate_id, $request->end_date, $module->name, $stage->name, $request->message, $add->approval_request_id);

            if ($request->ajax()) {
                return response()->json(['status' => 'ok', 'info' => 'Role delegated successfully.']);
            } else {
                return redirect()->back()->with(['status' => 'ok', 'info' => 'Role delegated successfully.']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'Sorry, An Error Occurred Please Try Again. ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    public function delegateApprovals($id)
    {
        //
        $delegate_approval = \App\LeaveRequest::where('id', $id)->where('id', $id)->first();
        $delegate = \App\DelegateRole::where('approval_request_id', $id)->first();

        $user = \Auth::user();

        $controllerName = new DelegateRoleController;

        return view('delegates.approvals', compact('user', 'delegate_approval', 'delegate', 'controllerName'));
    }

    public function approvalPayroll($id)
    {
        //
        $delegate_approval = \App\Payroll::where('id', $id)->where('id', $id)->first();
        $delegate = \App\DelegateRole::where('approval_request_id', $id)->first();

        $user = \Auth::user();

        $controllerName = new DelegateRoleController;

        return view('delegates.approvals-payroll', compact('user', 'delegate_approval', 'delegate', 'controllerName'));
    }

    public function getApprovalStatus($id)
    {
        $approval = \App\LeaveApproval::where('leave_request_id', $id)->orderBy('id', 'desc')->first();
        if ($approval) {
            $stage = \App\Stage::where('id', $approval->stage_id)->first();
            if ($stage) {
                return $stage->name;
            } else {
                return null;
            }
        } else {
            return 'N/A';
        }
    }


    public function getDelegateApprovalStatus($id)
    {
        $delegate = \App\DelegateRole::where('approval_request_id', $id)->first();
        if ($delegate) {
            $stage = \App\Stage::where('id', $delegate->stage_id)->first();
            if ($stage) {
                return $stage->name;
            } else {
                return null;
            }
        } else {
            return 'N/A';
        }
    }

    public function getDelegateApprovalStageId($id)
    {
        $delegate = \App\DelegateRole::where('approval_request_id', $id)->first();
        if ($delegate) {
            $stage = \App\Stage::where('id', $delegate->stage_id)->first();
            if ($stage) {
                return $stage->id;
            } else {
                return null;
            }
        } else {
            return 'N/A';
        }
    }



    public function getSelectedWorkflowStage(Request $request)
    {
        $stage = \App\Stage::where('workflow_id', $request->workflow_id)->first();
        return response()->json($stage);
    }

    public function getWorkflowStages(Request $request)
    {
        $stages = \App\Stage::where('workflow_id', $request->workflow_id)->get();
        return response()->json($stages);
    }

    public function getDelegateDetails(Request $request)
    {
        $delegate = \App\DelegateRole::where('id', $request->id)->first();
        return response()->json($delegate);
    }
}
