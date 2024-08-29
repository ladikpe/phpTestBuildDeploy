<script>
 function submitTraining() {
    var training_name = document.getElementById("training_name").value;
    var training_description = document.getElementById(
        "training_description"
    ).value;
    var assign_mode = document.getElementById("assign_mode").value;
    var employee_ids = $('.mul-select').val();
    var cost_per_head = document.getElementById("cost_per_head").value;
    var training_mode = document.getElementById("training_mode").value;
    var resource_link = document.getElementById("resource_link").value;
    var start_date = document.getElementById("start_date").value;
    var stop_date = document.getElementById("stop_date").value;
    var duration = document.getElementById("duration").value;
    var assign_mode = document.getElementById("assign_mode").value;
    var type = document.getElementById("training_type").value;
    var course_type = document.getElementById('course_type').value;
    var class_size  = document.getElementById('class_size').value;
    var training_location  = document.getElementById('training_location').value;
    var department_id = document.getElementById('department').value;
    var jobroles_id = document.querySelectorAll('.job_roles');
    var user_groups = document.getElementById('user_groups').value;
    var training_id = document.getElementById('training_id').value;
    var selected_roles = [];
    var selected_ids   = [];
    

  
    if (training_name.trim() == "") {
        swal("Kindly enter the training name.");
        return;
    }

    if (course_type.trim() == "") {
        swal("Kindly select the course type.");
        return;
    }

    if (training_description.trim() == "") {
        swal("Kindly enter the training description.");
        return;
    }

    if (assign_mode.trim() == "") {
        swal("Kindly enter the mode of assigning.");
        return;
    }

    if (assign_mode.trim() == "") {
        swal("Kindly enter the mode of assigning.");
        return;
    }


    if (cost_per_head.trim() == "") {
        swal("Kindly enter the cost per head");
        return;
    }

    if (training_mode.trim() == "") {
        swal("Kindly enter the mode of training");
        return;
    }

    if (start_date.trim() == "") {
        swal("Kindly enter the start date");
        return;
    }

    if (stop_date.trim() == "") {
        swal("Kindly enter the stop date");
        return;
    }

    if (duration.trim() == "") {
        swal("Kindly enter the duration");
        return;
    }

    if (type.trim() == "") {
        swal("Kindly enter the training type");
        return;
    }

    if(training_id.trim() == ''){
        swal("Kindly select a training to proceed.");
        return;
    }
   
    if (assign_mode == "department" && department_id.trim() == "") {
        swal("Kindly enter the department to assign training");
        return;
    }

    if (assign_mode == "group" && user_groups.trim() == "") {
        swal("Kindly select the user group to assign training");
        return;
    }
    
    if(assign_mode == 'department'){
        for(i = 0; i < jobroles_id.length; i++){
            if(jobroles_id[i].checked){
                selected_roles.push(jobroles_id[i].value);
            }
        }
    }
   
    
    if(assign_mode == "department" && selected_roles.length == 0){
        swal("Kindly select at least one role to assign training");
        return;
    }

    if(assign_mode == "employees"){
        for(k = 0; k < employee_ids.length; k++){
            selected_ids.push(employee_ids[k]);
        }
    }


    if(assign_mode == "employees" && selected_ids.length == 0){
        swal("Kindly select at least one user to assign training");
        return;
    }

   
    var form_data = new FormData();
    form_data.append("training_name", training_name);
    form_data.append("training_description", training_description);
    form_data.append("assign_mode", assign_mode);
    form_data.append("employee_ids", selected_ids);
    form_data.append("cost_per_head", cost_per_head);
    form_data.append("training_mode", training_mode); 
    form_data.append("resource_link", resource_link);
    form_data.append("start_date", start_date);
    form_data.append("stop_date", stop_date);
    form_data.append("duration", duration);
    form_data.append("assign_mode", assign_mode);
    form_data.append("type", type);
    form_data.append("location", training_location);
    form_data.append("location", training_location);
    form_data.append("jobroles_id", selected_roles.join(','));
    form_data.append("group_id", user_groups);
    form_data.append("course_type", course_type);
    form_data.append("department_id", department_id);
    form_data.append("training_id", training_id);


    $.ajax({
        url: "{{route('storetrainingplan')}}",
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        data: form_data,
        dataType  : 'json',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function(){
            document.getElementById('train_preload').style.display = 'block';
            document.getElementById('train_base').style.display = 'none';
        },
        success: function(response){
            console.log(response);
            var data = response;
            if(data.status == 200){
                swal("Success", "Training plan added successfully!", "success")
                location.reload();
            }else{
                swal("Ooops", "Something went wrong", "");
            }
            document.getElementById('train_preload').style.display = 'none';
            document.getElementById('train_base').style.display = 'block';
        }
    }); 
    
}


