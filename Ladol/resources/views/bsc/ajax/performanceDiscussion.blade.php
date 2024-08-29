<div class="panel-group" id="exampleAccordionDefault" aria-multiselectable="true" role="tablist">
    @if(count($discussions)>0)
        @foreach($discussions as $discussion)
            <div class="panel panel-info">
                <div class="panel-heading" id="exampleHeadingDefaultThree{{$discussion->id}}" role="tab">
                    <a class="panel-title collapsed" data-toggle="collapse"
                       href="#exampleCollapseDefaultThree{{$discussion->id}}"
                       data-parent="#exampleAccordionDefault{{$discussion->id}}" aria-expanded="false"
                       aria-controls="exampleCollapseDefaultThree{{$discussion->id}}">
                        {{$discussion->title}} @ {{$discussion->created_at->diffForHumans()}}
                    </a>
                </div>


                <div class="panel-collapse collapse" id="exampleCollapseDefaultThree{{$discussion->id}}"
                     aria-labelledby="exampleHeadingDefaultThree{{$discussion->id}}" role="tabpanel">
                    <div class="panel-body">
                        @if($discussion->line_manager_approved!=1 and $discussion->bscevaluation->user_id==Auth::user()->id and $discussion->employee_submitted!=1)
                            <button class="btn btn-success pull-right" id="" onclick="SubmitPD({{$discussion->id}})">Submit discussion</button>
                            <br>
                        @elseif($discussion->line_manager_approved!=1 and $discussion->bscevaluation->manager_id==Auth::user()->id and  $discussion->employee_submitted==1)
                            <button class="btn btn-success pull-right" id="" onclick="ApprovePD({{$discussion->id}})">Approve discussion</button>
                            <button class="btn btn-danger pull-right" id="pd_rjt_btn_{{$discussion->id}}" onclick="RejectPD({{$discussion->id}})">Reject discussion</button>
                            <br>
                            <div id="reject_discussion_container_{{$discussion->id}}" style="display:none;">
                                <br><textarea style="margin-top:5px;" row="3" id="rejection_reason_{{$discussion->id}}" class="form-control" placeholder="Enter rejection reason"></textarea>
                                <br><button class="btn btn-success btn-sm" onclick="saveRejection({{$discussion->id}})" >Save Rejection Reason</button>
                                <button class="btn btn-danger btn-sm"  onclick="cancelRejection({{$discussion->id}})" >Cancel Rejection Reason</button>
                            </div>
                        @endif
                        {!!  $discussion->discussion !!}
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>S/n</th>
                                <th>KPI/Goals</th>
                                <th>Action/ Status Update</th>
                                <th>Challenge</th>
                                <th>Comments/Discussions</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($discussion->discussion_details as $discussion_detail)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$discussion_detail->evaluation_detail->key_deliverable}}</td>
                                    <td>{{$discussion_detail->action_update}}</td>
                                    <td>{{$discussion_detail->challenges}}</td>
                                    <td>{{$discussion_detail->comment}}</td>
                                    <td>@if($discussion->line_manager_status!=1 and $discussion->bscevaluation->user_id==Auth::user()->id)
                                            <button class="btn btn-primary btn-xs "
                                                    onclick="EditPDD({{$discussion_detail}},{{$discussion_detail->evaluation_detail}})">
                                                Edit discussion
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

        @endforeach
</div>
@endif