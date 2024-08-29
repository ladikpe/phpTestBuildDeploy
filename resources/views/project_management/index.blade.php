@extends('layouts.master')
@section('stylesheets')

<link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
<style type="text/css">
  a.list-group-item:hover {
    text-decoration: none;
    background-color: #3f51b5;
}
</style>
@endsection
@section('content')
<div class="page ">
    <div class="page-header">
      <h1 class="page-title">Project Management</h1>
      
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
      <div class="row" data-plugin="matchHeight" data-by-row="true">
  <!-- First Row -->
  <div class="col-xl-4 col-md-6 col-xs-12 info-panel">
    <div class="card card-shadow">
      <div class="card-block bg-white p-20">
        <button type="button" class="btn btn-floating btn-sm btn-info" data-toggle="modal" data-target="#holidays">
          <i class="fa fa-lg fa-plane"></i>
        </button>
        <span class="m-l-15 font-weight-400 font-size-40">Total Projects</span>
        <div class="content-text text-xs-center m-b-0">
          <i class="text-success icon wb-triangle-up font-size-20"></i>
          <span class="font-size-40 font-weight-100">{{count($projects)}} </span>
          <p class="blue-grey-400 font-weight-100 m-0">Total</p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-4 col-md-6 col-xs-12 info-panel">
    <div class="card card-shadow">
      <div class="card-block bg-white p-20">
        <button type="button" class="btn btn-floating btn-sm btn-warning">
          <i class="fa fa-calendar-o"></i>
        </button>
        <span class="m-l-15 font-weight-400 font-size-40">Open Projects</span>
        <div class="content-text text-xs-center m-b-0">
          <i class="text-success icon wb-triangle-down font-size-20">
          </i>
          <span class="font-size-40 font-weight-100">{{count($projects_pending)}}</span>
          <p class="blue-grey-400 font-weight-100 m-0">Pending</p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-4 col-md-6 col-xs-12 info-panel">
    <div class="card card-shadow">
      <div class="card-block bg-white p-20">
        <button type="button" class="btn btn-floating btn-sm btn-success" data-toggle="modal" data-target="#requests">
          <i class="fa fa-question"></i>
        </button>
        <span class="m-l-15 font-weight-400 font-size-40">Closed Projects</span>
        <div class="content-text text-xs-center m-b-0">
          <i class="text-danger icon wb-triangle-up font-size-20">
          </i>
          <span class="font-size-40 font-weight-100">{{count($projects_completed)}}</span>
          <p class="blue-grey-400 font-weight-100 m-0"> Completed</p>
        </div>
      </div>
    </div>
  </div>
</div>
      <div class="panel panel-info panel-line">
                <div class="panel-heading">
                  <h3 class="panel-title">Project List</h3>
                  <div class="panel-actions">
                    <div class="panel-actions">
                      @if(Auth::user()->role->permissions->contains('constant', 'create_project'))
                      <button class="btn btn-info" data-toggle="modal" data-target="#addProjectModal">Add Project</button>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="panel-body">
      <table class="table table-striped" id="emptable" >
        <thead>
          <tr>
            <th style="width:30%;">Project Name</th>
            <th style="width:10%;">Project Code</th>
            <th style="width:30%;">Project Client</th>
            <th style="width:30%;">Project Manager</th>
            <th style="width:10%;">Project Status</th>
            <th style="width:20%;">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($projects as $project)
          <tr>
            <td>{{ $project->name}}</td>
            <td>{{$project->code}}</td>
             <td>{{$project->client->name}}</td>
             <td>{{$project->project_manager->name}}</td>
            <td><span class=" tag tag-outline  {{$project->status==1?'tag-success':'tag-warning'}}">{{$project->status==1?'completed':'pending'}}</span></td>
            <td>
              <div class="btn-group" role="group">
                  <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                  data-toggle="dropdown" aria-expanded="false">
                    Action
                  </button>
                <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                  <a style="cursor:pointer;"class="dropdown-item" id="{{$project->id}}" href="{{ url('projects/view_project?project_id='.$project->id) }}"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View Project Details</a>
                  
                </div>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  </div>
  </div>
  <!-- Site Action -->

    
  
  <!-- End Add User Form -->

@section('scripts')
  <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#emptable').DataTable();
} );

  
</script>
@endsection