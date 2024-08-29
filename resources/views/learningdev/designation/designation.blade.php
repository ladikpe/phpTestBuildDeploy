<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item ">{{__('Employee Settings')}}</li>
		    <li class="breadcrumb-item ">{{__('Designation')}}</li>
		    <li class="breadcrumb-item active">{{__('You are Here')}}</li>
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
      	<div class="row">
        	<div class="col-md-12 col-xs-12">
        		@if($designation)
        		<div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Edit Designation- {{$designation->name}}</h3>
		              <div class="panel-actions">
              			</div>
	            	</div>
		            <div class="panel-body">
		            	<form id="editRoleForm" method="Post">
		            	<div class="form-group">
		            		<h4 class="example-title">Designation Name</h4>
                  		    <input type="text" name="name" class="form-control" value="{{$designation->name}}">
		            	</div>
		            	<div class="form-group">
                             <h4 class="example-title">Designation Description</h4>
                            <input type="text" name="description" class="form-control" value="{{$designation->description}}">
		            	</div>
                       
	                	@csrf
		            		<button type="submit" class="btn btn-info pull-right btn-lg">Save</button>
						</form>
                        </div>
	          		</div>
		          </div>
		          @else
		          <div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Add Designation</h3>
		              <div class="panel-actions">
              			</div>
	            	</div>
		            <div class="panel-body">
            		<form id="addDesignationForm" method="post">
		                 <div class="form-group">
		            		<h4 class="example-title">Designation Name</h4>
                  		    <input type="text" name="name" class="form-control" value="">
		            	</div>

                        <div class="form-group">
		            		<h4 class="example-title">Designation Description</h4>
                  		    <input type="text" name="description" class="form-control" value="">
		            	</div>
                        <div class="form-group">
                             <h4 class="example-title">Department</h4>
                            <select class="form-control"  name = "department">
                                <option value = "">- SELECT -</option>  
                                @foreach($departments as $department)
                                    <option value = "{{$department->id}}">{{$department->name}}</option>
                                @endforeach
                            </select>
		            	</div>
	                	@csrf
	                	<button type="submit" class="btn btn-info pull-right btn-lg">Save</button>
						</form>
	          		</div>
		          </div>
		          @endif
	        	</div>
	    	</div>

		</div>
	  </div>
	  <script type="text/javascript">
  $(function() {
    $('.switch').bootstrapToggle({
      on: 'Yes',
      off: 'No',
      onstyle:'info',
      offstyle:'default'
    });

});

  $(function() {
  	$('#addDesignationForm').submit(function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('designations.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

                toastr.success("Designation Created successfully",'Success');

                $( "#ldr" ).load('{{route('employeesettings')}}');

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
  	$(function() {
  	$('#editRoleForm').submit(function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('roles.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Role Changes saved successfully",'Success');


					$( "#ldr" ).load('{{route('employeesettings')}}');
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
  	$('.manages').change(function(event){
  		state=$(this).val();
  		if (state=='none') {
  			$('div.nav-tabs-vertical').addClass('hide');
  			$('div.nav-tabs-vertical').removeClass('block');
  		}else{
  			$('div.nav-tabs-vertical').removeClass('hide');
  			$('div.nav-tabs-vertical').addClass('block');

  		}

  	});
  	});
  	function deleteRole(role_id){
  		$.ajax({
    type: 'POST',
    dataType: 'json',
    data: {
        id: id,
        _method: 'DELETE'
    },
    url: "{{ url('/roles/delete') }}/'+role_id",
    success: function (data) {
        if (data=='success') {
 		toastr.success("Role deleted successfully",'Success');
 		$( "#ldr" ).load('{{route('employeesettings')}}');
    	}else{
    		toastr.error("Error deleting grade",'Error');
    	}
    }
	});
   //  $.post('{{ url('/roles/delete') }}/'+role_id,{_method:'delete'},function(data){
   //  	if (data=='success') {
 		// toastr.success("Role deleted successfully",'Success');
 		// $( "#ldr" ).load('{{route('employeesettings')}}');
   //  	}else{
   //  		toastr["error"]("Error deleting grade",'Success');
   //  	}

   //  });
  }
</script>
