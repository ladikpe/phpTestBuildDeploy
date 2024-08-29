@extends('layouts.master')
@section('stylesheets')
<link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">

@endsection
@section('content')
<!-- Page -->
<div class="page ">
    <div class="page-header">
        <h1 class="page-title">{{__('Loan Request')}}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
            <li class="breadcrumb-item active">{{__('Loan Request')}}</li>
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
                        <h3 class="panel-title">New Loan Request Approvals</h3>

                    </div>
                    <div class="panel-body" id="tbd">
                        <div class="table-responsive">
                            <table class="table table striped">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Amount</th>
                                        <th>Year</th>
                                        <th>Created At</th>
                                        <th>Approval Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($user_approvals as $approval)
                                    @if($approval->loan_request)
                                    <tr>
                                        <td>{{$approval->loan_request->user->name}}</td>
                                        <td>{{$approval->loan_request->amount}}</td>
                                        <td>{{date("Y", strtotime($approval->loan_request->created_at))}}</td>
                                        <td>{{date("F j, Y", strtotime($approval->loan_request->created_at))}}</td>
                                        <td>My Approvals</td>

                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    id="exampleIconDropdown1" data-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1"
                                                    role="menu">
                                                    <a data-id="{{ $approval->loan_request_id }}"
                                                        style="cursor:pointer;" class="dropdown-item view-approval"
                                                        id="{{$approval->loan_request_id}}"
                                                        onclick="viewRequestApproval(this.id)"><i class="fa fa-eye"
                                                            aria-hidden="true"></i>&nbsp;View Details</a>
                                                    <a style="cursor:pointer;" class="dropdown-item"
                                                        id="{{$approval->id}}" onclick="approve(this.id)"><i
                                                            class="fa fa-eye"
                                                            aria-hidden="true"></i>&nbsp;Approve/Reject</a>

                                                    <a style="cursor:pointer;" class="dropdown-item" target="_blank"
                                                        id=""
                                                        href="{{url('loan/get_loan_details').'?loan_request_id='.$approval->loan_request->id}}"><i
                                                            class="fa fa-money" aria-hidden="true"
                                                            target="_blank"></i>&nbsp;View LoanRequest</a>

                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    @endif
                                    @endforeach
                                    @foreach($dr_approvals as $approval)
                                    @if($approval->loan_request)
                                    <tr>
                                        <td>{{$approval->loan_request->user->name}}</td>
                                        <td>{{$approval->loan_request->amount}}</td>
                                        <td>{{date("Y", strtotime($approval->loan_request->created_at))}}</td>
                                        <td>{{date("F j, Y", strtotime($approval->loan_request->created_at))}}</td>

                                        <td>Direct Report Approval</td>

                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    id="exampleIconDropdown1" data-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1"
                                                    role="menu">
                                                    <a data-id="{{ $approval->loan_request_id }}"
                                                        style="cursor:pointer;" class="dropdown-item view-approval"
                                                        id="{{$approval->loan_request_id}}"
                                                        onclick="viewRequestApproval(this.id)"><i class="fa fa-eye"
                                                            aria-hidden="true"></i>&nbsp;View Details</a>
                                                    <a style="cursor:pointer;" class="dropdown-item"
                                                        id="{{$approval->id}}" onclick="approve(this.id)"><i
                                                            class="fa fa-eye"
                                                            aria-hidden="true"></i>&nbsp;Approve/Reject</a>

                                                    <a style="cursor:pointer;" class="dropdown-item" target="_blank"
                                                        id=""
                                                        href="{{url('loan/get_loan_details').'?loan_request_id='.$approval->loan_request->id}}"><i
                                                            class="fa fa-money" aria-hidden="true"
                                                            target="_blank"></i>&nbsp;View LoanRequest</a>

                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    @endif
                                    @endforeach
                                    @foreach($ss_approvals as $approval)
                                    @if($approval->loan_request)
                                    <tr>
                                        <td>{{$approval->loan_request->user->name}}</td>
                                        <td>{{$approval->loan_request->amount}}</td>
                                        <td>{{date("Y", strtotime($approval->loan_request->created_at))}}</td>
                                        <td>{{date("F j, Y", strtotime($approval->loan_request->created_at))}}</td>

                                        <td>Supervisor of Supervisor Approval</td>

                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    id="exampleIconDropdown1" data-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1"
                                                    role="menu">
                                                    <a data-id="{{ $approval->loan_request_id }}"
                                                        style="cursor:pointer;" class="dropdown-item view-approval"
                                                        id="{{$approval->loan_request_id}}"
                                                        onclick="viewRequestApproval(this.id)"><i class="fa fa-eye"
                                                            aria-hidden="true"></i>&nbsp;View Details</a>
                                                    <a style="cursor:pointer;" class="dropdown-item"
                                                        id="{{$approval->id}}" onclick="approve(this.id)"><i
                                                            class="fa fa-eye"
                                                            aria-hidden="true"></i>&nbsp;Approve/Reject</a>
                                                    @if($approval->payroll)
                                                    <a style="cursor:pointer;" class="dropdown-item" target="_blank"
                                                        id=""
                                                        href="{{url('compensation/payroll_list').'?month='.date('m-Y',strtotime($approval->payroll->for))}}"><i
                                                            class="fa fa-money" aria-hidden="true"
                                                            target="_blank"></i>&nbsp;View LoanRequest</a>
                                                    @endif

                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    @endif
                                    @endforeach
                                    @foreach($role_approvals as $approval)
                                    @if($approval->loan_request)
                                    <tr>
                                        <td>{{$approval->loan_request->user->name}}</td>
                                        <td>{{$approval->loan_request->amount}}</td>
                                        <td>{{date("Y", strtotime($approval->loan_request->created_at))}}</td>
                                        <td>{{date("F j, Y", strtotime($approval->loan_request->created_at))}}</td>

                                        <td>Role Approvals</td>

                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    id="exampleIconDropdown1" data-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1"
                                                    role="menu">
                                                    <a data-id="{{ $approval->loan_request_id }}"
                                                        style="cursor:pointer;" class="dropdown-item view-approval"
                                                        id="{{$approval->loan_request_id}}"
                                                        onclick="viewRequestApproval(this.id)"><i class="fa fa-eye"
                                                            aria-hidden="true"></i>&nbsp;View Details</a>
                                                    <a style="cursor:pointer;" class="dropdown-item"
                                                        id="{{$approval->id}}" onclick="approve(this.id)"><i
                                                            class="fa fa-eye"
                                                            aria-hidden="true"></i>&nbsp;Approve/Reject</a>

                                                    <a style="cursor:pointer;" class="dropdown-item" target="_blank"
                                                        id=""
                                                        href="{{url('loan/get_loan_details').'?loan_request_id='.$approval->loan_request->id}}"><i
                                                            class="fa fa-money" aria-hidden="true"
                                                            target="_blank"></i>&nbsp;View LoanRequest</a>

                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    @endif
                                    @endforeach
                                    @foreach($group_approvals as $approval)
                                    @if($approval->loan_request)
                                    <tr>
                                        <td>{{$approval->loan_request->user->name}}</td>
                                        <td>{{$approval->loan_request->amount}}</td>
                                        <td>{{date("Y", strtotime($approval->loan_request->created_at))}}</td>
                                        <td>{{date("F j, Y", strtotime($approval->loan_request->created_at))}}</td>

                                        <td>Group Approvals</td>

                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    id="exampleIconDropdown1" data-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1"
                                                    role="menu">
                                                    <a data-id="{{ $approval->loan_request_id }}"
                                                        style="cursor:pointer;" class="dropdown-item view-approval"
                                                        id="{{$approval->loan_request_id}}"
                                                        onclick="viewRequestApproval(this.id)"><i class="fa fa-eye"
                                                            aria-hidden="true"></i>&nbsp;View Details</a>
                                                    <a style="cursor:pointer;" class="dropdown-item"
                                                        id="{{$approval->id}}" onclick="approve(this.id)"><i
                                                            class="fa fa-eye"
                                                            aria-hidden="true"></i>&nbsp;Approve/Reject</a>

                                                    {{-- <a style="cursor:pointer;" class="dropdown-item"
                                                        target="_blank" id=""
                                                        href="{{url('loan/get_loan_details/').$approval->loan_request_id}}"><i
                                                            class="fa fa-download" aria-hidden="true"></i>&nbsp;View
                                                        LoanRequest</a> --}}

                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    @endif
                                    @endforeach

                                </tbody>

                            </table>
                        </div>


                    </div>
                </div>
                {{-- beginning of historical approval --}}
                <div class="panel panel-info panel-line">
                    <div class="panel-heading">
                        <h3 class="panel-title">Approval History</h3>

                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table striped">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Year</th>
                                        <th>Created At</th>
                                        <th>Created By</th>
                                        <th>Approved At</th>
                                        <th>Comment</th>
                                        <th>Status</th>

                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach(Auth::user()->loan_approvals as $approval)
                                    @if($approval->loan_request )
                                    <tr>


                                        <td>{{date("M", strtotime($approval->loan_request->created_at))}}</td>
                                        <td>{{date("Y", strtotime($approval->loan_request->created_at))}}</td>

                                        <td>{{date("F j, Y", strtotime($approval->loan_request->created_at))}}</td>
                                        <td>{{$approval->loan_request->user->name}}</td>
                                        <td>{{date("F j, Y", strtotime($approval->updated_at))}}</td>
                                        <td>{{$approval->comments}}</td>
                                        <td><span
                                                class=" tag   {{$approval->status==0?'tag-warning':($approval->status==1?'tag-success':'tag-danger')}}">{{$approval->status==0?'pending':($approval->status==1?'approved':'rejected')}}</span>
                                        </td>
                                        <td><a data-id="{{ $approval->loan_request_id }}" style="cursor:pointer;"
                                                class="btn btn-primary" id="{{$approval->loan_request_id}}"
                                                onclick="viewRequestApproval(this.id)"><i class="fa fa-eye"
                                                    aria-hidden="true"></i>&nbsp;View Details</a>
                                        </td>
                                    </tr>

                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- end of historical approval --}}

            </div>

        </div>
    </div>
