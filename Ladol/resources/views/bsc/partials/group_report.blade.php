@extends('layouts.master')
@section('stylesheets')


    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <div>
        <div class="page">
            <!-- Mailbox Sidebar -->

            <!-- Mailbox Content -->
            <div class="page-main">
                <!-- Mailbox Header -->
                <div class="page-header">
                    <h1 class="page-title">Performance Report for  {{date('F-Y', strtotime($measurement_period->from))}} to {{date('F-Y', strtotime($measurement_period->to))}}</h1>
                    <div class="page-header-actions">


                    </div>
                </div>
                <!-- Mailbox Content -->
                <div class="page-content container-fluid">
                    <!-- Actions -->


                    <table id="data_table" class="table">

    <thead>
    <tr>
        <th>Employee ID</th>
        <th>Employee name</th>
        <th>Employment Date</th>
        <th>Department</th>
        <th>Designation</th>
        @foreach($metricNames as $metricName)
        <th>{{$metricName}}</th>
        @endforeach
        {{-- <th>Score</th> --}}
        <!-- <th>Behavioral Score</th> -->
        <th>Total</th>
        <th>Manager's Comment</th>
        <!-- <th>Manager's Manager Comment</th>
        <th>Head of Strategy Comment</th> -->
        <th>Head of HR Comment</th>
        <th>Evaluated by</th>
        <th class="not-export-col">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($evaluations as $evaluation)
        <tr>
            <td>{{$evaluation->user->emp_num}}</td>
            <td>{{$evaluation->user->name}}</td>
            <td>{{date("F j, Y", strtotime($evaluation->user->hiredate))}}</td>
            <td>{{$evaluation->user->job?$evaluation->user->job->department->name:''}}</td>
            <td>{{$evaluation->user->job?$evaluation->user->job->title:''}}</td>
            @foreach($metricNames as $metricName)
                <td>{{($evaluation->grouped && isset($evaluation->grouped[strval($metricName)]) ) ? $evaluation->grouped[strval($metricName)]['totalScore'] : ''}}</td>
            @endforeach
            {{--<td>{{$evaluation->scorecard_percentage}}</td>--}}
            {{-- <!-- <td>{{$evaluation->behavioral_percentage}}</td> --> --}}
            <td>{{$evaluation->scorecard_percentage+$evaluation->behavioral_percentage}}</td>
            <td>{{$evaluation->manager_approval_comment}}</td>
            <!-- <td>{{$evaluation->manager_of_manager_approval_comment}}</td>
            <td>{{$evaluation->head_of_strategy_approval_comment}}</td> -->
            <td>{{$evaluation->head_of_hr_approval_comment}}</td>
            <td>{{$evaluation->manager?$evaluation->manager->name:''}}</td>
            <td><a class="btn btn-info" target="_blank" href="{{url('bsc/individual_report?evaluation_id='.$evaluation->id)}}">View Individual Report</a></td>
        </tr>
    @endforeach

    </tbody>
</table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add Label Form -->
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/App/Mailbox.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <!-- <script src="{{ asset('assets/examples/js/apps/mailbox.js')}}"></script> -->
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                autoclose: true,
                format:'mm-yyyy',
                viewMode: "months",
                minViewMode: "months"
            });
        });
    </script>

    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <script type="text/javascript">

        $("#data_table").DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', {
                    extend:'excel',
                    exportOptions: {
                        // columns: ':visible(.export-col)'
                        columns: ':visible:not(:last-child)'
                    }}, 'print', {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A1',
                    exportOptions:{
                        columns: ':visible:not(:last-child)'
                    }
                }
            ]
        });

        function fnSubmit(arg)
        {
            $("#successor_id").val(arg);
            $("#update_form").submit();
        }

        function deleteEmployee($id){
            // deleteEmployee
        }

    </script>
@endsection
