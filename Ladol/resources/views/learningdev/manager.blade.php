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
        @if(auth()->user()->role_id == '1')
          <h1 class="page-title"><i class="icon fa fa-dashboard" aria-hidden="true"></i>&nbsp;Manager Dashboard</h1>
        @elseif(auth()->user()->role_id == "2")
        <h1 class="page-title"><i class="icon fa fa-dashboard" aria-hidden="true"></i>&nbsp;HR Dashboard</h1>
        @else
        <h1 class="page-title"><i class="icon fa fa-dashboard" aria-hidden="true"></i>&nbsp;Dashboard</h1>
        @endif
        <div class="page-header-actions">
            <div class="row no-space hidden-sm-down"> 
                <div class="col-sm-12 col-xs-12">
                    @if(auth()->user()->role_id == '1')
                        <a href = "{{route('filled-reports')}}" class = "btn custom-btn"><i class="icon fa fa-list"
                            aria-hidden="true"></i>&nbsp;View Reports
                        </a>
                    @elseif(auth()->user()->role->id == '2')
                        <div class="boxer">
                            <div>
                                <a href = "{{url('training/budget/get')}}" class = "btn green-btn"><i class="icon fa fa-paper-plane"
                                    aria-hidden="true"></i>&nbsp;Budget
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
      </div>
    </div>