function FetchTrainingDetails(){
   var course_type   = document.getElementById("course_type").value;
   var form_data = new FormData();
   form_data.append("course_type", course_type);
   $.ajax({
        url: "{{url('/settings/training/all')}}",
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        data: form_data,
        dataType  : 'json',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function(){
            document.getElementById('plan_preload').style.display = 'block';
        },
        success: function(response){
            console.log(response.data);
            var data = response;
            if(data.status == 200){
                document.getElementById('plan_base').style.display = 'block';
                document.getElementById('plan_preload').style.display = 'none';
                var selectElement = document.getElementById('training_name');
                response.data.forEach(element => {
                    var option = document.createElement('option');
                    option.value = element.id;
                    option.id  = element.id;
                    option.text = element.name;
                    selectElement.appendChild(option);
                });

            }else if(data.status == 401){
                swal("Ooops", "Course type is required", "");
            } else{
                swal("Ooops", "Something went wrong", "");
                document.getElementById('plan_base').style.display = 'block';
                document.getElementById('plan_preload').style.display = 'none';
            }
            document.getElementById('plan_base').style.display = 'block';
            document.getElementById('plan_preload').style.display = 'none';
        }
    });
}

function FetchMainDetails()
{
    var training_id = document.getElementById('training_name').value;
    $.ajax({
        url: "{{url('/settings/training')}}/" + training_id,
        method: "GET",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        dataType  : 'json',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function(){
            document.getElementById('sub_preload').style.display = 'block';
        },
        success: function(response){
            console.log(response);
            var data = response;
            if(data.status == 200){
                document.getElementById('sub_base').style.display = 'block';
                document.getElementById('sub_preload').style.display = 'none';
                document.getElementById('training_description').value = data.data.description;
                document.getElementById('training_id').value = data.data.id;
                document.getElementById('duration').value = data.data.duration;
                document.getElementById('cost_per_head').value = data.data.cost_per_head;
                document.getElementById('class_size').value = data.data.class_size;
                document.getElementById('training_type').value = data.data.type_of_training;
                document.getElementById('training_location').value = data.data.training_location == null ? 'Nil' : data.data.training_location ;
                document.getElementById('resource_link').value = data.data.training_url == null ? 'Nil' : data.data.training_url;
            }else if(data.status == 401){
                swal("Ooops", "Kindly select a training name", "");
            } else{
                swal("Ooops", "Something went wrong", "");
                document.getElementById('sub_base').style.display = 'block';
                document.getElementById('sub_preload').style.display = 'none';
            }
            document.getElementById('sub_base').style.display = 'block';
            document.getElementById('sub_preload').style.display = 'none';
        }
    });
}


