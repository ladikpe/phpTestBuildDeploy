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
    .my-btn.btn-sm {
    font-size: 0.75rem;
    width: 1.5rem;
    height: 1.5rem;
    padding: 0;
}
  </style>

@endsection
@section('content')
<div class="page ">
    <div class="page-header">
      <h1 class="page-title">360 Review Report</h1>
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
                <h3 class="panel-title">Employee review - {{$employee->name}} for {{date('F-Y',strtotime($mp->from))}} to {{date('F-Y',strtotime($mp->to))}}</h3>
                 <div class="panel-actions">


                    </div>
              </div>

              <div class="panel-body">
                <br>

                <table class="table table-striped">
                  <tr>
                    <th width="70">Question</th>
                     <th width="10">Total Reviewers</th>
                     <th width="10">Total Score</th>
                     <th width="10">Average Score</th>
                  </tr>
                  @php
                     $total_reviews=$evaluations->count();

                  @endphp
                @foreach($det->questions as $question)
                @php

                  $question_score=0;


                  foreach ($evaluations as $evaluation) {

                    foreach ($evaluation->details as $detail) {
                       if ($detail->question->id==$question->id) {
                        $question_score+=$detail->option->score;
                        }
                    }
                  }



                @endphp

                  <tr>
                    <th >{{$question->question}}</th>
                    <th >{{ $total_reviews}}</th>
                    <th >{{$question_score}}</th>
                    <th >{{$question_score/$total_reviews}}</th>
                  </tr>


               @endforeach
             </table>
          </div>
          <div class="panel-footer">

          </div>

        </div>


          </div>
          </div>

  </div>


</div>
@include('e360.modals.upload_template')
@include('e360.modals.addquestion')
@include('e360.modals.editquestion')
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
     @foreach($det->questions as $question)
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
                url: "{{url("e360settings/get_question_options")}}",
                data: {

                        "question_id": "{{$question->id}}"
                    }
               });
              },
              insertItem: function(item){
               return $.ajax({
                type: "POST",
                url: "{{url("e360settings")}}",
                data:item
               });
              },
              updateItem: function(item){
               return $.ajax({
                type: "POST",
                url: "{{url("e360settings")}}",
                data: item
               });
              },
              deleteItem: function(item){
               return $.ajax({
                type: "GET",
                url: "{{url("e360settings/delete_question_option")}}",
                data: item
               });
              },
             }, onItemInserting: function(args) {


                  args.item._token="{{csrf_token()}}";
                  args.item.e360_det_question_id="{{$question->id}}";
                  args.item.type="save_question_option";


          },onItemUpdating: function(args) {
        // cancel insertion of the item with empty 'name' field

                  args.item._token="{{csrf_token()}}";
                  args.item.type="save_question_option";

          },



             fields: [


                  {
                   name: "content",
                type: "text",
                title: "Option",
                validate: "required"
                  },{
                   name: "score",

                type: "number",
                title: "Score",
                width:50
                  },



                  {
                   type: "control"
                  }
                 ]

    });
    @endforeach
} );

    $(function() {
    $(document).on('submit','#uploadDETForm',function(event){
     event.preventDefault();
     var form = $(this);
        var formdata = false;
        if (window.FormData){
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url         : '{{route('e360settings.store')}}',
            data        : formdata ? formdata : form.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){

                toastr.success("Data Import Successful",'Success');
                $('#uploadDETModal').modal('toggle');
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

    $('.questionbtn').on('click', function(event) {
  id=$(this).attr('id');


  $.get('{{ url('e360settings/get_question') }}',{question_id:id},function(data){

     $('#editqquestion').val(data.question);
     $('#editqscore').val(data.score);
      $('#editqid').val(data.id);

    });
    $('#editQuestionModal').modal();

});

    $(document).on('submit','#addQuestionForm',function(event){
     event.preventDefault();
     var form = $(this);
        var formdata = false;
        if (window.FormData){
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url         : '{{url('e360settings')}}',
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
            url         : '{{url('e360settings')}}',
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
  $.get('{{ url('e360settings/delete_question') }}',{
    id:question_id
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
  </script>
@endsection
