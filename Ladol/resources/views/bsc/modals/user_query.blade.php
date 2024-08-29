<div class="modal fade in modal-3d-flip-horizontal modal-info" id="queries" aria-hidden="true" aria-labelledby="queries" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">

	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Queries</h4>
	        </div>
            <div class="modal-body">
            <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Issued By</th>
                    <th>Content</th>
                    <th>Action Taken</th>
                    <th>Issued On</th>
                  </tr>
                </thead> 
                <tbody> 
                  @foreach($userQuery as $userQ)
                  @if(strtotime($userQ->created_at) >= strtotime($evaluation->measurement_period->from) && strtotime($userQ->created_at)  <= strtotime($evaluation->measurement_period->to))
                  <tr>
                    <td>{{$userQ->createdby->name}}</td>
                    <td>{{$userQ->content}}</td>
                    <td>{{$userQ->action_taken}}</td>
                    <td>{{date('F-Y',strtotime($userQ->created_at))}}</td>
                  </tr>
                  @endif
                  @endforeach
                </tbody>
              </table>
                
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
             </div>
	       </div>

	    </div>
	  </div>