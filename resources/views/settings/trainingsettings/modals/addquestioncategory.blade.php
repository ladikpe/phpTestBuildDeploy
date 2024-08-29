<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addQuestionCatModal" aria-hidden="true" aria-labelledby="addQuestionCatModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog modal-dialog-scrollable">
	      <form class="form-horizontal" id="addQuestionCatForm"  method="POST">
	        <div class="modal-content">
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add Question Category</h4>
	        </div>
            <div class="modal-body" style="max-height:200px; overflow: none !important;">
                <div class="row row-lg col-xs-12">
                  <div class="col-xs-12" style = "width:100%;">
                  	@csrf
                    <div class="form-group">
                      <h4 class="example-title">Question</h4>
                      <input class="form-control" type="text" name = "category" id = "question_cat"/>
                    </div> 
                </div>
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
               
                </div>
             </div>
	       </div>
	      </form>
	    </div>
	  </div>
