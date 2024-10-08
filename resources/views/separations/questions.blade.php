@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />

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
        .hide{
            display:none;
        }

    </style>

@endsection
@section('content')
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">Separation Question Template</h1>
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
                            <h3 class="panel-title">Separation Question Template</h3>
                            <div class="panel-actions">
                                <button class="btn btn-default" data-toggle="modal" data-target="#addQuestionModal">Add Question</button>
                                {{-- <button class="btn btn-default" data-toggle="modal" data-target="#uploadQuestionModal">Upload Template</button>--}}

                            </div>
                        </div>

                        <div class="panel-body">
                            <br>





                            @foreach($questions as $question)
                                @if($question->type=='chk'||$question->type=='rad')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-striped">
                                                <tr>
                                                    <th>Question</th>
                                                    <td>{{$question->question}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Question Category</th>
                                                    <td>{{$question->category?$question->category->name:''}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Type </th>
                                                    <td>{{$question->type=='txt'?'Textbox':($question->type=='chk'?'Checkbox':($question->type=='rad'?'Radio Button':''))}}</td>
                                                </tr>
                                                <tr>
                                                    <th> Status</th>
                                                    <td>{{$question->status==1?'Active':($question->status==0?'Inactive':'')}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Compulsory</th>
                                                    <td>{{$question->compulsory==1?'Yes':($question->status==0?'No':'')}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Actions</th>
                                                    <td>
                                                        <button type="button"  class="btn btn-info   btn-sm  " id="{{$question->id}}" onclick="edit_question(this.id)" ><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                        <button type="button" id="{{$question->id}}" class="btn btn-danger btn-sm " onclick="deletequestion(this.id)"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="table-responsive">

                                                <div id="grid_table_{{$question->id}}"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                @else
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-striped">
                                                <tr>
                                                    <th>Question</th>
                                                    <td>{{$question->question}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Question Category</th>
                                                    <td>{{$question->category?$question->category->name:''}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Type </th>
                                                    <td>{{$question->type=='txt'?'Textbox':($question->type=='chk'?'Checkbox':($question->type=='rad'?'Radio Button':''))}}</td>
                                                </tr>
                                                <tr>
                                                    <th> Status</th>
                                                    <td>{{$question->status==1?'Active':($question->status==0?'Inactive':'')}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Compulsory</th>
                                                    <td>{{$question->compulsory==1?'Yes':($question->status==0?'No':'')}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Actions</th>
                                                    <td>
                                                        <button type="button"  class="btn btn-info   btn-sm  " id="{{$question->id}}" onclick="edit_question(this.id)" ><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                        <button type="button" id="{{$question->id}}" class="btn btn-danger btn-sm " onclick="deletequestion(this.id)"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <hr>
                                @endif



                            @endforeach


                        </div>
                        <div class="panel-footer">

                        </div>

                    </div>


                </div>
            </div>

        </div>


    </div>
    @include('separations.modals.upload_template')
    @include('separations.modals.addquestion')
    @include('separations.modals.editquestion')
@endsection
@section('scripts')

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            @foreach($option_questions as $question)
            $('#grid_table_{{$question->id}}').jsGrid({

                width: "100%",
                height: "300px",

                filtering: false,
                inserting:true,
                editing: true,
                sorting: true,
                paging: true,
                autoload: true,
                pageSize: 10,
                pageButtonCount: 5,
                deleteConfirm: "Do you really want to delete data?",

                controller: {
                    loadData: function(filter){
                        return $.ajax({
                            type: "GET",
                            url: "{{url("separation/get_separation_question_options")}}",
                            data: {

                                "separation_question_id": "{{$question->id}}"
                            }
                        });
                    },
                    insertItem: function(item){
                        return $.ajax({
                            type: "POST",
                            url: "{{url("separation")}}",
                            data:item
                        });
                    },
                    updateItem: function(item){
                        return $.ajax({
                            type: "POST",
                            url: "{{url("separation")}}",
                            data: item
                        });
                    },
                    deleteItem: function(item){
                        return $.ajax({
                            type: "GET",
                            url: "{{url("separation/delete_separation_question_option")}}",
                            data: item
                        });
                    },
                }, onItemInserting: function(args) {


                    args.item._token="{{csrf_token()}}";
                    args.item.separation_question_id="{{$question->id}}";
                    args.item.type="save_separation_question_option";


                },onItemUpdating: function(args) {
                    // cancel insertion of the item with empty 'name' field

                    args.item._token="{{csrf_token()}}";
                    args.item.type="save_separation_question_option";

                },



                fields: [


                    {
                        name: "value",
                        type: "text",
                        title: "Option",
                        validate: "required"
                    },



                    {
                        type: "control"
                    }
                ]

            });
            @endforeach
        } );

        $(function() {
            $(document).on('submit','#uploadQuestionForm',function(event){
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData){
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url         : '{{url('separation')}}',
                    data        : formdata ? formdata : form.serialize(),
                    cache       : false,
                    contentType : false,
                    processData : false,
                    type        : 'POST',
                    success     : function(data, textStatus, jqXHR){


                        toastr.success("Data Import Successful",'Success');
                        $('#uploadQuestionModal').modal('toggle');
                        location.reload();
                    },
                    error:function(data, textStatus, jqXHR){
                        jQuery.each( data['responseJSON'], function( i, val ) {
                            jQuery.each( val, function( i, valchild ) {
                                toastr["error"](valchild[0]);
                            });
                        });
                    }
                });

            });


            $(document).on('submit','#addQuestionForm',function(event){
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData){
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url         : '{{url('separation')}}',
                    data        : formdata ? formdata : form.serialize(),
                    cache       : false,
                    contentType : false,
                    processData : false,
                    type        : 'POST',
                    success     : function(data, textStatus, jqXHR){

                        toastr["success"]("Changes saved successfully",'Success');
                        $('#addQuestionModal').modal('toggle');
                        location.reload();
                    },
                    error:function(data, textStatus, jqXHR){
                        jQuery.each( data['responseJSON'], function( i, val ) {
                            jQuery.each( val, function( i, valchild ) {
                                toastr["error"](valchild[0]);
                            });
                        });
                    }
                });

            });
            $(document).on('submit','#editQuestionForm',function(event){
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData){
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url         : '{{url('separation')}}',
                    data        : formdata ? formdata : form.serialize(),
                    cache       : false,
                    contentType : false,
                    processData : false,
                    type        : 'POST',
                    success     : function(data, textStatus, jqXHR){

                        toastr["success"]("Changes saved successfully",'Success');
                        $('#editQuestionModal').modal('toggle');
                        location.reload();
                    },
                    error:function(data, textStatus, jqXHR){
                        jQuery.each( data['responseJSON'], function( i, val ) {
                            jQuery.each( val, function( i, valchild ) {
                                toastr["error"](valchild[0]);
                            });
                        });
                    }
                });

            });
        });

        function deletequestion(question_id){

            alertify.confirm('Are you sure you want to delete this question ?', function(){
                $.get('{{ url('separation/delete_separation_question') }}',{
                        separation_question_id:question_id
                    },
                    function(data, status){
                        if(data=="success"){
                            toastr.success('Question Deleted Successfully');
                            location.reload();
                        }else{
                            toastr.error(data);
                        }
                    });
            });

        }
        function edit_question(separation_question_id){
            $.get('{{ url('separation/get_separation_question') }}',{separation_question_id:separation_question_id},function(data){

                $('#editqquestion').val(data.question);
                $('#editqtype').val(data.type);
                $('#editqstatus').val(data.status);
                $('#editqcompulsory').val(data.compulsory);
                $('#editqquestion_category').val(data.separation_question_category_id);


                $('#editqid').val(data.id);

            });
            $('#editQuestionModal').modal('show');
        }
    </script>
@endsection
