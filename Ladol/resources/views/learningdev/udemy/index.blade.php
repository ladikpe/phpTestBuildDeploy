@extends('learningdev.layouts.app')
@section('stylesheets')

<link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('assets/examples/css/charts/chartjs.css')}}">

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
          <h1 class="page-title">Udemy Reports</h1>
        <div class="page-header-actions">
            <div class="row no-space hidden-sm-down">
                <div class="col-sm-12 col-xs-12">
                        <a href = "{{route('manager_dashboard')}}" class = "btn custom-btn"><i class="icon fa fa-arrow-left"
                            aria-hidden="true"></i>&nbsp;Back to Dashboard  
                        </a>
                </div>
            </div>
        </div>
      </div>
    </div>
<div class="page-content container-fluid">   
    <div class="row">
        <div class="col-xl-6 col-md-6 col-xs-12 info-panel marg">
            <a href = "{{route('udemy.users')}}">
                <div class="card custom-card">
                    <div class="card-block bg-white p-30">
                        <div class = "rowflex">
                            <div>
                                <svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="21" cy="21" r="21" fill="#0803F4"/>
                                <path d="M25.6667 23C25.6667 25.6667 22.3333 27.6667 22.3333 29.6667H19.6667C19.6667 27.6667 16.3333 25.6667 16.3333 23C16.3333 20.4267 18.4267 18.3333 21 18.3333C23.5733 18.3333 25.6667 20.4267 25.6667 23ZM22.3333 31H19.6667V31.6667C19.6667 32.4 20.2667 33 21 33C21.7333 33 22.3333 32.4 22.3333 31.6667V31ZM30.3333 22.3333C30.3333 24.1867 29.7867 25.92 28.84 27.3733C28.6791 27.6293 28.6099 27.9323 28.6438 28.2327C28.6777 28.5331 28.8127 28.8131 29.0267 29.0267C29.6133 29.6133 30.6267 29.5333 31.08 28.8267C32.337 26.8946 33.0042 24.6383 33 22.3333C33 19.1867 31.7867 16.32 29.8 14.1733C29.28 13.6133 28.4 13.6 27.8667 14.1333C27.36 14.64 27.36 15.4533 27.84 15.9867C29.4452 17.7099 30.3364 19.9783 30.3333 22.3333ZM25.8667 11.2L22.1467 7.48001C22.0539 7.38475 21.9349 7.31932 21.8047 7.2921C21.6746 7.26489 21.5393 7.27712 21.4162 7.32723C21.293 7.37735 21.1877 7.46306 21.1135 7.57342C21.0394 7.68377 20.9998 7.81373 21 7.94668V10.3333C18.8434 10.3339 16.7269 10.9155 14.8729 12.0171C13.0189 13.1188 11.4961 14.6996 10.4646 16.5935C9.43306 18.4874 8.93096 20.6242 9.0111 22.7793C9.09124 24.9343 9.75065 27.028 10.92 28.84C11.3733 29.5467 12.3867 29.6267 12.9733 29.04C13.4133 28.6 13.4933 27.92 13.16 27.4C11.3067 24.5333 10.9733 20.6 13.3333 16.7333C14.9333 14.1333 17.9467 12.7467 21 13V15.3867C21 15.9867 21.72 16.28 22.1333 15.8533L25.8533 12.1333C26.12 11.88 26.12 11.4533 25.8667 11.2Z" fill="white"/>
                                </svg>
                            </div>
                            @if((auth()->user()->job_id == "3") || (auth()->user()->job_id == "1"))
                                <div class="font-weight-400 custom-typo">Total Users Enrolled</div>
                            @else 
                                <div class="font-weight-400 custom-typo">{{auth()->user()->role->name == "Employee" ? 'My Udemy Trainings' : 'User Trainings'}}</div>
                            @endif
                        </div>
                        <div class="content-text text-xs-center m-b-0">
                            <div class = "counter-box">
                                <h3 class="number_demo" style = "font-weight:lighter;">{{$users}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
     
        <div class="col-xl-6 col-md-6 col-xs-12 info-panel marg">
            <a href = "{{route('udemy.paths')}}">
                <div class="card custom-card">
                    <div class="card-block bg-white p-30">
                        <div class = "rowflex">
                            <div>
                                <svg width="43" height="42" viewBox="0 0 43 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="21.25" cy="21" r="21" fill="#03A9F4"/>
                                <path d="M23.25 15.6667H21.25V22.3333L26.9567 25.72L27.9167 24.1067L23.25 21.3333V15.6667ZM22.5834 9C19.4008 9 16.3485 10.2643 14.0981 12.5147C11.8477 14.7652 10.5834 17.8174 10.5834 21H6.58337L11.8634 26.3733L17.25 21H13.25C13.25 18.5246 14.2334 16.1507 15.9837 14.4003C17.7341 12.65 20.108 11.6667 22.5834 11.6667C25.0587 11.6667 27.4327 12.65 29.183 14.4003C30.9334 16.1507 31.9167 18.5246 31.9167 21C31.9167 23.4754 30.9334 25.8493 29.183 27.5997C27.4327 29.35 25.0587 30.3333 22.5834 30.3333C20.01 30.3333 17.6767 29.28 15.9967 27.5867L14.1034 29.48C15.212 30.6008 16.5329 31.4894 17.989 32.0938C19.445 32.6982 21.0069 33.0063 22.5834 33C25.766 33 28.8182 31.7357 31.0687 29.4853C33.3191 27.2348 34.5834 24.1826 34.5834 21C34.5834 17.8174 33.3191 14.7652 31.0687 12.5147C28.8182 10.2643 25.766 9 22.5834 9Z" fill="white"/>
                                </svg>
                            </div>
                            <div class="font-weight-400 custom-typo">Learning Paths</div>  
                        </div>
                        <div class="content-text text-xs-center m-b-0">
                            <div class = "counter-box">
                                <h3 style = "font-weight:lighter;">{{$path_count}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
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
