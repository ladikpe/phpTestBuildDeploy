
    <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Exit Interview Form for {{$separation->user->name}}</title>


    <style type="text/css">
        body {
            background: white;
            display: block;
            margin: 0 auto;
            margin-bottom: 0.5cm;
            margin-top:10px;
            /*box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);*/
            width: 18cm;
            height: 29.7cm;
            padding: 0px;
            font-family: arial, sans-serif;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        .small-font{
            font-size: 12px;
        }

        td,  th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
        h1,h4 {
            font-family: arial,sans-serif;
        }
        #header td, #header th {
            border: 0px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        header {
            position: fixed;
            top: -40px;
            float:right;
            height: 50px;


        }

        #watermark {
            position: fixed;

            /**
                Set a position in the page for your image
                This should center it vertically
            **/
            top:45%;
            width :100%;
            height :100%;
            opacity:0.1;
            /*-ms-transform: rotate(310deg);  IE 9 */
            /*-webkit-transform: rotate(310deg);  Safari 3-8 */
            /*transform: rotate(310deg);*/
            padding:300px;

            /** Your watermark should be behind every content**/
            z-index:  -1000;
        }

    </style>
</head>
<body>
<div id="watermark">
    {{-- <img src="{{url('uploads/logo').$company->logo}}" /> --}}
</div>
{{-- <header style="float: right;"><img src="{{url('uploads/logo').$company->logo}}" style="height: 2rem;background-color:#fff; width: auto;"></header> --}}
<div class="cont">
    <center>

        <h2 style="text-transform: uppercase;">{{$company->name}}</h2></center>
    <hr style="height:5px;background-color:#f00;">
    <table style="width:100%" id="header">
        <tr>
            <td style="width:33%">
                {{$company->address}}
                <br>

                {{ $company->email}}
                <br>

            </td>
            <td style="width:34%">
                <center style="background: #337ab7;color: #fff;"><h2>Exit Interview Form</h2></center>
            </td>
            <td style="width:33%">
                {{-- <img style="width: 150px;height:auto;" src="{{ asset('storage/'.$logo) }}" class="img-responsive"> --}}
            </td>
        </tr>
    </table>

    <center><h4 class="bg-primary text-center" style="background: #337ab7;color: #fff; padding: 5px 0px; ">Exit Interview Form of <strong>{{$separation->user->name}}</strong> in </h4></center>
    <table class="payslip" style="width:100%;">
        <thead class="head">
        <tr class="bg-primary">
            <th style="width: 33%;"></th>
            <th style="width: 34%; text-align: center;"  class="bg-primary"><span>Employee Details</span></th>
            <th style="width: 33%;"></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            @if($separation->user->grade)

                <td>
                    Grade:{{$separation->user->only_grade}}{{$separation->user->only_level?'-'.$separation->user->only_level:''}}
                </td>
            @endif
            <td>
                DEPT:{{$separation->user->job?$separation->user->job->department->name:''}}
            </td>
        </tr>
        <tr>


            <td>
                Hiredate:{{date('Y-m-d',strtotime($separation->user->hiredate))}}
            </td>

            <td>
                Termination Date:{{date('Y-m-d',strtotime($separation->date_of_separation))}}
            </td>
        </tr>
        </tbody>
    </table>
    <hr>
    <h4>Handover Note</h4>
    <p>{{$separation->separation_form->handover_note}}</p>
    @foreach($separation->separation_form->details as $detail)
        {{--                                            @if($question->status==1)--}}
        <div class="form-group">
            <h4>{{$detail->question->question}}</h4>

            @if($detail->type=='txt')
                <p >{{$detail->answer}}</p>
            @elseif($detail->type=='rad')
                <ul class="list-unstyled example">

                    <li class="m-b-15">
                        &nbsp;{{$detail->option->value}}
                    </li>

                </ul>
            @elseif($detail->type=='chk')
                <ul class="list-unstyled example">
                    @if($detail->options)
                        @foreach($detail->options as $option)
                            <li class="m-b-15">
                               {{$option->value}}
                            </li>
                        @endforeach
                    @endif
                </ul>
            @endif


        </div>
        {{--                                            @endif--}}

    @endforeach
</div>
</body>
</html>
