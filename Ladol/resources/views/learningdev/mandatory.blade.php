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

  .row_selected {
    background-color: #00bcd4 !important;
    z-index: 9999;
    color: #fff;
  }

  .custom-btn{
    background-color:#0803F4;
    box-shadow: 0px 2px 12px 4px rgba(0, 0, 0, 0.16) !important;
    border-radius: 10px;
    padding: 7px 10px !important;
    color:white !important;
    width: 180px !important;
  }
  th{
    color:white !important;
    padding:12px !important;
  }

  .btn-custom{
    text-decoration: none !important;
  }
</style>
@endsection
@section('content')
<!-- Page -->
<div class="page ">
  <div class="page-header">
    <h1 class="page-title">Mandatory Trainings</h1>
    <ol class="breadcrumb mt-2" style = "margin-top:8px !important;">
      <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
      <li class="breadcrumb-item "><a href="{{route('manager_dashboard')}}">Dashboard</a></li>
    </ol>
    <div class="page-header-actions">
      <div class="row no-space w-250 hidden-sm-down">

        <div class="col-sm-6 col-xs-12">
            @if(auth()->user()->role->id == '1')
              <a type = "button" class = "btn custom-btn" data-toggle="modal" data-target="#exampleModal"><i class="icon fa fa-plus"
                      aria-hidden="true"></i>&nbsp;Add Training Plan</a>
            @endif
        </div>
        <div class="col-sm-6 col-xs-12">
          
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
        <table class="table table-striped table-bordered" id="dataTable">
          <thead>
            <tr style = "background-color:#03A9F4 !important; padding:5px !important; color:white !important;">
              <th>S/N</th>
              <th>Training Name</th>
              <th>Cost Per Head</th>
              <th>Start Date</th>
              <th>Stop Date</th>
              <th>Type</th>
              <th>Mode</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>    
            @if(auth()->user()->role->id == '1')
              @if(count($plans) > 0)
                @php
                    $n = 0;
                @endphp
                @foreach($plans as $plan)
                    <tr id="">
                    <td>{{++$n}}</td>
                    <td>{{$plan->name}}</td>
                    <td>{{number_format($plan->cost_per_head, 2)}}</td>
                    <td>{{$plan->start_date}}</td>
                    <td>{{$plan->end_date}}</td>
                    <td>{{$plan->type}}</td>
                    <td>{{$plan->mode}}</td>
                    <td>
                      @if($plan->status == 'ongoing')
                        <span class="badge-success">{{$plan->status}}</span>
                      @elseif($plan->status == 'overdue')
                        <span class="badge-red">{{$plan->status}}</span>
                      @else
                        <span class="badge-blue">{{$plan->status}}</span>
                      @endif
                    </td>
                    <td>
                      <div style = "display:flex; flex-direction:row;">
                        <a type = "button" style = "margin-right:10px;" href = "{{route('viewtrainingplan', $plan->id)}}"class = "btn btn-sm btn-primary btn-custom" style = "color:white;"><i class="icon fa fa-eye"
                              aria-hidden="true"></i>&nbsp;View</a>
                              <form method="POST" action="{{route('deletetrainingplan', $plan->id)}}">
                                  @csrf
                                  @method('DELETE')
                                  <button type = "submit" class = "btn btn-sm btn-danger btn-custom" style = "color:white;"><i class="icon fa fa-trash"  aria-hidden="true"></i>&nbsp;Delete</button> </td>
                              </form>
                      </div>
                    </tr>
                @endforeach
              @endif
            @endif
            @if(auth()->user()->role->name == 'Employee')
                @if(count($plans) > 0)
                    @php
                        $n = 0;
                    @endphp
                    @foreach($plans as $training)
                        <tr id="">
                        <td>{{++$n}}</td>
                        <td>{{$training->name}}</td>
                        <td>{{\Carbon\Carbon::parse($training->start_date)->format('F j, Y')}}</td>
                        <td>{{\Carbon\Carbon::parse($training->end_date)->format('F j, Y')}}</td>
                        <td>
                          <div style = "display:flex; flex-direction:row;">
                            <a type = "button" style = "margin-right:10px;" href = "{{route('viewtrainingplan', $training->id)}}"class = "btn btn-sm btn-primary btn-custom" style = "color:white;"><i class="icon fa fa-eye"
                                  aria-hidden="true"></i>&nbsp;View</a>
                                @if(auth()->user()->role->name == 'Employee')
                                  @if($training->user_active == "0")
                                      <form method="POST" action = "{{route('start.trainings')}}">
                                          @csrf
                                          <input type = "hidden" name = "training_plan_id" value="{{$training->id}}"/>
                                          <input type = "hidden" name = "user_training_id" value="{{$training->main_id}}"/>
                                          <input type = "hidden" name = "auth_id" value="{{auth()->user()->id}}"/>
                                          <button type = "submit" style = "margin-right:10px;" class = "btn btn-sm btn-success btn-custom" style = "color:white;"><i class="icon fa fa-check"
                                            aria-hidden="true"></i>&nbsp;Commence</button>
                                      </form>
                                        <button  style = "margin-right:10px;" type = "button" onclick="ToggleRejectionModal('{{$training->id}}', '{{auth()->user()->id}}')" class = "btn btn-sm btn-warning btn-custom" style = "color:white;"><i class="icon fa fa-times"
                                        aria-hidden="true"></i>&nbsp;Reject</button>
                                      </form>
                                    @elseif($training->user_active == "1")
                                      <form method="POST" action = "{{route('complete.trainings')}}">
                                          @csrf
                                          <input type = "hidden" name = "training_plan_id" value="{{$training->id}}"/>
                                          <input type = "hidden" name = "user_training_id" value="{{$training->main_id}}"/>
                                          <input type = "hidden" name = "auth_id" value="{{auth()->user()->id}}"/>
                                          <button type = "submit" style = "margin-right:10px;" class = "btn btn-sm btn-success btn-custom" style = "color:white;"><i class="icon fa fa-check"
                                            aria-hidden="true"></i>&nbsp;Complete</button>
                                      </form>
                                      @elseif($training->user_active == "2")
                                      <a type = "button" href = "{{route('evaluate.new', ['id' => $training->id])}}" style = "margin-right:10px;" class = "btn btn-sm btn-success btn-custom" style = "color:white;"><i class="icon fa fa-paper-plane"
                                      aria-hidden="true"></i>&nbsp;Evaluate</a>
                                    @endif
                                @endif
                          </div>
                        </td>
                        <td>{{$training->type}}</td>
                        <td>{{$training->mode}}</td>
                        <td>
                            @if($training->user_active == '1')
                              <span class="badge-success">ongoing</span>
                            @elseif($training->user_active == '0')
                              <span class="badge-blue">pending</span>
                            @elseif($training->user_active == '-1')
                              <span class="badge-main">Rejected</span>
                            @elseif($training->user_active == '2')
                              <span class="badge-blue">Completed</span>
                            @endif
                        </td>
                        </tr>
                    @endforeach
                @endif
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