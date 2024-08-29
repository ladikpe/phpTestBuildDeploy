@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{asset('assets/examples/css/apps/message.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('global/vendor/jsgrid/1.5.3/jsgrid.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('global/vendor/alertify/alertify.min.css') }}">
    <link href="{{ asset('global/vendor/jsgrid/1.5.3/jsgrid-theme.min.css') }}" rel="stylesheet"/>
    {{-- <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" /> --}}
    <link rel="stylesheet" href="{{asset('global/vendor/summernote/summernote.min.css')}}">
    <style media="screen">
        .form-cont {
            border: 1px solid #cccccc;
            padding: 10px;
            border-radius: 5px;
        }

        #stgcont {
            list-style: none;
        }

        #stgcont li {
            margin-bottom: 10px;
        }

        .hide {
            display: none;
        }

        .jsgrid-edit-row > .jsgrid-cell {
            background-color: #c4e2ff;
        }

        .jsgrid-header-row > .jsgrid-header-cell {
            background-color: #03a9f4;
            font-size: 1em;
            color: #fff;
            font-weight: normal;
        }

        .red-column {
            background-color: #e52b1e !important;
            font-size: 1em;
            color: #fff;
            font-weight: normal;
        }

        .green-column {
            background-color: #c4e2ff !important;
            font-size: 1em;
            color: #000000 !important;
            font-weight: normal;
        }
    </style>


    <style>
        [data-toggle] {
            cursor: pointer;
        }

        [data-toggle]:hover {
            background-color: #03a9f4;
            color: #fff;
        }
    </style>
    @include('training_new.rating_style')

