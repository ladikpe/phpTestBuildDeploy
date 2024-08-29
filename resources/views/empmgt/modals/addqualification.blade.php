<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addQualificationModal" aria-hidden="true" aria-labelledby="addQualificationModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="addQualificationForm"  method="POST">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add New Qualification</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                  	@csrf
                  	<div class="form-group">
                  		<h4 class="example-title">Qualification</h4>
                  		<select class="form-control" name="qualification_id" required>
                        @foreach($qualifications as $qualification)
                      <option value="{{$qualification->id}}">{{$qualification->name}}</option> 
                      @endforeach 
                      </select>
                  	</div>
                    <div class="form-group">
                      <h4 class="example-title">Title</h4>
                       <input type="text"  required placeholder="Qualification Title" name="title"   class="form-control">
                    </div>
                  	<div class="form-group">
                  		<h4 class="example-title">Institution</h4>
                  		 <input type="text"  required placeholder="Name of Institution" name="institution"   class="form-control">
                  	</div>
                    <div class="form-group">
                      <h4 class="example-title">Year</h4>
                       <input type="number"  required placeholder="Year" name="year"   class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Course</h4>
                       <input type="text"  required placeholder="Course" name="course"   class="form-control">
                    </div>
                  	<div class="form-group">
                  		<h4 class="example-title">Grade</h4>
                  		<input type="text"  required placeholder="Grade" name="grade"   class="form-control"> 
                  	</div>
                  	 <input type="hidden" name="user_id" value="{{$user->id}}">
                     <input type="hidden" name="type" value="academic_history">
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