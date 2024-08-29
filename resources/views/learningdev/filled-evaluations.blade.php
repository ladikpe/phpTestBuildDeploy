@extends('learningdev.layouts.app')
@section('stylesheets')
<link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">

<link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
<link rel="stylesheet" href="{{ asset('global/vendor/alertify/alertify.min.css') }}">
<link rel="stylesheet" href="{{ asset('global/vendor/morris/morris.css')}}">
<link rel="stylesheet" href="../../../global/vendor/ladda/ladda.css">
<style type="text/css">
  .btn[disabled] {
    pointer-events: none;
    cursor: not-allowed;
  }
</style>
@endsection
@section('content')
<!-- Page -->
<div class="page ">
  <div class="page-header" style="height:220px; display:flex; align-items:center;">
  <div>
    <h1 class="page-title">User Evaluation Reports</h1>
    <ol class="breadcrumb mt-2" style = "margin-top:8px !important;">
      <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
      <li class="breadcrumb-item "><a href="{{route('manager_dashboard')}}">Dashboard</a></li>
    </ol>
  </div>
  
    <div class="page-header-actions">
      <div style="display: flex; flex-direction:row; justify-content:space-between;">
        <div class="col-sm-6 col-xs-12">
           
        </div>
        <div class="col-sm-6 col-xs-12">
    
        </div>
      </div>
    </div>
  </div>

  <div class="page-content container-fluid bg-white">    
    <div class="panel panel-info panel-line">
      <div class="panel-body">
        <table class="table table-striped table-bordered" id="dataTable">
          <thead>
            <tr style = "background-color:#03A9F4 !important; padding:5px !important; color:white !important;">
              <th>S/N</th>
              <th>Name</th>
              <th>Training Plan</th>
              <th>Filled Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @php
              $n = 0; 
            @endphp
            @forelse($feedbacks  as  $feedback)
              <tr id="">
                  <td>{{++$n}}</td>
                  <td>{{$feedback->user->name}}</td>
                  <td>{{$feedback->trainingPlan->name}}</td>
                  <td>{{$feedback->created_at}}</td>
                  <td>
                    <div style = "display:flex; flex-direction:row;">          
                          <a type = "button" style = "margin-right:10px;" href = "{{route('user-report', ['id' => $feedback->user_id, 'plan' => $feedback->trainingPlan->id])}}" class = "btn btn-sm btn-success btn-custom " style = "color:black;"><i class="icon fa fa-eye"
                            aria-hidden="true"></i>&nbsp;View</a>
                    </div>
                  </td>
              </tr>
            @empty
             <tr id="">
                <td style="color:black;">No Feedback has been submitted!</td>
              </tr>
            @endforelse
          </tbody>
        </table>
       
      </div>
    </div>
  </div>
</div>

</div>
</div>
<!-- End Page -->

@endsection
@section('scripts')
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}">
</script>
<script type="text/javascript" src="{{ asset('global/vendor/alertify/alertify.js') }}"></script>
<script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
<script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
<script src="{{ asset('global/vendor/raphael/raphael-min.js')}}"></script>
<script src="{{ asset('global/vendor/morris/morris.min.js')}}"></script>
<script src="{{ asset('global/vendor/ladda/spin.min.js')}}"></script>
<script src="{{ asset('global/vendor/ladda/ladda.min.js')}}"></script>
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/material.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
@include('learningdev.includes.script')

{{-- <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}">
</script>--}}
<script type="text/javascript">

</script>

@endsection