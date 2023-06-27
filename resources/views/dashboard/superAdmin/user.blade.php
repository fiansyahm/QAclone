@extends('layouts.main')
@section('container')
    
    <div class="modal fade" id="exampleVerticallycenteredModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Add New User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addUser">
                <div class="modal-body">
                    @csrf
                    <div class="col mb-3">
                        <label class="form-label">Name</label>
                        <input id="addName-input" type="text" class="form-control" name="name" placeholder="Enter Name..." value="{{ old('name') }}" autocomplete="off" required>
                        <div class="invalid-feedback" id="addName-feedback">
                        </div>
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">Username</label>
                        <input id="addUsername-input" type="text" class="form-control" name="username" placeholder="Enter Username..." autocomplete="off" value="{{ old('username') }}" required>
                        <div class="invalid-feedback" id="addUsername-feedback">
                        </div>
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">Password (Default: Password = Username)</label>
                        <input type="text" class="form-control" name="password" placeholder="Enter Password..." autocomplete="off" value="{{ old('password') }}">
                    </div>      
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
        </div>
    </div>
    
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Edit User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUser">
                <div class="modal-body">
                    @csrf
                    @method('put')
                    <div class="col mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Enter Name..." value="{{ old('name') }}" autocomplete="off" required>
                        @error('name')
                            <div class="invalid-feedback" id="name-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" placeholder="Enter Username..." autocomplete="off" value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">Password (Isi jika ingin diubah)</label>
                        <input type="text" class="form-control" name="password" placeholder="Enter Password..." autocomplete="off" value="{{ old('password') }}">
                    </div>
                    <input type="hidden" name="user_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
        </div>
    </div>

    <h1>User Management</h1>
          <hr/>
          @if (session()->has('success'))    
            <div class="alert alert-dismissible fade show py-2 bg-success">
                <div class="d-flex align-items-center">
                <div class="fs-3 text-white"><ion-icon name="checkmark-circle-sharp"></ion-icon>
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
                  <button type="button" class="btn btn-sm btn-success px-5" data-bs-toggle="modal" data-bs-target="#exampleVerticallycenteredModal"><ion-icon name="add-circle-outline"></ion-icon>Add User</button>
              </div>
              <div class="card-body">
                  <div class="table-responsive">
                      <table id="user_table" class="table table-striped table-bordered" style="width:100%">
                          <thead>
                              <tr>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                              </tr>
                          </thead>
                          {{-- <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>
                                            <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEdit" data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-username="{{ $user->username }}">
                                                <ion-icon name="create-outline"></ion-icon> Edit
                                            </a>
                                            <button class="btn btn-danger deleteUser" data-id="{{ $user->id }}"><ion-icon name="trash-outline"></ion-icon> Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                          </tbody> --}}
                      </table>
                  </div>
              </div>
          </div>
@endsection

@section('script')
    @if (session()->has('errors'))
        <script>
            $(document).ready(function(){
                $('#exampleVerticallycenteredModal').modal('show');
                $('#modalEdit').modal('show');
            });
        </script>
    @endif
    <script>
        var table = $('#user_table').DataTable({
            aaSorting: [],
            serverSide: true,
            processing: true,
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
            },
            ajax: {
                url: '{!! URL::to('userTable') !!}',
                type: 'GET',
            },
            columns: [
                {
                    title: 'No',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    width: '5%'
                },
                {
                    title: 'Name',
                    data: 'name',
                    name: 'name',
                    width: '30%'
                },
                {
                    title: 'Username',
                    data: 'username',
                    name: 'username',
                    width: '30%'
                },
                {
                    title: 'Action',
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: '35%'
                },
            ],
        }).on('click', '.aksi', function(e) {
            e.preventDefault();
            if ($(this).hasClass('deleteUser')) {
                var id = $(this).data('id');
                console.log(id);
                Swal.fire({
                title: 'Delete this user?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/deleteUser',
                            type: 'DELETE',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "id": id
                            },
                            dataType: 'json',
                            success: function(response){
                                console.log(response);
                                if (response.error != null || response.error != undefined) {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: response.error,
                                        icon: 'error',
                                        confirmButtonText: 'Ok',
                                    })
                                } else{
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: 'User has been deleted.',
                                        icon: 'success',
                                        confirmButtonText: 'Ok',
                                    }).then(isConfirmed => {
                                        table.ajax.reload();
                                    });
                                }
                            },
                            error: function(error){
                                console.log('error');
                            }
                        })
                    }
                })
            } else {
                var modal = $('#modalEdit');
                var id = $(this).data('id');
                var name = $(this).data('name');
                var username = $(this).data('username');
                modal.find('.modal-body input[name="user_id"]').val(id);
                modal.find('.modal-body input[name="name"]').val(name);
                modal.find('.modal-body input[name="username"]').val(username);
                modal.modal('show');
            }
        });
        //ajax post with sweet alert
        $('#addUser').on('submit', function(e){
            e.preventDefault();
            //get data from form
            var formData = new FormData(this);
            $.ajax({
                url: '{!! URL::to('addUser') !!}',
                type:"POST",
                data:formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    console.log(data);
                    $('#exampleVerticallycenteredModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'User has been added',
                        showConfirmButton: true,
                        timer: 5000,
                    }).then(isConfirmed => {
                        table.ajax.reload();
                        $('#addUser').trigger("reset");
                    });
                },
                error: function(data) {
                    //return error message
                    console.log(data.responseJSON.errors.username[0]);
                    console.log(data.responseJSON.errors.name);
                    if (data.responseJSON.errors.name) {
                        $('#addName-input').addClass('is-invalid')
                        $('#addName-feedback').text(data.responseJSON.errors.name[0]);
                    }
                    else {
                        $('#addName-input').removeClass('is-invalid')
                    }
                    if (data.responseJSON.errors.username) {
                        $('#addUsername-input').addClass('is-invalid')
                        $('#addUsername-feedback').text(data.responseJSON.errors.username[0]);
                    }
                    else {
                        $('#addUsername-input').removeClass('is-invalid')
                    }
                }
            });
        });

        $('#editUser').on('submit', function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: '{!! URL::to('updateUser') !!}',
                type:"POST",
                data:formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    console.log(data);
                    $('#modalEdit').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'User has been updated',
                        showConfirmButton: true,
                        timer: 5000
                    }).then(isConfirmed => {
                        table.ajax.reload();
                    });
                },
                error: function(data) {
                    //return error message
                    console.log(data.responseJSON.errors.username[0]);
                    console.log(data.responseJSON.errors.name);
                    if (data.responseJSON.errors.name) {
                        $('#editName-input').addClass('is-invalid')
                        $('#editName-feedback').text(data.responseJSON.errors.name[0]);
                    } else {
                        $('#editName-input').removeClass('is-invalid')
                    }
                    if (data.responseJSON.errors.username) {
                        $('#editUsername-input').addClass('is-invalid')
                        $('#editUsername-feedback').text(data.responseJSON.errors.username[0]);
                    } else {
                        $('#editUsername-input').removeClass('is-invalid')
                    }
                }
            })
        })
    </script>
@endsection