function getJobRoles(){
    var department_id = document.getElementById('department').value;
    if(department_id.trim() == ''){
        swal("Kindly select a department to get job roles.");
        return;
    }
    $.ajax({
        url: "{{url('/training/jobroles')}}/" + department_id,
        method: "GET",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        dataType  : 'json',
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function(){
            document.getElementById('job_preload').style.display = 'block';
        },
        success: function(response){
            var data = response;
            if(data.status == 200){
                document.getElementById('job_preload').style.display = 'none';
                document.getElementById('job_base').style.display = 'block';
                var selectItem = document.getElementById('job_rolesbox');
                selectItem.innerHTML = "";
                var n = 3; // Number of columns
                var row = document.createElement('div'); // Create a row container
                row.classList.add('row'); 

                response.data.forEach((element, index) => {
                if (index % n === 0) {
                    row = document.createElement('div');
                    row.classList.add('row');
                }

                var column = document.createElement('div'); 
                column.classList.add('col'); 

                var checkbox = document.createElement('input');
                checkbox.type = "checkbox";
                checkbox.value = element.id;
                checkbox.id = "checkbox_" + element.id;
                checkbox.classList.add("form-check-input");
                checkbox.classList.add("job_roles");

                var label = document.createElement('label');
                label.setAttribute("for", "checkbox_" + element.id);
                label.textContent = element.title;
                label.classList.add("form-check-label");

                column.appendChild(checkbox);
                column.appendChild(label);

                row.appendChild(column);
                selectItem.appendChild(row);
                });

            }else if(data.status == 401){
                swal("Ooops", "Kindly select a training name", "");
            } else{
                swal("Ooops", "Something went wrong", "");
                document.getElementById('job_base').style.display = 'block';
                document.getElementById('job_preload').style.display = 'none';
            }
            document.getElementById('job_base').style.display = 'block';
            document.getElementById('job_preload').style.display = 'none';
        }
    });
}


    $(document).ready(function() {
        $(".mul-select").select2({
            placeholder: "select user name",
            tags: true,
        });

        $(".mul-select").on("change", function() {
            var selectedValues = $(this).val();
            console.log(selectedValues);
        });
    });


    function ToggleRejectionModal(training_id, user_id){
        document.getElementById('auth_id').value =  user_id;
        document.getElementById('user_training_id').value = training_id;
        $('#rejectionModal').modal();
    }

    function rejectTraining(){
        var reason = document.getElementById('reason').value
        if(reason.trim() ==  ""){
            swal("Kindly enter the rejection reason to proceed");
            return;
        }
        var auth_id = document.getElementById('auth_id').value;
        var user_training_id = document.getElementById('user_training_id').value;
        var form_data = new FormData();
        form_data.append('auth_id', auth_id);
        form_data.append('reason', reason);
        form_data.append('training_id', user_training_id);
        $.ajax({
            url: "{{url('/training/reject')}}/",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            data: form_data,
            dataType  : 'json',
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function(){
                document.getElementById('reject_preload').style.display = 'block';
                document.getElementById('reject_base').style.display = 'block';
            },
            success: function(response){
                var data = response;
                if(data.code == 201){
                    swal("Success", "Rejection reason sent successfully.", "");
                    location.reload();
                }else{
                    swal("Ooops", "Something went wrong", "");
                    document.getElementById('reject_base').style.display = 'block';
                    document.getElementById('reject_preload').style.display = 'none';
                }
            }
        });
    }


    function saveBudget(){
        var department = document.getElementById("budget_department").value;
        var allocation = document.getElementById("allocation").value;
        var stop_date  = document.getElementById("stop_date").value;
        var id         = document.getElementById("budget_id").value;

        if(department.trim() ==  ""){
            swal("Kindly select a department to proceed.");
            return;
        }

        if(allocation.trim() == ""){
            swal("Kindly enter cost allocated");
            return;
        }

        if(stop_date.trim() == ""){
            swal("Kindly enter the stop date");
            return;
        }

        var form_data = new FormData();
        form_data.append("department", department);
        form_data.append("allocation", allocation);
        form_data.append("stop_date", stop_date);
        form_data.append("budget_id", id);
        
        $.ajax({
            url: "{{url('/training/budget/save')}}/",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            data: form_data,
            dataType  : 'json',
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function(){
                document.getElementById('budget_preload').style.display = 'block';
                document.getElementById('budget_base').style.display = 'block';
            },
            success: function(response){
                var data = response;
                if(data.status == 200){
                    swal("Success", "Budget saved successfully.", "");
                    location.reload();
                }else{
                    swal("Ooops", "Something went wrong", "");
                    document.getElementById('budget_base').style.display = 'block';
                    document.getElementById('budget_preload').style.display = 'none';
                }
            }
        });
    }

    function ModifyBudget(id)
    {
        $(".modal-title#exampleModalLabel").text("Update Budget");
        $('#budgetModal').modal();
        $.get("{{ url('/training/budget') }}/" + id, function (response) {
            var data = response.data; 
            console.log(data);   
            $('#budget_id').val(data.id);
            $('#budget_department').val(data.department_id);
            $('#allocation').val(data.allocation);
            $('#stop_date').val(data.stop_date);
        });
    }


$(document).ready(function() {
  $('.comment-checkbox').on('change', function() {
    $('.comment-checkbox').not(this).prop('checked', false);
  });
});
</script>

