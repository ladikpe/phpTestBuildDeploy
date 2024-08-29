	<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item ">{{__('Payroll Settings')}}</li>
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
        		<div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Loan Policy Settings</h3>
		              <div class="panel-actions">
                			

              			</div>
		            	</div>
		            	<form id="editLoanPolicyForm" enctype="multipart/form-data">
		            <div class="panel-body">
		            <div class="col-md-6">
		            	@csrf
		            		
	          				<div class="form-group" >
	          					<h4>Annual Interest (%)</h4>
	          					<input type="text" name="annual_interest_percentage" class="form-control" value="{{$lp->annual_interest_percentage}}">
	          				</div>
						<div class="form-group" >
							<h4>Repayment length (months)</h4>
							<input type="text" name="repayment_length" class="form-control" value="{{$lp->repayment_length}}">
						</div>
						<div class="form-group" >
							<h4>Can the Loan be accessed by only confirmed staff?</h4>
							<input type="checkbox" name="uses_confirmation" class="active-toggle lp-item"
								   {{$lp->uses_confirmation==1?'checked':''}} value="1">
						</div>
						<div class="form-group" >
							<h4>Can Employees access new loans while servicing another?</h4>
							<input type="checkbox" name="concurrent_loans" class="active-toggle lp-item"
								   {{$lp->concurrent_loans==1?'checked':''}} value="1">
						</div>
						<div class="form-group" >
							<h4>Do you use performance percentage?</h4>
							<input type="checkbox" name="uses_performance" class="active-toggle lp-item"
								   {{$lp->uses_performance==1?'checked':''}} value="1">
						</div>
						<div class="form-group" >
							<h4>Minimum Performance Mark</h4>
							<input type="text" name="minimum_performance_mark" class="form-control" value="{{$lp->minimum_performance_mark}}">
						</div>
						<div class="form-group" >
							<h4>Minimum Length of Stay to be eligible (months)</h4>
							<input type="text" name="minimum_length_of_stay" class="form-control" value="{{$lp->minimum_length_of_stay}}">
						</div>
						<div class="form-group" >
							<h4>Maximum monthly DSR</h4>
							<input type="text" name="dsr_percentage" class="form-control" value="{{$lp->dsr_percentage}}">
						</div>


	          				<div class="form-group" >
	          					<h4>Approval Workflow</h4>
	          					<select class="form-control" name="workflow_id">
	          						@forelse($workflows as $workflow)
	          						<option value="{{$workflow->id}}" {{$lp->workflow_id==$workflow->id?'selected':''}}>{{$workflow->name}}</option>
	          						@empty
	          						<option value="0">Please Create a Workflow</option>
	          						@endforelse
	          						
	          					</select>
	          				</div>
						<div class="form-group" >
							<h4>Specific Salary Component Type</h4>
							<select class="form-control" name="specific_salary_component_type_id">
								@forelse($specific_salary_component_types as $ssc_type)
									<option value="{{$ssc_type->id}}" {{$lp->specific_salary_component_type_id==$ssc_type->id?'selected':''}}>{{$ssc_type->name}}</option>
								@empty
									<option value="0">Please Create a Specific Salary Component Type</option>
								@endforelse

							</select>
						</div>
	          				<input type="hidden" name=" type" value="loan_policy">
	          					            	
		            </div>
						<div class="col-md-6">
						<ul id="compcont">
							@if(!is_null($policy_components))
							@forelse($policy_components as $component)
								<li>
									<input type="hidden" name="component_id[]" value="{{$component->id}}">
									<div class="form-cont" >
										<div class="form-group">
											<label for="">Percentage</label>
											<input type="text" class="form-control" value="{{$component->percentage}}" name="comp_percentage[]" id="" placeholder="" required>
										</div>
										<div class="form-group type">
											<label for="">Source</label>
											<select class="form-control select-source " name="comp_source[]" >
												<option value="payroll_constant" {{$component->source=="payroll_constant"?'selected':''}}>Payroll Component</option>
												<option value="salary_component" {{$component->source=="salary_component"?'selected':''}}>Salary Component </option>
												<option value="amount" {{$component->source=="amount"?'selected':''}}>Amount</option>
											</select>
										</div>
										<div class="form-group payroll_constant-div" @if($component->source!=='payroll_constant')style="display: none;" @endif >
											<label for="">Payroll Component</label>
											<select class="form-control payroll_constants" name="comp_payroll_constant[]" >
												<option value="basic_salary" {{$component->payroll_constant=="basic_salary"?'selected':''}}>Basic Salary</option>
											</select>
										</div>
										<div class="form-group salary_component_constant-div" @if($component->source!=='salary_component')style="display: none;" @endif>
											<label for="">Salary Component</label>
											<select class="form-control salary_component_constants" name="comp_salary_component[]" >
												@foreach ($project_salary_components as $key=>$comp)
													<option value="{{$key}}" {{$component->salary_component_constant==$key?'selected':''}}>{{$comp}}</option>
												@endforeach
											</select>
										</div>
										<div class="form-group amount-div" @if($component->source!='amount')style="display: none;" @endif>
											<label for="">Amount</label>
											<input type="number" step="0.01" value="{{$component->amount}}" name="comp_amount[]" class="form-control amount_components">
										</div>
										<div class="form-group">
											<button type="button" class="btn btn-primary " id="remComp">Remove Component</button>
										</div>
									</div>
								</li>
							@empty
							@endforelse
								@endif

						</ul>
						<button type="button" id="addComp" name="button" class="btn btn-primary">New Component</button>
						</div>
	          		</div>
	          		<div class="panel-footer">
	          			<div class="form-group">
	          					<button class="btn btn-info" >Save Changes</button>
	          				</div>
	          		</div>
	          		</form>
		          </div>

	        	</div>
	    	</div>
	    	<div class="col-md-12 col-xs-12">
	    		
	    	</div>
		</div>
	  </div>
	  
	    <script type="text/javascript">
  	
  	

  	$(function() {
  	$(document).on('submit','#editLoanPolicyForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('payrollsettings.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		           
					$( "#ldr" ).load('{{url('payrollsettings/loan_policy')}}');
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
		$('.lp-item').bootstrapToggle({
			on: 'Yes',
			off: 'No',
			onstyle:'info',
			offstyle:'default'
		});

		$('#salary_component_constant').attr("disabled",true);
		$('#salary_component_constant_div').hide();
		$('#amount').attr("disabled",true);
		$('#amount_div').hide();
		var compcont = $('#compcont');
		var i = $('#compcont li').length + 1;

		$('#addComp').on('click', function() {
			//console.log('working');
			$(' <li><div class="form-cont" > <div class="form-group"> <label for="">Percentage</label> <input type="text" class="form-control" name="comp_percentage[]" id="" placeholder="" required> </div><div class="form-group type"> <label for="">Source</label> <select class="form-control select-source " name="comp_source[]" >  <option value="payroll_constant">Payroll Component</option> <option value="salary_component">Salary Component </option> <option value="amount">Amount</option></select> </div> <div class="form-group payroll_constant-div"> <label for="">Payroll Component</label> <select class="form-control payroll_constants" name="comp_payroll_constant[]" ><option value="basic_salary">Basic Salary</option>  </select> </div> <div class="form-group salary_component_constant-div"> <label for="">Salary Component</label> <select class="form-control salary_component_constants" name="comp_salary_component[]" >   @foreach ($project_salary_components as $key=>$component) <option value="{{$key}}">{{$component}}</option> @endforeach </select> </div><div class="form-group amount-div">  <label for="">Amount</label><input type="number" step="0.01" name="comp_amount[]" class="form-control amount_components"> </div> <div class="form-group"> <button type="button" class="btn btn-primary " id="remComp">Remove Component</button> </div> </div> </li>').appendTo(compcont);
			//console.log('working'+i);
			$('#compcont li').last().find('.salary_component_constant-div').hide();
			$('#compcont li').last().find('.salary_component_constant-div').find('.salary_component_constants').attr("disabled",true);
			$('#compcont li').last().find('.amount-div').hide();
			$('#compcont li').last().find('.amount-div').find('.amount_components').attr("disabled",true);
			i++;
			return false;
		});

		$(document).on('click',"#remComp",function() {
			//console.log('working'+i);
			if( i > 1 ) {
				console.log('working'+i);
				$(this).parents('li').remove();
				i--;
			}
			return false;
		});
		$(document).on('change',"#source",function() {


			if (this.value=='payroll_constant')
			{
				$('#salary_component_constant').attr("disabled",true);
				$('#salary_component_constant_div').hide();
				$('#amount').attr("disabled",true);
				$('#amount_div').hide();
				$('#payroll_constant').removeAttr("disabled");
				$('#payroll_constant_div').show();


			}

			if (this.value=='salary_component_constant')
			{
				$('#amount').attr("disabled",true);
				$('#amount_div').hide();
				$('#payroll_constant').attr("disabled",true);
				$('#payroll_constant_div').hide();
				$('#salary_component_constant').removeAttr("disabled");
				$('#salary_component_constant_div').show();


			}
			if (this.value=='amount')
			{
				$('#payroll_constant').attr("disabled",true);
				$('#payroll_constant_div').hide();
				$('#salary_component_constant').attr("disabled",true);

				$('#amount').removeAttr("disabled");
				$('#amount_div').show();
				$('#salary_component_constant_div').hide();


			}


		});
		$(document).on('change',".select-source",function() {


			if (this.value=="payroll_constant")
			{
				$(this).parents('li').find('.payroll_constant-div').find('.payroll_constants').attr("disabled",true);
				$(this).parents('li').find('.payroll_constant-div').hide();
				$(this).parents('li').find('.salary_component_constant-div').find('.salary_component_constants').attr("disabled",true);
				$(this).parents('li').find('.salary_component_constant-div').hide();
				$(this).parents('li').find('.amount-div').find('.amount_components').removeAttr("disabled");
				$(this).parents('li').find('.amount-div').show();


			}

			if (this.value=="salary_component")
			{
				$(this).parents('li').find('.payroll_constant-div').find('.payroll_constants').attr("disabled",true);
				$(this).parents('li').find('.payroll_constant-div').hide();
				$(this).parents('li').find('.amount-div').find('.amount_components').attr("disabled",true);
				$(this).parents('li').find('.amount-div').hide();
				$(this).parents('li').find('.salary_component_constant-div').find('.salary_component_constants').removeAttr("disabled");
				$(this).parents('li').find('.salary_component_constant-div').show();


			}
			if (this.value=="amount")
			{
				$(this).parents('li').find('.payroll_constant-div').find('.payroll_constants').attr("disabled",true);
				$(this).parents('li').find('.payroll_constant-div').hide();
				$(this).parents('li').find('.salary_component_constant-div').find('.salary_component_constants').attr("disabled",true);

				$(this).parents('li').find('.amount-div').find('.amount_components').removeAttr("disabled");
				$(this).parents('li').find('.amount-div').show();
				$(this).parents('li').find('.salary_component_constant-div').hide();


			}


		});
  });


 
  	

  </script>

