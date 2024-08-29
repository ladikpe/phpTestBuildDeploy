@php
    $bsc_self=$evaluation->scorecard_self_score>0?$evaluation->scorecard_self_score*($evaluation->measurement_period->scorecard_percentage/100) : 0;
    $beh_self=$evaluation->behavioral_self_score>0?$evaluation->behavioral_self_score*($evaluation->measurement_period->behavioral_percentage/100) : 0;
@endphp
<table>

    <tr>
        <th>Employee Number:</th>
        <td>{{$evaluation->user->emp_num}}</td>
        <td></td>
        <th>Scorecard Performance Rating:</th>
        <td>{{$evaluation->scorecard_percentage}}</td>

    </tr>
    <tr>
        <th>Name:</th>
        <td>{{$evaluation->user->name}}</td>
        <td></td>
        <th>Behavioral Performance Rating:</th>
        <td>{{$evaluation->behavioral_percentage}}</td>
    </tr>
    <tr>
        <th>Job Role:</th>
        <td>{{$evaluation->user->job->title}}</td>
        <td></td>
        <th>Total Score:</th>
        <td>{{$evaluation->scorecard_percentage+$evaluation->behavioral_percentage}}</td>
    </tr>
    <tr>
        <th>Department:</th>
        <td>{{$evaluation->department->name}}</td>
        <td></td>
        <th>Self Scorecard Performance Rating:</th>
        <td>{{$bsc_self}}</td>
    </tr>
    <tr>
        <th>Measurement Period:</th>
        <td>{{date('F-Y',strtotime($evaluation->measurement_period->from))}} to {{date('F-Y',strtotime($evaluation->measurement_period->to))}}</td>
        <td></td>
        <th>Self Behavioral Performance Rating:</th>
        <td>{{$beh_self}}</td>
    </tr>
    <tr><th colspan="5" style="text-align: center"></th></tr>
    <tr><th colspan="5" style="text-align: center">Balance Scorecard Evaluation</th></tr>
    <tr>
        <th>S/N</th>
        <th>Focus</th>
        <th>Objective</th>
        <th>Key Deliverables</th>
        <th>Measure of Success</th>
        <th>Means of verification</th>
        <th>Weight (%)</th>
        <th>Self Assessment</th>
        <th>Manager Assessment</th>
        <th>Manager<br> Comment</th>
        <th>Manager's Manager Assessment</th>

    </tr>
    @foreach($metrics as $metric)
        <tr><th colspan="11" style="text-align: center">{{$metric->name}}</th></tr>
    @foreach($evaluation->evaluation_details->where('metric_id',$metric->id) as $detail)

        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$detail->focus}}</td>
            <td>{{$detail->objective}}</td>
            <td>{{$detail->key_deliverable}}</td>
            <td>{{$detail->measure_of_success}}</td>
            <td>{{$detail->means_of_verification}}</td>
            <td>{{$detail->weight}}</td>
            <td>{{$detail->self_assessment}}</td>
            <td>{{$detail->manager_assessment}}</td>
            <td>{{$detail->justification_of_rating}}</td>
            <td>{{$detail->manager_of_manager_assessment}}</td>

        </tr>
        @endforeach
    @endforeach
    <tr><th colspan="5" style="text-align: center">Behavioral Evaluation</th></tr>
    <tr>
        <th>S/N</th>
        <th>Business Goal</th>
        <th>Weight (%)</th>
        <th>Measure/ KPI</th>

        <th>Self Assessment</th>
        <th>Manager Assessment</th>
        <th>Manager's Manager Assessment</th>

    </tr>
    @foreach($evaluation->behavioral_evaluation_details as $detail)

        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$detail->objective}}</td>
            <td>{{$detail->weighting}}</td>
            <td>{{$detail->measure}}</td>

            <td>{{$detail->self_assessment}}</td>
            <td>{{$detail->manager_assessment}}</td>
            <td>{{$detail->manager_of_manager_assessment}}</td>

        </tr>
    @endforeach
    <tr><th colspan="5" style="text-align: center"></th></tr>
    <tr>
        <th>Appraisee's Strength</th>
        <td>{{$evaluation->employee_strength}}</td>
        <td></td>
        <th>Manager's Manager Comment</th>
        <td>{{$evaluation->manager_of_manager_approval_comment}}</td>

    </tr>
    <tr>
        <th>Appraisee's Developmental Areas</th>
        <td>{{$evaluation->employee_developmental_area}}</td>
        <td></td>
        <th>Head of Strategy Comment</th>
        <td>{{$evaluation->head_of_strategy_approval_comment}}</td>

    </tr>
    <tr>
        <th>Special Achievement</th>
        <td>{{$evaluation->special_achievement}}</td>
        <td></td>
        <th>Head of HR Comment</th>
        <td>{{$evaluation->head_of_hr_approval_comment}}</td>

    </tr>
    <tr>
        <th>Approval Comment</th>
        <td>{{$evaluation->manager_approval_comment}}</td>
        <td></td>
        <th>Manager's Manager Comment</th>
        <td>{{$evaluation->manager_of_manager_approval_comment}}</td>

    </tr>
    <tr>
        <th>Appraisee's Strength</th>
        <td>{{$evaluation->employee_strength}}</td>


    </tr>

</table>