@endsection
@section('content')


    <div class="page" id="app">
        <div class="page-header">
            <h1 class="page-title">Balance Scorecard Evaluation</h1>
            @if((!in_array($evaluation->kpi_accepted,[4,3,1]) and Auth::user()->id==$evaluation->user_id and $evaluation->kpi_submitted==1 and $evaluation->kpi_accepted==5) || (!in_array($evaluation->kpi_accepted,[4,3,1]) and Auth::user()->id==$evaluation->user_id and $evaluation->kpi_accepted!=5) || (!in_array($evaluation->kpi_accepted,[3,1]) and Auth::user()->id==$evaluation->head_of_strategy_id and $evaluation->kpi_accepted!=null) || (!in_array($evaluation->kpi_accepted,[1]) and Auth::user()->id==$evaluation->head_of_hr_id and $evaluation->kpi_accepted!=4)  )
                          <button type="button" class="btn btn-primary" onclick="acceptKPI(1);">Accept KPIs/Measures</button>
                          <button type="button" class="btn btn-danger" onclick="acceptKPI(2);">Reject KPIs/Measures</button>
                      @endif
                      

                      @if(($evaluation->head_of_hr_id==Auth::user()->id  && $user->status=="0" && $evaluation->approval_status=='approved'))
                      <button type="button" data-action="folder" class="btn btn-success" title="Change Employee Status" data-toggle="modal" data-target="#changeStatusModal">
                      Change Employee Status
            </button>
                      @endif
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
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times</span></button>
                            {{ session('success') }}
                        </div>
                    @elseif (session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times</span></button>
                            {{ session('error') }}
                        </div>
                    @endif


                    <div class="panel panel-info " id="evaluation-panel">
                        <div class="panel-heading main-color-bg">
                            <h3 class="panel-title">Balance Scorecard Evaluation</h3>
                            
                            <div class="panel-actions">
                                <button id='performance-btn' style="background:none;outline:none;border:none;"></button>
                                {{--<button onclick="loadPerformanceDiscussion({{$evaluation->id}})"
                                        class="btn btn-default">View Performance Discussion(s)
                                </button>--}}
                                <button class="btn btn-default" data-toggle="modal" data-target="#uploadEmeasuresModal">
                                    Upload Template
                                </button>
                                {{--                      <button class="btn btn-default" onclick="useDepartmentTemplate();">Use Department Template</button>--}}
                                <a target="_blank" href="{{url('app-get/course-training')}}?id={{Auth::id()}}"
                                   class="btn btn-default">Online-Trainings</a>
                                <a href="#" data-toggle="modal" data-target="#recommend-offline-modal"
                                   class="btn btn-default">Offline-Training</a>
                                   
                                @if(count($userQuery)>0)
                                   <button class="btn btn-danger" data-toggle="modal" data-target="#queries">Query</button>
                                @endif

                            </div>
                        </div>

                        <div class="panel-body">
                            <br>
                            <div class="row">

                                <div class="col-md-3">
                                    <ul class="list-group list-group-bordered">
                                        <li class="list-group-item ">Employee Number:<span
                                                    class="pull-right">{{$evaluation->user->emp_num}}</span></li>
                                        <li class="list-group-item ">Name:<span
                                                    class="pull-right">{{$evaluation->user->name}}</span></li>
                                        <li class="list-group-item ">Job Role:<span
                                                    class="pull-right">{{$evaluation->user->job->title}}</span></li>
                                        <li class="list-group-item ">Department:<span
                                                    class="d-block">{{$evaluation->department->name}}</span></li>
                                        <li class="list-group-item ">Measurement Period:<span class="d-block">{{date('F-Y',strtotime($evaluation->measurement_period->from))}} to {{date('F-Y',strtotime($evaluation->measurement_period->to))}}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-3">
                                    <ul class="list-group list-group-bordered">
                                        <!-- <li class="list-group-item ">Is disputed:<span
                                            class="pull-right"
                                            >{{$evaluation->is_disputed==1?'Disputed':'No Dispute'}}</span></li> -->
                                        <li class="list-group-item ">Scorecard Performance Rating:<span
                                                    class="d-block"
                                                    id="spr">{{$evaluation->scorecard_percentage}}</span></li>

                                        <!-- <li class="list-group-item ">Behavioral Performance Rating:<span
                                                    class="d-block"
                                                    id="behavioral_score">{{$evaluation->behavioral_percentage}}</span>
                                        </li> -->
                                        <li class="list-group-item ">Total Score:<span class="d-block"
                                                                                                 id="final_score">{{$evaluation->scorecard_percentage+$evaluation->behavioral_percentage}}</span>
                                        </li>
                                        @php
                                        $bsc_self=$evaluation->scorecard_self_score>0?$evaluation->scorecard_self_score*($evaluation->measurement_period->scorecard_percentage/100) : 0;
                                        $beh_self=$evaluation->behavioral_self_score>0?$evaluation->behavioral_self_score*($evaluation->measurement_period->behavioral_percentage/100) : 0;
                                        @endphp
                                        <li class="list-group-item ">Self Scorecard Performance Rating:<span
                                                    class="d-block"
                                                    id="self_spr">{{$bsc_self}}</span></li>

                                        <!-- <li class="list-group-item ">Self Behavioral Performance Rating:<span
                                                    class="d-block"
                                                    id="self_behavioral_score">{{$beh_self}}</span> -->
                                        </li>
                                        <li class="list-group-item ">Self Total Score:<span class="d-block"
                                                                                                 id="self_final_score">{{$bsc_self+$beh_self}}</span>
                                        </li>


                                        {{-- <li class="list-group-item ">Average Performance Rating:<span class="d-block" id="avg_score">{{$average>1?round($average,1):"1.0"}}</span></li> --}}
                                        <li class="list-group-item">Remark:<span class="d-block" id="remark">

                  </span></li>
                                        <li class="list-group-item">
                                            Approval Status: <span
                                                    class="pull-right tag tag-{{$evaluation->status_color}}">{{$evaluation->approval_status}}</span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-md-3">

                                    <form id="evaluationCommentForm">
                                        @csrf
                                        <div class="form-group">
                                            <!-- <label for="">Appraisee's Strength</label>
                                            <textarea class="form-control" name="employee_strength" rows="2"
                                                      placeholder="Appraisee's Strength" {{$evaluation->manager_id==Auth::user()->id && $evaluation->approval_status=='appraisal'?'':'readonly'}}>{{$evaluation->employee_strength}}</textarea>
                                            <label for="">Appraisee's Developmental Areas</label>
                                            <textarea class="form-control" name="employee_developmental_area" rows="2"
                                                      placeholder="Appraisee's Developmental areas" {{$evaluation->manager_id==Auth::user()->id && $evaluation->approval_status=='appraisal'?'':'readonly'}}>{{$evaluation->employee_developmental_area}}</textarea>
                                            <label for="">Special Achievement</label>
                                            <textarea class="form-control" name="special_achievement" rows="2"
                                                      placeholder="Special Achievement" {{$evaluation->manager_id==Auth::user()->id && $evaluation->approval_status=='appraisal'?'':'readonly'}}>{{$evaluation->special_achievement}}</textarea> -->
                                            <label for="">Approval Comment</label>
                                            <textarea class="form-control" name="manager_approval_comment" rows="2"
                                                      placeholder="Approval Comment" {{$evaluation->manager_id==Auth::user()->id && $evaluation->approval_status=='appraisal'?'':'readonly'}}>{{$evaluation->manager_approval_comment}}</textarea>
                                            <input type="hidden" name="bsc_evaluation_id" value="{{$evaluation->id}}">
                                            <input type="hidden" name="type" value="save_evaluation_comment">
                                        </div>
                                        <!-- not working work onlater -->
                                        <!-- @if(($evaluation->manager_id==Auth::user()->id && $evaluation->approval_status=='appraisal'))
                                            <button type="submit" class="btn btn-primary">Save Comment</button>
                                        @endif -->
                                        {{--<button type="button" class="btn btn-success" onclick="submitKPI();">Submit for Review</button>--}}

                                    </form>

                                </div>
                                <div class="col-md-3">
                                    <!-- <div class="form-group">
                                        <label for="">Manager's Manager Comment</label>
                                        <textarea class="form-control" rows="2"
                                                  placeholder="Manager's Manager Comment" readonly>{{$evaluation->manager_of_manager_approval_comment}}</textarea>
                                        <label for="">Head of Strategy Comment</label>
                                        <textarea class="form-control"  rows="2"
                                                  placeholder="Head of Strategy Comment" readonly>{{$evaluation->head_of_strategy_approval_comment}}</textarea>
                                        <label for="">Head of HR Comment</label>
                                        <textarea class="form-control"  rows="2"
                                                  placeholder="Head of HR Comment" readonly>{{$evaluation->head_of_hr_approval_comment}}</textarea>
                                    </div> -->
                                    @if(($evaluation->manager_id==Auth::user()->id && $evaluation->approval_status=='appraisal')||
                                    ($evaluation->user_id==Auth::user()->id && $evaluation->approval_status=='accepting')||
                                    ($evaluation->user_id==Auth::user()->id && $evaluation->approval_status=='employee')||
                                    
                                    ($evaluation->manager_of_manager_id==Auth::user()->id && $evaluation->approval_status=='manager_of_manager')||
                                    ($evaluation->head_of_hr_id==Auth::user()->id && $evaluation->approval_status=='head_of_hr')||
                                    ($evaluation->head_of_strategy_id==Auth::user()->id && $evaluation->approval_status=='head_of_strategy') || ($evaluation->manager_of_manager_id==Auth::user()->id && $evaluation->approval_status=='hod')
)
                                        <button class="btn btn-success" data-toggle="modal"
                                                data-target="#approveEvaluationModal">Complete 
                                        </button>
                                    @endif

                                
                           

                                </div>

                            </div>

                            <hr>
                            @foreach($metrics as $metric)
                                <div class="table-responsive">
                                    {{-- <h3 align="center" id="">{{$metric->name}} (<span
                                                id="metric_title_{{$metric->id}}"></span>%) </h3><br/> --}}
                                    <h3 align="center">{{$metric->name}} (<span>{{$metric->description}}</span>%)</h3><br />
                                    <div id="grid_table_{{$metric->id}}"></div>
                                </div>
                            @endforeach
                            <!-- <div class="table-responsive">
                                <h3 align="center">Behavioral Evaluation</h3><br/>
                                <div id="behavioral_evaluation_table"></div>
                            </div> -->

                        </div>
                        <div class="panel-footer">

                        </div>

                    </div>


                    @include('bsc.training.template')

                </div>
            </div>

        </div>


    </div>

    @if(count($userQuery)>0)
        @include('bsc.modals.user_query')
    @endif
    @include('bsc.modals.upload_measure_template')
    @include('bsc.modals.performanceDiscussion')
    @include('bsc.modals.approval')
    @include('empmgt.modals.changeStatus')

@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
    <script type="text/javascript"
            src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
    <script type="text/javascript"
            src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/jsgrid/1.5.3/jsgrid.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/jsgrid/1.5.3/jsgrid.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/alertify/alertify.js') }}"></script>
    <script src="{{asset('global/vendor/summernote/summernote.min.js')}}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>

    <script type="text/javascript">
        var evaluationData = {!! json_encode($evaluation) !!};
        var userData = {!! json_encode(Auth::user()) !!};
        $(document).ready(function () {
            @foreach($metrics as $metric)
            fetch('{!! url("bsc/get_metric_weight?metric_id=".$metric->id."&evaluation_id=".$evaluation->id) !!}')
                .then(response => response.json())
                .then(data => {
                    document.querySelector("#metric_title_{{$metric->id}}").innerText = data;
                });
            $('#grid_table_{{$metric->id}}').jsGrid({

                width: "100%",
                height: "1100px",

                filtering: false,
                editing: true,
                sorting: true,
                paging: true,
                autoload: true,
                pageSize: 10,
                pageButtonCount: 5,
                deleteConfirm: "Do you really want to delete data?",
                rowClick: function(args) {
                    var $row = $(args.event.target).closest("tr");

                    if(this._editingRow) {
                        this.updateItem().done($.proxy(function() {
                            this.editing && this.editItem($row);
                        }, this));
                        return;
                    }

                    this.editing && this.editItem($row);
                },
                controller: {

                    loadData: function (filter) {
                        return $.ajax({
                            type: "GET",
                            url: "{{url("bsc/get_evaluation_details")}}",
                            data: {
                                "bsc_evaluation_id": "{{$evaluation->id}}",
                                "metric_id": "{{$metric->id}}"
                            }
                        });
                    },
                    insertItem: function (item) {
                        return $.ajax({
                            type: "POST",
                            url: "{{url("bsc")}}",
                            data: item
                        });
                    },
                    updateItem: function (item) {
                        return $.ajax({
                            type: "POST",
                            url: "{{url("bsc")}}",
                            data: item
                        });
                    },
                    deleteItem: function (item) {
                        return $.ajax({
                            type: "GET",
                            url: "{{url("bsc/delete_evaluation_detail")}}",
                            data: item
                        });
                    },

                }, onItemInserting: function (args) {


                    args.item._token = "{{csrf_token()}}";
                    args.item.metric_id = "{{$metric->id}}";
                    args.item.type = "save_evaluation_detail";
                    args.item.bsc_evaluation_id = "{{$evaluation->id}}";


                }, onItemUpdating: function (args) {
// cancel insertion of the item with empty 'name' field

                    args.item._token = "{{csrf_token()}}";
                    args.item.type = "save_evaluation_detail";

                },
                onItemUpdated: function (args) {
// cancel insertion of the item with empty 'name' field
                    console.log('updated');
                    $.get('{{ url('/bsc/get_evaluation_wcp') }}/', {
                        bsc_evaluation_id: {{$evaluation->id}},
                        metric_id: args.item.metric_id
                    }, function (data) {
                        $('#spr').html(data.evaluation.scorecard_percentage);
                        $('#final_score').html(parseInt(data.evaluation.scorecard_percentage) + parseInt(data.evaluation.behavioral_percentage));
                        // $('#remark').html(data.remark);
                        $("#metric_title_{{$metric->id}}").html(data.metric_weight);

                    });


                    lastPrevItem = args.previousItem;


                },
                onItemInserted: function (args) {
// cancel insertion of the item with empty 'name' field
                    console.log('inserted');
                    $.get('{{ url('/bsc/get_evaluation_wcp') }}/', {bsc_evaluation_id: {{$evaluation->id}} }, function (data) {
                        $('#spr').html(data.evaluation.scorecard_percentage);
                        $('#final_score').html(parseInt(data.evaluation.scorecard_percentage) + parseInt(data.evaluation.behavioral_percentage));
                        // $('#remark').html(data.remark);
                        $("#metric_title_{{$metric->id}}").html(data.metric_weight);

                    });
                    lastPrevItem = args.previousItem;


                },
                onItemDeleted: function (args) {
// cancel insertion of the item with empty 'name' field
                    console.log(args.item);
                    $.get('{{ url('/bsc/get_evaluation_wcp') }}/', {bsc_evaluation_id: {{$evaluation->id}} }, function (data) {
                        $('#spr').html(data.evaluation.scorecard_percentage);
                        $('#final_score').html(parseInt(data.evaluation.scorecard_percentage) + parseInt(data.evaluation.behavioral_percentage));
                        $("#metric_title_{{$metric->id}}").html(data.metric_weight);
                        $('#self_spr').html(data.evaluation.scorecard_self_score);
                        $('#self_final_score').html(parseInt(data.evaluation.scorecard_percentage) + parseInt(data.evaluation.behavioral_percentage));

                    });
                    lastPrevItem = args.previousItem;


                },

                fields: [
                    {
                        type: "control",
                        deleteButton:false,

                        width: 150,
                    },
                    {
                        name: "focus",
                        type: "text",
                        width: 150,
                        title: "Key Performance Area",
                        editing: false,
                        inserting: false,
                    },
                    {
                        name: "objective",
                        type: "text",
                        width: 150,
                        title: "Key Performance <br> Indicator",
                        editing: false,
                        inserting: false,
                    },
                    {
                        name: "key_deliverable",
                        type: "text",
                        width: 350,
                        title: "Measurement",
                        editing: false,
                        inserting: false,
                    },
                    {
                        name: "measure_of_success",
                        type: "text",
                        width: 150,
                        title: "Target",
                        editing: false,
                        inserting: false,
                    },
                    {
                        type: "control",
                        width: 100,
                    },
                    {
                        name: "means_of_verification",
                        type: "text",
                        width: 150,
                        title: "Frequency",
                        editing: false,
                        inserting: false,
                    },
                    {
                        name: "weight",
                        type: "number",
                        title: "Weight <br> (%)",
                        width: 50,
                        editing: false,
                        inserting: false,
                    },
                    {
                        name: "self_assessment",
                        type: "number",
                        width: 100,
                        title: "Self<br> Assessment",
                        editing: {{$evaluation->user_id==Auth::user()->id && $evaluation->approval_status=='employee'?'true':'false'}},
                        inserting: false,
                        @if($evaluation->user_id==Auth::user()->id and  $evaluation->approval_status=='employee')
                        headercss: 'green-column',
                        css: 'green-column',
                        @endif

                    },
                    {
                        name: "employee_comment",
                        type: "text",
                        width: 150,
                        title: "Employee<br> Comment",
                        editing: {{$evaluation->user_id==Auth::user()->id && $evaluation->approval_status=='employee'?'true':'false'}},
                        inserting: false,
                        @if($evaluation->user_id==Auth::user()->id and  $evaluation->approval_status=='employee')
                        headercss: 'green-column',
                        css: 'green-column'
                        @endif
                    },
                    {
                        name: "manager_assessment",
                        type: "number",
                        title: "Manager<br> Assessment",
                        width: 100,
                        editing: {{$evaluation->manager_id==Auth::user()->id && $evaluation->approval_status=='appraisal'?'true':'false'}},
                        inserting: false,
                        @if($evaluation->manager_id==Auth::user()->id and  $evaluation->approval_status=='appraisal')
                        headercss: 'green-column',
                        css: 'green-column',
                        @endif

                    },
                    {
                        type: "control",
                        width: 50,
                    },
                    {
                        name: "justification_of_rating",
                        type: "text",
                        title: "Manager<br> Comment",
                        width: 250,
                        editing: {{$evaluation->manager_id==Auth::user()->id && $evaluation->approval_status=='appraisal'?'true':'false'}},
                        inserting: false,
                        @if($evaluation->manager_id==Auth::user()->id and  $evaluation->approval_status=='appraisal')
                        headercss: 'green-column',
                        css: 'green-column'
                        @endif

                    },
                  
                    // {
                    //     name: "manager_of_manager_assessment",
                    //     type: "text",
                    //     title: "Manager's <br>Manager<br> Assessment",
                    //     width: 100,
                    //     editing: {{$evaluation->manager_of_manager_id==Auth::user()->id && $evaluation->approval_status=='manager_of_manager'?'true':'false'}},
                    //     inserting: false,
                    //     @if($evaluation->manager_of_manager_id==Auth::user()->id and  $evaluation->approval_status=='manager_of_manager')
                    //     headercss: 'green-column',
                    //     css: 'green-column',
                    //     @endif

                    // },
                    {
                        name: "score",
                        type: "number",
                        title: "Head <br>of HR<br> Assessment",
                        width: 150,
                        editing: {{$evaluation->head_of_hr_id==Auth::user()->id && $evaluation->approval_status=='head_of_hr'?'true':'false'}},

                        inserting: false,
                        @if($evaluation->head_of_hr_id==Auth::user()->id && $evaluation->approval_status=='head_of_hr')
                        headercss: 'green-column',
                        css: 'green-column',
                        @endif

                    },
                    {
                        name: "modified_date",
                        type: "text",
                        title: "Last Updated",
                        width: 100,
                        editing: false,
                        inserting: false

                    },
             

                ]

            });
            @endforeach
            //behavioral evaluation functions
            $('#behavioral_evaluation_table').jsGrid({

                width: "100%",
                height: "400px",

                filtering: false,
                editing: true,

                sorting: true,
                paging: true,
                autoload: true,
                pageSize: 10,
                pageButtonCount: 5,
                deleteConfirm: "Do you really want to delete data?",
                rowClick: function(args) {
                    var $row = $(args.event.target).closest("tr");

                    if(this._editingRow) {
                        this.updateItem().done($.proxy(function() {
                            this.editing && this.editItem($row);
                        }, this));
                        return;
                    }

                    this.editing && this.editItem($row);
                },
                controller: {
                    loadData: function (filter) {
                        return $.ajax({
                            type: "GET",
                            url: "{{url("bsc/get_behavioral_evaluation_details")}}",
                            data: {
                                "bsc_evaluation_id": "{{$evaluation->id}}"
                            }
                        });
                    },
                    insertItem: function (item) {
                        return $.ajax({
                            type: "POST",
                            url: "{{url("bsc")}}",
                            data: item
                        });
                    },
                    updateItem: function (item) {
                        return $.ajax({
                            type: "POST",
                            url: "{{url("bsc")}}",
                            data: item
                        });
                    },
                    deleteItem: function (item) {
                        return $.ajax({
                            type: "GET",
                            url: "{{url("bsc/delete_evaluation_detail")}}",
                            data: item
                        });
                    },
                }, onItemInserting: function (args) {


                    args.item._token = "{{csrf_token()}}";
                    args.item.type = "save_behavioral_evaluation_detail";
                    args.item.bsc_evaluation_id = "{{$evaluation->id}}";


                }, onItemUpdating: function (args) {
// cancel insertion of the item with empty 'name' field

                    args.item._token = "{{csrf_token()}}";
                    args.item.type = "save_behavioral_evaluation_detail";

                },
                onItemUpdated: function (args) {
// cancel insertion of the item with empty 'name' field
                    console.log('updated');
                    $.get('{{ url('/bsc/get_behavioral_evaluation_wcp') }}/', {bsc_evaluation_id: {{$evaluation->id}} }, function (data) {

                        $('#behavioral_score').html(data.evaluation.behavioral_percentage);
                        $('#final_score').html(parseInt(data.evaluation.scorecard_percentage) + parseInt(data.evaluation.behavioral_percentage));
// $('#remark').html(data.remark);

                    });
                    lastPrevItem = args.previousItem;


                },
                onIteminserted: function (args) {
// cancel insertion of the item with empty 'name' field
                    console.log('inserted');
                    $.get('{{ url('/bsc/get_behavioral_evaluation_wcp') }}/', {bsc_evaluation_id: {{$evaluation->id}} }, function (data) {
                        $('#behavioral_score').html(data.evaluation.behavioral_percentage);
                        $('#final_score').html(parseInt(data.evaluation.scorecard_percentage) + parseInt(data.evaluation.behavioral_percentage));


                    });
                    lastPrevItem = args.previousItem;


                },

                fields: [

                    {
                        type: "control"
                    },
                    {
                        name: "business_goal",
                        type: "text",
                        width: 150,
                        title: "Business Goal",
                        editing: false,
                        inserting: false
                    },
                    {
                        name: "weighting",
                        type: "number",
                        width: 60,
                        title: "Weighting<br>(%)",
                        editing: false,
                        inserting: false
                    },
                    {
                        name: "objective",
                        type: "number",
                        width: 150,
                        title: "Objective/Strategic Focus",
                        editing: false,
                        inserting: false
                    },
                    {
                        name: "measure",
                        type: "text",
                        width: 350,
                        title: "Measure/ KPI",
                        editing: false,
                        inserting: false
                    },
                    {
                        name: "self_assessment",
                        type: "number",
                        width: 150,
                        title: "Self<br> Assessment",
                        editing: {{$evaluation->user_id==Auth::user()->id && $evaluation->approval_status=='employee'?'true':'false'}},
                        inserting: false,
                        @if($evaluation->user_id==Auth::user()->id and  $evaluation->approval_status=='employee')
                        headercss: 'green-column',
                        css: 'green-column',
                        @endif

                    },
                    {
                        name: "employee_comment",
                        type: "text",
                        width: 150,
                        title: "Employee<br> Comment",
                        editing: {{$evaluation->user_id==Auth::user()->id && $evaluation->approval_status=='employee'?'true':'false'}},
                        inserting: false,
                        @if($evaluation->user_id==Auth::user()->id and  $evaluation->approval_status=='employee')
                        headercss: 'green-column',
                        css: 'green-column'
                        @endif
                    },
                    {
                        name: "manager_assessment",
                        type: "number",
                        title: "Manager<br> Assessment",
                        width: 150,
                        editing: {{$evaluation->manager_id==Auth::user()->id && $evaluation->approval_status=='appraisal'?'true':'false'}},
                        inserting: false,
                        @if($evaluation->manager_id==Auth::user()->id and  $evaluation->approval_status=='appraisal')
                        headercss: 'green-column',
                        css: 'green-column',
                        @endif

                    },

                    
                    {
                        name: "manager_of_manager_assessment",
                        type: "text",
                        title: "Manager's <br>Manager<br> Assessment",
                        width: 150,
                        editing: {{$evaluation->manager_of_manager_id==Auth::user()->id && $evaluation->approval_status=='manager_of_manager'?'true':'false'}},
                        inserting: false,
                        @if($evaluation->manager_of_manager_id==Auth::user()->id and  $evaluation->approval_status=='manager_of_manager')
                        headercss: 'green-column',
                        css: 'green-column',
                        @endif

                    },
                    {
                        name: "head_of_strategy",
                        type: "text",
                        title: "Head of <br>Strategy<br> Assessment",
                        width: 150,
                        editing: {{$evaluation->head_of_strategy_id==Auth::user()->id && $evaluation->approval_status=='head_of_strategy'?'true':'false'}},
                        inserting: false,
                        @if($evaluation->head_of_strategy_id==Auth::user()->id and  $evaluation->approval_status=='head_of_strategy')
                        headercss: 'green-column',
                        css: 'green-column',
                        @endif

                    },
                    {
                        name: "score",
                        type: "number",
                        title: "Head <br>of HR<br> Assessment",
                        width: 150,
                        editing: {{$evaluation->head_of_hr_id==Auth::user()->id && $evaluation->approval_status=='head_of_hr'?'true':'false'}},
                        inserting: false,
                        @if($evaluation->head_of_hr_id==Auth::user()->id && $evaluation->approval_status=='head_of_hr' )
                        headercss: 'green-column',
                        css: 'green-column',
                        @endif

                    },

                    {
                        name: "modified_date",
                        type: "text",
                        title: "Last Updated",
                        width: 100,
                        editing: false,
                        inserting: false

                    }


                ]

            });
        });


        function useDepartmentTemplate(det_id) {
            alertify.confirm('Are you sure you want to use this Template?', function () {
                fetch('{!! url("bsc/use_dept_template?bsc_evaluation_id=".$evaluation->id."&det_id=") !!}' + det_id)
                    .then(response => response.json())
                    .then(data => {
                        if (data == 'success') {
                            toastr.success("Data Import Successful", 'Success');
                            location.reload();
                        }
                    });

            }, function () {
                alertify.error('Template was not used');
            });
        }

        function submitKPI() {
            alertify.confirm('Are you sure you want to Submit this review?', function () {
                $.get('{{ url('/bsc/submit_kpis_for_review') }}/', {bsc_evaluation_id:{{$evaluation->id}} }, function (data) {
                    if (data == 'success') {
                        toastr.success("KPIs submitted successfully", 'Success');

                        location.reload();
                    }

                });
            }, function () {
                alertify.error('Evaluation was not submitted');
            });
        }
            
        


     

        $(function () {

            $(document).ready(function () {
                $('.summernote').summernote({
                    height: 150,
                });
            });
    

            $(document).on('submit', '#approveEvaluationForm', function (event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{route('bsc.store')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        console.log('The Data', data)


                        toastr.success("Comment Saved Successfully", 'Success');
                        $('#approveEvaluationModal').modal('toggle');
                        location.reload();

                    },
                    error: function (data, textStatus, jqXHR) {
                        jQuery.each(data['responseJSON'], function (i, val) {
                            jQuery.each(val, function (i, valchild) {
                                toastr["error"](valchild[0]);
                            });
                        });
                    }
                });

            });
        });

        $(function () {
            // change status
            $(document).on('click','#changeStatus', function (event) {
           event.preventDefault();
           
           var users = [{{$user->id}}];
           console.log('users eval =>',users)
           status=$("#status_id").val();
           $.get('{{ url('/users/alterstatus') }}/',{ status: status,users:users }, function (data) {
               toastr.success("Status Changed Successfully",'Success');
            //    console.log('data eval =>', data)
               $('#changeStatusModal').modal('toggle');
            location.reload();  

           });
        })
            $(document).on('submit', '#uploadEmeasuresForm', function (event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{route('bsc.store')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        if (data == 'success') {
                            toastr.success("Data Import Successful", 'Success');
                            $('#uploadEmeasuresModal').modal('toggle');
                            location.reload();
                        } else {
                            toastr.error("Error with document uploaded", 'Error');
                        }


                    },
                    error: function (data, textStatus, jqXHR) {
                        jQuery.each(data['responseJSON'], function (i, val) {
                            jQuery.each(val, function (i, valchild) {
                                toastr["error"](valchild[0]);
                            });
                        });
                    }
                });

            });
        });

       
               
         
        

        function acceptKPI(action=1) {
      formData={
          bsc_evaluation_id:{{$evaluation->id}},
          line_manager_id:{{$user->line_manager_id}},
          action : action
      }

      alertify.confirm('Are you sure you want to perform Operation ?', function () {
          $.get('{{ url('/bsc/accept_kpis') }}/',formData,function(data){
           
              if (data=='success') {
                  toastr.success("KPIs Assigned has been accepted",'Success');

                  location.reload();
              }

          });
      }, function () {
          alertify.error('Operation Cancelled');
      });


  }
    </script>



    {{--@include('training_new.js_plugin.rating_plugin')--}}

    {{--@include('training_new.js_framework.binder')--}}

    {{--@include('training_new.js_framework.binder_v2')--}}


    {{--@include('training_new.js_framework.vue')--}}
    {{--@include('training_new.vue_components.training_plan_stat')--}}
    {{--@include('training_new.vue_components.training_eligibility')--}}
    {{--@include('training_new.vue_components.training_enroll_status')--}}
    {{--@include('training_new.vue_components.training_progress_status')--}}
    {{--@include('training_new.vue_components.training_feedback')--}}
    {{--@include('training_new.vue_components.ajax_text')--}}
    {{--@include('training_new.vue_components.training_plan_recommendation')--}}
    {{--@include('training_new.vue_components.vue_root')--}}


    @include('training_new.crud.js')

    @include('bsc.training.js')


@endsection
