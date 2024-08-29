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
</style>
@endsection
@section('content')
<!-- Page -->
<div class="page ">
  <div class="page-header">
    <h1 class="page-title">User Evaluation</h1>
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
            <div>
                <h4 style="margin-left: -10px;">Course Evaluation</h4>
           </div>
           <div class="row">
                <p>Score Range:</p>
           </div>
           <div class="row" style = "display:flex; flex-direction:row; justify-content:space-between;">
              <p>Excellent 85 - 100%</p>
              <p>Good 60 - 84%</p>
              <p>Average 50 - 59%</p>
              <p>Poor 40 - 49%</p>
              <p>Unsatisfactory 0 - 40%</p>
           </div>
           <div>
            
         @if(session()->has('message'))
              <div class="alert alert-success">
                  {{ session()->get('message') }}
              </div>
          @endif
           <form action="{{route('submit.evaluation')}}" method="POST">
                @csrf
                <input type="hidden" name = "training_plan_id" value="{{$id_user}}"/>
                <input type="hidden" name = "user_id" value="{{auth()->user()->id}}"/>
                <table class="table table-striped table-bordered" id="dataTable" >
                    <thead>
                        <tr style = "background-color:#03A9F4 !important; padding:5px !important; color:white !important;">
                            <th style="width: auto;">Comments</th>
                            <th class="col-1">Score(%)</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>The course content supported the learning objectives</td>
                        <td><input type="text" name="is_support_learning" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>The course length was sufficient to deliver the content</td>
                        <td><input type="text" name="is_length_sufficient" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>The course design (e.g., materials and learning activities) encouraged my participation in the class</td>
                        <td><input type="text" name="is_participation_encouraged" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>The course provided opportunities to practice and reinforce what was taught.</td>
                        <td><input type="text" name="is_opportunities_provided" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>The course information was at an appropriate level to understand the learning objectives.</td>
                        <td><input type="text" name="is_appropriate_level" class="form-control" /></td>
                    </tr>
                    </tbody>
                </table>
                <div>
                    <h4 style="margin-left: -15px;">Training Tools</h4>
               </div>
                <table class="table table-striped table-bordered" id="dataTable" >
                    <thead>
                        <tr style = "background-color:#03A9F4 !important; padding:5px !important; color:white !important;">
                            <th style="width: auto;">Comments</th>
                            <th class="col-1">Score(%)</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>The quiz questions helped me to learn the course information.</td>
                        <td><input type="text" name="is_quiz_helped" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>The learning aids (e.g., workbooks, hand-outs, role-playing exercises, PowerPoint slides, software) assisted my learning.</td>
                        <td><input type="text" name="is_learning_aids" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>The technology equipment was working properly</td>
                        <td><input type="text" name="is_equipment_working" class="form-control" /></td>
                    </tr>
                    <!-- Add more rows as needed -->
                    </tbody>
                </table>
                <div>
                    <h4 style="margin-left: -15px;">Instructor and Enrollment Evaluation</h4>
               </div>
                <table class="table table-striped table-bordered" id="dataTable" >
                    <thead>
                        <tr style = "background-color:#03A9F4 !important; padding:5px !important; color:white !important;">
                            <th style="width: auto;">Comments</th>
                            <th class="col-1">Score(%)</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>The instructor was knowledgeable about the course content.</td>
                        <td><input type="text" name="is_instructor_knowledgeable" class="form-control" /></td>
                    </tr>
                  
                    <tr>
                        <td>The instructor was responsive to participants needs.</td>
                        <td><input type="text" name="is_instructor_responsive" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>The instructor presented the content in an interesting manner.</td>
                        <td><input type="text" name="is_instructor_interesting" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>The instructor encouraged a participatory and interactive learning environment.</td>
                        <td><input type="text" name="is_instructor_participatory" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>The training facilities were suitable for learning and had adequate room for all.</td>
                        <td><input type="text" name="is_faccilities_suitable" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>The training location was easy to locate</td>
                        <td><input type="text" name="is_location_easy" class="form-control" /></td>
                    </tr>
                    <!-- Add more rows as needed -->
                    </tbody>
                </table>

                <div>
                    <h4 style="margin-left: -15px;">Overall Benefit</h4>
               </div>
                <table class="table table-striped table-bordered" id="dataTable" >
                    <thead>
                        <tr style = "background-color:#03A9F4 !important; padding:5px !important; color:white !important;">
                            <th style="width: auto;">Comments</th>
                            <th class="col-1">Score(%)</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>The training was relevant to improving the knowledge/skills I need to accomplish my job.</td>
                        <td><input type="text" name="is_training_relevant" class="form-control" /></td>
                    </tr>
                  
                    <tr>
                        <td>The practical exercises were good simulations of the tasks that I perform on my job.</td>
                        <td><input type="text" name="is_practical_exercises_good" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>There was more than one training style used that was conducive to my learning style (e.g. straight lecture, lecture with visual aids and/or interaction).</td>
                        <td><input type="text" name="is_training_style_more" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>Overall, I am satisfied with the Training course.</td>
                        <td><input type="text" name = "is_course_satisifying" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>Overall, I am satisfied with the instructor(s).</td>
                        <td><input type="text" name="is_instructor_satisfying" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>Overall, I am satisfied with the training environment.</td>
                        <td><input type="text" name="is_environment_satisfying" class="form-control" /></td>
                    </tr>
                    <!-- Add more rows as needed -->
                    </tbody>
                </table>
                <div style="margin-bottom: 10px;">
                    <label>Additional Comments(optional)</label>
                    <textarea class="form-control" rows = "4" name = "comments">

                    </textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
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