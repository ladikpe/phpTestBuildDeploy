<!-- Modal -->
<div class="modal fade" id="budgetModal" tabindex="-1" role="dialog" aria-labelledby="budgetModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Set Up Budget</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form>
        <input type="hidden" id = "budget_id"/>
            <div class="modal-body" style="max-height: 700px; overflow-y: auto;">
                <div class="form-group">
                        <label for="exampleInputEmail1">Department</label>
                        <select class="form-control" id = "budget_department">
                            <option value = "">- SELECT -</option>
                        @foreach($departments as $department)
                            <option value = "{{$department->id}}">{{$department->name}}</option>
                        @endforeach
                        </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Allocation</label>
                    <input type="text" class="form-control" id="allocation" placeholder="Enter Cost Allocation" >
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Stop Date</label>
                    <input type="date" class="form-control" id="stop_date" placeholder="Enter Stop Date">
                </div>
            </div>
            <div class="modal-footer">
                <div id = "budget_preload" style = "display:none;">
                    <img src  = "{{asset('assets/loaders/preloader.gif')}}"/>
                </div>
                <div id = "budget_base">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon fa fa-times"
                            aria-hidden="true"></i>&nbsp;Cancel</button>
                    <button type="button" onClick = "saveBudget()" class="btn btn-success"><i class="icon fa fa-check"
                            aria-hidden="true"></i>&nbsp;Save</button>
                </div>
            </div>
        </form>
    </div>
  </div>
</div>