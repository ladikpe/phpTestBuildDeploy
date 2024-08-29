<table>
                    <thead>
                      <tr>
                        <th class="bold" style="width:10%;">S/N</th>
                        <th class="bold" style="width:24%;">Enrollee Name</th>
                        <th class="bold" style="width:24%;">Employee ID</th>
                        <th class="bold" style="width:24%;">DOB</th>
                        <th class="bold" style="width:24%;">Email</th>
                        <th class="bold" style="width:24%;">Phone</th>
                        <th class="bold" style="width:24%;">Marital Status</th>
                        <th class="bold" style="width:24%;">LGA of Residence</th>
                        <th class="bold" style="width:24%;">Status</th>
                        <th class="bold" style="width:24%;">Uses PC</th>
                        <th class="bold" style="width:45%;">Job Role</th>
                        <th class="bold" style="width:45%;">Department</th>
                        <th class="bold" style="width:12%;">Gender</th>
                        <th class="bold" style="width:30%;">HMO</th>
                        <th class="bold" style="width:24%;">HMO ID</th>
                        <th class="bold" style="width:23%;">Primary Hospital</th>
                        <th class="bold" style="width:23%;">Secondary Hospital</th>
                        <th class="bold" style="width:20%;">Dependant Count</th>
                      </tr>
                    </thead>
                    <tbody>


                      @foreach($employeeHmos as $key => $value)
                      <tr>
                        <td> {{ $loop->iteration }}. </td>
                        <td> {{ $value->FindUser->name  }} </td>
                        <td> {{ $value->FindUser->emp_num  }} </td>
                        <td> {{ $value->FindUser->dob  }} </td>
                        <td> {{ $value->FindUser->email  }} </td>
                        <td> {{ $value->FindUser->phone  }} </td>
                        <td> {{ $value->FindUser->marital_status  }} </td>
                        <td> {{ $value->FindUser->lga_of_residence  }} </td>
                        <td> {{ $value->FindUser->my_status  }} </td>
                        <td> {{ $value->FindUser->uses_pc == '1' ? 'Yes' : 'No'  }} </td>
                        <td> {{ $value->FindUser->job ? $value->FindUser->job->title : 'N/A'  }} </td>
                        <td> {{ $value->FindUser->job ? $value->FindUser->job->department->name : 'N/A'  }} </td>
                        <td> {{ $value->FindUser->sex == 'M' ? 'Male' : 'Female'  }} </td>
                        <td> {{ $value->FindHMO ? $value->FindHMO->hmo : '' }} </td>
                        <td> {{ $value->FindUser->medical_code  }} </td>
                        <td> {{ $value->health_plan_type }} </td>
                        <td> {{ $value->FindHospital1 && $value->FindHospital1->hospital ?  $value->FindHospital1->hospital : '' }} </td>
                        <td> {{ $value->FindHospital2 && $value->FindHospital2->hospital ?  $value->FindHospital2->hospital : '' }} </td>
                        <td align="center"> <b>  {{ $value->withCount('CountDependants')->get()[$key]->count_dependants_count }} </b></td>
                   
                      </td>


                    </tr>

                    @endforeach
                   

                  </tbody>
                </table>