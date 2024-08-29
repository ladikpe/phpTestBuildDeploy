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
          <h1 class="page-title">Learning Paths</h1>
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
        @forelse($paths as $path)
            <div class="udemy-card">
                <div class="row">
                    <div class="col-md-3">
                        <div>
                            <i class="fa fa-graduation-cap" style="font-size:90px"></i>
                        </div>
                        <div class="mt-2">
                            <p class="user_name">{{$path->title}}</p>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-md-3">
                                <div>
                                    <p class="user-summary"><span class="title-nick">Description: </span></p>
                                    <p>
                                        {{$path->description}}
                                    </p>
                                </div>
                                <div>
                                    <p class="user-summary"><span class="title-nick">Estimated Course Length: </span></p>
                                    <p>
                                        {{$path->estimated_content_length}}
                                    </p>
                                </div>
                                <div>
                                    <p class="user-summary"><span class="title-nick">Number Of Content Items: </span></p>
                                    <p>
                                        {{$path->number_of_content_items}}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div>
                                        <p class="user-summary"><span class="title-nick">Path ID </span></p>
                                        <p>
                                            {{$path->path_id}}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="user-summary"><span class="title-nick">Editor Name: </span></p>
                                        <p>
                                            {{$path->editor_name}}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="user-summary"><span class="title-nick">Editor Email: </span></p>
                                        <p>
                                            {{$path->editor_email}}
                                        </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                    <div>
                                        <p class="user-summary"><span class="title-nick">Estimated Course Length: </span></p>
                                        <p>
                                            {{$path->estimated_content_length}}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="user-summary"><span class="title-nick">Number Of Content Items: </span></p>
                                        <p>
                                            {{$path->number_of_content_items}}
                                        </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <p>SECTIONS</p>
                    @foreach($path->learning_path_sections as $section)
                        <div class="row">
                            <div class="col-md-3">
                                <div>
                                    <img class="avatar image-shadow" src="{{$section->thumbnail}}" style = " height:100%;"/>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-md-3">
                                        <div>
                                            <p class="user-summary"><span class="title-nick">Title: </span></p>
                                            <p>
                                                {{$section->title}}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="user-summary"><span class="title-nick">Type: </span></p>
                                            <p>
                                                {{$section->type}}
                                            </p>
                                        </div>
                                       
                                    </div>
                                    <div class="col-md-3">
                                        <div>
                                                <p class="user-summary"><span class="title-nick">Order </span></p>
                                                <p>
                                                    {{$section->order}}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="user-summary"><span class="title-nick">Duration: </span></p>
                                                <p>
                                                    {{$section->duration}}
                                                </p>
                                            </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div>
                                            <p class="user-summary"><span class="title-nick">Number Of Items: </span></p>
                                            <p>
                                                {{$section->no_of_items}}
                                            </p>
                                        </div>
                                            <div>
                                                <p class="user-summary"><span class="title-nick">Course URL: </span></p>
                                                <p>
                                                    <a href="{{$section->url}}" target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="icon fa fa-paper-plane" aria-hidden="true"></i>&nbsp;Go TO URL</a>
                                                </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
