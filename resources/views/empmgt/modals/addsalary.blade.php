<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addSalaryModal" aria-hidden="true" aria-labelledby="addSalaryModal" role="dialog" >
    <div class="modal-dialog ">
      <form class="form-horizontal" id="addSalaryForm"  method="POST">
        <div class="modal-content">        
        <div class="modal-header" >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="training_title">Add New Salary</h4>
        </div>
        <div class="modal-body">         
            <div class="row row-lg col-xs-12">            
              <div class="col-xs-12"> 
                  @csrf
                  
                <div class="form-group">
                  <h4 class="example-title">Salary</h4>
                    <input type="number" class="form-control " required step="0.01" name="salary" >
                </div>
                  <div class="form-group">
                      <h4 class="example-title">Effective Date</h4>
                      <input type="text" class="form-control datepicker" required autocomplete="off"  name="effective_date">

                  </div>

                   <input type="hidden" name="user_id" value="{{$user->id}}">
                 <input type="hidden" name="type" value="salary">
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