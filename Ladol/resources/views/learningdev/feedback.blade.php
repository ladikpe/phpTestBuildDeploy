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

  .title-text{
    font-weight:500;
    background-color: #f8f9fa;
    color:black;
    padding: 6px;
    max-width: 180px;
    border-radius: 10px;
    margin-bottom: 12px;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
  }
  .main-fresh{
    font-weight: bolder;
    font-size: 18px;
  }
  .check-body{
    margin-left: 20px !important;
  }
</style>
@endsection
@section('content')
<!-- Page -->
<div class="page ">
  <div class="page-header">
    <h1 class="page-title">Training Feedback Form</h1>
    <ol class="breadcrumb mt-2" style = "margin-top:8px !important;">
      <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
      <li class="breadcrumb-item "><a href="{{route('manager_dashboard')}}">Dashboard</a></li>
    </ol>
    <div class="page-header-actions">
      <div class="row no-space w-250 hidden-sm-down">
        <div class="col-sm-6 col-xs-12">
        </div>
      </div>
    </div>
  </div>

  <div class="page-content container-fluid bg-white bg-white  col-md-12 ">    
    <div class="panel panel-info panel-line">
      <div class="panel-body card shadow-lg">
           <div class="row">
                <p>Score Range:</p>
           </div>
           <div class="row" style = "display:flex; flex-direction:row; justify-content:space-between;">
              <p>Excellent (5)</p>
              <p>Good (4)</p>
              <p>Average (3)</p>
              <p>Poor (2)</p>
              <p>Unsatisfactory (1)</p>
           </div>
           <div>
            @if(session()->has('message'))
              <div class="alert alert-success">
                  {{ session()->get('message') }}
              </div>
             @endif
                <form action="{{route('evaluated.store')}}" method="POST" style=" margin-top: 40px;">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{$training_plan_id}}"/>
                        <div>
                            <h2 class="main-fresh">USER INFORMATION</h2>
                        </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Name</label>
                                <input type="text" name = "name" value="{{auth()->user()->name}}" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Designation</label>
                                <input type="text" name = "designation" class="form-control" value="{{auth()->user()->job->title}}" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Department</label>
                                <input name = "department" type="text" class="form-control" value="{{auth()->user()->department->name ?? 'N/A'}}" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Training Title</label>
                                <input type="text" name = "training_plan_id" value="{{$training->name}}" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Training Type</label>
                                <input type="text" name = "training_plan_type" value="{{$training->type}}" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Duration</label>
                                <input type="text" name = "training_plan_duration" value="{{$training->duration}}" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                            </div>
                        </div>
                    </div>
                    @foreach($questionsByCategory as $categoryName => $questions)
                        <div>
                            <h2 class="main-fresh">{{$categoryName}}</h2>
                        </div>
                        <div class="row">
                        @foreach($questions as $question)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">{{$question->question}}</label>
                                    <input type="hidden" value="{{$question->id}}" name="question_id[]" />
                                    @if($question->type == "textbox")
                                        <input type="text" name="response[{{$question->id}}]" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                                    @elseif($question->type == "textarea")
                                        <textarea class="form-control" name="response[{{$question->id}}]" id="exampleFormControlTextarea1" rows="3"></textarea>
                                    @elseif($question->type == "radiobutton")
                                        <div class="check-body">
                                            @foreach($options as $k => $option)
                                                <div class="form-check">
                                                    <input class="form-check-input" name="response[{{$question->id}}]" type="radio" id="exampleRadios{{$question->id}}{{$k}}" value="{{$option->mark}}">
                                                    <label class="form-check-label" for="exampleRadios{{$question->id}}{{$k}}">
                                                        {{$option->option}}    
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        </div>
                    @endforeach
                    <div class="mt-2">
                         <button class="btn btn-primary">Submit Feedback</button>
                    </div>
                </form>
           </div>
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