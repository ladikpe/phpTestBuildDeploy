@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="{{ asset('global/vendor/datatables/datatables.min.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
@endsection
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">Data Policy Compliance</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">Data Policy Compliance</li>
            </ol>
            <div class="page-header-actions">
                <div class="row no-space w-250 hidden-sm-down">

                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium">{{date('Y-m-d')}}</span>

                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium" id="time"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-content container-fluid">
            <div class="row" data-plugin="matchHeight" data-by-row="true">
                <!-- First Row -->


                <!-- End First Row -->
                {{-- second row --}}
                <div class="col-ms-12 col-xs-12 col-md-12">
                    <div class="panel panel-info panel-line">
                        <div class="panel-heading">
                            <h3 class="panel-title">Data Policy Acceptances</h3>

                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table striped datatable">
                                    <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Accepted on</th>

                                  
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($dpas as $dpa)
                                        @if($dpa->user)
                                            <tr>
                                                <td>{{$dpa->user ? $dpa->user->name : 'N/A'}}</td>
                                                <td>{{date("F j, Y", strtotime($dpa->updated_at))}}</td>
                                                

                                            </tr>
                                        @endif
                                    @endforeach
                                 
                                   
                                    </tbody>

                                </table>
                            </div>


                        </div>
                    </div>
                 
                   
                </div>

            </div>
    <!-- End ROW -->


        </div>
    </div>
    <!-- End Page -->

@endsection

