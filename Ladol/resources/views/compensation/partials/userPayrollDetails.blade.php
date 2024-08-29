@php
	$pdetails=unserialize($detail->details);
	// $num=count($days);
@endphp
<h4>{{$detail->user->name}}</h4>
<div class="table-responsive">
<table class="table table-striped ">
	
	<tbody>
		<tr>
			
			<th>Gross Pay</th>
			<td style="text-align: right">&#8358;{{number_format($detail->gross_pay,2)}}</td>
			
		</tr>
		<tr>
			
			<th>Total Allowances</th>
			<td style="text-align: right">&#8358;{{number_format($detail->allowances,2)}}</td>
			
		</tr>
		<tr>
			
			<th>Total Deductions</th>
			<td style="text-align: right">&#8358;{{number_format($detail->deductions,2)}}</td>
			
		</tr>
	</tbody>
	
</table>

<h4>Allowances</h4>
<table class="table table-striped ">
	
	<tbody>
		@foreach($pdetails['allowances'] as $key=>$allowance)
		<tr>
			
			<th>{{$pdetails['component_names'][$key]}}</th>
			<td style="text-align: right">&#8358;{{number_format($allowance,2)}}</td>
			
		</tr>
		@endforeach
	</tbody>
	
</table>

<h4>Deductions</h4>
<table class="table table-striped ">
	
	<tbody>
		@foreach($pdetails['deductions'] as $key=>$deduction)
		<tr>
			
			<th>{{$pdetails['component_names'][$key]}}</th>
			<td style="text-align: right">-&#8358;{{number_format($deduction,2)}}</td>
			
		</tr>
		@endforeach
		{{--<tr>
			<th>Income Tax</th>
			<td style="text-align: right">&#8358;{{number_format($detail->paye,2)}}</td>
		</tr>--}}
		@if($detail->user->union)
        <tr>
          <th >Union Dues</th>
          <td style="text-align: right">&#8358;{{number_format($detail->union_dues,2)}}</td>
        </tr>
    @endif
	</tbody>
	
</table>
<h4>Deactivated Deductions</h4>
<table class="table table-striped ">
	
	<tbody>
		@foreach($pdetails['deductions_deactivated'] as $key=>$deduction)
		<tr>
			
			<th>{{$pdetails['component_names'][$key]}}</th>
			<td style="text-align: right">&#8358;{{number_format($deduction,2)}}</td>
			
		</tr>
		@endforeach
	</tbody>
	
</table>
<h4>Deactivated Allowances</h4>
<table class="table table-striped ">
	
	<tbody>
		@foreach($pdetails['allowances_deactivated'] as $key=>$allowance)
		<tr>
			
			<th>{{$pdetails['component_names'][$key]}}</th>
			<td style="text-align: right">&#8358;{{number_format($allowance,2)}}</td>
			
		</tr>
		@endforeach
	</tbody>
	
</table>
<hr>
<h4><span class="">Net Salary</span><span class="pull-right">&#8358;{{number_format(($detail->basic_pay+$detail->allowances)-($detail->deductions+$detail->paye+$detail->union_dues),2)}}</span></h4>
</div>