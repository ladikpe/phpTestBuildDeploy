@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <style media="screen">
        .form-cont{
            border: 1px solid #cccccc;
            padding: 10px;
            border-radius: 5px;
        }
        #stgcont {
            list-style: none;
        }
        #stgcont li{
            margin-bottom: 10px;
        }
    </style>

@endsection
@section('content')
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">Balance Scorecard Evaluation Approvals</h1>
            <div class="page-header-actions">
                <div class="row no-space w-250 hidden-sm-down">

                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium">{{date("M j, Y")}}</span>

                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium" id="time">{{date('h:i s a')}}</span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content container-fluid">
            <div class="row">

                <div class="col-md-12">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                            {{ session('success') }}
                        </div>
                    @elseif (session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close" ><span aria-hidden="true">&times</span> </button>
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="panel panel-info ">
                        <div class="panel-heading main-color-bg">
                            <h3 class="panel-title">Employees</h3>
                        </div>


                        <div class="panel-body">

                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Job Role</th>
                                    <th>Grade</th>
                                    <th>Measurement Period</th>
                                    <th>Approval Type</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($evaluations as $evaluation)
                                    @php
                                    $user= $evaluation->user;
                                    @endphp
                                    <tr>
                                        <td>{{$evaluation->user->name}}</td>
                                        <td>{{$user->job?$user->job->department->name:''}}</td>
                                        <td>{{$user->job?$user->job->title:''}}</td>
                                        <td>{{$user->user_grade?$user->user_grade->level:''}}</td>
                                        <td>{{date('F-Y',strtotime($evaluation->measurement_period->from))}} - {{date('F-Y',strtotime($evaluation->measurement_period->to))}}</td>

                                        <td>{{$evaluation->approval_status? $evaluation->approval_status: 'pending'}}</td>
                                        <td><a class="btn btn-info" href="{{ url('bsc/get_evaluation').'?employee='.$user->id.'&mp='.$evaluation->measurement_period->id }}">View Evaluation</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>


                    </div>


                </div>
            </div>

        </div>


    </div>
@endsection
@section('scripts')

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.input-daterange').datepicker({
                autoclose: true
            });
            $('.select2').select2();
            var selected = [];

            $('.active-toggle').change(function() {
                var id = $(this).attr('id');
                var isChecked = $(this).is(":checked");
                console.log(isChecked);
                $.get(
                    '{{ route('workflows.alter-status') }}',
                    { id: id, status: isChecked },
                    function(data) {
                        if(data=="enabled"){
                            toastr.success('Enabled!', 'Workflow Status');
                        }
                        if(data=="disabled"){
                            toastr.error('Disabled!', 'Workflow Status')
                        }else{
                            toastr.error(data, 'Workflow Status');

                        }


                    }
                );

            });
            $('#user').select2({
                ajax: {
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    url: function (params) {
                        return '{{url('bsc/usersearch')}}';
                    }
                }
            });
        } );
    </script>
@endsection