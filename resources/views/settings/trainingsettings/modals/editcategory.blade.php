<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editCategoryModal" aria-hidden="true" aria-labelledby="editCategoryModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog modal-dialog-scrollable">
	      <form class="form-horizontal" id="addCategoryForm"  method="POST">
	        <div class="modal-content">
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Edit Training Category</h4>
	        </div>
            <div class="modal-body" style="max-height: 700px; overflow-y: auto;">
                <div class="row row-lg col-xs-12">
                  <div class="col-xs-12" style = "width:100%;">
                   <input type = "hidden" name = "category_id" id = "category_id" value=""/>
                  	@csrf
                    <div class="form-group">
                      <h4 class="example-title">Name</h4>
                      <input type="text" name="name" id = "editcategory" class="form-control" >
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Description</h4>
                      <textarea class="form-control" rows = "5" name = "description" id = "editcategory_description"></textarea>
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
