<div class="tab-pane animation-slide-left" id="hmo" role="tabpanel">
    <div class="panel-body">

        <div class="table-responsive col-md-12">
            @if(isset($hmo))
                <table class="table table-striped"  >
                    <thead class="bg-blue-grey-100">
                    <tr>
                        
                        <th>
                           HMO
                        </th>
                        
                        <th>
                            Precondition
                        </th>
                        <th>
                             Genotype
                        </th>
                        <th>
                             Bloud Group
                        </th>
                        <th>
                             Primary Hospital
                        </th>
                        <th>
                             Secondary Hospital
                        </th>
                        <th>
                             Dependant Count
                        </th>

                       
                    </tr>
                    </thead>
                    <tbody>
                 
                        <tr>
                        
                            <td>
                            {{$hmo->FindHMO ? $hmo->FindHMO->hmo : ''}}
                            </td>
                            
                            <td>
                            {{$hmo->precondition}}
                            </td>
                            <td>
                            {{$hmo->genotype}}
                            </td>
                            <td>
                            {{$hmo->bloodgroup}}
                            </td>
                            <td>
                            {{$hmo->FindHospital1 ? $hmo->FindHospital1->hospital : ''}}
                            </td>
                            <td>
                            {{$hmo->FindHospital2 ? $hmo->FindHospital2->hospital : ''}}
                            </td>
                            
                            <td>
                            {{$hmo->CountDependants->count()}}
                            </td>
                        
                            
                            
                        </tr>

                   
                    </tbody>
                </table>

            @else
                <div style="margin-top:10px;" class="alert alert-danger"><h4>HMO is unavailable</h4></div>
            @endif

        </div>
    </div>
</div>