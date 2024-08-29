<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editTrainingModal" aria-hidden="true" aria-labelledby="editTrainingModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog modal-dialog-scrollable">
	      <form class="form-horizontal" id="editTrainingForm"  method="POST">
	        <div class="modal-content">
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Edit Training</h4>
	        </div>
            <div class="modal-body" style="max-height: 700px; overflow-y: auto;">
                <div class="row row-lg col-xs-12">
                  <div class="col-xs-12" style = "width:100%;">
                  	@csrf
                    <div class="form-group">
                      <h4 class="example-title">Name</h4>
                      <input type="text" id = "editname" name="name" class="form-control" >
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Description</h4>
                      <textarea class="form-control" id = "editdescription" rows = "5" name = "description"></textarea>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Category</h4>
                      <select class="form-control" name="category" id = "editcategory">
                        <option value="">- SELECT -</option>
                        @foreach($categories as $category)
                          <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Cost Per Head</h4>
                      <input type="text" name="cost_per_head" id = "editcost_per_head" class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Class Size</h4>
                      <input type="text" name="class_size" id = "editclass_size" class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Mode of Training</h4>
                      <select class="form-control" name="mode_of_training" id = "editmode_of_training" >
                          <option value="">- SELECT -</option>
                        @foreach($types as $type)
                          <option value="{{$type->id}}">{{$type->type}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Training Location</h4>
                      <input type="text" name="training_location" id = "edittraining_location" class="form-control" >
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Training URL</h4>
                      <input type="text" name="training_url" id = "edittraining_url" class="form-control" >
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Training Duration</h4>
                      <input type="text" name="training_duration" id = "edittraining_duration" class="form-control" >
                    </div>
                    <div class="form-group">
                          <h4 class="example-title">Does this training require certification ?</h4>
                          <select class="form-control" id = "editis_certification" name="is_certification" >
                              <option value="all">- SELECT -</option>
                              <option value="1" >Yes</option>
                              <option value="0" >No</option>
                          </select>
                      </div>
                      <div class="form-group">
                          <h4 class="example-title">Type of Training </h4>
                          <input type="radio" id="editold_int"  name="type_of_training"
                          value="internal"> Internal
                          &nbsp;<input type="radio" id="editnew_int" name="type_of_training"
                          value="external"> External
                      </div>
                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>
                </div>
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
                  <div class="form-group">
                    <button type="submit" class="btn btn-info"><i class="fa fa-check"
                                aria-hidden="true"></i>&nbsp;Save</button>
                    <button type="button" style = "background-color:red; color:white;" class="btn btn-cancel" data-dismiss="modal"><i class="fa fa-times"
                    aria-hidden="true"></i>&nbsp;Cancel</button>
                  </div>
                  <!-- End Example Textarea -->
                </div>
             </div>
	       </div>
	      </form>
	    </div>
	  </div>
