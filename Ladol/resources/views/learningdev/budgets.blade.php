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
    <h1 class="page-title">Training Plan</h1>
    <ol class="breadcrumb mt-2" style = "margin-top:8px !important;">
      <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
      <li class="breadcrumb-item "><a href="{{route('manager_dashboard')}}">Dashboard</a></li>
    </ol>
    <div class="page-header-actions">
      <div class="row no-space w-250 hidden-sm-down">
        <div class="col-sm-6 col-xs-12">
              <a type = "button" class = "btn custom-btn" data-toggle="modal" data-target="#budgetModal"><i class="icon fa fa-plus"
                      aria-hidden="true"></i>&nbsp;Add Training Budget
            </a>
        </div>
        <div class="col-sm-6 col-xs-12">
          
        </div>
      </div>
    </div>
  </div>

  <div class="page-content container-fluid bg-white">    
    <div class="panel panel-info panel-line">
      <div class="panel-body">
          @if(session()->has('message'))
              <div class="alert alert-success">
                  {{ session()->get('message') }}
              </div>
          @endif
        <table class="table table-striped table-bordered" id="dataTable">
          <thead>
            <tr style = "background-color:#03A9F4 !important; padding:5px !important; color:white !important;">
              <th>S/N</th>
              <th>Department</th>
              <th>Allocation</th>
              <th>Stop Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
           @if(count($budgets) > 0)
                @php
                    $n = 0;
                @endphp
                @foreach($budgets as $budget)
                    <tr id="">
                    <td>{{++$n}}</td>
                    <td>{{$budget->department->name}}</td>
                    <td>{{number_format($budget->allocation, 2)}}</td>
                    <td>{{$budget->stop_date}}</td>
                    <td>
                      <div style = "display:flex; flex-direction:row;">
                        <a type = "button" style = "margin-right:10px;" id = "{{$budget->id}}" href = "Javascript:void(0)"class = "btn btn-sm btn-primary btn-custom" style = "color:white;"  onclick="ModifyBudget(this.id)"><i class="icon fa fa-pencil"
                        aria-hidden="true"></i>&nbsp;Modify</a>
                        <form method="POST" action = "{{route('budget.destroy', ['id' => $budget->id])}}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name  = "budget_id" value="{{$budget->id}}"/>
                            <button type = "submit" style = "margin-right:10px;" class = "btn btn-sm btn-warning btn-custom" style = "color:white;"><i class="icon fa fa-trash"
                            aria-hidden="true"></i>&nbsp;Delete</button>
                          </form>
                      </div>
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

@include('learningdev.modals.budget')


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