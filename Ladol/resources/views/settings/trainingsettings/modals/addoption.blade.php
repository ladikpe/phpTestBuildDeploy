<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addQuestionOptionModal" aria-hidden="true" aria-labelledby="addQuestionOptionModal" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-dialog-scrollable">
    <form class="form-horizontal" id="addQuestionOptionForm"  method="POST">
      <input id = "option_id" name="option_id" value="" type="hidden"/>
      <div class="modal-content">
      <div class="modal-header" >
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="training_title">Add Question Option</h4>
      </div>
        <div class="modal-body" style="max-height:200px; overflow: none !important;">
            <div class="row row-lg col-xs-12">
                <div class="col-xs-12" style = "width:100%;">
                  @csrf
                  <div class="form-group">
                    <h4 class="example-title">Option</h4>
                    <input class="form-control" type="text" name = "option" id = "option"/>
                  </div> 
                  <div class="form-group mt-2">
                    <h4 class="example-title">Mark Obtainable</h4>
                    <input class="form-control" type="text" name = "mark" id = "mark"/>
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

