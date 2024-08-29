<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreTrainingPlan;
use Illuminate\Http\Request;
use App\TrainingPlan;
use App\TrainingData;
use App\UserTrainingPlan;
use App\Jobs\ProcessTrainingsJob;
use Illuminate\Support\Facades\Validator;
use App\RejectedTraining;
use Illuminate\Support\Facades\DB;

class TrainingPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(StoreTrainingPlan $request)
    {
        try{
            $total = intval($request['cost_per_head']) * intval($request['enrollees_no']);
            if($request['assign_mode'] == "employees"){
                $user_ids = json_encode(explode(',', $request['employee_ids']));
            }

            if($request['assign_mode'] == "department"){
                $job_ids = json_encode(explode(',', $request['jobroles_id']));
            }

            $name = TrainingData::find($request['training_name']);

            $plan = TrainingPlan::create([
                'name'             =>  $name->name,
                'description'      =>  $request['training_description'],
                'enrollees_number' =>  $request['assign_mode'],
                'cost_per_head'    =>  (int)str_replace(',', '', $request['cost_per_head']),
                'grand_total'      =>  $total,
                'mode'             =>  $request['training_mode'],
                'assign_mode'      =>  $request['assign_mode'],
                'type'             =>  $request['type'],
                'supervisor'       =>  $request['supervisor'],
                'resource_link'    =>  $request['resource_link'],
                'start_date'       =>  $request['start_date'],
                'end_date'         =>  $request['stop_date'],
                'duration'         =>  $request['duration'],
                'employee_email'   =>  $request['employee_email'],
                'enrollees_number' =>  $request['enrollees_no'],
                'course_type'      =>  $request['course_type'],
                'department_id'    =>  $request['assign_mode'] == "department" ?  (int) $request['department_id'] : null ,
                'group_id'         =>  $request['group_id'],
                'jobroles_id'      =>  $request['assign_mode'] == "department" ? $job_ids : null,
                'location'         =>  $request['location'],
                'user_ids'         =>  $request['assign_mode'] == "employees" ?  $user_ids: null
            ]);
            
            //Dispatch an event to assign Training::

            if($request['assign_mode'] == 'department'){
                ProcessTrainingsJob::dispatch('jobrole', $plan->jobroles_id, $plan->id, $request['training_id']);
            }else if($request['assign_mode'] == 'employees'){
                ProcessTrainingsJob::dispatch('employee', $plan->user_ids, $plan->id, $request['training_id']);
            }else{
                ProcessTrainingsJob::dispatch('group', $plan->group_id, $plan->id, $request['training_id']);
            }
           
            return response()->json([
                'message'   => 'Training plan added successfully.',
                'status'    =>  200
            ], 200);

       }catch(\Throwable $e){
            return response()->json([
                'message' => $e->getMessage(),
                'status'  =>  500,
            ], 500);
       }
    }


    public function rejectTraining(Request $request){
        $validator = Validator::make($request->all(), [
            'reason'      => 'required',
            'auth_id'     => 'required',
            'training_id' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'validation_errors' => $validator->messages()
            ]);
        }

        $plan = TrainingPlan::find($request['training_id']);
        if($plan->active == "1"){
            return response()->json(['message' => 'You cannot reject an ongoing training', 'code' => 201 ], 201);
        }
        try{
            $training = RejectedTraining::create([
                'reason'           => $request['reason'],
                'user_id'          => $request['auth_id'],
                'training_plan_id' => $request['training_id']
            ]);
            return response()->json(['message' => 'request successful', 'code' => 201 ], 201);
        }catch(\Exception $e){
            return response()->json(['message' => 'Something went wrong', 'code' => 500], 500);
        }
    }
     
    public function startTraining(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'user_id'           => 'required',
            'training_plan_id'  => 'required',
            'user_training_id'  => 'required'
        ]);

        $plan = TrainingPlan::find($request['training_plan_id']);

        //If the training has not been approved redirect back::
        
        if($plan->is_approved != "1"){
           return redirect()->back()->with('message', 'You cannot start an unapproved training!');
        }
        if($plan->status == "pending"){
            return redirect()->back()->with('message', 'Kindly wait till the scheduled day to start this training.');
        }

        $training_assigned = UserTrainingPlan::findOrFail($request['user_training_id']);
        $training_assigned->user_active = "1";
        $training_assigned->update();
        return redirect()->back()->with('message', 'training started successfully');
    }

    public function completeTraining(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'user_id'           => 'required',
            'training_plan_id'  => 'required',
            'user_training_id'  => 'required'
        ]);

        $plan = TrainingPlan::find($request['training_plan_id']);

        //If the training has not been approved redirect back::
        
        if($plan->is_approved != "1"){
           return redirect()->back()->with('message', 'You cannot complete an unapproved training!');
        }
        if($plan->status != "ongoing"){
            return redirect()->back()->with('message', 'You cannot complete a training not started');
        }

        $training_assigned = UserTrainingPlan::findOrFail($request['user_training_id']);
        $training_assigned->user_active = "2";
        $training_assigned->update();
        return redirect()->back()->with('message', 'training completed successfully');
    }



    /**
     * Display the specified resource.
     *ß
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
      
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
}
