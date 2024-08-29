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
    <h1 class="page-title">Evaluation Reports</h1>
    <ol class="breadcrumb mt-2" style = "margin-top:8px !important;">
      <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
      <li class="breadcrumb-item "><a href="{{route('manager_dashboard')}}">Dashboard</a></li>
    </ol>
    <div class="page-header-actions">
    </div>
  </div>
  <div class="page-content container-fluid bg-white">    
    <div class="panel panel-info panel-line">
      <div class="panel-body">
            <div>
              <div class="mb-3">
                  <h3 align = "center">Training Tools</h3>
              </div>
              <div class="row">
                    <div class="col-md-4">
                      <div style="width:500px; height:500px;">
                          <div>
                              <canvas id="myChart3"></canvas>
                          </div>
                      </div>
                      <div style = "margin-left:70px; margin-top:20px;"">
                          <h4 style = "color:black;">The course content supported the learning objectives</h4>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div style="width:500px; height:500px;">
                          <div>
                              <canvas id="myChart1"></canvas>
                          </div>
                      </div>
                      <div style = "margin-left:70px; margin-top:20px;"">
                          <h4 style = "color:black;">The course length was sufficient to deliver the content</h4>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div style="width:500px; height:500px;">
                          <div>
                              <canvas id="myChart2"></canvas>
                          </div>
                      </div>
                      <div style = "margin-left:70px; margin-top:20px;"">
                          <h4 style = "color:black;">The course design (e.g., materials and learning activities) encouraged my participation in the class</h4>
                      </div>
                    </div>
              </div>
              <div class="row">
                    <div class="col-md-4">
                      <div style="width:500px; height:500px;">
                          <div>
                              <canvas id="myChart4"></canvas>
                          </div>
                      </div>
                      <div style = "margin-left:70px; margin-top:20px;"">
                          <h4 style = "color:black;">The course information was at an appropriate level to understand the learning objectives.</h4>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div style="width:500px; height:500px;">
                          <div>
                              <canvas id="myChart5"></canvas>
                          </div>
                      </div>
                      <div style = "margin-left:70px; margin-top:20px;"">
                          <h4 style = "color:black;">The quiz questions helped me to learn the course information.</h4>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div style="width:500px; height:500px;">
                          <div>
                              <canvas id="myChart6"></canvas>
                          </div>
                      </div>
                      <div style = "margin-left:70px; margin-top:20px;"">
                          <h4 style = "color:black;">The learning aids (e.g., workbooks, hand-outs, role-playing exercises, PowerPoint slides, software) assisted my learning</h4>
                      </div>
                    </div>
              </div>
              <div class="mb-5" style="margin-bottom: 40px;">
                  <h3 align = "center">Instructor and Enrollment Evaluation</h3>
              </div>
              <div class="row">
                    <div class="col-md-4">
                      <div style="width:500px; height:500px;">
                          <div>
                              <canvas id="myChart7"></canvas>
                          </div>
                      </div>
                      <div style = "margin-left:70px; margin-top:20px;"">
                          <h4 style = "color:black;">The technology equipment was working properly</h4>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div style="width:500px; height:500px;">
                          <div>
                              <canvas id="myChart8"></canvas>
                          </div>
                      </div>
                      <div style = "margin-left:70px; margin-top:20px;"">
                          <h4 style = "color:black;">The instructor was knowledgeable about the course content.</h4>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div style="width:500px; height:500px;">
                          <div>
                              <canvas id="myChart9"></canvas>
                          </div>
                      </div>
                      <div style = "margin-left:70px; margin-top:20px;"">
                          <h4 style = "color:black;">The instructor was responsive to participants needs.</h4>
                      </div>
                    </div>
              </div>
              <div class="row">
                    <div class="col-md-4">
                      <div style="width:500px; height:500px;">
                          <div>
                              <canvas id="myChart10"></canvas>
                          </div>
                      </div>
                      <div style = "margin-left:70px; margin-top:20px;"">
                          <h4 style = "color:black;">The instructor presented the content in an interesting manner.</h4>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div style="width:500px; height:500px;">
                          <div>
                              <canvas id="myChart11"></canvas>
                          </div>
                      </div>
                      <div style = "margin-left:70px; margin-top:20px;"">
                          <h4 style = "color:black;">The instructor encouraged a participatory and interactive learning environment.</h4>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div style="width:500px; height:500px;">
                          <div>
                              <canvas id="myChart12"></canvas>
                          </div>
                      </div>
                      <div style = "margin-left:70px; margin-top:20px;"">
                          <h4 style = "color:black;">The training facilities were suitable for learning and had adequate room for all.</h4>
                      </div>
                    </div>
              </div>
              <div class="row">
                    <div class="col-md-4">
                      <div style="width:500px; height:500px;">
                          <div>
                              <canvas id="myChart13"></canvas>
                          </div>
                      </div>
                      <div style = "margin-left:70px; margin-top:20px;"">
                          <h4 style = "color:black;">The training location was easy to locate.</h4>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div style="width:500px; height:500px;">
                          <div>
                              <canvas id="myChart14"></canvas>
                          </div>
                      </div>
                      <div style = "margin-left:70px; margin-top:20px;"">
                          <h4 style = "color:black;">The training was relevant to improving the knowledge/skills I need to accomplish my job.</h4>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div style="width:500px; height:500px;">
                          <div>
                              <canvas id="myChart15"></canvas>
                          </div>
                      </div>
                      <div style = "margin-left:70px; margin-top:20px;"">
                          <h4 style = "color:black;">The practical exercises were good simulations of the tasks that I perform on my job.</h4>
                      </div>
                    </div>
              </div>
              <div class="row">
                    <div class="col-md-4">
                      <div style="width:500px; height:500px;">
                          <div>
                              <canvas id="myChart16"></canvas>
                          </div>
                      </div>
                      <div style = "margin-left:70px; margin-top:20px;"">
                          <h4 style = "color:black;">There was more than one training style used that was conducive to my learning style (e.g. straight lecture, lecture with visual aids and/or interaction</h4>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div style="width:500px; height:500px;">
                          <div>
                              <canvas id="myChart17"></canvas>
                          </div>
                      </div>
                      <div style = "margin-left:70px; margin-top:20px;"">
                          <h4 style = "color:black;">Overall, I am satisfied with the Training course.</h4>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div style="width:500px; height:500px;">
                          <div>
                              <canvas id="myChart18"></canvas>
                          </div>
                      </div>
                      <div style = "margin-left:70px; margin-top:20px;"">
                          <h4 style = "color:black;">Overall, I am satisfied with the instructor(s).</h4>
                      </div>
                    </div>
              </div>
              <div class="row">
                    <div class="col-md-4">
                      <div style="width:500px; height:500px;">
                          <div>
                              <canvas id="myChart19"></canvas>
                          </div>
                      </div>
                      <div style = "margin-left:70px; margin-top:20px;"">
                          <h4 style = "color:black;">Overall, I am satisfied with the training environment.</h4>
                      </div>
                    </div>
              </div>
            </div>
      </div>
    </div>
  </div>
</div>

</div>
</div>
<!-- End Page -->
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx       = document.getElementById('myChart3');
        const ctx_one   = document.getElementById('myChart1');
        const ctx_two   = document.getElementById('myChart2');
        const ctx_four  = document.getElementById('myChart4');
        const ctx_five  = document.getElementById('myChart5');
        const ctx_six   = document.getElementById('myChart6');
        const ctx_seven = document.getElementById('myChart7');
        const ctx_eight = document.getElementById('myChart8');
        const ctx_nine  = document.getElementById('myChart9');
        const ctx_ten   = document.getElementById('myChart10');
        const ctx_eleven  = document.getElementById('myChart11');
        const ctx_tweleve=  document.getElementById('myChart12');
        const ctx_13      = document.getElementById('myChart13');
        const ctx_14      = document.getElementById('myChart14');
        const ctx_15      = document.getElementById('myChart15');
        const ctx_16      = document.getElementById('myChart16');
        const ctx_17      = document.getElementById('myChart17');
        const ctx_18      = document.getElementById('myChart18');
        const ctx_19      = document.getElementById('myChart19');

        const data_one = {
            labels: [
            'Excellent',
            'Good',
            'Average',
            'Poor',
            'Unsatisfactory'
            ],
            datasets: [{
            label: 'My First Dataset',
            data: {{json_encode($is_support_learning)}},
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
            }]
        };
        const data_two = {
            labels: [
            'Excellent',
            'Good',
            'Average',
            'Poor',
            'Unsatisfactory'
            ],
            datasets: [{
            label: 'My First Dataset',
            data: {{json_encode($is_length_sufficient)}},
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
            }]
        };

        const data_three = {
            labels: [
            'Excellent',
            'Good',
            'Average',
            'Poor',
            'Unsatisfactory'
            ],
            datasets: [{
            label: 'My First Dataset',
            data: {{json_encode($is_participation_encouraged)}},
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
            }]
        };

        const data_four = {
            labels: [
            'Excellent',
            'Good',
            'Average',
            'Poor',
            'Unsatisfactory'
            ],
            datasets: [{
            label: 'My First Dataset',
            data: {{json_encode($is_appropriate_level)}},
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
            }]
        };

        const data_five = {
            labels: [
            'Excellent',
            'Good',
            'Average',
            'Poor',
            'Unsatisfactory'
            ],
            datasets: [{
            label: 'My First Dataset',
            data: {{json_encode($is_quiz_helped)}},
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
            }]
        };

        const data_six = {
            labels: [
            'Excellent',
            'Good',
            'Average',
            'Poor',
            'Unsatisfactory'
            ],
            datasets: [{
            label: 'My First Dataset',
            data: {{json_encode($is_learning_aids)}},
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
            }]
        };

        const data_seven = {
            labels: [
            'Excellent',
            'Good',
            'Average',
            'Poor',
            'Unsatisfactory'
            ],
            datasets: [{
            label: 'My First Dataset',
            data: {{json_encode($is_equipment_working)}},
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
            }]
        };

        const data_eight = {
            labels: [
            'Excellent',
            'Good',
            'Average',
            'Poor',
            'Unsatisfactory'
            ],
            datasets: [{
            label: 'My First Dataset',
            data: {{json_encode($is_instructor_knowledgeable)}},
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
            }]
        };
        const data_nine = {
            labels: [
            'Excellent',
            'Good',
            'Average',
            'Poor',
            'Unsatisfactory'
            ],
            datasets: [{
            label: 'My First Dataset',
            data: {{json_encode($is_learning_aids)}},
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
            }]
        };

        const data_ten = {
            labels: [
            'Excellent',
            'Good',
            'Average',
            'Poor',
            'Unsatisfactory'
            ],
            datasets: [{
            label: 'My First Dataset',
            data: {{json_encode($is_content_presented )}},
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
            }]
        };
        const data_eleven = {
            labels: [
            'Excellent',
            'Good',
            'Average',
            'Poor',
            'Unsatisfactory'
            ],
            datasets: [{
            label: 'My First Dataset',
            data: {{json_encode($is_instructor_participatory)}},
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
            }]
        };
        const data_twelve = {
            labels: [
            'Excellent',
            'Good',
            'Average',
            'Poor',
            'Unsatisfactory'
            ],
            datasets: [{
            label: 'My First Dataset',
            data: {{json_encode($is_faccilities_suitable)}},
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
            }]
        };

        const data_13 = {
            labels: [
            'Excellent',
            'Good',
            'Average',
            'Poor',
            'Unsatisfactory'
            ],
            datasets: [{
            label: 'My First Dataset',
            data: {{json_encode($is_location_easy)}},
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
            }]
        };

        const data_14 = {
            labels: [
            'Excellent',
            'Good',
            'Average',
            'Poor',
            'Unsatisfactory'
            ],
            datasets: [{
            label: 'My First Dataset',
            data: {{json_encode($is_faccilities_suitable)}},
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
            }]
        };

        const data_15 = {
            labels: [
            'Excellent',
            'Good',
            'Average',
            'Poor',
            'Unsatisfactory'
            ],
            datasets: [{
            label: 'My First Dataset',
            data: {{json_encode($is_practical_exercises_good)}},
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
            }]
        };

        const data_16 = {
            labels: [
            'Excellent',
            'Good',
            'Average',
            'Poor',
            'Unsatisfactory'
            ],
            datasets: [{
            label: 'My First Dataset',
            data: {{json_encode($is_training_style_more)}},
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
            }]
        };

        const data_17 = {
            labels: [
            'Excellent',
            'Good',
            'Average',
            'Poor',
            'Unsatisfactory'
            ],
            datasets: [{
            label: 'My First Dataset',
            data: {{json_encode($is_course_satisfying)}},
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
            }]
        };

        const data_18 = {
            labels: [
            'Excellent',
            'Good',
            'Average',
            'Poor',
            'Unsatisfactory'
            ],
            datasets: [{
            label: 'My First Dataset',
            data: {{json_encode($is_instructor_satisfying)}},
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
            }]
        };

        const data_19 = {
            labels: [
            'Excellent',
            'Good',
            'Average',
            'Poor',
            'Unsatisfactory'
            ],
            datasets: [{
            label: 'My First Dataset',
            data: {{json_encode($is_environment_satisfying )}},
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
            }]
        };



        new Chart(ctx, {
            type: 'pie',
            data: data_one
        });
        new Chart(ctx_one, {
            type: 'pie',
            data: data_two
        });
        new Chart(ctx_two, {
            type: 'pie',
            data: data_three
        });
        new Chart(ctx_four, {
            type: 'pie',
            data: data_four
        });
        new Chart(ctx_five, {
            type: 'pie',
            data: data_five
        });
        new Chart(ctx_six, {
            type: 'pie',
            data: data_six
        });

        new Chart(ctx_seven, {
            type: 'pie',
            data: data_seven
        });

        new Chart(ctx_eight, {
            type: 'pie',
            data: data_eight
        });

        new Chart(ctx_nine, {
            type: 'pie',
            data: data_nine
        });

        new Chart(ctx_ten, {
            type: 'pie',
            data: data_ten
        });

        new Chart(ctx_eleven, {
            type: 'pie',
            data: data_eleven
        });

        new Chart(ctx_tweleve, {
            type: 'pie',
            data: data_twelve
        });

        new Chart(ctx_13, {
            type: 'pie',
            data: data_13
        });
        new Chart(ctx_14, {
            type: 'pie',
            data: data_14
        });
        new Chart(ctx_15, {
            type: 'pie',
            data: data_15
        });

        new Chart(ctx_16, {
            type: 'pie',
            data: data_16
        });

        new Chart(ctx_17, {
            type: 'pie',
            data: data_17
        });
        
        new Chart(ctx_18, {
            type: 'pie',
            data: data_18
        });

        new Chart(ctx_19, {
            type: 'pie',
            data: data_19
        });
    </script>
@endpush
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