</div>
<!-- End Page -->
@include('loan.modals.approve_request')
{{-- LoanRequest Details Modal --}}

{{-- Leave Request Details Modal --}}
<div class="modal fade in modal-3d-flip-horizontal modal-info" id="loanDetailsModal" aria-hidden="true"
    aria-labelledby="loanDetailsModal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="training_title">LoanRequest Details</h4>
            </div>
            <div class="modal-body">

                <div class="row row-lg col-xs-12">
                    <div class="col-xs-12" id="dtl">

                    </div>
                    <div class="clearfix hidden-sm-down hidden-lg-up"></div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-xs-12">


                    <!-- End Example Textarea -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}">
</script>
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/material.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
            $('.input-daterange').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
            $('#approval').on('change', function () {
                type = $(this).val();

                if (type == 1) {

                    $('#comment').attr('required', false);

                }
                if (type == 2) {
                    $('#comment').attr('required', true);
                }


            });
            $(document).on('submit', '#approveLoanRequestForm', function (event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{route('loan.store')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {

                        if (data == 'success') {
                            toastr.success("Changes saved successfully", 'Success');
                            $('#approveLoanRequestModal').modal('toggle');
                            location.reload();
                        } else {
                            toastr.error(data);
                        }

                    },
                    error: function (data, textStatus, jqXHR) {
                        jQuery.each(data['responseJSON'], function (i, val) {
                            jQuery.each(val, function (i, valchild) {
                                toastr.error(valchild[0]);
                            });
                        });
                    }
                });

            });
        });

        function approve(loan_approval_id) {
            $(document).ready(function () {
                $('#approval_id').val(loan_approval_id);
                $('#approveLoanRequestModal').modal();
            });

        }
        function viewRequestApproval(loan_request_id)
        {
            $(document).ready(function() {
                $("#dtl").load("{{ url('/loan/get_loan_details') }}/?loan_request_id="+loan_request_id);
                $('#loanDetailsModal').modal();
            });

        }

        window.viewRequestApproval = viewRequestApproval;  //Export to global windows object.

</script>
@endsection