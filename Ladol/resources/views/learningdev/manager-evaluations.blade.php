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
    <h1 class="page-title">{{$user->name}} Report</h1>
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
              <th>Question</th>
              <th>Score/Response</th>
              <th>Remark</th>
            </tr>
          </thead>
          <tbody>
            @php
              $n = 0; 
            @endphp
            @forelse($feedbacks as  $feedback)
              <tr id="">
                  <td>{{++$n}}</td>
                  <td>{{$feedback->question->question}}</td>
                  <td>{{$feedback->response}}</td>
                  <td>{{getRemark(intval($feedback->response))}}</td>
              </tr>
            @empty
             <tr id="">
                <td style="color:black;">No Feedback has been submitted!</td>
              </tr>
            @endforelse
          </tbody>
        </table>
        <div class="mt-5"  style="margin-top: 20px;">
          <h2 style="margin-left: 30px;">User Assessment</h2>
      </div>
        <div class="col-md-12"  style="margin-top: 10px;">
          <div class="col-md-4">
                <table class="table table-striped table-bordered" id="dataTable">
                  <thead>
                    <tr style = "background-color:#03A9F4 !important; padding:5px !important; color:white !important;">
                      <th>Total Score</th>
                      <th>Marks Obtainable</th>
                      <th>Grade(%)</th>
                      <th>Remark</th>
                    </tr>
                  </thead>
                  <tbody>
                      <tr id="">
                          <td>{{$total_score}}</td>
                          <td>{{$total_obtainable}}</td>
                          <td>{{round($percentage)}}%</td>
                          <td>{{getUserGrade($percentage)}}</td>
                      </tr>
                  </tbody>
                </table>
          </div>
        </div>
       @if(!$assessment)
        <div class="mt-5"  style="margin-top: 200px;">
            <h2 style="margin-left: 30px;">Manager Evaluations</h2>
        </div>
          <form method="POST" action="{{route('manager-feedback')}}">
            @csrf
            @foreach($questions as $question)
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="hidden" name="member_id" value="{{ request()->segment(3) }}"/>
                        <input type="hidden" name="plan_id" value="{{ $plan_id }}"/>
                        <label for="exampleInputEmail1">{{ $question->question }}</label>
                        <input type="hidden" value="{{ $question->id }}" name="question_id[]"/>
                        @if($question->type == "textbox")
                            <input type="text" name="response[{{ $question->id }}]" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                        @elseif($question->type == "textarea")
                            <textarea class="form-control" name="response[{{ $question->id }}]" id="exampleFormControlTextarea1" rows="3"></textarea>
                        @elseif($question->type == "checkbox")
                            <div>
                                <select class="custom-select custom-select-lg mb-3" name="response[{{ $question->id }}]">
                                    <option>- SELECT -</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        @elseif($question->type == "radiobutton")
                            <div class="check-body">
                                @foreach($options as $k => $option)
                                    <div class="form-check">
                                        <input class="form-check-input" name="response[{{ $question->id }}]" type="radio" id="exampleRadios{{ $question->id }}{{ $k }}" value="{{ $option->mark }}">
                                        <label class="form-check-label" for="exampleRadios{{ $question->id }}{{ $k }}">
                                            {{ $option->option }}    
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="mt-2">
                  <button class="btn btn-primary">Submit Feedback</button>
            </div>
          </form>
      </div>
      @else
        <div class="mt-5"  style="margin-top: 200px;">
            <h2 style="margin-left: 30px;">Manager Assessment</h2>
        </div>
        <div class="col-md-12"  style="margin-top: 10px;">
          <div class="col-md-4">
                <table class="table table-striped table-bordered" id="dataTable">
                  <thead>
                    <tr style = "background-color:#03A9F4 !important; padding:5px !important; color:white !important;">
                      <th>Total Score</th>
                      <th>Marks Obtainable</th>
                      <th>Grade(%)</th>
                      <th>Remark</th>
                    </tr>
                  </thead>
                  <tbody>
                      <tr id="">
                          <td>{{$assessment->manager_evaluation_score}}</td>
                          <td>{{$obtainable}}</td>
                          <td>{{round($user_percentage)}}%</td>
                          <td>{{getUserGrade($user_percentage)}}</td>
                      </tr>
                  </tbody>
                </table>
          </div>
        </div>
      @endif
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