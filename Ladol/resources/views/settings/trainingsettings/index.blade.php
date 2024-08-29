<div class="page-header">
    <h1 class="page-title">{{__('Training Settings')}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item ">{{__('Training Settings')}}</li>
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
                    <h3 class="panel-title">Training Categories</h3>
                    <div class="panel-actions">
                        <button class="btn btn-info" data-toggle="modal" data-target="#addCategoryModal"><i class="fa fa-plus"
                                    aria-hidden="true"></i>&nbsp;Add Training Category</button>
                    </div>
                </div>
                <div class="panel-body">

                    <table id="exampleTablePagination" data-toggle="table"
                    data-query-params="queryParams" data-mobile-responsive="true"
                    data-height="400" data-pagination="true" data-search="true" class="table table-striped">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Action:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $n = 1;
                        @endphp
                        @forelse($categories as $category)
                            <tr>
                                <td>{{$n++}}</td>
                                <td>{{$category->name}}</td>
                                <td>{{$category->description}}</td>
                                <td>
                                    <a class="" title="edit" class="btn btn-icon btn-info" id="{{$category->id}}"
                                    onclick="prepareEditCategory(this.id);"><i class="fa fa-pencil"
                                    aria-hidden="true"></i>
                                    </a>
                                    <a class="" title="delete" class="btn btn-icon btn-danger" id="{{$category->id}}"
                                        onclick="deleteCategory(this.id)"><i class="fa fa-trash"
                                        aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <p>No Category added!</p>
                        @endforelse
                     </tbody>
                 </table>
             </div>
         </div>
     </div>
     <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">Training Types</h3>
                    <div class="panel-actions">
                        <button class="btn btn-info" data-toggle="modal" data-target="#addTypeModal"><i class="fa fa-plus"
                                    aria-hidden="true"></i>&nbsp;Add Training Type</button>
                    </div>
                </div>
                <div class="panel-body">

                    <table id="exampleTablePagination" data-toggle="table"
                    data-query-params="queryParams" data-mobile-responsive="true"
                    data-height="400" data-pagination="true" data-search="true" class="table table-striped">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Action:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $n = 1;
                        @endphp
                        @forelse($types as $type)
                            <tr>
                                <td>{{$n++}}</td>
                                <td>{{$type->type}}</td>
                                <td>{{$type->description}}</td>
                                <td>
                                    <a class = "" title="edit" class="btn btn-icon btn-info" id="{{$type->id}}"
                                    onclick="prepareEditType(this.id);"><i class="fa fa-pencil"
                                    aria-hidden="true"></i>
                                    </a>
                                    <a class="" title="delete" class="btn btn-icon btn-danger" id="{{$type->id}}"
                                        onclick="deleteType(this.id)"><i class="fa fa-trash"
                                        aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <p>No Category added!</p>
                        @endforelse
                     </tbody>
                 </table>
             </div>
         </div>
     </div>
     <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">Training Feedback Category</h3>
                    <div class="panel-actions">
                        <button class="btn btn-info" data-toggle="modal" data-target="#addQuestionCatModal"><i class="fa fa-plus"
                                    aria-hidden="true"></i>&nbsp;Add Question Category</button>
                    </div>
                </div>
                <div class="panel-body">

                    <table id="exampleTablePagination" data-toggle="table"
                    data-query-params="queryParams" data-mobile-responsive="true"
                    data-height="400" data-pagination="true" data-search="true" class="table table-striped">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Category</th>
                            <th>Action:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $n = 1;
                        @endphp
                        @forelse($question_categories as $category)
                            <tr>
                                <td>{{$n++}}</td>
                                <td>{{$category->category}}</td>
                                <td>
                                    <a class = "" title="edit" class="btn btn-icon btn-info" id="{{$category->id}}"
                                    onclick="prepareEditQuestionCat(this.id);"><i class="fa fa-pencil"
                                    aria-hidden="true"></i>
                                    </a>
                                    <a class="" title="delete" class="btn btn-icon btn-danger" id="{{$category->id}}"
                                        onclick="deleteQuestionCat(this.id)"><i class="fa fa-trash"
                                        aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <p>No Category added!</p>
                        @endforelse
                     </tbody>
                 </table>
             </div>
         </div>
     </div>
     <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">Training Feedback Options (Checkboxes and Radios)</h3>
                    <div class="panel-actions">
                        <button class="btn btn-info" data-toggle="modal" data-target="#addQuestionOptionModal"><i class="fa fa-plus"
                                    aria-hidden="true"></i>&nbsp;Add Question Option</button>
                    </div>
                </div>
                <div class="panel-body">

                    <table id="exampleTablePagination" data-toggle="table"
                    data-query-params="queryParams" data-mobile-responsive="true"
                    data-height="400" data-pagination="true" data-search="true" class="table table-striped">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Option</th>
                            <th>Mark Obtainable</th>
                            <th>Action:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $n = 1;
                        @endphp
                        @forelse($options as $option)
                            <tr>
                                <td>{{$n++}}</td>
                                <td>{{$option->option}}</td>
                                <td>{{$option->mark}}</td>
                                <td>
                                    <a class = "" title="edit" class="btn btn-icon btn-info" id="{{$option->id}}"
                                    onclick="prepareEditOption(this.id);"><i class="fa fa-pencil"
                                    aria-hidden="true"></i>
                                    </a>
                                    <a class="" title="delete" class="btn btn-icon btn-danger" id="{{$option->id}}"
                                        onclick="deleteQuestionOption(this.id)"><i class="fa fa-trash"
                                        aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <p>No Option added!</p>
                        @endforelse
                     </tbody>
                 </table>
             </div>
         </div>
     </div>
     <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">Training Feedback Questions</h3>
                    <div class="panel-actions">
                        <button class="btn btn-info" data-toggle="modal" data-target="#addQuestionModal"><i class="fa fa-plus"
                                    aria-hidden="true"></i>&nbsp;Add Question</button>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                    @forelse($questions as $question)
                        @if(($question->type ===  "radiobutton"))
                        <div class="col-md-12">
                             <div class="row">
                                <div class="col-md-6">
                                    <table id="exampleTablePagination" data-toggle="table"
                                        data-query-params="queryParams" data-mobile-responsive="true"
                                        data-height="400" data-pagination="true" data-search="true" class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td>Question</td>
                                                <td>{{$question->question}}</td>
                                            </tr>
                                            <tr>
                                                <td>Type</td>
                                                <td>{{$question->type}}</td>
                                            </tr>
                                            <tr>
                                                <td>Category</td>
                                                <td>{{$question->category->category}}</td>
                                            </tr>
                                            <tr>
                                                <td>Assign To:</td>
                                                <td>{{$question->assign_method}}</td>
                                            </tr>
                                            <tr>
                                                <td>Compulsory</td>
                                                <td>{{$question->compulsory}}</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>{{$question->status}}</td>
                                            </tr>
                                            <tr style="margin-top: 15px !important;">
                                                <td>Actions</td>
                                                <td>
                                                <a class="" title="edit" class="btn btn-icon q-icon" id="{{$question->id}}"
                                                    onclick="prepareEditQuestion(this.id)" style = "background-color:#00a9f4; padding:8px; margin-right:3px;"><i class="fa fa-pencil"
                                                    aria-hidden="true" style="color:white;"></i>
                                                    </a>
                                                    <a class="" title="delete" class="btn btn-icon btn-danger y-icon" id="{{$question->id}}"
                                                        onclick="deleteQuestion(this.id)" style = "background-color:red; padding:8px; margin-right:3px;"><i class="fa fa-trash"
                                                        aria-hidden="true" style="color:white;"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table id="exampleTablePagination" data-toggle="table"
                                        data-query-params="queryParams" data-mobile-responsive="true"
                                        data-height="400" data-pagination="true" data-search="true" class="table table-striped">
                                        <tbody>
                                            <tr style="color:black; font-weight:bold;" class="p-2">
                                                <td> Options</td>
                                                <td></td>
                                            </tr>
                                            @foreach($options as $option)
                                                <tr>
                                                    <td>{{$option->option}}</td>
                                                    <td><a class="" title="edit" class="btn btn-icon q-icon" id="{{$option->id}}"
                                                        onclick="prepareEditOption(this.id);" style = "background-color:#00a9f4; padding:8px; margin-right:3px;"><i class="fa fa-pencil"
                                                        aria-hidden="true" style="color:white;"></i>
                                                        </a>
                                                        <a class="" title="delete" class="btn btn-icon btn-danger y-icon" id="{{$question->id}}"
                                                            onclick="deleteQuestionOption(this.id)" style = "background-color:red; padding:8px; margin-right:3px;"><i class="fa fa-trash"
                                                            aria-hidden="true" style="color:white;"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                             </div>
                        </div>
                        @else
                            <div class="col-md-12" style="margin-top: 10px;">
                                <table id="exampleTablePagination" data-toggle="table"
                                    data-query-params="queryParams" data-mobile-responsive="true"
                                    data-height="400" data-pagination="true" data-search="true" class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td>Question</td>
                                            <td>{{$question->question}}</td>
                                        </tr>
                                        <tr>
                                            <td>Type</td>
                                            <td>{{$question->type}}</td>
                                        </tr>
                                        <tr>
                                            <td>Assign To:</td>
                                            <td>{{$question->assign_method}}</td>
                                        </tr>
                                        <tr>
                                            <td>Category</td>
                                            <td>{{$question->category->category}}</td>
                                        </tr>
                                        <tr>
                                            <td>Compulsory</td>
                                            <td>{{$question->compulsory}}</td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td>{{$question->status}}</td>
                                        </tr>
                                        <tr style="margin-top: 15px !important;">
                                            <td>Actions</td>
                                            <td>
                                            <a class="" title="edit" class="btn btn-icon q-icon" id="{{$question->id}}"
                                                onclick="prepareEditQuestion(this.id)" style = "background-color:#00a9f4; padding:8px; margin-right:3px;"><i class="fa fa-pencil"
                                                aria-hidden="true" style="color:white;"></i>
                                                </a>
                                                <a class="" title="delete" class="btn btn-icon btn-danger y-icon" id="{{$question->id}}"
                                                    onclick="deleteQuestion(this.id)" style = "background-color:red; padding:8px; margin-right:3px;"><i class="fa fa-trash"
                                                    aria-hidden="true" style="color:white;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @empty
                        <p>No Question available!</p>
                    @endforelse
                    </div>
                 </div>
         </div>
     </div>
     <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info panel-line" >
                <div class="panel-heading main-color-bg">
                    <h3 class="panel-title">Import Trainings</h3>
                    <div class="panel-actions">
                        <button class=" btn-primary btn"onclick="showUploadModal('trainings','Import Trainings')"><i class="fa fa-upload"
                        aria-hidden="true"></i>&nbsp;Upload </button>
                    </div>
                </div>
                <div class="panel-body">
                    <p class="list-group-item-text">Import all Training Data for the organization<br>
                        <a href="{{url('import/download_training_line_template')}}">Download Template</a>
                    </p>
                </div>
            </div>
        </div>
     </div>
     <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">Training Lists</h3>
                    <div class="panel-actions">
                        <button class="btn btn-info" data-toggle="modal" data-target="#addTrainingModal"><i class="fa fa-plus"
                                    aria-hidden="true"></i>&nbsp;Add Training</button>
                    </div>
                </div>
                <div class="panel-body">

                    <table id="exampleTablePagination" data-toggle="table"
                    data-query-params="queryParams" data-mobile-responsive="true"
                    data-height="400" data-pagination="true" data-search="true" class="table table-striped">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Duration</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Class Size</th>
                            <th>Location</th>
                            <th>Certification Required</th>
                            <th>Action:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $n = 0;
                        @endphp
                        @forelse($trainings as $training)
                            <tr>
                                <td>{{++$n}}</td>
                                <td>{{$training->name}}</td>
                                <td>{{$training->duration}}</td>
                                <td>{{$training->type_of_training}}</td>
                                <td>{{$training->category->name}}</td>
                                <td>{{$training->class_size}}</td>
                                <td>{{$training->training_location ?? 'Nil'}}</td>
                                <td>{{$training->is_certification_required == '1' ? 'Yes' : 'No'}}</td>
                                <td>
                                    <a class="" title="edit" class="btn btn-icon btn-info" id="{{$training->id}}"
                                    onclick="prepareTrainingEditData(this.id);"><i class="fa fa-pencil"
                                    aria-hidden="true"></i>
                                    </a>
                                    <a class="" title="delete" class="btn btn-icon btn-danger" id="{{$training->id}}"
                                        onclick="deleteTraining(this.id)"><i class="fa fa-trash"
                                        aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <p>No training found!</p>
                            </tr>
                        @endforelse
                     </tbody>
                 </table>
             </div>
         </div>
     </div>
</div>
@include('settings.trainingsettings.modals.addoption')
@include('settings.trainingsettings.modals.addtype')
@include('settings.trainingsettings.modals.edittype')
@include('settings.trainingsettings.modals.addtraining')
@include('settings.trainingsettings.modals.addcategory')
@include('settings.trainingsettings.modals.editcategory')
@include('settings.trainingsettings.modals.edittraining')
@include('settings.trainingsettings.modals.upload')
@include('settings.trainingsettings.modals.addquestion')
@include('settings.trainingsettings.modals.addquestioncategory')
@include('settings.trainingsettings.modals.editquestioncategory')
@include('settings.trainingsettings.modals.editquestion')

<div class="col-md-12 col-xs-12"></div>
<script type="text/javascript">
    $(document).on('submit', '#addTrainingForm', function (event) {
        event.preventDefault();
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url: "{{route('trainings.store')}}",
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                toastr.success("Changes saved successfully", 'Success');
                $('#addTrainingModal').modal('toggle');
                location.reload();
            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });

    });

    $(document).on('submit', '#editTrainingForm', function (event) {
        event.preventDefault();
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url: '{{route('trainings.store')}}',
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                toastr.success("Changes saved successfully", 'Success');
                $('#addTrainingModal').modal('toggle');
                location.reload();
            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });

    });

    function prepareTrainingEditData(training_id) {
        $.get('{{ url('/settings/training') }}/' + training_id, function (response) {
            var data = response.data;
            console.log(data);
            $('#editTid').val(data.id);
            $('#editname').val(data.name);
            $('#editdescription').val(data.description);
            $('#editcategory').val(data.category_id);
            $('#editcost_per_head').val(data.cost_per_head);
            $('#editclass_size').val(data.class_size);
            $('#edittraining_duration').val(data.duration);
            $('#edittraining_url').val(data.training_url);
            $('#edittraining_location').val(data.training_location);
            $('#editmode_of_training').val(data.mode_of_training);
            $('#editis_certification').val(data.is_certification_required);
            var typeOfTrainingValue = data.type_of_training;
            if (typeOfTrainingValue === "internal") {
                $('#editold_int').prop('checked', true);
                $('#editnew_int').prop('checked', false);
            } else if (typeOfTrainingValue === "external") {
                $('#editold_int').prop('checked', false);
                $('#editnew_int').prop('checked', true);
            }
        });

        $('#editTrainingModal').modal();
    }

    //DELETE TRAINING

    function deleteTraining(training_id) {
        alertify.confirm('Are you sure you want to delete this training?', function () {
            $.get('{{ url('/settings/training/delete') }}/' + training_id, function (data) {
                if (data == 'success') {
                    toastr["success"]("Training deleted successfully", 'Success');
                    // $( "#ldr" ).load('{{route('employeesettings')}}');
                    location.reload();
                } else {
                    toastr["error"]("Error deleting Training", 'Success');
                }

            });
        }, function () {
            alertify.error('Training not deleted');
        });
    }

    //ADD CATEGORY
    $(document).on('submit', '#addCategoryForm', function (event) {
        event.preventDefault();
        var category_id          = document.getElementById('category_id').value;

        if(!category_id){
            var training_category    = document.getElementById('category').value;
            var category_description = document.getElementById('category_description').value;
        }else{
            var training_category    = document.getElementById('editcategory').value;
            var category_description = document.getElementById('editcategory_description').value;
        }
      
        if(training_category.trim() == ''){
            alertify
                .alert("Kindly enter a training category");
                return;
            }

            if(category_description.trim() == ''){
                alertify
                    .alert("Kindly enter the category description");
                return;
        }

        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url: "{{route('categories.store')}}",
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                toastr.success("Changes saved successfully", 'Success');
                $('#addCategoryModal').modal('toggle');
                location.reload();
            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });

    });

    //ADD OPTIONS
    $(document).on('submit', '#addQuestionOptionForm', function (event) {
        event.preventDefault();
        var option_id   = document.getElementById('option_id').value;

        if(!option_id){
            var option    = document.getElementById('option').value;
            var mark      = document.getElementById('mark').value;
        }else{
            var option    = document.getElementById('option').value;
            var mark      = document.getElementById('mark').value;
        }
      
        if(option.trim() == ''){
            alertify
                .alert("Kindly enter an option");
                return;
            }
        
        
        if(mark.trim() == ''){
            alertify
                .alert("Kindly enter a mark obtainable");
                return;
            }

        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url: "{{route('options.store')}}",
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                toastr.success("Option saved successfully", 'Success');
                $('#addQuestionOptionModal').modal('toggle');
                location.reload();
            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });

    });

    //TRAINING TYPE ADD
    $(document).on('submit', '#addTypeForm', function(event){
        event.preventDefault();
        var type = document.querySelector('#type').value;
        var description = document.querySelector('#description').value;
        if(type.trim() == ''){
            Swal.fire("Error!", "Kindly enter a training type", "error");
            return;
        }

        if(description.trim() == ''){
            Swal.fire("Error!", "Kindly enter a description", "error");
            return;
        }
     
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
      
        $.ajax({
            url: "{{route('types.store')}}",
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                toastr.success("Changes saved successfully", 'Success');
                $('#addTypeModal').modal('toggle');
                location.reload();
            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });

    });

    $(document).on('submit', '#editTypeForm', function(event){
        event.preventDefault();
        var type_id  = document.querySelector('#type_id').value;
        var type = document.querySelector('#edit_typename').value;
        var description = document.querySelector('#edit_typedescription').value;
       

        if(type.trim() == ''){
            Swal.fire("Error!", "Kindly enter a training type", "error");
            return;
        }

        if(description.trim() == ''){
            Swal.fire("Error!", "Kindly enter a description", "error");
            return;
        }
     
        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
      
        $.ajax({
            url: "{{route('types.store')}}",
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            success: function (data, textStatus, jqXHR) {
                toastr.success("Changes saved successfully", 'Success');
                $('#addTypeModal').modal('toggle');
                location.reload();
            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });

    });
    //QUESTION FEEDBACK
    $(document).on('submit', '#addQuestionForm', function(event){
        event.preventDefault();
        const question   = document.getElementById('question').value;
        const category   = document.getElementById('question_category').value;
        const type       = document.getElementById('question_type').value;
        const status     = document.getElementById('question_status').value;
        const compulsory = document.getElementById('is_compulsory').value;
        const assign     = document.getElementById('assign').value;

        if(question.trim() == ''){
            Swal.fire("Error!", "Kindly enter a question", "error");
            return;
        }

        if(category.trim() == ''){
            Swal.fire("Error!", "Kindly enter a question category", "error");
            return;
        }

        if(type.trim() == ''){
            Swal.fire("Error!", "Kindly enter a question type", "error");
            return;
        }

        if(assign.trim() == ''){
            Swal.fire("Error!", "Kindly select the assigning method", "error");
            return;
        }

        if(status.trim() == ''){
            Swal.fire("Error!", "Kindly enter a question status", "error");
            return;
        }

        if(compulsory.trim() == ''){
            Swal.fire("Error!", "Kindly select if question is compulsory or not", "error");
            return;
        }

        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
      
        $.ajax({
            url: "{{route('questions.store')}}",
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                toastr.success("Question saved successfully", 'Success');
                $('#addTypeModal').modal('toggle');
                location.reload();
            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });

    });

    $(document).on('submit', '#editQuestionForm', function(event){
        event.preventDefault();
        const question    = document.getElementById('edit_question').value;
        const category    = document.getElementById('editqquestion_category').value;
        const type        = document.getElementById('edit_question_type').value;
        const status      = document.getElementById('edit_question_status').value;
        const compulsory  = document.getElementById('is_compulsory').value;
        const question_id = document.getElementById('question_id').value;

        if(question.trim() == ''){
            Swal.fire("Error!", "Kindly enter a question", "error");
            return;
        }

        if(category.trim() == ''){
            Swal.fire("Error!", "Kindly enter a question category", "error");
            return;
        }

        if(type.trim() == ''){
            Swal.fire("Error!", "Kindly enter a question type", "error");
            return;
        }

        if(status.trim() == ''){
            Swal.fire("Error!", "Kindly enter a question status", "error");
            return;
        }

        if(compulsory.trim() == ''){
            Swal.fire("Error!", "Kindly select if question is compulsory or not", "error");
            return;
        }

        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
      
        $.ajax({
            url: "{{route('questions.store')}}",
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                toastr.success("Question saved successfully", 'Success');
                $('#addTypeModal').modal('toggle');
                location.reload();
            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });

    });

    //QUESTION CATEGORY

    $(document).on('submit', '#addQuestionCatForm', function(event){
        event.preventDefault();

        const category = document.getElementById('question_cat').value;
        if(category.trim() == ""){
            Swal.fire("Error!", "Kindly enter a question category", "error");
            return;
        }

        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
      
        $.ajax({
            url: "{{route('category.store')}}",
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                toastr.success("Question Category saved successfully", 'Success');
                $('#addQuestionCatModal').modal('toggle');
                location.reload();
            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });
    });

    $(document).on('submit', '#editQuestionCatForm', function(event){
        event.preventDefault();
        const category_id = document.getElementById('cat_id').value;
        const category = document.getElementById('edit_catname').value;
        if(category.trim() == ""){
            Swal.fire("Error!", "Kindly enter a question category", "error");
            return;
        }

        var form = $(this);
        var formdata = false;
        if (window.FormData) {
            formdata = new FormData(form[0]);
        }
      
        $.ajax({
            url: "{{route('category.store')}}",
            data: formdata ? formdata : form.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                toastr.success("Question Category saved successfully", 'Success');
                $('#addQuestionCatModal').modal('toggle');
                location.reload();
            },
            error: function (data, textStatus, jqXHR) {
                jQuery.each(data['responseJSON'], function (i, val) {
                    jQuery.each(val, function (i, valchild) {
                        toastr.error(valchild[0]);
                    });
                });
            }
        });
    });

    function deleteCategory(id) {
        var token = $("meta[name='csrf-token']").attr("content");
        alertify.confirm('Are you sure you want to delete this category?', function () {
        $.ajax({
            url: "{{ url('/settings/categories') }}/" + id,
            type: 'DELETE',
            data: {
            "id": id,
            "_token": token,
            },
            success: function(data) {
                if (data.status) {
                    toastr["success"]("Training deleted successfully", 'Success');
                    location.reload();
                } else {
                    toastr["error"]("Error deleting Training", 'Error');
                }
            },
            error: function() {
                toastr["error"]("Error deleting Training", 'Error');
            }
        });
    }, function () {
        alertify.error('Training not deleted');
    });
    }

    function prepareEditCategory(id){
        $('#editCategoryModal').modal();
        $.ajax({
            url: "{{ url('/settings/categories') }}/" + id,
            type: 'GET',
            data: {
            "id": id,
            },
            success: function(data) {
                if (data.status) {
                    $("#editcategory").val(data.data.name); 
                    $("#category_id").val(data.data.id);
                    $("#editcategory_description").val(data.data.description);
                } 
            },
            error: function() {
                toastr["error"]("Something went wrong", 'Error');
            }
        });
    }

    function prepareEditType(id){
        $('#editTypeModal').modal();
        $.ajax({
            url: "{{ url('/settings/types') }}/" + id,
            type: 'GET',
            data: {
            "id": id,
            },
            success: function(data) {
                if (data.status) {
                    $("#edit_typename").val(data.data.type); 
                    $("#type_id").val(data.data.id);
                    $("#edit_typedescription").val(data.data.description);
                } 
            },
            error: function() {
                toastr["error"]("Something went wrong", 'Error');
            }
        });
    }

    function prepareEditQuestionCat(id){
        $('#editQuestionCatModal').modal();
        $.ajax({
            url: "{{ url('/training/category') }}/" + id,
            type: 'GET',
            data: {
            "id": id,
            },
            success: function(data) {
                console.log(data);
                if (data.status) {
                    $("#edit_catname").val(data.data.category); 
                    $("#cat_id").val(data.data.id);
                } 
            },
            error: function() {
                toastr["error"]("Something went wrong", 'Error');
            }
        });
    }
    function prepareEditQuestion(id)
    {
        $('#editQuestionMainModal').modal();
        var radios = document.querySelectorAll('.com-check');
        $.ajax({
            url: "{{ url('/training/questions') }}/" + id,
            type: 'GET',
            data: {
            "id": id,
            },
            success: function(data) {
                console.log(data);
                if (data.status) {
                   $('#edit_question').val(data.data.question);
                   $('#editqquestion_category').val(data.data.category_id);
                   $('#edit_question_type').val(data.data.type);
                   $('#edit_question_status').val(data.data.status);
                   $('#question_id').val(data.data.id);
                   $('#editassign').val(data.data.assign_method);

                   for( let i = 0; i < radios.length; i++){
                     if(radios[i].value === data.data.compulsory.toString()){
                        radios[i].checked = true;
                     }
                   }
                } 
            },
            error: function() {
                toastr["error"]("Something went wrong", 'Error');
            }
        });
    }


    function prepareEditOption(id){
        $('#addQuestionOptionModal').modal();
        $.ajax({
            url: "{{ url('/training/options') }}/" + id,
            type: 'GET',
            data: {
            "id": id,
            },
            success: function(data) {
                if (data.status) {
                    $("#option").val(data.data.option); 
                    $("#option_id").val(data.data.id);
                    $("#mark").val(data.data.mark);
                } 
            },
            error: function() {
                toastr["error"]("Something went wrong", 'Error');
            }
        });
    }

    function deleteQuestionOption(id){
        var token = $("meta[name='csrf-token']").attr("content");
        alertify.confirm('Are you sure you want to delete this question option ?', function () {
        $.ajax({
            url: "{{ url('/training/options') }}/" + id,
            type: 'DELETE',
            data: {
            "id": id,
            "_token": token,
            },
            success: function(data) {
                if (data.status) {
                    toastr["success"]("Training Option deleted successfully", 'Success');
                    location.reload();
                } else {
                    toastr["error"]("Error deleting Type", 'Error');
                }
            },
            error: function() {
                toastr["error"]("Error deleting Type", 'Error');
            }
        });
    }, function () {
            alertify.error('Option not deleted');
        });
    }

    function deleteQuestionCat(id) {
        var token = $("meta[name='csrf-token']").attr("content");
        alertify.confirm('Are you sure you want to delete this question category ?', function () {
        $.ajax({
            url: "{{ url('/training/category') }}/" + id,
            type: 'DELETE',
            data: {
            "id": id,
            "_token": token,
            },
            success: function(data) {
                if (data.status) {
                    toastr["success"]("Training Category deleted successfully", 'Success');
                    location.reload();
                } else {
                    toastr["error"]("Error deleting Type", 'Error');
                }
            },
            error: function() {
                toastr["error"]("Error deleting Type", 'Error');
            }
        });
    }, function () {
        alertify.error('Type not deleted');
    });
    }

    function deleteType(id) {
        var token = $("meta[name='csrf-token']").attr("content");
        alertify.confirm('Are you sure you want to delete this type?', function () {
        $.ajax({
            url: "{{ url('/settings/types') }}/" + id,
            type: 'DELETE',
            data: {
            "id": id,
            "_token": token,
            },
            success: function(data) {
                if (data.status) {
                    toastr["success"]("Type deleted successfully", 'Success');
                    location.reload();
                } else {
                    toastr["error"]("Error deleting Type", 'Error');
                }
            },
            error: function() {
                toastr["error"]("Error deleting Type", 'Error');
            }
        });
    }, function () {
        alertify.error('Type not deleted');
    });
    }

    function deleteQuestion(id){
        var token = $("meta[name='csrf-token']").attr("content");
        alertify.confirm('Are you sure you want to delete this question  ?', function () {
        $.ajax({
            url: "{{ url('/training/questions') }}/" + id,
            type: 'DELETE',
            data: {
            "id": id,
            "_token": token,
            },
            success: function(data) {
                if (data.status) {
                    toastr["success"]("Question deleted successfully", 'Success');
                    location.reload();
                } else {
                    toastr["error"]("Error deleting Type", 'Error');
                }
            },
            error: function() {
                toastr["error"]("Error deleting Question", 'Error');
            }
        });
    }, function () {
        alertify.error('Question not deleted');
    });
    }

    function showUploadModal(import_type,name){
        $(document).ready(function () {
            $('#upload_type').val(import_type);
            $('#action_name').html(name);
            $('#UploadModal').modal();
        });
    }

   
</script>