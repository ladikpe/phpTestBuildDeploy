@extends('layouts.master')
@section('stylesheets')
 <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}"> 
 <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
@endsection

@section('content')
<!-- Page -->
  <div class="page ">
    <div class="page-header">
      <h1 class="page-title">{{__('Delegate Role - Payroll')}}</h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item active">{{__('Delegate Role - Payroll')}}</li>
      </ol>
      <div class="page-header-actions">
        <div class="row no-space w-250 hidden-sm-down">

          <div class="col-sm-6 col-xs-12">
            <div class="counter">
              <span class="counter-number font-weight-medium">{{date('Y-m-d')}}</span>

            </div>
          </div>
          <div class="col-sm-6 col-xs-12">
            <div class="counter">
              <span class="counter-number font-weight-medium" id="time"></span>
            </div>
          </div>
        </div>
      </div>
  </div>
    
      <div class="page-content container-fluid">
        <div class="row" data-plugin="matchHeight" data-by-row="true">
  <!-- First Row -->
  
  
  <!-- End First Row -->
  {{-- second row --}}
  <div class="col-ms-12 col-xs-12 col-md-12">
    <div class="panel panel-info panel-line">
      <div class="panel-heading">
        <h3 class="panel-title">Roles Delegated {{$delegates->count()}}
            <button type="button" class="btn btn-primary btn-sm addNew" data-toggle="modal" data-target="#addModal" 
            title="Delegate Role" style="float:right" id="add"><i class="la la-plus"></i> Delegate Role - Payroll</button>
        </h3>
        
        </div>
      <div class="panel-body">
        <div class="table-reponsive">
         <table class="table table-striped table-sm dtable" id="conflict-table">
          <thead>
            <tr>
            <th style="color: transparent!important">#</th>
            <th>Approval Request Name</th>
            <th>Module Name</th>
            <th>Role Type</th>
            <th>Delegated to</th>
            <th>Expires On</th>
            <th>Delegated by</th>
            <th>Delegated On</th>           
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          
            @foreach($delegates as $delegate)
             
                <tr>           
                  <td style="color: transparent!important">{{$delegate->id}}</td>
                  <td>{{$controllerName->resolveMonth($delegate->payroll->month)}}, {{$delegate->payroll->year}}</td>
                  <td>{{$delegate->module?$delegate->module->name:''}}</td>
                  <td>{{$delegate->stage?$delegate->stage->name:''}}</td>
                  <td>{{$delegate->delegate?$delegate->delegate->name:''}}</td>
                  <td>{{date('M, j Y', strtotime($delegate->end_date))}}</td>
                  <td>{{$delegate->delegator?$delegate->delegator->name:''}}</td>
                  <td>{{date('M, j Y', strtotime($delegate->created_at))}}</td>         
                  <td>
                    <a href="{{ url('delegate-approvals', $delegate->approval_request_id) }}" class="btn-sm text-primary pull-right" data-toggle="tooltip" title="View Details For Approval"><i class="fa fa-eye" aria-hidden="true" style="font-size:13px"></i> </a>

                    <a href="#" class="btn-sm text-info pull-right" data-toggle="modal" data-target="#editModal" title="Edit Details" onclick="getDelegateDetailById({{$delegate->id}})"><i class="fa fa-pencil" aria-hidden="true" style="font-size:13px"></i> </a>                    
                  </td>
                </tr>
                
              @endforeach        
           
            </tbody>          
         </table>
         </div> 
                  
        </div>
      </div>

    </div>
    
  </div>
</div>
</div>








