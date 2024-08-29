<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addQuestionModal" aria-hidden="true" aria-labelledby="addQuestionModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog modal-dialog-scrollable">
	      <form class="form-horizontal" id="addQuestionForm"  method="POST">
	        <div class="modal-content">
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add Question</h4>
	        </div>
            <div class="modal-body" style="max-height: 700px; overflow-y: auto;">
                <div class="row row-lg col-xs-12">
                  <div class="col-xs-12" style = "width:100%;">
                  	@csrf
                    <div class="form-group">
                      <h4 class="example-title">Question</h4>
                      <textarea class="form-control" rows = "4" name = "question" id = "question"></textarea>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Question Category</h4>
                      <select class="form-control" id = "question_category" name="category">
                        <option value="">- SELECT -</option>
                        @foreach($question_categories as $category)
                          <option value="{{$category->id}}">{{$category->category}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Assing To</h4>
                      <select class="form-control" name="assign_method" id = "assign" >
                        <option value="">- SELECT -</option>
                        <option value="user">user</option>
                        <option value="manager">manager</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Question Type</h4>
                      <select class="form-control" name="type" id = "question_type" >
                        <option value="">- SELECT -</option>
                        <option value="textbox">TextBox</option>
                        <option value="checkbox">CheckBox</option>
                        <option value="radiobutton">Radio Button</option>
                        <option value="textarea">TextArea</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <h4 class="example-title">Status</h4>
                      <select class="form-control" name="status" id = "question_status" >
                        <option value="">- SELECT -</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                      </select>
                    </div>
                    
                    <div class="form-group">
                          <h4 class="example-title">Compulsory</h4>
                          <input type="radio" id="is_compulsory"  name="compulsory"
                          value="yes"> Yes
                          <input type="radio" id="is_compulsory" name="compulsory"
                          value="no"> No
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
