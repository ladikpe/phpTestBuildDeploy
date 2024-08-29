@extends('learningdev.layouts.app')
@section('stylesheets')

<link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('assets/examples/css/charts/chartjs.css')}}">
<link rel="stylesheet" href="{{ asset('css/udemy.css') }}">
<style type="text/css">
* {
  text-decoration: none;
}

*:hover {
  text-decoration: none !important;
}
  a.list-group-item:hover {
    text-decoration: none;
    background-color: #26a69a;
    color:#fff;

}

  a.list-group-item:hover h4 {
    color:#fff;
    background-color: #26a69a;

}
.panel-title{
  font-size:14px !important;
}
.list-group-item-heading {
  font-size:14px !important;
}

.counter-box >h3{
    font-size: 42px !important;
    font-weight: 400 !important;
}
.bouton-image:before {
    content: "";
    width: 16px;
    height: 16px;
    display: inline-block;
    margin-right: 5px;
    vertical-align: text-top;
    background-color: transparent;
    background-position : center center;
    background-repeat:no-repeat;
}

.monBouton:before{
     background-image : url({{ asset('assets/images/microsoft-sharepoint.png')}});
}

.custom-card{
    padding:3px!important;
    box-shadow: 0px 2px 16px 2px rgba(0, 0, 0, 0.12) !important;
    border-radius: 10px !important;
    cursor: pointer;
}

.custom-typo{
    font-size:18px !important;
    font-weight:400 !important;
    font-style:normal !important;
    color:#000000 !important;
    margin-left:10px;
}

.custom-type:hover{
    text-decoration:none !important;
}

.rowflex{
    flex-direction:row !important;
    display:flex !important;
    align-items:center !important;
}

h3:hover{
    text-decoration:none !important;
}
.counter-box{
    display:flex;
    flex-direction:row;
    justify-content:flex-start;
}

.custom-btn{
    background-color:#0803F4;
    box-shadow: 0px 2px 12px 4px rgba(0, 0, 0, 0.16) !important;
    border-radius: 10px;
    padding: 7px 10px !important;
    color:white !important;
    width: 180px !important;
  }

.green-btn{
    background-color:green;
    box-shadow: 0px 2px 12px 4px rgba(0, 0, 0, 0.16) !important;
    border-radius: 10px;
    padding: 7px 10px !important;
    color:white !important;
    width: 180px !important;
  }

.boxer{
    width: 100% !important;
    display:flex; 
    flex-direction: row !important; 
    justify-content:space-between;
}

.boxer > div{
    margin-left: 10px;
}

.btn-custom{
text-decoration: none !important;
}
</style>
@endsection
@section('content')
<div class="page">
    <div class="page-header">
      <div>
          <h1 class="page-title">Users Enrolled</h1>
        <div class="page-header-actions">
            <div class="row no-space hidden-sm-down">
                <div class="col-sm-12 col-xs-12">
                    <a href = "{{route('manager_dashboard')}}" class = "btn custom-btn"><i class="icon fa fa-arrow-left"
                        aria-hidden="true"></i>&nbsp;Back To Dashboard
                    </a>
                </div>
            </div>
        </div>
      </div>
    </div>
<div class="page-content container-fluid">   
    <div>
        @forelse($users as $user)
            <div class="udemy-card">
                <div class="row">
                    <div class="col-md-3">
                        <img class="avatar image-shadow" src="{{asset('assets/images/user.png')}}"/>
                        <div class="mt-2">
                            <p class="user_name">{{$user->username}}</p>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-md-3">
                                <p class="user-summary"><span class="title-nick">Role: </span>{{$user->user_role}}</p>
                                <p class="user-summary"><span class="title-nick">User Deactivated: </span>{{$user->user_is_deactivated == 0 ? 'false' : 'true'}}</p>
                                <p class="user-summary"><span class="title-nick"><i class="fa fa-calendar" style="font-size:18px"></i>&nbsp;Joined Date:</span>{{$user->user_joined_date}}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="user-summary"><span class="title-nick">New Enrolled Courses: </span>{{$user->num_new_enrolled_courses}}</p>
                                <p class="user-summary"><span class="title-nick">Newly Assigned courses: </span>{{$user->num_new_assigned_courses}}</p>
                                <p class="user-summary"><span class="title-nick">Newly Started Courses</span>: {{$user->num_new_started_courses}}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="user-summary"><span class="title-nick">Completed Quizzes: </span> {{$user->num_completed_quizzes}}</p>
                                <p class="user-summary"><span class="title-nick">Video Consumed:</span>{{$user->num_video_consumed_minutes}} mins</p>
                                <p class="user-summary"><span class="title-nick"><i class="fa fa-calendar" style="font-size:18px"></i>&nbsp;Last Visit</span>: {{$user->last_date_visit}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>No Data available!</p>
        @endforelse
    </div>
<div>
    <div>
        <p style="color:black; font-size:30px;"></p>
    </div>
        <div>
            <canvas id="myChart"></canvas>
        </div>
        <div>
            <canvas id="myChart"></canvas>
        </div>
    </div>
</div>



  <!-- End Add User Form -->
@endsection
@section('scripts')
<script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
<script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
<script src="{{ asset('global/vendor/chart-js/Chart.js') }}"></script>
<script src="{{ asset('global/vendor/moment/moment.min.js') }}"></script>
<script src="{{ asset('global/vendor/moment/moment-duration-format.js') }}"></script>
<script>
    function getLocation(type) {
    if (navigator.geolocation) {
        if (type=='clockin'){
        navigator.geolocation.getCurrentPosition(clockInPost);
        }
        else {
        navigator.geolocation.getCurrentPosition(clockOutPost);
        }
    } else {
        alertify.error('Cannot clock in or out with current device');
    }
    }
    function showPosition(position) {
    message = "Latitude: " + position.coords.latitude + "<br>Longitude: " + position.coords.longitude;
    console.log(message)
    }
    function clockIn() {
    alertify.success('Processing...');
    getLocation('clockin')
    }
    function clockOut() {
    getLocation('clockout')
    }
    function clockInPost(position) {
    alertify.confirm('Are you sure you want to Clock In now?', function () {
        $.get('{{ url('/bio/softclockin') }}?long='+position.coords.longitude+'&lat='+position.coords.latitude, function (data) {
        console.log(data)
        toastr.success(data);
        });
    }, function () {
        alertify.error('Cancelled');
    });
    }
    function clockOutPost(position) {
    alertify.confirm('Are you sure you want to Clock Out now?', function () {
        $.get('{{ url('/bio/softclockout') }}?long='+position.coords.longitude+'&lat='+position.coords.latitude, function (data) {
        toastr.success(data);
        });
    }, function () {
        alertify.error('Cancelled');
    });
    }
</script>
@include('learningdev.includes.script')
@endsection
