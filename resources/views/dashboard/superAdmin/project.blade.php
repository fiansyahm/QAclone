@extends('layouts.main')
@section('container')
{{-- Modal for add --}}
    <div class="modal fade" id="exampleVerticallycenteredModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addProject">
                    <div class="modal-body">
                        @csrf
                        <div class="col mb-3">
                            <label class="form-label">Project Name</label>
                            <input id="projectName-input" type="text" class="form-control" name="project_name"
                                placeholder="Enter Project Name..." value="{{ old('project_name') }}" autocomplete="off"
                                required>
                            <div class="invalid-feedback" id="projectName-feedback">
                            </div>
                        </div>
                        <div class="col mb-3">
                            <label class="form-label">Limit Min Reviewer</label>
                            <input id="limitReviewer-input" type="number"
                                class="form-control @error('limit') is-invalid @enderror" name="limit"
                                placeholder="Enter Max Reviewer..." autocomplete="off" value="{{ old('limit') }}" required>
                            <div class="invalid-feedback" id="limitReviewer-feedback">
                            </div>
                        </div>
                        <div class="col mb-3">
                            <label class="form-label">Project Admin</label>
                            <select id="adminProject-input" class="select_user" name="admin_project[]" multiple="multiple">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('admin_project') == $user->id ? 'selected' : '' }}>{{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback d-block" id="adminProject-feedback">
                            </div>
                        </div>
                        <div class="col mb-3">
                            <label class="form-label">Project Reviewer</label>
                            <select class="reviewerProject-input" data-placeholder="Choose anything" multiple="multiple"
                                name="reviewer[]">
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal For Edit --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateProject">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="col mb-3">
                            <label class="form-label">Project Name</label>
                            <input type="text" class="form-control @error('project_name') is-invalid @enderror"
                                name="project_name" placeholder="Enter Project Name..." value="{{ old('project_name') }}"
                                autocomplete="off" required>
                            @error('project_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col mb-3">
                            <label class="form-label">Limit Min Reviewer</label>
                            <input type="number" class="form-control @error('limit') is-invalid @enderror" name="limit"
                                placeholder="Enter Max Reviewer..." autocomplete="off" value="{{ old('limit') }}" required>
                            @error('limit')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col mb-3">
                            <label class="form-label">Project Admin</label>
                            <select class="select_edit_user" name="admin_project[]" id="admin" multiple="multiple">
                            </select>
                            @error('admin_project')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col mb-3">
                            <label class="form-label">Project Reviewer</label>
                            <select class="reviewerProject-edit" data-placeholder="Choose anything" multiple="multiple"
                                name="reviewer[]">
                            </select>
                        </div>
                        <input type="hidden" name="project_id">
                        <input type="hidden" name="old_admin">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <h1>Project Management</h1>
    <hr />
    @if (session()->has('success'))
        <div class="alert alert-dismissible fade show py-2 bg-success">
            <div class="d-flex align-items-center">
                <div class="fs-3 text-white">
                    <ion-icon name="checkmark-circle-sharp"></ion-icon>
                </div>
                <div class="ms-3">
                    <div class="text-white">{{ session('success') }}</div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="col mb-3 mt-3 ms-3">
            <button type="button" class="btn btn-sm btn-success px-5" data-bs-toggle="modal"
                data-bs-target="#exampleVerticallycenteredModal">
                <ion-icon name="add-circle-outline"></ion-icon>Add Project
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="project_table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="text-align: left !important"></th>
                            <th style="text-align: left !important"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @if (session()->has('errors'))
        <script>
            $(document).ready(function() {
                $('#exampleVerticallycenteredModal').modal('show');
                $('#modalEdit').modal('show');
            });
        </script>
    @endif
    <script>
        //Datatable
        var table = $('#project_table').DataTable({
            aaSorting: [],
            processing: true,
            serverSide: true,
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
            },
            ajax: {
                url: '{!! URL::to('projectTable') !!}',
                type: 'GET',
            },
            columns: [{
                    title: 'No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    title: 'Project Name',
                    data: 'project_name',
                    name: 'project_name'
                },
                {
                    title: 'Project Admin',
                    data: 'admin_project',
                    name: 'admin_project',
                    render: function(data, type, row) {
                        return '<span style="white-space:normal">' + data + "</span>";
                    }
                },
                {
                    title: 'Reviewer',
                    data: 'reviewer',
                    name: 'reviewer',
                    render: function(data, type, row) {
                        return '<span style="white-space:normal">' + data + "</span>";
                    }
                },
                {
                    title: 'Action',
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],
        }).on('click', '.aksi', function(e) {
            e.preventDefault();
            if ($(this).hasClass('deleteProject')) {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Delete this project?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading()
                            },
                        });

                        $.ajax({
                            url: '/deleteProject',
                            type: 'DELETE',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "id": id
                            },
                            dataType: 'json',
                            success: function(response) {
                                console.log(response);
                                Swal.close();
                                if (response.error != null || response.error != undefined) {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: response.error,
                                        icon: 'error',
                                        confirmButtonText: 'Ok',
                                    })
                                }
                                else {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: 'Project has been deleted.',
                                        icon: 'success',
                                        confirmButtonText: 'Ok',
                                        timer: 5000
                                    }).then(isConfirmed => {
                                        table.ajax.reload();
                                    });
                                }
                            }
                        })
                    }
                })
            } else {
                var modal = $('#modalEdit');
                var id = $(this).data('id');

                var name = $(this).data('project_name');
                var limit = $(this).data('limit');
                var user = $(this).data('admin_project');
                console.log(user);
                modal.find('.modal-body input[name="project_id"]').val(id);
                modal.find('.modal-body input[name="project_name"]').val(name);
                modal.find('.modal-body input[name="limit"]').val(limit);
                modal.find('.modal-body input[name="old_admin"]').val(user);
                modal.modal('show');
                var admin = '';
                var reviewer = '';
                var role = '';

                $.ajax({
                    type: 'get',
                    url: '{!! URL::to('findProjectUser') !!}',
                    data: {
                        'id': id
                    },
                    dataType: 'json',
                    success: function(data) {
                        
                        for (var i = 0; i < data.length; i++) {
                            if (data[i].user_role == 'admin') {
                                user = data[i].user_id;
                                admin += '<option value="' + data[i].user_id + '" selected>' + data[i].user
                                    .name + '</option>';
                            } else {
                                reviewer += '<option value="' + data[i].user_id + '" selected>' + data[
                                    i].user.name + '</option>';
                                admin += '<option value="' + data[i].user_id + '">' + data[i].user
                                    .name + '</option>';
                            }
                        }
                        $('.select_edit_user').html(admin);
                        $('.select_edit_user').val();
                        $('.select_edit_user').trigger('change');
                        $('.reviewerProject-edit').html(reviewer);
                    },
                })

                $('.select_edit_user').on('change', function() {
                    var id = $(this).val();
                    var project_id = $('#modalEdit').find('.modal-body input[name="project_id"]').val();
                    var selected;
                    $.ajax({
                        url: '{!! URL::to('findEditReviewer') !!}',
                        type: 'GET',
                        data: {
                            'user_id': JSON.stringify(id),
                            'project_id': project_id
                        },
                        dataType: 'json',
                        success: function(data) {
                            var reviewer = '';
                            for (var i = 0; i < data.length; i++) {
                                if (data[i].project_user.length > 0) {
                                    reviewer += '<option value="' + data[i].id + '" selected>' + data[i].name + '</option>';
                                } else {
                                    selected = $('.reviewerProject-edit option[value="' + data[i].id + '"]').is(':selected');
                                    if (selected) {
                                        reviewer += '<option value="' + data[i].id + '" selected>' + data[i].name + '</option>';
                                    } else {
                                        reviewer += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                                    }
                                }
                            }
                            $('.reviewerProject-edit').html(reviewer);
                        }
                    })
                })
            }
        });
    </script>


    <script>
        $(document).ready(function() {
            $('.select_user').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#exampleVerticallycenteredModal .modal-content'),
                closeOnSelect: false,
                allowClear: true,
                placeholder: 'Select Admin Project',
                tags: true,
                tokenSeparators: [',', ' '],
            });
            $('.select_edit_user').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#modalEdit .modal-content'),
                closeOnSelect: false,
                allowClear: true,
                placeholder: 'Select Admin Project',
                tags: true,
                tokenSeparators: [',', ' '],
            });
            $('.reviewerProject-input').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#exampleVerticallycenteredModal .modal-content'),
                closeOnSelect: false,
                allowClear: true,
                placeholder: 'Select Reviewer',
                tags: true,
                tokenSeparators: [',', ' '],
            });
            $('.reviewerProject-edit').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#modalEdit .modal-content'),
                closeOnSelect: false,
                allowClear: true,
                placeholder: 'Select Reviewer',
                tags: true,
                tokenSeparators: [',', ' ']
            });
        });

        // add project find reviewer
        $('#adminProject-input').on('change', function() {
            var user_id = $(this).val();

            $.ajax({
                url: '{!! URL::to('findReviewer') !!}',
                type: 'GET',
                data: {
                    'user_id': JSON.stringify(user_id)
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    var html = '';
                    var old_reviewer = $('.reviewerProject-input').val();
                    for (var i = 0; i < data.length; i++) {
                        if (old_reviewer == data[i].id) {
                            html += '<option value="' + data[i].id + '" selected>' + data[i].name +
                                '</option>';
                        } else {
                            html += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                        }
                    }
                    $('.reviewerProject-input').html(html);
                    $('.reviewerProject-input').trigger('change');
                },
            })
        })

        // Add Project
        $('#addProject').on('submit', function(e) {
            e.preventDefault();
            var form = new FormData(this);
            $.ajax({
                url: '{!! URL::to('addProject') !!}',
                type: "POST",
                data: form,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    $('#exampleVerticallycenteredModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Project has been added',
                        showConfirmButton: true,
                        timer: 5000,
                    }).then(isConfirmed => {
                        table.ajax.reload();
                        $('#addProject').trigger('reset');
                        //reset the select option
                        $('.select_user').val(null).trigger('change');
                        // remove all invalid feedback
                        $('.invalid-feedback').text('');
                        // remove all is-invalid class
                        $('.is-invalid').removeClass('is-invalid');
                        $('.reviewerProject-input').select2('close');
                    });
                },
                error: function(data) {
                    console.log(data);
                    if (data.responseJSON.errors.admin_project) {
                        $('#adminProject-input').addClass('is-invalid')
                        $('#adminProject-feedback').text(data.responseJSON.errors.admin_project[0])
                    } else {
                        $('#adminProject-input').removeClass('is-invalid')
                    }

                    if (data.responseJSON.errors.project_name) {
                        $('#projectName-input').addClass('is-invalid')
                        $('#projectName-feedback').text(data.responseJSON.errors.project_name[0])
                    } else {
                        $('#projectName-input').removeClass('is-invalid')
                    }

                    if (data.responseJSON.errors.limit) {
                        $('#limitReviewer-input').addClass('is-invalid')
                        $('#limitReviewer-feedback').text(data.responseJSON.errors.limit[0])
                    } else {
                        $('#limitReviewer-input').removeClass('is-invalid')
                    }
                }
            })
        })
        // Update Project
        $('#updateProject').on('submit', function(e) {
            e.preventDefault();
            var form = new FormData(this);
            $('#modalEdit').modal('hide');

            Swal.fire({
                title: 'Updating...',
                html: 'Please wait while updating project',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                },
            });

            $.ajax({
                url: '{!! URL::to('updateProject') !!}',
                type: "POST",
                data: form,
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    Swal.close()
                    if (data.error != null || data.error != undefined) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.error,
                            showConfirmButton: true,
                        }).then(isConfirmed => {
                            $('#modalEdit').modal('show');
                        });
                    }
                    else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Project has been updated',
                            showConfirmButton: true,
                            timer: 5000
                        }).then(isConfirmed => {
                            table.ajax.reload();
                            //reset the select option
                            //reload the multi select
                            $('.reviewerProject-edit').select2('close');
                        });
                    }
                },
                error: function(data) {    
                    console.log(data.error);
                    Swal.close()
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        showConfirmButton: true,
                    });
                    // if (data.errors.admin_project) {
                    //     $('#adminProject-input-edit').addClass('is-invalid')
                    //     $('#adminProject-feedback-edit').text(data.errors.admin_project[0])
                    // } else {
                    //     $('#adminProject-input-edit').removeClass('is-invalid')
                    // }

                    // if (data.errors.project_name) {
                    //     $('#projectName-input-edit').addClass('is-invalid')
                    //     $('#projectName-feedback-edit').text(data.errors.project_name[0])
                    // } else {
                    //     $('#projectName-input-edit').removeClass('is-invalid')
                    // }

                    // if (data.errors.limit) {
                    //     $('#limitReviewer-input-edit').addClass('is-invalid')
                    //     $('#limitReviewer-feedback-edit').text(data.errors.limit[0])
                    // } else {
                    //     $('#limitReviewer-input-edit').removeClass('is-invalid')
                    // }
                }
            })
        })
    </script>
@endsection
