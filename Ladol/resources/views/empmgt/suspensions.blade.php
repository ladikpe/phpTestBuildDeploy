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
      <h1 class="page-title">Suspensions</h1>
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
            @endif
            <div class="panel panel-info panel-line">
              <div class="panel-heading main-color-bg">
                <h3 class="panel-title">Employee Suspension Log</h3>

               
              </div>

              <div class="panel-body">
            <table class="table table-stripped" id="">
              <thead>
                <tr>
                  <th>Name</th>
                   <th>Suspension Started</th>
                   <th>Suspension Ended</th>
                   <th>Length of Suspenion</th>
                   <th>Approved By</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                  @foreach ($suspensions as $suspension)
                    <tr>
                      <td>{{ $suspension->user? $suspension->user->name :'' }}</td>
                      <td>{{ date("F j, Y", strtotime($suspension->start_date)) }}</td>
                      <td>{{ date("F j, Y", strtotime($suspension->end_date)) }}</td>
                      <td>{{ $diff = Carbon\Carbon::parse($suspension->start_date)->diffForHumans(Carbon\Carbon::parse($suspension->end_date)) }} </td>
                      <td>{{ $suspension->approver? $suspension->approver->name :"" }}</td>
                      
                      <td>


                 <span  data-toggle="tooltip" title="Edit"><a href="#"  class="btn-sm btn btn-info   " onclick="prepareEditData({{$suspension}})"><i class="fa fa-pencil" aria-hidden="true"></i></a></span>
                <span  data-toggle="tooltip" title="Delete"><a href="{{ url('separation/delete_suspension?suspension_id='.$suspension->id) }}"  class="btn-sm btn btn-danger   "><i class="fa fa-trash" aria-hidden="true"></i></a></span>
            </td>

                    </tr>
                  @endforeach

              </tbody>

            </table>
              {!! $suspensions->appends(Request::capture()->except('page'))->render() !!}
          </div>
        </div>


          </div>
          
          </div>

  </div>


</div>
 @include('empmgt.modals.editsuspension')
 @endsection
@section('scripts')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <script type="text/javascript">
  $(document).ready(function() {
    $('.datepicker').datepicker();

$('.select2').select2();
    var selected = [];
     
    
{{--
    $('#gtable tbody').on('click', 'tr', function () {
        var id = this.id;
        var index = $.inArray(id, selected);

        // if ( index === -1 ) {
        //     selected.push( id );
        // } else {
        //     selected.splice( index, 1 );
        // }

        $(this).toggleClass('selected');
    }); --}}
} );
  function prepareEditData(suspension)
    {
    {{--$.get('{{ url('/separation/suspension') }}/',{ suspension_id: suspension_id },function(data){--}}
      
     // $('#edit_dos').val(suspension.startdate);
     $('#edit_suspension_ends').val(suspension.end_date);
     $('#edit_commment').val(suspension.comment);
        $('#suspension_id').val(suspension.id);
  
     
    // });
    $('#editSuspensionModal').modal();
  }
  $(function() {
      $(document).on('submit','#editSuspensionForm',function(event){
          event.preventDefault();
          var form = $(this);
          var formdata = false;
          if (window.FormData){
              formdata = new FormData(form[0]);
          }
          $.ajax({
              url         : '{{route('separation.store')}}',
              data        : formdata ? formdata : form.serialize(),
              cache       : false,
              contentType : false,
              processData : false,
              type        : 'POST',
              success     : function(data, textStatus, jqXHR){

                  if(data=='success'){
                      toastr.success("Suspension Modified successfully",'Success');
                      $('#addSuspensionModal').modal('toggle');
                      location.reload();
                  }
                  if(data=='failed'){
                      toastr.error("Unsuccessful",' Suspension days used');
                  }


              },
              error:function(data, textStatus, jqXHR){
                  jQuery.each( data['responseJSON'], function( i, val ) {
                      jQuery.each( val, function( i, valchild ) {
                          toastr.error(valchild[0]);
                      });
                  });
              }
          });

      });

  });
  </script>
@endsection