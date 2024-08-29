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
    <h1 class="page-title">Training Plan Details</h1>
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

  <div class="page-content container-fluid bg-white">    
    <div class="panel panel-info panel-line">
      <div class="panel-body card shadow-lg bg-white">
            <div class = "row"> 
                <div class = "col-md-3">
                    <div class = "mb-4 holder">
                        <h5 class="title-text">Training Title</h5>
                    </div>
                    <div class = "m-2 holder">
                        <h5  class="title-text">Training Description</h5>
                    </div>
                </div>
                <div class = "col-md-9">
                    <div>
                        <h5>{{$plan->name}}</h5>
                    <div>
                        <h5>
                         {{$plan->description}}
                        </h5>
                    </div>
                    <div class = "row " style = "margin-top:20px !important;">
                        <div class = "col-md-4">
                            <div>
                                <h5 class="title-text">
                               Type
                                </h5>
                                <h5>
                                {{$plan->type}}
                                </h5>
                            </div>

                            <div>
                                <h5 class="title-text">
                                Assign To
                                </h5>
                                <h5>
                               {{$plan->assign_mode}}
                                </h5>
                            </div>
                            <div>
                                <h5 class="title-text">
                                End Date
                                </h5>
                                <h5>
                                 {{ \Carbon\Carbon::parse($plan->end_date)->format('F j, Y') }}
                                </h5>
                            </div>
                        </div>
                        <div class = "col-md-4">
                            <div>
                                <h5 class="title-text">
                                 Duration(in hours)
                                </h5>
                                <h5>
                                 {{$plan->duration}}
                                </h5>
                            </div>

                           
                            <div>
                                <h5 class="title-text">
                               Start Date
                                </h5>
                                <h5>
                                {{ \Carbon\Carbon::parse($plan->start_date)->format('F j, Y') }}
                                </h5>
                            </div>
                          @if((auth()->user()->job_id != "1") && (auth()->user()->job_id != "3"))
                            <div>
                                <h5 class="title-text">
                                   Progress(%)
                                </h5>
                                <h5>
                                {{ $rate ."%" }}
                                </h5>
                            </div>
                          @endif
                        </div>
                        <div class = "col-md-4">
                            <div>
                                <h5 class="title-text">
                                  Mode
                                </h5>
                                <h5>
                                 {{$plan->mode}}
                                </h5>
                            </div>

                            <div>
                                <h5 class="title-text">
                                 Cost per head
                                </h5>
                                <h5>
                                   &#x20A6;&nbsp;{{$plan->cost_per_head}}
                                </h5>
                            </div>

                          
                            
                        </div
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