<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editCertificationModal" aria-hidden="true" aria-labelledby="editCertificationModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="editCertificationForm"  method="POST">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Edit Certification</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                  	@csrf
                      <div class="form-group">
                          <h4 class="example-title">Expires?</h4>
                          <select class="form-control" name="expires" required>

                              <option value="1">Yes</option>
                              <option value="0">No</option>

                          </select>
                      </div>
                      <div class="form-group">
                          <h4 class="example-title">Title</h4>
                          <input type="text"  required placeholder="Certificate Title" name="title" id="editctitle"  class="form-control">
                      </div>
                      <div class="form-group">
                          <h4 class="example-title">Credential ID</h4>
                          <input type="text"  required placeholder="Credential ID" name="credential_id" id="editccredential_id"   class="form-control">
                      </div>

                      <div class="form-group">
                          <h4 class="example-title">Issued On</h4>
                          <input type="text"  required placeholder="Issued On" name="issued_on" id="editcissued_on" readonly class="form-control datepicker">
                      </div>
                      <div class="form-group">
                          <h4 class="example-title">Expires On</h4>
                          <input type="text"  required placeholder="Expires On" name="expires_on" id="editcexpires_on" readonly class="form-control datepicker">
                      </div>
                      <div class="form-group">
                          <h4 class="example-title">Issuer Name</h4>
                          <input type="text"  required placeholder="Issuer name" name="issuer_name"  id="editcissuer_name" class="form-control">
                      </div>
                      <div class="form-group">
                          <h4 class="example-title">Certificate file</h4>
                          <input type="file"  required  name="certificate_file"   class="form-control">
                      </div>
                  	 <input type="hidden" name="user_id" value="{{$user->id}}">
                     <input type="hidden" name="type" value="certification">
                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>            
                </div>        
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
              	<input type="hidden" name="cert_id" id="cert_id" >
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