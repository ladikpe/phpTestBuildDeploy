@extends('layouts.master')
@section('stylesheets')
 <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}"> 
 <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
@endsection

@section('content')
<!-- Page -->
  <div class="page ">
    <div class="page-header">
      <h1 class="page-title">{{__('Leave Plan Conflict')}}</h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item active">{{__('Leave Plan Conflict')}}</li>
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
        <h3 class="panel-title">Leave Conflict for {{currentYear()}}</h3>
        
        </div>
      <div class="panel-body">
        <div class="table-reponsive">
         <table class="table table-striped table-sm dtable" id="conflict-table">
          <thead>
            <tr>
            <th>Department</th>
            <th>Employee</th>
            <th>Conflict With</th>
            <th>On</th>
            <th>Message</th>
            <th>Status</th>           
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          
            @foreach($leave_conflicts as $leave_conflict)
             
                <tr>           
                  <td>{{$leave_conflict->department?$leave_conflict->department->name:''}}</td>
                  <td>{{$leave_conflict->user?$leave_conflict->user->name:''}}</td>
                  <td>{{$leave_conflict->conflict_user?$leave_conflict->conflict_user->name:''}}</td>
                  <td>{{date('M, j Y', strtotime($leave_conflict->date))}}</td>
                  <td>{{$leave_conflict->message}}</td>
                  <td> Pending </td>            
                  <td>
                    
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



@endsection
@section('scripts')

  <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
  <script src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
          
  <script type="text/javascript">
    $(function()
    {  
      //DATATABLE
      $(function () { $('.dtable').DataTable({"order": [0, "desc"]}); });
    });
  </script>

  </script>
@endsection