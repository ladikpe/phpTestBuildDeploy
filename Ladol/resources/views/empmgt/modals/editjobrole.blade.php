<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editJobRoleModal" aria-hidden="true" aria-labelledby="assignJobRoleModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="editJobRoleForm"  method="POST">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Edit Job Role</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                  	@csrf
                  	
                
                  	<div class=" form-group">
                                <div class="form-group " >
                                  <label class="form-control-label" for="jobroles">Job Role</label>
                                  <select class="form-control" id="jobroles"  required disabled>
                                    
                                    <option value="vbv" id = 'job_role_option' selected ></option>
                                   
                                  </select>
                                </div>
                              </div>
                    <div class="form-group">
                      <h4 class="example-title">Started</h4>
                       <input type="text" id = "job_role_start_date"  required placeholder="Started"   class="form-control datepicker" disabled>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Ended</h4>
                       <input type="text"   placeholder="Ended" name="ended"   class="form-control datepicker">
                    </div>
                  	 <input type="hidden" name="user_id" value="{{$user->id}}">
                     <input type="hidden" name="type" value="job_history">
                     <input type="hidden" name="job_id" value="" id = 'job_send_id'>
                     <input type="hidden" name="started" value="" id = 'job_send_started'>
                     
                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>            
                </div>        
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
              
                  <div class="form-group">
                    
                    <button type="submit" class="btn btn-info pull-left">Save</button>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                  </div>
                  <!-- End Example Textarea -->
                </div>
             </div>
	       </div>
	      </form>
	    </div>
	  </div>