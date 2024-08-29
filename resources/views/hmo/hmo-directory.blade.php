@extends('layouts.master')
@section('stylesheets')

<link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

<link rel="stylesheet" href="{{ asset('global/vendor/alertify/alertify.min.css') }}">

@endsection


@section('content')
<div class="page ">
  <div class="page-header">
    <a href=""> <h1 class="page-title">HMO Service - Admin</h1> </a>
  </div>
  <div class="page-content container-fluid">
    <div class="row">
      <div class="col-md-12">

        @if(isset($HMODirectory))
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-info ">
              <div class="panel-heading main-color-bg" style="display:flex; justify-content:space-between; align-items: center; margin-bottom: 10px; background: #85B054;">
                <h3 class="panel-title"> HMO Enrollee </h3>
                <div style="margin-right: 20px;">
                   <a type="button" class="btn btn-primary btn-outline btn-sm"
                    href="{{url('/hmo/download_employee_hmos')}}">
                    <i class="icon fa fa-file-excel-o" aria-hidden="true"></i>
                    <span class="text-uppercase hidden-sm-down">Generate Report</span>
                  </a>
                </div>
              </div>

              <div class="panel-body">
                <div class="col-md-13">  

             
                  <table id="data_table" class="table">
                    <thead>
                      <tr>
                        <th class="bold" style="width:1%;">S/N</th>
                        <th class="bold" style="width:24%;">Enrollee Name</th>
                        <th class="bold" style="width:3%;">Gender</th>
                        <th class="bold" style="width:10%;">HMO</th>
                        <th class="bold" style="width:10%;">HMO ID</th>
                        <th class="bold" style="width:23%;">Primary Hospital</th>
                        <th class="bold" style="width:23%;">Secondary Hospital</th>
                        <th class="bold" style="width:2%;">Dependant Count</th>
                        <th class="bold not-export-col" style="width:4%;">Actions</th>
                      </tr>
                    </thead>
                    <tbody>


                      @foreach($HMODirectory as $key => $value)
                      <tr>
                        <td> {{ $loop->iteration }}. </td>
                        <td> {{ $value->name  }} </td>
                        <td> {{ $value->sex == 'M' ? 'Male' : 'Female'  }} </td>
                        <td> {{ $value->hmo && $value->hmo->FindHMO ? $value->hmo->FindHMO->hmo : '' }} </td>
                        <td> {{  $value->medical_code }} </td>
                        <td> {{ $value->hmo && $value->hmo->FindHospital1 && $value->hmo->FindHospital1->hospital ?  $value->hmo->FindHospital1->hospital : '' }} </td>
                        <td> {{ $value->hmo && $value->hmo->FindHospital2 && $value->hmo->FindHospital2->hospital ?  $value->hmo->FindHospital2->hospital : '' }} </td>
                        
                        <td align="center"> <b>  {{$value->hmo && $value->hmo  ? $value->hmo->CountDependants()->count() : '' }} </b></td>
                        <td>
                          <div class="btn-group" role="group">
                            <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                            data-toggle="dropdown" aria-expanded="false">
                            Action
                          </button>
                          <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                            @if($value->hmo)
                            <a href="/selfservice-hmo?userId={{ base64_encode($value->id) }}&path" target="_blank" style="cursor:pointer;"class="dropdown-item" ><i class="fa fa-eye" aria-hidden="true" ></i>&nbsp;View</a>
                            @endif
                            
                            @if($value->hmo)
                            <a href="/selfservice-hmo?userId={{ base64_encode($value->id) }}&patch={{rand(10,999)}}&path" target="_blank" style="cursor:pointer;"class="dropdown-item"><i class="fa fa-edit" aria-hidden="true" ></i>&nbsp;Modify</a>
                            @endif
                            @if(!$value->hmo)
                            <a href="/selfservice-hmo?userId={{ base64_encode($value->id) }}&patch={{rand(10,999)}}&path" target="_blank" style="cursor:pointer;"class="dropdown-item"><i class="fa fa-edit" aria-hidden="true" ></i>&nbsp;Create</a>
                            @endif
                            @if($value->hmo)
                            <a style="cursor:pointer;" class="dropdown-item" onclick="deleteUserHMO({{$value->id}} );" ><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete</a>
                            @endif
                          </div>
                        </div>
                      </td>


                    </tr>

                    @endforeach
                    @if(count($HMODirectory) == '0')
                    <tr>
                      <td colspan="9">  <h4 style="padding: 30px;">No enrollee found.</h4> </td>
                    </h4>
                    @endif

                  </tbody>
                </table>

              </div>
            </div>
          </div>
          @endif

        </div>
      </div>

    </div>
  </div>



  @endsection
  @section('scripts')
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>

  <script type="text/javascript" src="{{ asset('global/vendor/alertify/alertify.js') }}"></script>


  <script type="text/javascript">
    let columnsToExport = Array(8).fill(0).map((_,i) => i);
    $("#data_table").DataTable( {
  dom: 'Bfrtip',
  buttons: [
    {
      extend: 'copy',
      exportOptions: {
        columns: columnsToExport
      }
    },
    {
      extend: 'csv',
      exportOptions: {
        columns: columnsToExport
      }
    },
    {
      extend: 'excel',
      exportOptions: {
        columns: columnsToExport
      }
    },
    {
      extend: 'pdf',
      exportOptions: {
        columns: columnsToExport
      }
    },
    {
      extend: 'print',
      exportOptions: {
        columns: columnsToExport
      }
    }
  ]
});



    function deleteUserHMO(userId) {
        alertify.confirm('Are you sure you want to delete this Enrollee?', function () {
            $.get('{{ url('/hmo/deleteUserHMO') }}/' + userId, function (data) {
                if (data == 'success') {
                    toastr["success"]("Enrollee deleted successfully", 'Success');
                    location.reload();
                } else {
                    toastr["error"]("Error deleting Enrollee", 'Success');
                }
            });
        }, function () {
            alertify.error('Enrollee not deleted');
        });
    }

    </script>
    @endsection
