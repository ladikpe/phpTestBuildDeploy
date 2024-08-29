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
</style>
@endsection
@section('content')
<!-- Page -->
<div class="page ">
  <div class="page-header">
    <h1 class="page-title">Training Plan(Completed)</h1>
    <ol class="breadcrumb mt-2" style = "margin-top:8px !important;">
      <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
      <li class="breadcrumb-item "><a href="{{route('manager_dashboard')}}">Dashboard</a></li>
    </ol>
    <div class="page-header-actions">
      <div class="row no-space w-250 hidden-sm-down">

        <div class="col-sm-6 col-xs-12">
           <a type = "button" class = "btn custom-btn" data-toggle="modal" data-target="#exampleModal"><i class="icon fa fa-plus"
                  aria-hidden="true"></i>&nbsp;Add Training Plan</a>
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
           @if($plans->count() > 0)
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
                    @if((auth()->user()->job_id == "1") || (auth()->user()->job_id == "3"))
                      <td>
                        @if($plan->status == 'ongoing')
                          <span class="badge-success">{{$plan->status}}</span>
                        @elseif($plan->status == 'overdue')
                          <span class="badge-red">{{$plan->status}}</span>
                        @else
                          <span class="badge-blue">{{$plan->status}}</span>
                        @endif
                      </td>
                    @else
                     <td>
                        @if($plan->user_active == '1')
                          <span class="badge-success">ongoing</span>
                        @elseif($plan->user_active == '0' && $plan->status == "pending")
                          <span class="badge-red">pending</span>
                        @elseif($plan->user_active == '2')
                          <span class="badge-blue">completed</span>
                        @elseif($plan->status == "overdue")
                          <span class="badge-red">ovedue</span>
                        @endif
                      </td>
                    @endif
                    <td><a type = "button" href = "{{route('viewtrainingplan', $plan->id)}}"class = "btn btn-sm btn-primary" style = "color:white;"><i class="icon fa fa-eye"
                            aria-hidden="true"></i>&nbsp;View</a> </td>
                    </tr>
                @endforeach
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

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Training Plan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form>
            <div class="modal-body">
                    <div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Training Name</label>
                            <input type="textt" class="form-control" id="training_name" aria-describedby="emailHelp" placeholder="Enter training name">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Training Description</label>
                            <input type="text" class="form-control" id="training_description" placeholder="Enter training description">
                        </div>
                       
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Choose Mode of Assigning</label>
                            <select class="form-control" id = "assign_mode" onChange = "ToggleMode()">
                                <option value = "">- SELECT -</option>
                                <option value = "employees">Assign to employees</option>
                                <option value = "designation">Assign to designation</option>
                            </select>
                            <small id="emailHelp" class="form-text text-muted">kindly select a designation</small>
                        </div>
                        <div class="form-group" style = "display:none;" id = "employee_mode">
                            <label for="exampleInputPassword1">Employee Email</label>
                            <input type="email" class="form-control" id="employee_email" placeholder="Enter Employee Email">
                        </div>
                        <div class="form-group" id = "designation_mode">
                            <label for="exampleInputPassword1">Select Designation</label>
                            <select class="form-control" id="designation">
                                <option value = "">- SELECT -</option>
                                <option>Frontend Developer</option>
                                <option>Backend Developer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Number of Enrollees</label>
                            <input type="number" class="form-control" id="enrollees_no" placeholder="Enter number of enrollees">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Cost per Head</label>
                            <input type="text" class="form-control" id="cost_per_head" placeholder="Enter Cost per head">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Mode of Training</label>
                            <select class="form-control" id="training_mode">
                            <option value = "">- SELECT -</option>
                            <option value = "mandatory">mandatory</option>
                            <option value = "optional">optional</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Type</label>
                            <select class="form-control" id="training_type">
                            <option value = "">- SELECT -</option>
                            <option value = "online">online</option>
                            <option value = "offline">offline</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Select Supervisor</label>
                            <select class="form-control" id="supervisor">
                                <option value = "">- SELECT -</option>
                                <option value = "Ruth Godwin">Ruth Godwin</option>
                                <option value = "Basil Ikpe">Basil Ikpe</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Resource Link</label>
                            <input type="text" class="form-control" id="resource_link" placeholder="Enter Resource Link">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Start Date</label>
                            <input type="date" class="form-control" id="start_date" placeholder="Enter Stop Date">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Stop Date</label>
                            <input type="date" class="form-control" id="stop_date" placeholder="Enter Stop Date">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Duration</label>
                            <input type="text" class="form-control" id="duration" placeholder="Enter Duration">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <div id = "train_preload" style = "display:none;">
                    <img src  = "{{asset('assets/loaders/preloader.gif')}}"/>
                </div>
                <div id = "train_base">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon fa fa-times"
                            aria-hidden="true"></i>&nbsp;Close</button>
                    <button type="button" onClick = "submitTraining()" class="btn btn-success"><i class="icon fa fa-check"
                            aria-hidden="true"></i>&nbsp;Save</button>
                </div>
            </div>
        </form>
    </div>
  </div>
</div>
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