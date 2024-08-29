<?php
namespace App\Traits;
use App\Project;
use App\ProjectTask;
use App\Client;
use Auth;
use Illuminate\Http\Request;

/**
 *
 */
trait ProjectTrait
{
	public function processGet($route,Request $request)
	{
		switch ($route) {
			case 'get_project':
			return $this->getProject($request);
			break;
			case 'view_project':
			return $this->viewProject($request);
			break;
			case 'get_project_task':
			return $this->getProjectTask($request);
			break;
			case 'get_project_members':
			return $this->getProjectMembers($request);
			break;
			case 'project_tasks':
			return $this->projectTasks($request);
			break;
			case 'project_members':
			return $this->projectMembers($request);
			break;
			case 'project_details':
			return $this->projectDetails($request);
			break;
			case 'delete_project_task':
			return $this->deleteProjectTask($request);
			break;
			case 'delete_project':
			return $this->deleteProject($request);
			break;
			case 'change_project_status':
			return $this->changeProjectStatus($request);
			break;
			case 'change_project_task_status':
			return $this->changeProjectTaskStatus($request);
			break;
			
			default:
				# code...
				break;
		}
	}

	public function processPost(Request $request)
	{
		switch ($request->type) {
			case 'project':
			return $this->saveProject($request);
			break;
			case 'task':
			return $this->saveTask($request);
			break;
			case 'project_member':

			return $this->saveProjectMembers($request);
			break;
			
			default:
				# code...
				break;
		}
	}

	public function getProject(Request $request)
	{
		return $project=Project::find($request->project_id);
		
	}
	public function viewProject(Request $request)
	{
		 $project=Project::find($request->project_id);
		 $clients=Client::all();
		 return view('project_management.project_details',compact('project','clients'));
		
	}
	public function getProjectTask(Request $request)
	{
		return $task=Project::find($request->project_task_id);
		
	}
	public function projectTasks(Request $request)
	{
		 $project=Project::find($request->project_id);
		return view('project_management.partials.tasks',compact('project'));
	}
	public function projectMembers(Request $request)
	{
		 $project=Project::find($request->project_id);
		return view('project_management.partials.members',compact('project'));
	}
	public function projectDetails(Request $request)
	{
		 $project=Project::find($request->project_id);
		return view('project_management.partials.details',compact('project'));
	}
	public function getProjectMembers(Request $request)
	{
		$members=Project::find($request->project_id)->project_members;
		return ['project_id'=>$request->project_id,'members'=>$members];
		
	}
	public function deleteProject(Request $request)
	{
		$project=Project::find($request->project_id);
		if ($project) {
			$project->delete();
		}
		return 'success';
	}
	public function deleteProjectTask(Request $request)
	{
		$task=ProjectTask::find($request->project_task_id);
		if ($task) {
			$task->delete();
		}
		return 'success';
	}
	public function changeProjectStatus(Request $request)
	  {
	   $project=Project::find($request->project_id);
	   if ($project) {
	     $project->update(['status'=>1]);
	   }
	   return 'success';
	  
	  }
	  public function changeProjectTaskStatus(Request $request)
	  {
	   $task=ProjectTask::find($request->project_task_id);
	   if ($task) {
	     $task->update(['status'=>1]);
	   }
	   return 'success';
	  
	  }

	  public function saveProject(Request $request)
	  {
	  	$project=Project::updateorCreate(['id'=>$request->id],['name'=>$request->name,'project_manager_id'=>$request->project_manager_id,'code'=>$request->code,'start_date'=>$request->start_date,'end_est_date'=>$request->end_est_date,'actual_ending_date'=>$request->actual_ending_date,'remark'=>$request->remark,'client_id'=>$request->client_id,'created_by'=>$request->created_by,'status'=>$request->status]);
	  	
        return 'success';
	  }
	  public function saveProjectTask(Request $request)
	  {
	  	$task=ProjectTask::create(['name'=>$request->name,'project_id'=>$request->project_id,'froms'=>$request->froms,'tos'=>$request->tos,'status'=>$request->status]);
	  	
        return 'success';
	  }
	  public function saveProjectMembers(Request $request)
	  {
	  	  $request->all();
	  	$project=Project::find($request->project_id);
	   $project_members_count=count($request->input('project_members'));

	  	 if($project_members_count>0){
      		$project->project_members()->detach();
              for ($i=0; $i <$project_members_count ; $i++) {
                if ($request->project_members[$i]!=0) {
                  $project->project_members()->attach($request->project_members[$i]);
                }
            
            }
        }
        return 'success';
	  }

}