<div class="modal fade in modal-3d-flip-horizontal modal-info" id="UploadDocumentRequestFileModal" aria-hidden="true" aria-labelledby="addDocumentRequestModal" role="dialog" >
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="UploadDocumentRequestFileForm" enctype="multipart/form-data">
	        <div class="modal-content">
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Upload Document Request File </h4>
	        </div>
            <div class="modal-body">
            @csrf
          <div class="form-group">
            <label for="">FIle</label>
              <input type="file" class="form-control" name="requested_doc">
          </div>


          <input type="hidden" name="type" value="upload_document_request_file">
                <input id="document_request_id" type="hidden" name="document_request_id" value="">

            </div>
            <div class="modal-footer">
              <div class="col-xs-12">

                  <div class="form-group">

                    <button type="submit" class="btn btn-info pull-left">Submit</button>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                  </div>
                  <!-- End Example Textarea -->
                </div>
             </div>
	       </div>
	      </form>
	    </div>
	  </div>