{{-- ADD MODAL --}}
<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addModal" aria-hidden="true" aria-labelledby="addModal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
        <div class="modal-header" >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="training_title">Role Delegate Form - Payroll </h4>
        </div>
        <form class="form-horizontal" id="addForm" method="POST" action="{{ route('delegate-role.store') }}">
        @csrf
          <div class="modal-body">

            <div class="form-group row">
                <label for="workflow_id" class="col-sm-3 col-form-label"> Module Name </label>
                <div class="col-sm-9">
                    <select class="form-control" name="workflow_id" id="workflow_id">
                        <option value=""></option>
                        @foreach ($workflows as $workflow)
                            <option value="{{$workflow->id}}">{{$workflow->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="stage_id" class="col-sm-3 col-form-label"> Role Type </label>
                <div class="col-sm-9">
                    <select class="form-control" name="stage_id" id="stage_id">
                        <option value=""></option>
                    </select>
                </div>                                             
            </div>

            <div class="form-group row">
                <label for="approval_request_id" class="col-sm-3 col-form-label"> Approval Request </label>
                <div class="col-sm-9">
                    <select class="form-control select2" name="approval_request_id" id="approval_request_id">
                        <option value=""></option>
                        @forelse($approval_requests as $approval_request)
                            <option value="{{$approval_request->id}}"> {{$controllerName->resolveMonth($approval_request->month)}}, {{$approval_request->year}}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="delegate_id" class="col-sm-3 col-form-label"> Delegate To </label>
                <div class="col-sm-9">
                    <select class="form-control select2" name="delegate_id" id="delegate_id">
                        <option value=""></option>
                        @forelse($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="end_date" class="col-sm-3 col-form-label"> Expire on </label>
                <div class="col-sm-9">
                    <input type="date" class="form-control" placeholder="Role expiry date" name="end_date" id="end_date" required>
                </div>                                             
            </div>

            <div class="form-group row">
                <label for="message" class="col-sm-12 col-form-label"> Message </label>
                <div class="col-sm-12">
                    <textarea rows="6" class="form-control" placeholder="Enter message here ... " name="message" id="message" required></textarea>
                </div>                                             
            </div>

          </div>

          <div class="modal-footer">
            <div class="col-xs-12">

                <div class="form-group">
                  <button type="submit" class="btn btn-success btn-sm pull-right" style="margin-left: 10px" onclick="return confirm('Are you sure you want to delegate role to the selected user ?')">Delegate</button>
                  <button type="button" class="btn btn-cancel btn-sm" data-dismiss="modal">Cancel</button>
                </div>
                <!-- End Example Textarea -->
              </div>
           </div>
           </form>
       </div>

    </div>
</div>




{{-- EDIT MODAL --}}
<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editModal" aria-hidden="true" aria-labelledby="editModal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
        <div class="modal-header" >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="training_title">Modify Role Delegate Form </h4>
        </div>
        <form class="form-horizontal" id="editForm" method="POST" action="{{ route('delegate-role.store') }}">
        @csrf
        <input type="hidden" class="form-control" name="id" id="id" required>
          <div class="modal-body">

            <div class="form-group row">
                <label for="_workflow_id" class="col-sm-3 col-form-label"> Module Name </label>
                <div class="col-sm-9">
                    <select class="form-control" name="workflow_id" id="_workflow_id">
                        <option value=""></option>
                        @foreach ($workflows as $workflow)
                            <option value="{{$workflow->id}}">{{$workflow->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="_stage_id" class="col-sm-3 col-form-label"> Role Type </label>
                <div class="col-sm-9">
                    <select class="form-control" name="stage_id" id="_stage_id">
                        <option value=""></option>
                    </select>
                </div>                                             
            </div>

            <div class="form-group row">
                <label for="_approval_request_id" class="col-sm-3 col-form-label"> Approval Request </label>
                <div class="col-sm-9">
                    <select class="form-control select2" name="approval_request_id" id="_approval_request_id">
                        <option value=""></option>
                        @forelse($approval_requests as $approval_request)
                            <option value="{{$approval_request->id}}"> {{$controllerName->resolveMonth($approval_request->month)}}, {{$approval_request->year}}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="_delegate_id" class="col-sm-3 col-form-label"> Delegate To </label>
                <div class="col-sm-9">
                    <select class="form-control select2" name="delegate_id" id="_delegate_id">
                        <option value=""></option>
                        @forelse($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="_end_date" class="col-sm-3 col-form-label"> Expire on </label>
                <div class="col-sm-9">
                    <input type="date" class="form-control" placeholder="Role expiry date" name="end_date" id="_end_date" required>
                </div>                                             
            </div>

            <div class="form-group row">
                <label for="_message" class="col-sm-12 col-form-label"> Message </label>
                <div class="col-sm-12">
                    <textarea rows="6" class="form-control" placeholder="Enter message here ... " name="message" id="_message" required></textarea>
                </div>                                             
            </div>

          </div>

          <div class="modal-footer">
            <div class="col-xs-12">

                <div class="form-group">
                  <button type="submit" class="btn btn-success btn-sm pull-right" style="margin-left: 10px" onclick="return confirm('Are you sure you want to re-delegate role to the selected user ?')">Re Delegate</button>
                  <button type="button" class="btn btn-cancel btn-sm" data-dismiss="modal">Cancel</button>
                </div>
                <!-- End Example Textarea -->
              </div>
           </div>
           </form>
       </div>

    </div>
</div>








@endsection
@section('scripts')

  <script src="{{ asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script src="{{ asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
  <script src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
          
  <script>
       
    function getDelegateDetailById(id)
    {
        $.get('{{url('get-delegate-details')}}?id=' +id, function(data)
        { 
            $('#id').val(id); 
            $('#_workflow_id').prop('value', data.workflow_id);
            $('#_stage_id').prop('value', data.stage_id);
            $('#_approval_request_id').prop('value', data.approval_request_id);
            $('#_delegate_id').prop('value', data.delegate_id);
            $('#_end_date').val(data.end_date);
            $('#_message').val(data.message); 

            $.get('{{url('get-selected-workflow-stage')}}?workflow_id=' + data.workflow_id, function(data)
            {  //success data
                $('#_stage_id').empty();
                $('#_stage_id').append('<option value="'+ data.id +'"> '+data.name+' </option>')

                $('#_stage_id').append('<option value=""> Select Stage  </option>')                
            });
            
            // APPEND OTHER ROLES
            $.get('{{url('get-workflow-stages')}}?workflow_id=' + data.workflow_id, function(data)
            {  //success data
                $.each(data, function(index, stage)
                {
                    $('#_stage_id').append('<option value="'+ stage.id +'"> '+stage.name+' </option>')
                });
            });
        });
    }


    $(function()
    { 
        $("#workflow_id").on('change', function(e)
        { 
            var workflow_id = e.target.value;
            $.get('{{url('get-workflow-stages')}}?workflow_id=' + workflow_id, function(data)
            {  //success data
                $('#stage_id').empty();
                $('#stage_id').append('<option value=""> Select Stage  </option>')
                $.each(data, function(index, stage)
                {
                    $('#stage_id').append('<option value="'+ stage.id +'"> '+stage.name+' </option>')
                });
            });
        });



         


      //DATATABLE
      $(function () { $('.dtable').DataTable({"order": [0, "desc"]}); });
    });
  </script>

  </script>
@endsection