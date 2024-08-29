<div class="modal fade in modal-3d-flip-horizontal modal-info" id="approveLeaveRequestModal" aria-hidden="true" aria-labelledby="approveLeaveRequestModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="approveLeaveRequestForm" enctype="multipart/form-data">
	        <div class="modal-content">
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Approve Leave Request</h4>
	        </div>
            <div class="modal-body">

            @csrf

          <div class="form-group">
            <label for="">Approve/Reject</label>
            <select class="form-control" id="approval" name="approval"  data-allow-clear="true">

              <option value="1">Approve</option>
              <option value="2">Reject</option>
            </select>
          </div>

          <div class="form-group">
            <label for="">Comment</label>
            <textarea class="form-control" id="comment" name="comment" style="height: 100px;resize: none;" placeholder="Comment" ></textarea>
          </div>
          <input type="hidden" name="type" value="save_relieve_approval">
          <input type="hidden" name="leave_request_id" id="recall_id" >

            </div>
            <div class="modal-footer">
              <div class="col-xs-12">

                  <div class="form-group">

                    <button type="submit" class="btn btn-info pull-left">Approve/Reject</button>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                  </div>
                  <!-- End Example Textarea -->
                </div>
             </div>
	       </div>
	      </form>
	    </div>
	  </div>
