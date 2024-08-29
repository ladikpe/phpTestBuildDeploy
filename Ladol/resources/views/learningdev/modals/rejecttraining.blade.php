<!-- Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1" role="dialog" aria-labelledby="rejectionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rejectionModalLabel">Reject Training</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form>
            <div class="modal-body">
                <input type="hidden" value = "" id = "auth_id"/>
                <input type="hidden" value = "" id = "user_training_id"/>
                    <div>
                        <div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Reason for Rejection:</label>
                                <textarea  class="form-control" id="reason" placeholder="Enter reason for rejection" rows = "5"></textarea>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <div id = "reject_preload" style = "display:none;">
                    <img src  = "{{asset('assets/loaders/preloader.gif')}}"/>
                </div>
                <div id = "reject_base">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon fa fa-times"
                            aria-hidden="true"></i>&nbsp;Close</button>
                    <button type="button" onClick = "rejectTraining()" class="btn btn-success"><i class="icon fa fa-check"
                            aria-hidden="true"></i>&nbsp;Submit</button>
                </div>
            </div>
        </form>
    </div>
  </div>
</div>