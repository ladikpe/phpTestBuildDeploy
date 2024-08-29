<div class="page-content container-fluid ">
            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">All Query</h3>
                    <div class="panel-actions">
                        <td>
                            <a type="button" target="_blank" href="javascript::void(0)" data-toggle="modal" data-target="#queryEmployee" class="btn btn-success"><i class="icon fa fa-warning"></i>Query Employee</a>
                        </td>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    <table class="table table-striped" style="width: 97.5%">
                        <thead>
                        <tr>
                            <th>Query Type</th>
                            <th>Employee Name</th>
                            <th>Status</th>
                            <th>Query Excerpt</th>
                            <th>Action Taken</th>
                            <th>Date Issued</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($all_depenadant_query as $query)
                            <tr>
                                <td id="query_title{{$query->id}}">{{$query->querytype->title}}</td>
                                <td>{{$query->querieduser->name}}</td>
                                <td><label class="tag tag-info" id="status{{$query->id}}">{{$query->status}}<span
                                            style="visibility: hidden">..</span> </label></td>
                                <td>
                                    <input type="hidden" value="{{$query->content}}" id="query_parent{{$query->id}}">
                                    <input type="hidden" value="{{$query->query_type_id}}"
                                           id="query_type_id{{$query->id}}">
                                    <input type="hidden" value="{{$query->createdby->user_image}}"
                                           id="query_user_image{{$query->id}}">
                                    <input type="hidden" value="{{$query->created_by}}" id="created_by{{$query->id}}">
                                    <input type="hidden" value="{{$query->queried_user_id}}"
                                           id="queried_user_id{{$query->id}}">
                                    <input type="hidden" value="{{$query->status}}" id="thread_status{{$query->id}}">
                                    @if(!request()->filled('excel'))
                                    {{substr($query->content,0,200)}}...
                                        @else
                                        {{$query->content}}
                                    @endif
                                </td>
                                <td>
                                    <b>{{strtoupper($query->action_taken)}}</b>
                                </td>
                                <td>
                                    {{$query->created_at->diffForHumans()}}
                                </td>

                               
                            </tr>

                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>