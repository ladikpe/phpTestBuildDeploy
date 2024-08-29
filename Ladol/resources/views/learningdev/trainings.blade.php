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
    <h1 class="page-title">Training Plan</h1>
    <ol class="breadcrumb mt-2" style = "margin-top:8px !important;">
      <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
      <li class="breadcrumb-item "><a href="{{route('manager_dashboard')}}">Dashboard</a></li>
    </ol>
  </div>
  
    <div class="page-header-actions">
      <div style="display: flex; flex-direction:row; justify-content:space-between;">
        <div class="col-sm-6 col-xs-12">
            @if(auth()->user()->role_id == '1')
              <a type = "button" class = "btn custom-btn" data-toggle="modal" data-target="#exampleModal"><i class="icon fa fa-plus"
                      aria-hidden="true"></i>&nbsp;Add Training Plan</a>
            @endif
        </div>
        <div class="col-sm-6 col-xs-12">
        @if(auth()->user()->role_id == '1')
            <div class="btn-group" role="group" style="margin-left: 10px;">
              <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Export Data
              </button>
              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <a class="dropdown-item" href="{{route('trainings.export-new', ['arg' => 'users'])}}">By Users</a>
                <a class="dropdown-item" href="{{route('trainings.export-new', ['arg' => 'departments'])}}">By Job Roles</a>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  <div class="page-content container-fluid bg-white">    
    <div class="panel panel-info panel-line">
      <div class="panel-body">
          @if(session()->has('message'))
              <div class="alert alert-success">
                  {{ session()->get('message') }}
              </div>
          @endif

        @include('learningdev.includes.search')

        <table class="table table-striped table-bordered" id="dataTable">
          <thead>
            <tr style = "background-color:#03A9F4 !important; padding:5px !important; color:white !important;">
              <th>S/N</th>
              <th>Training Name</th>
              <th>Cost Per Head</th>
              <th>Start Date</th>
              <th>Stop Date</th>
              <th>Status</th>
              <th>Type</th>
              <th>Mode</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
           @if(count($plans) > 0)
                @php
                    $n = 0;
                @endphp
                @forelse($plans as $plan)
                    <tr id="">
                    <td>{{++$n}}</td>
                    <td>{{$plan->name}}</td>
                    <td>{{number_format($plan->cost_per_head, 2)}}</td>
                    <td>{{$plan->start_date}}</td>
                    <td>{{$plan->end_date}}</td>
                    <td>
                      @if($plan->status == 'ongoing')
                        <span class="badge-success">{{$plan->status}}</span>
                      @elseif($plan->status == 'overdue')
                        <span class="badge-red">{{$plan->status}}</span>
                      @else
                        <span class="badge-blue">{{$plan->status}}</span>
                      @endif
                    </td>
                    <td>{{$plan->type}}</td>
                    <td>{{$plan->mode}}</td>
                    <td>
                      <div style = "display:flex; flex-direction:row;">
                            @if(auth()->user()->role->name == 'Employee')
                              <form method="POST" action = "{{route('start.trainings')}}">
                                  @csrf
                                  <input type = "hidden" name = "plan_id" value="{{$plan->id}}"/>
                                  <input type = "hidden" name = "auth_id" value="{{auth()->user()->id}}"/>
                                  <button type = "submit" style = "margin-right:10px;" class = "btn btn-sm btn-success btn-custom" style = "color:white;"><i class="icon fa fa-check"
                                    aria-hidden="true"></i>&nbsp;Start</button>
                              </form>
                              <form method="POST" action = "{{route('cancel.trainings')}}">
                                <button type = "submit" style = "margin-right:10px;" class = "btn btn-sm btn-warning btn-custom" style = "color:white;"><i class="icon fa fa-times"
                                aria-hidden="true"></i>&nbsp;Cancel</button>
                              </form>
                            @endif
                            @if((auth()->user()->role_id == '1') || (auth()->user()->role_id == '2'))
                            @if($plan->is_approved == "0" && auth()->user()->role_id == '2')
                                <a type = "button" style = "margin-right:10px;" href = "{{route('training_plan.approve', $plan->id)}}"class = "btn btn-sm btn-success btn-custom" style = "color:white;"><i class="icon fa fa-check"
                                  aria-hidden="true"></i>&nbsp;Approve</a>
                                  <a type = "button" style = "margin-right:10px;" href = "{{route('training_plan.reject', $plan->id)}}"class = "btn btn-sm btn-danger btn-custom" style = "color:white;"><i class="icon fa fa-times"
                                aria-hidden="true"></i>&nbsp;Reject</a>
                              @endif
                              <a type = "button" style = "margin-right:10px;" href = "{{route('viewtrainingplan', $plan->id)}}"class = "btn btn-sm btn-primary btn-custom" style = "color:white;"><i class="icon fa fa-eye"
                                aria-hidden="true"></i>&nbsp;View</a>
                              <!-- <a type = "button" style = "margin-right:10px;" href = "{{route('evaluate.data', $plan->id)}}"class = "btn btn-sm btn-warning btn-custom " style = "color:black;"><i class="icon fa fa-bar-chart"
                                aria-hidden="true"></i>&nbsp; Check Reports</a> -->
                              @if(!$plan->trashed())
                              <form method="POST" action="{{route('deletetrainingplan', $plan->id)}}">
                                  @csrf
                                  @method('DELETE')
                                  <button type = "submit" class = "btn btn-sm btn-danger btn-custom" style = "color:white;"><i class="icon fa fa-trash"  aria-hidden="true"></i>&nbsp;Deactivate</button> </td>
                              </form>
                              @else
                              <form method="POST" action="{{route('activatetrainingplan')}}">
                                  @csrf
                                  <input type="hidden" value="{{$plan->id}}" name = "plan_id"/>
                                  <button type = "submit" hre class = "btn btn-sm btn-warning btn-custom" style = "color:white;"><i class="icon fa fa-paper-plane"  aria-hidden="true"></i>&nbsp;Reactivate</button> </td>
                              </form>
                              @endif
                            @endif
                           
                      </div>
                    </td>
                    </tr>
                  @empty
                  <tr id="">
                    <td style="color:black;">No Data Available!</td>
                  </tr>
                @endforelse
            @endif
          </tbody>
        </table>
       
      </div>
    </div>
  </div>
</div>

</div>
</div>
<!-- End Page -->

@include('learningdev.modals.addplan')

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