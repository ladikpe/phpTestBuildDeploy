<div>
	<ul class="list-group list-group-dividered ">
		<li class="list-group-item"><strong>Amount:&#8358; {{number_format($loan->amount,2)}}</strong></li>
		<li class="list-group-item">Repayment Period:{{$loan->period}}</li>
		<li class="list-group-item">Interest Rate:{{$loan->current_rate}}%</li>
		<li class="list-group-item">Monthly repayment:&#8358;{{number_format( $loan->monthly_deduction,2)}}</li>
		<li class="list-group-item">Interest Amount:&#8358;{{number_format( $loan->total_interest,2)}}</li>
		<li class="list-group-item">Total Repayment:&#8358;{{number_format( $loan->total_repayments,2)}}</li>
	</ul>
</div>
<div class="table-responsive">
	<h4>Approval Details ({{$loan->workflow->name}})</h4>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Stage</th>
				<th>Approver</th>
				<th>Comment</th>
				<th>Status</th>
				<th>Time in Stage</th>

			</tr>
		</thead>
		<tbody>
			@foreach($loan->approvals as $approval)
			<tr>
				<td>{{$approval->stage->name}}</td>
				<td>{{$approval->approver_id>0?$approval->approver->name:""}}</td>
				<td>{{$approval->comments}}</td>
				<td><span
						class=" tag   {{$approval->status==0?'tag-warning':($approval->status==1?'tag-success':'tag-danger')}}">{{$approval->status==0?'pending':($approval->status==1?'approved':'rejected')}}</span>
				</td>
				<td>{{
					$approval->created_at==$approval->updated_at?\Carbon\Carbon::parse($approval->created_at)->diffForHumans():\Carbon\Carbon::parse($approval->created_at)->diffForHumans($approval->updated_at)
					}}</td>
			</tr>
			@endforeach
		</tbody>

	</table>
</div>