<div class="page-content container-fluid">   
    <div class="row">
        <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
            <a href = "{{route('manage.trainings')}}">
                <div class="card custom-card">
                    <div class="card-block bg-white p-30">
                        <div class = "rowflex">
                            <div>
                                <svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="21" cy="21" r="21" fill="#0803F4"/>
                                <path d="M25.6667 23C25.6667 25.6667 22.3333 27.6667 22.3333 29.6667H19.6667C19.6667 27.6667 16.3333 25.6667 16.3333 23C16.3333 20.4267 18.4267 18.3333 21 18.3333C23.5733 18.3333 25.6667 20.4267 25.6667 23ZM22.3333 31H19.6667V31.6667C19.6667 32.4 20.2667 33 21 33C21.7333 33 22.3333 32.4 22.3333 31.6667V31ZM30.3333 22.3333C30.3333 24.1867 29.7867 25.92 28.84 27.3733C28.6791 27.6293 28.6099 27.9323 28.6438 28.2327C28.6777 28.5331 28.8127 28.8131 29.0267 29.0267C29.6133 29.6133 30.6267 29.5333 31.08 28.8267C32.337 26.8946 33.0042 24.6383 33 22.3333C33 19.1867 31.7867 16.32 29.8 14.1733C29.28 13.6133 28.4 13.6 27.8667 14.1333C27.36 14.64 27.36 15.4533 27.84 15.9867C29.4452 17.7099 30.3364 19.9783 30.3333 22.3333ZM25.8667 11.2L22.1467 7.48001C22.0539 7.38475 21.9349 7.31932 21.8047 7.2921C21.6746 7.26489 21.5393 7.27712 21.4162 7.32723C21.293 7.37735 21.1877 7.46306 21.1135 7.57342C21.0394 7.68377 20.9998 7.81373 21 7.94668V10.3333C18.8434 10.3339 16.7269 10.9155 14.8729 12.0171C13.0189 13.1188 11.4961 14.6996 10.4646 16.5935C9.43306 18.4874 8.93096 20.6242 9.0111 22.7793C9.09124 24.9343 9.75065 27.028 10.92 28.84C11.3733 29.5467 12.3867 29.6267 12.9733 29.04C13.4133 28.6 13.4933 27.92 13.16 27.4C11.3067 24.5333 10.9733 20.6 13.3333 16.7333C14.9333 14.1333 17.9467 12.7467 21 13V15.3867C21 15.9867 21.72 16.28 22.1333 15.8533L25.8533 12.1333C26.12 11.88 26.12 11.4533 25.8667 11.2Z" fill="white"/>
                                </svg>
                            </div>
                            @if((auth()->user()->role_id == "2") || (auth()->user()->role_id == "1"))
                                <div class="font-weight-400 custom-typo">Total Trainings</div>
                            @else 
                                <div class="font-weight-400 custom-typo">My Trainings</div>
                            @endif
                        </div>
                        <div class="content-text text-xs-center m-b-0">
                            <div class = "counter-box">
                                <h3 class="number_demo" style = "font-weight:lighter;">{{$total_trainings}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
            <a href = "{{route('mandatory.trainings')}}">
                <div class="card custom-card">
                    <div class="card-block bg-white p-30">
                        <div class = "rowflex">
                            <div>
                                <svg width="43" height="42" viewBox="0 0 43 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="21.25" cy="21" r="21" fill="#B503F4"/>
                                <path d="M14.3333 27.6667H17.1333L25.1333 19.7334L22.2667 16.8667L14.3333 24.8V27.6667ZM26.0667 18.8L27.4667 17.3334C27.6 17.2 27.6667 17.0445 27.6667 16.8667C27.6667 16.6889 27.6 16.5334 27.4667 16.4L25.6 14.5334C25.4667 14.4 25.3111 14.3334 25.1333 14.3334C24.9556 14.3334 24.8 14.4 24.6667 14.5334L23.2 15.9334L26.0667 18.8ZM11.6667 33C10.9333 33 10.3053 32.7387 9.78267 32.216C9.26 31.6934 8.99911 31.0658 9 30.3334V11.6667C9 10.9334 9.26134 10.3054 9.784 9.78271C10.3067 9.26004 10.9342 8.99915 11.6667 9.00004H17.2667C17.5556 8.20004 18.0391 7.5556 18.7173 7.06671C19.3956 6.57782 20.1564 6.33337 21 6.33337C21.8444 6.33337 22.6058 6.57782 23.284 7.06671C23.9622 7.5556 24.4453 8.20004 24.7333 9.00004H30.3333C31.0667 9.00004 31.6947 9.26137 32.2173 9.78404C32.74 10.3067 33.0009 10.9343 33 11.6667V30.3334C33 31.0667 32.7387 31.6947 32.216 32.2174C31.6933 32.74 31.0658 33.0009 30.3333 33H11.6667ZM11.6667 30.3334H30.3333V11.6667H11.6667V30.3334ZM21 10.6667C21.2889 10.6667 21.5276 10.572 21.716 10.3827C21.9044 10.1934 21.9991 9.95471 22 9.66671C22 9.37782 21.9053 9.13915 21.716 8.95071C21.5267 8.76226 21.288 8.6676 21 8.66671C20.7111 8.66671 20.4724 8.76137 20.284 8.95071C20.0956 9.14004 20.0009 9.37871 20 9.66671C20 9.9556 20.0947 10.1943 20.284 10.3827C20.4733 10.5712 20.712 10.6658 21 10.6667Z" fill="white"/>
                                </svg>

                            </div>
                            <div class="font-weight-400 custom-typo">Mandatory Trainings</div>  
                        </div>
                        <div class="content-text text-xs-center m-b-0">
                            <div class = "counter-box">
                                <h3 class="number_demo" style = "font-weight:lighter;">{{$mandatory_trainings}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
            <a href = "{{route('optional.trainings')}}">
                <div class="card custom-card">
                    <div class="card-block bg-white p-30">
                        <div class = "rowflex">
                            <div>
                                <svg width="43" height="42" viewBox="0 0 43 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="21.5" cy="21" r="21" fill="#CE933A"/>
                                <path d="M31 9H11C10.4696 9 9.96086 9.21071 9.58579 9.58579C9.21071 9.96086 9 10.4696 9 11V31C9 31.5304 9.21071 32.0391 9.58579 32.4142C9.96086 32.7893 10.4696 33 11 33H31C31.5304 33 32.0391 32.7893 32.4142 32.4142C32.7893 32.0391 33 31.5304 33 31V11C33 10.4696 32.7893 9.96086 32.4142 9.58579C32.0391 9.21071 31.5304 9 31 9Z" stroke="white" stroke-width="3.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14.3333 23.6666L19.6666 18.3333L22.3333 23L27.6666 17.6666" stroke="white" stroke-width="3.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="font-weight-400 custom-typo">Optional Trainings</div>  
                        </div>
                        <div class="content-text text-xs-center m-b-0">
                            <div class = "counter-box">
                                <h3 style = "font-weight:lighter;">{{$optional_trainings}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
            <a href = "{{route('completed.trainings')}}">
                <div class="card custom-card">
                    <div class="card-block bg-white p-30">
                        <div class = "rowflex">
                            <div>                  
                                <svg width="43" height="42" viewBox="0 0 43 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="21.75" cy="21" r="21" fill="#4CAF50"/>
                                    <g clip-path="url(#clip0_48_846)">
                                    <path d="M29.0469 14.2969L30.4531 15.7031L18.75 27.4062L13.0469 21.7031L14.4531 20.2969L18.75 24.5938L29.0469 14.2969ZM21.75 5C23.2188 5 24.6354 5.1875 26 5.5625C27.3646 5.9375 28.6406 6.47396 29.8281 7.17188C31.0156 7.86979 32.0938 8.70312 33.0625 9.67188C34.0312 10.6406 34.8646 11.724 35.5625 12.9219C36.2604 14.1198 36.7969 15.3958 37.1719 16.75C37.5469 18.1042 37.7396 19.5208 37.75 21C37.75 22.4688 37.5625 23.8854 37.1875 25.25C36.8125 26.6146 36.276 27.8906 35.5781 29.0781C34.8802 30.2656 34.0469 31.3438 33.0781 32.3125C32.1094 33.2812 31.026 34.1146 29.8281 34.8125C28.6302 35.5104 27.3542 36.0469 26 36.4219C24.6458 36.7969 23.2292 36.9896 21.75 37C20.2812 37 18.8646 36.8125 17.5 36.4375C16.1354 36.0625 14.8594 35.526 13.6719 34.8281C12.4844 34.1302 11.4062 33.2969 10.4375 32.3281C9.46875 31.3594 8.63542 30.276 7.9375 29.0781C7.23958 27.8802 6.70312 26.6094 6.32812 25.2656C5.95312 23.9219 5.76042 22.5 5.75 21C5.75 19.5312 5.9375 18.1146 6.3125 16.75C6.6875 15.3854 7.22396 14.1094 7.92188 12.9219C8.61979 11.7344 9.45312 10.6562 10.4219 9.6875C11.3906 8.71875 12.474 7.88542 13.6719 7.1875C14.8698 6.48958 16.1406 5.95312 17.4844 5.57812C18.8281 5.20312 20.25 5.01042 21.75 5ZM21.75 35C23.0312 35 24.2656 34.8333 25.4531 34.5C26.6406 34.1667 27.7552 33.6979 28.7969 33.0938C29.8385 32.4896 30.7865 31.7552 31.6406 30.8906C32.4948 30.026 33.224 29.0833 33.8281 28.0625C34.4323 27.0417 34.9062 25.9271 35.25 24.7188C35.5938 23.5104 35.7604 22.2708 35.75 21C35.75 19.7188 35.5833 18.4844 35.25 17.2969C34.9167 16.1094 34.4479 14.9948 33.8438 13.9531C33.2396 12.9115 32.5052 11.9635 31.6406 11.1094C30.776 10.2552 29.8333 9.52604 28.8125 8.92188C27.7917 8.31771 26.6771 7.84375 25.4688 7.5C24.2604 7.15625 23.0208 6.98958 21.75 7C20.4688 7 19.2344 7.16667 18.0469 7.5C16.8594 7.83333 15.7448 8.30208 14.7031 8.90625C13.6615 9.51042 12.7135 10.2448 11.8594 11.1094C11.0052 11.974 10.276 12.9167 9.67188 13.9375C9.06771 14.9583 8.59375 16.0729 8.25 17.2812C7.90625 18.4896 7.73958 19.7292 7.75 21C7.75 22.2812 7.91667 23.5156 8.25 24.7031C8.58333 25.8906 9.05208 27.0052 9.65625 28.0469C10.2604 29.0885 10.9948 30.0365 11.8594 30.8906C12.724 31.7448 13.6667 32.474 14.6875 33.0781C15.7083 33.6823 16.8229 34.1562 18.0312 34.5C19.2396 34.8438 20.4792 35.0104 21.75 35Z" fill="white"/>
                                    </g>
                                    <defs>
                                    <clipPath id="clip0_48_846">
                                    <rect width="32" height="32" fill="white" transform="translate(5.75 5)"/>
                                    </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <div class="font-weight-400 custom-typo">Completed Trainings</div>  
                        </div>
                        <div class="content-text text-xs-center m-b-0">
                         <div class = "counter-box">
                                <h3 class="number_demo" style = "font-weight:lighter;">{{$completed_trainings}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
            <a href = "{{route('overdue.trainings')}}">
                <div class="card custom-card">
                    <div class="card-block bg-white p-30">
                        <div class = "rowflex">
                            <div>                  
                                <svg width="43" height="42" viewBox="0 0 43 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="21.75" cy="21" r="21" fill="#4CAF50"/>
                                    <g clip-path="url(#clip0_48_846)">
                                    <path d="M29.0469 14.2969L30.4531 15.7031L18.75 27.4062L13.0469 21.7031L14.4531 20.2969L18.75 24.5938L29.0469 14.2969ZM21.75 5C23.2188 5 24.6354 5.1875 26 5.5625C27.3646 5.9375 28.6406 6.47396 29.8281 7.17188C31.0156 7.86979 32.0938 8.70312 33.0625 9.67188C34.0312 10.6406 34.8646 11.724 35.5625 12.9219C36.2604 14.1198 36.7969 15.3958 37.1719 16.75C37.5469 18.1042 37.7396 19.5208 37.75 21C37.75 22.4688 37.5625 23.8854 37.1875 25.25C36.8125 26.6146 36.276 27.8906 35.5781 29.0781C34.8802 30.2656 34.0469 31.3438 33.0781 32.3125C32.1094 33.2812 31.026 34.1146 29.8281 34.8125C28.6302 35.5104 27.3542 36.0469 26 36.4219C24.6458 36.7969 23.2292 36.9896 21.75 37C20.2812 37 18.8646 36.8125 17.5 36.4375C16.1354 36.0625 14.8594 35.526 13.6719 34.8281C12.4844 34.1302 11.4062 33.2969 10.4375 32.3281C9.46875 31.3594 8.63542 30.276 7.9375 29.0781C7.23958 27.8802 6.70312 26.6094 6.32812 25.2656C5.95312 23.9219 5.76042 22.5 5.75 21C5.75 19.5312 5.9375 18.1146 6.3125 16.75C6.6875 15.3854 7.22396 14.1094 7.92188 12.9219C8.61979 11.7344 9.45312 10.6562 10.4219 9.6875C11.3906 8.71875 12.474 7.88542 13.6719 7.1875C14.8698 6.48958 16.1406 5.95312 17.4844 5.57812C18.8281 5.20312 20.25 5.01042 21.75 5ZM21.75 35C23.0312 35 24.2656 34.8333 25.4531 34.5C26.6406 34.1667 27.7552 33.6979 28.7969 33.0938C29.8385 32.4896 30.7865 31.7552 31.6406 30.8906C32.4948 30.026 33.224 29.0833 33.8281 28.0625C34.4323 27.0417 34.9062 25.9271 35.25 24.7188C35.5938 23.5104 35.7604 22.2708 35.75 21C35.75 19.7188 35.5833 18.4844 35.25 17.2969C34.9167 16.1094 34.4479 14.9948 33.8438 13.9531C33.2396 12.9115 32.5052 11.9635 31.6406 11.1094C30.776 10.2552 29.8333 9.52604 28.8125 8.92188C27.7917 8.31771 26.6771 7.84375 25.4688 7.5C24.2604 7.15625 23.0208 6.98958 21.75 7C20.4688 7 19.2344 7.16667 18.0469 7.5C16.8594 7.83333 15.7448 8.30208 14.7031 8.90625C13.6615 9.51042 12.7135 10.2448 11.8594 11.1094C11.0052 11.974 10.276 12.9167 9.67188 13.9375C9.06771 14.9583 8.59375 16.0729 8.25 17.2812C7.90625 18.4896 7.73958 19.7292 7.75 21C7.75 22.2812 7.91667 23.5156 8.25 24.7031C8.58333 25.8906 9.05208 27.0052 9.65625 28.0469C10.2604 29.0885 10.9948 30.0365 11.8594 30.8906C12.724 31.7448 13.6667 32.474 14.6875 33.0781C15.7083 33.6823 16.8229 34.1562 18.0312 34.5C19.2396 34.8438 20.4792 35.0104 21.75 35Z" fill="white"/>
                                    </g>
                                    <defs>
                                    <clipPath id="clip0_48_846">
                                    <rect width="32" height="32" fill="white" transform="translate(5.75 5)"/>
                                    </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <div class="font-weight-400 custom-typo">Overdue Trainings</div>  
                        </div>
                        <div class="content-text text-xs-center m-b-0">
                             <div class = "counter-box">
                                <h3 style = "font-weight:lighter;">{{$overdue_trainings}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
            <a href = "{{route('ongoing.trainings')}}">
                <div class="card custom-card">
                    <div class="card-block bg-white p-30">
                        <div class = "rowflex">
                            <div>
                                <svg width="43" height="42" viewBox="0 0 43 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="21.25" cy="21" r="21" fill="#03A9F4"/>
                                <path d="M23.25 15.6667H21.25V22.3333L26.9567 25.72L27.9167 24.1067L23.25 21.3333V15.6667ZM22.5834 9C19.4008 9 16.3485 10.2643 14.0981 12.5147C11.8477 14.7652 10.5834 17.8174 10.5834 21H6.58337L11.8634 26.3733L17.25 21H13.25C13.25 18.5246 14.2334 16.1507 15.9837 14.4003C17.7341 12.65 20.108 11.6667 22.5834 11.6667C25.0587 11.6667 27.4327 12.65 29.183 14.4003C30.9334 16.1507 31.9167 18.5246 31.9167 21C31.9167 23.4754 30.9334 25.8493 29.183 27.5997C27.4327 29.35 25.0587 30.3333 22.5834 30.3333C20.01 30.3333 17.6767 29.28 15.9967 27.5867L14.1034 29.48C15.212 30.6008 16.5329 31.4894 17.989 32.0938C19.445 32.6982 21.0069 33.0063 22.5834 33C25.766 33 28.8182 31.7357 31.0687 29.4853C33.3191 27.2348 34.5834 24.1826 34.5834 21C34.5834 17.8174 33.3191 14.7652 31.0687 12.5147C28.8182 10.2643 25.766 9 22.5834 9Z" fill="white"/>
                                </svg>
                            </div>
                            <div class="font-weight-400 custom-typo">Ongoing Trainings</div>  
                        </div>
                        <div class="content-text text-xs-center m-b-0">
                            <div class = "counter-box">
                                <h3 style = "font-weight:lighter;">{{$ongoing_trainings}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
             <a href = "{{route('pending.trainings')}}">
                <div class="card custom-card">
                    <div class="card-block bg-white p-30">
                        <div class = "rowflex">
                            <div>
                                <svg width="43" height="42" viewBox="0 0 43 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="21.5" cy="21" r="21" fill="#FF9800"/>
                                <path d="M28.1667 34.3333C26.3222 34.3333 24.7498 33.6831 23.4493 32.3827C22.1489 31.0822 21.4991 29.5102 21.5 27.6666C21.5 25.8222 22.1502 24.2498 23.4507 22.9493C24.7511 21.6489 26.3231 20.9991 28.1667 21C30.0111 21 31.5836 21.6502 32.884 22.9506C34.1844 24.2511 34.8342 25.8231 34.8333 27.6666C34.8333 29.5111 34.1831 31.0835 32.8827 32.384C31.5822 33.6844 30.0102 34.3342 28.1667 34.3333ZM30.4 30.8333L31.3333 29.9L28.8333 27.4V23.6666H27.5V27.9333L30.4 30.8333ZM12.1667 33C11.4333 33 10.8053 32.7387 10.2827 32.216C9.76 31.6933 9.49911 31.0658 9.5 30.3333V11.6666C9.5 10.9333 9.76134 10.3053 10.284 9.78265C10.8067 9.25998 11.4342 8.99909 12.1667 8.99998H17.7333C17.9778 8.2222 18.4556 7.58309 19.1667 7.08265C19.8778 6.5822 20.6556 6.33243 21.5 6.33332C22.3889 6.33332 23.1836 6.58354 23.884 7.08398C24.5844 7.58443 25.0564 8.22309 25.3 8.99998H30.8333C31.5667 8.99998 32.1947 9.26132 32.7173 9.78398C33.24 10.3066 33.5009 10.9342 33.5 11.6666V20C33.1 19.7111 32.6778 19.4666 32.2333 19.2666C31.7889 19.0666 31.3222 18.8889 30.8333 18.7333V11.6666H28.1667V15.6666H14.8333V11.6666H12.1667V30.3333H19.2333C19.3889 30.8222 19.5667 31.2889 19.7667 31.7333C19.9667 32.1778 20.2111 32.6 20.5 33H12.1667ZM21.5 11.6666C21.8778 11.6666 22.1947 11.5386 22.4507 11.2826C22.7067 11.0266 22.8342 10.7102 22.8333 10.3333C22.8333 9.95554 22.7053 9.63865 22.4493 9.38265C22.1933 9.12665 21.8769 8.99909 21.5 8.99998C21.1222 8.99998 20.8053 9.12798 20.5493 9.38398C20.2933 9.63998 20.1658 9.95643 20.1667 10.3333C20.1667 10.7111 20.2947 11.028 20.5507 11.284C20.8067 11.54 21.1231 11.6675 21.5 11.6666Z" fill="white"/>
                                </svg>
                            </div>
                            <div class="font-weight-400 custom-typo">Pending Trainings</div>  
                        </div>
                        <div class="content-text text-xs-center m-b-0">
                            <div class = "counter-box">
                                <h3 style = "font-weight:lighter;">{{$pending_trainings}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6 col-xs-12 info-panel marg">
            <a href = "{{route('udemy.report')}}">
                <div class="card custom-card">
                    <div class="card-block bg-white p-30">
                        <div class = "rowflex">
                            <div>
                                <svg width="43" height="42" viewBox="0 0 43 42" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <circle cx="21.75" cy="21" r="21" fill="#D3D3D3"/>
                                <rect x="13.75" y="5" width="16" height="32.7111" fill="url(#pattern0)"/>
                                <defs>
                                <pattern id="pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
                                <use xlink:href="#image0_66_6483" transform="scale(0.0222222 0.0108696)"/>
                                </pattern>
                                <image id="image0_66_6483" width="300" height="112" xlink:href="data:image/png;base64,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"/>
                                </defs>
                                </svg>

                            </div>
                            <div class="font-weight-400 custom-typo">View Udemy Reports</div>  
                        </div>
                        <div class="content-text text-xs-center m-b-0">
                            <div class = "counter-box">
                                <h3 style = "font-weight:lighter;">{{$udemy_courses}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div>
    <div>
        <p style="color:black; font-size:30px;"><i class="icon fa fa-bar-chart"
                            aria-hidden="true"></i>&nbsp;&nbsp;Training Progress Chart</p>
    </div>
    <div style="display:flex; flex-direction:row;">
        <div style="width: 60%;">
            <canvas id="myChart"></canvas>
        </div>
        <div style="width: 30%;">
            <canvas id="myChart2"></canvas>
        </div>
    </div>
    </div>
</div>


@include('learningdev.modals.budget')
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
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart');
        const ctx_two = document.getElementById('myChart2');
        const labels_one = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const labels_two = ['completed', 'pending', 'overdue', 'completed'];
        new Chart(ctx, {
            type: 'bar',
            data: {
            labels: labels_one,
            datasets: [{
                label: 'Pending Training',
                data: {{json_encode($pending_counter)}},
                borderWidth: 1,
            },{
                label: 'Ongoing Training',
                data: {{json_encode($ongoing_counter)}},
                borderWidth: 1,
            },{
                label: 'Overdue Training',
                data: {{json_encode($overdue_counter)}},
                borderWidth: 1,
            },{
                label: 'Completed Training',
                data: {{json_encode($completed_counter)}},
                borderWidth: 1
            }]
            },
            options: {
            scales: {
                y: {
                beginAtZero: true,
                min:1,
                max: {{$max % 2 == 1 ? $max + 1 : $max}},
                }
            }
            }
        });

        const data = {
            labels: [
            'Ongoing',
            'Completed',
            'Pending',
            'Overdue',
            ],
            datasets: [{
            label: 'My First Dataset',
            data: [{{$ongoing_pie}}, {{$completed_pie}}, {{$pending_pie}}, {{$overdue_pie}}],
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(0, 0, 86)',
                'rgb(255, 200, 86)'
            ],
            hoverOffset: 4
            }]
        };
        
        new Chart(ctx_two, {
            type: 'doughnut',
            data: data
        });
    </script>
@endpush