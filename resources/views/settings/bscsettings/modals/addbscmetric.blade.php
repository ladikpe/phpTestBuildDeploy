<!--- Add Subject modal start -->
  <div class="modal fade in modal-3d-flip-horizontal modal-primary" id="addBscMetricModal" aria-hidden="true" aria-labelledby="addBscMetricModal"
  role="dialog" tabindex="-1">
   
    <div class="modal-dialog modal-lg">
      <form class="form-horizontal" id="addBscMetricForm" role="form" method="POST">
        <div class="modal-content">        
        <div class="modal-header" >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
          <h4 class="modal-title" id="training_title">Add BSC Metric</h4>
        </div>
        <div class="modal-body">         
               
          <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <h4 class="example-title">Name</h4>
                    <input required type="text" name="name" class="form-control">

                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <h4 class="example-title">Percentage</h4>
                    <input name="description" class="form-control" type="number"  required>
                  </div>
            </div>
          </div>
                    


                    <input type="hidden" name="type" value="bsc_metric">    
        </div>
        <div class="modal-footer">
          <div class="col-xs-12">
              <!-- Example Textarea -->
              <div class="form-group">
               
                {{ csrf_field() }}
                <div class="text-xs-left"><span class="no-left-padding" id="btn_div"><input type="submit" class="btn btn-primary waves-effect" id="sugtraining_btn" value="Save" ></span>
                <span class="no-left-padding"><input type="button" class="btn btn-default waves-effect" value="Cancel"  data-dismiss="modal"></span></div>
              </div>
              <!-- End Example Textarea -->
            </div>
         </div>
       </div>
      </form>
    </div>
  </div>
  <!-- Add subject modal end -->