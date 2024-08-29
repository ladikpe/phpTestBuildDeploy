<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Training Plan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form>
            <div class="modal-body" style="max-height: 700px; overflow-y: auto;">
                    <div>
                        <div class="form-group">
                              <label for="exampleInputEmail1">Course Type</label>
                              <select class="form-control" id = "course_type" onChange = "FetchTrainingDetails()">
                                  <option value = "">- SELECT -</option>
                                    <option value = "udemy">Udemy Courses</option>
                                    <option value = "non-udemy">Non-Udemy Courses</option>
                              </select>
                        </div>
                        <div style = "display:none;" id = "plan_base">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Training Name</label>
                                <select class="form-control" id = "training_name" onChange = "FetchMainDetails()">
                                     <option value = "">- SELECT -</option>
                                </select>
                            </div>
                        </div>
                        <div id = "plan_preload" style="display:none;">
                            <img src  = "{{asset('assets/loaders/loader.gif')}}"/>
                        </div>
                        <div id = "sub_base" style="display:none;">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Training Description</label>
                                <textarea  class="form-control" id="training_description" placeholder="Enter training description" rows = "5" disabled></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Cost per Head</label>
                                <input type="text" class="form-control" id="cost_per_head" placeholder="Enter Cost per head" disabled>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Class Size</label>
                                <input type="text" class="form-control" id="class_size" placeholder="Enter Class Size" disabled>
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Type</label>
                                <input type="text" class="form-control" id="training_type" placeholder="Enter Training type" disabled>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Resource Link</label>
                                <input type="text" class="form-control" id="resource_link" placeholder="" disabled>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Training Location</label>
                                <input type="text" class="form-control" id="training_location" placeholder="Enter Resource Link" disabled>
                            </div>
                            <input type="hidden" value="" name = "training_id" id = "training_id"/>
                        </div>
                        <div id = "sub_preload" style="display:none;">
                            <img src  = "{{asset('assets/loaders/loader.gif')}}"/>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Assign To</label>
                            <select class="form-control" id = "assign_mode" onChange = "ToggleMode()">
                                <option value = "">- SELECT -</option>
                                <option value = "employees">employees</option>
                                <option value = "department">department</option>
                                <option value = "group">groups</option>
                            </select>
                            <small id="emailHelp" class="form-text text-muted">kindly select a mode for assigning</small>
                        </div>
                        <div>
                            
                            <div class="form-group" style = "display:none;" id = "employee_mode">
                                <label for="exampleFormControlSelect1">Employee </label>
                                <select class="mul-select" multiple="true" id="employee_email">
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>    
                                    @endforeach
                            </select>
                            </div>
                        </div>
                        <div id = "group_mode" style="display:none;">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">User Groups</label>
                                <select class="form-control" id = "user_groups">
                                    <option value = "">- SELECT -</option>
                                    @foreach($groups as $group)
                                        <option value = "{{$group->id}}">{{$group->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id = "designation_mode">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Department</label>
                                <select class="form-control" id = "department" onchange="getJobRoles()" >
                                    <option value = "">- SELECT -</option>
                                    @foreach($departments as $department)
                                    <option value = "{{$department->id}}}">{{$department->name}}</option>
                                    @endforeach
                                </select>
                                <small id="emailHelp" class="form-text text-muted">kindly select a department</small>
                            </div>
                            <div id = "job_base" style="display:none;">
                                <div class="form-check" style="margin-left: 40px;" id = "job_rolesbox">
                                    
                                </div>
                            </div>
                            <div id = "job_preload" style="display:none;">
                                 <img src  = "{{asset('assets/loaders/loader.gif')}}"/>
                            </div>
                           
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Mode of Training</label>
                            <select class="form-control" id="training_mode">
                            <option value = "">- SELECT -</option>
                            <option value = "mandatory">mandatory</option>
                            <option value = "optional">optional</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="exampleInputPassword1">Start Date</label>
                            <input type="date" class="form-control" id="start_date" placeholder="Enter Stop Date">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Stop Date</label>
                            <input type="date" class="form-control" id="stop_date" placeholder="Enter Stop Date">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Duration</label>
                            <input type="text" class="form-control" id="duration" placeholder="Enter Duration" disabled>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <div id = "train_preload" style = "display:none;">
                    <img src  = "{{asset('assets/loaders/preloader.gif')}}"/>
                </div>
                <div id = "train_base">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon fa fa-times"
                            aria-hidden="true"></i>&nbsp;Close</button>
                    <button type="button" onClick = "submitTraining()" class="btn btn-success"><i class="icon fa fa-check"
                            aria-hidden="true"></i>&nbsp;Save</button>
                </div>
            </div>
        </form>
    </div>
  </div>
</div>