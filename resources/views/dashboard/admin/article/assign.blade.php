@extends('layouts.main')

@section('container')
    <h1>Assign Article</h1>
    <hr>
    <a href="/dashboard/admin/project/{{ encrypt($project_id) }}"><button type="button" class="btn btn-secondary mb-2">
            <ion-icon name="arrow-back"></ion-icon> Back
        </button></a>
    <div class="card">
        <form action="/dashboard/admin/assign/store" id="assign_form">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user_id }}">
            <input type="hidden" name="project_id" value="{{ $project_id }}">
            <div class="col mb-3 mt-3 ms-3">
                <button disabled type="submit" class="btn btn-primary" id="assign_btn">
                    <ion-icon name="bookmark"></ion-icon> Assign Article
                </button>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex justify-content-end">
                        <select class="select_reviewer_count" name="count_reviewer" class="form-control">
                            <option disabled selected>Select Reviewer Number</option>
                            <option value="all">All Article</option>
                            <option value="less">Less Than Limit</option>
                            <option value="fits">Fits Limit</option>
                            <option value="more">More Than Limit</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="notAssignTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>


    <h1>Article Management</h1>
    <hr>
    <div class="card">
        <form action="/dashboard/admin/assign/delete" id="rm_assign_form">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user_id }}">
            <input type="hidden" name="project_id" value="{{ $project_id }}">
            <div class="col mb-3 mt-3 ms-3">
                <button disabled type="submit" class="btn btn-danger" id="delete_btn">
                    <ion-icon name="trash"></ion-icon> Unassign Article
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="AssignTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>
        $('.select_reviewer_count').select2({
            width: '25%',
            placeholder: 'Select Reviewer Number',
            minimumResultsForSearch: Infinity,
        });

        var project_id = {{ $project_id }};
        var user_id = {{ $user_id }};
        var rows_selected = [];
        var rows_selected2 = [];

        function updateDataTableSelectAllCtrl(table) {
            var $table = table.table().node();
            var $chkbox_all = $('tbody input[type="checkbox"]', $table);
            var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
            var chkbox_select_all = $('thead input[name="select_all"]', $table).get(0);

            // If none of the checkboxes are checked
            if ($chkbox_checked.length === 0) {
                chkbox_select_all.checked = false;
                if ('indeterminate' in chkbox_select_all) {
                    chkbox_select_all.indeterminate = false;
                }

                // If all of the checkboxes are checked
            } else if ($chkbox_checked.length === $chkbox_all.length) {
                chkbox_select_all.checked = true;
                if ('indeterminate' in chkbox_select_all) {
                    chkbox_select_all.indeterminate = false;
                }

                // If some of the checkboxes are checked
            } else {
                chkbox_select_all.checked = true;
                if ('indeterminate' in chkbox_select_all) {
                    chkbox_select_all.indeterminate = true;
                }
            }
        }

        $(document).ready(function() {
            var articleNotAssign = $('#notAssignTable').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
                },
                order: [
                    [1, 'asc']
                ],
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                ajax: {
                    url: '{{ route('notAssigned.table') }}',
                    data: {
                        project_id: project_id,
                        user_id: user_id
                    },
                    type: 'GET',
                },
                columns: [{
                        title: '<input type="checkbox" id="head_cb" name="select_all" value="1">',
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return '<input type="checkbox" class="cb_child" value="' + data + '">';
                        }
                    },
                    {
                        title: 'ID - No',
                        data: 'no',
                        name: 'no',
                    },
                    {
                        title: 'Title',
                        data: 'title',
                        name: 'title',
                        render: function(data, type, row) {
                            return '<span style="white-space:normal">' + data + "</span>";
                        }
                    },
                    {
                        title: 'Year',
                        data: 'year',
                        name: 'year',
                    },
                    {
                        title: 'Publication',
                        data: 'publication',
                        name: 'publication',
                        render: function(data, type, row) {
                            return '<span style="white-space:normal">' + data + "</span>";
                        }
                    },
                    {
                        title: 'Authors',
                        data: 'authors',
                        name: 'authors',
                        render: function(data, type, row) {
                            return '<span style="white-space:normal">' + data + "</span>";
                        }
                    },
                    {
                        title: 'Reviewer',
                        data: 'reviewer',
                        name: 'reviewer',
                        className: 'text-center',
                    }
                ],
                rowCallback: function(row, data, dataIndex) {
                    var rowId = data['id'];
                    if ($.inArray(rowId, rows_selected) !== -1) {
                        $(row).find('input[type="checkbox"]').prop('checked', true);
                        $(row).addClass('selected');
                    }
                },
            }).on('init.dt', function() {
                $('#notAssignTable').wrap('<div class="dataTables_scroll" />');
            });

            $('.select_reviewer_count').on('change', function() {
                var count_reviewer = $(this).val();

                $('#notAssignTable').DataTable().destroy();
                $('#notAssignTable').DataTable({
                    processing: true,
                    serverSide: true,
                    language: {
                        processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
                    },
                    order: [
                        [1, 'asc']
                    ],
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ],
                    ajax: {
                        url: '{{ route('notAssigned.table') }}',
                        data: {
                            project_id: project_id,
                            user_id: user_id,
                            count_reviewer: count_reviewer
                        },
                        type: 'GET',
                    },
                    columns: [{
                            title: '<input type="checkbox" id="head_cb" name="select_all" value="1">',
                            data: 'id',
                            name: 'id',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                return '<input type="checkbox" class="cb_child" value="' +
                                    data + '">';
                            }
                        },
                        {
                            title: 'ID - No',
                            data: 'no',
                            name: 'no',
                        },
                        {
                            title: 'Title',
                            data: 'title',
                            name: 'title',
                            render: function(data, type, row) {
                                return '<span style="white-space:normal">' + data +
                                    "</span>";
                            }
                        },
                        {
                            title: 'Year',
                            data: 'year',
                            name: 'year',
                        },
                        {
                            title: 'Publication',
                            data: 'publication',
                            name: 'publication',
                            render: function(data, type, row) {
                                return '<span style="white-space:normal">' + data +
                                    "</span>";
                            }
                        },
                        {
                            title: 'Authors',
                            data: 'authors',
                            name: 'authors',
                            render: function(data, type, row) {
                                return '<span style="white-space:normal">' + data +
                                    "</span>";
                            }
                        },
                        {
                            title: 'Reviewer',
                            data: 'reviewer',
                            name: 'reviewer',
                            className: 'text-center',
                        }
                    ],
                    rowCallback: function(row, data, dataIndex) {
                        var rowId = data['id'];
                        if ($.inArray(rowId, rows_selected) !== -1) {
                            $(row).find('input[type="checkbox"]').prop('checked', true);
                            $(row).addClass('selected');
                        }
                    },
                }).on('init.dt', function() {
                    $('#notAssignTable').wrap('<div class="dataTables_scroll" />');
                });
            });

            $('#notAssignTable tbody').on('click', '.cb_child', function(e) {
                var $row = $(this).closest('tr');

                // Get row data
                var data = articleNotAssign.row($row).data();

                // Get row ID
                var rowId = data['id'];

                // Determine whether row ID is in the list of selected row IDs
                var index = $.inArray(rowId, rows_selected);

                // If checkbox is checked and row ID is not in list of selected row IDs
                if (this.checked && index === -1) {
                    rows_selected.push(rowId);
                    $('#assign_btn').prop('disabled', false);
                    // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
                } else if (!this.checked && index !== -1) {
                    rows_selected.splice(index, 1);
                    if (rows_selected.length == 0) {
                        $('#assign_btn').prop('disabled', true);
                    }
                }

                if (this.checked) {
                    $row.addClass('selected');
                } else {
                    $row.removeClass('selected');
                }

                // Update state of "Select all" control
                updateDataTableSelectAllCtrl(articleNotAssign);

                // Prevent click event from propagating to parent
                e.stopPropagation();
            });

            // Handle click on table cells with checkboxes
            $('#notAssignTable').on('click', 'tbody td, thead th:first-child', function(e) {
                $(this).parent().find('input[type="checkbox"]').trigger('click');
            });

            $('thead input[name="select_all"]', articleNotAssign.table().container()).on('click', function(e) {
                if (this.checked) {
                    $('#notAssignTable tbody input[type="checkbox"]:not(:checked)').trigger('click');
                } else {
                    $('#notAssignTable tbody input[type="checkbox"]:checked').trigger('click');
                }

                // Prevent click event from propagating to parent
                e.stopPropagation();
            });

            articleNotAssign.on('draw', function() {
                // Update state of "Select all" control
                updateDataTableSelectAllCtrl(articleNotAssign);
            });

            //on submit
            $('#assign_form').on('submit', function(e) {
                e.preventDefault();
                var form = this;

                // Iterate over all selected checkboxes
                $.each(rows_selected, function(index, rowId) {
                    // Create a hidden element
                    $(form).append(
                        $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'article_id[]')
                        .val(rowId)
                    );
                });

                var formData = new FormData(this);
                $.ajax({
                    url: '{{ route('assign.store') }}',
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Article has been assigned',
                            showConfirmButton: true,
                        }).then(isConfirmed => {
                            rows_selected = [];
                            $('#assign_btn').prop('disabled', true);
                            articleNotAssign.ajax.reload();
                            articleAssign.ajax.reload();
                        })
                    },
                    error: function(data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            showConfirmButton: true,
                        })
                    }
                });
            });
            // })




            // Assign Table
            function updateDataTableSelectAllCtrlAssign(table) {
                var $table = table.table().node();
                var $chkbox_all = $('tbody input[type="checkbox"]', $table);
                var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
                var chkbox_select_all = $('thead input[name="select_all_assign"]', $table).get(0);

                // If none of the checkboxes are checked
                if ($chkbox_checked.length === 0) {
                    chkbox_select_all.checked = false;
                    if ('indeterminate' in chkbox_select_all) {
                        chkbox_select_all.indeterminate = false;
                    }

                    // If all of the checkboxes are checked
                } else if ($chkbox_checked.length === $chkbox_all.length) {
                    chkbox_select_all.checked = true;
                    if ('indeterminate' in chkbox_select_all) {
                        chkbox_select_all.indeterminate = false;
                    }

                    // If some of the checkboxes are checked
                } else {
                    chkbox_select_all.checked = true;
                    if ('indeterminate' in chkbox_select_all) {
                        chkbox_select_all.indeterminate = true;
                    }
                }
            }

            // $(document).ready(function(){
            var articleAssign = $('#AssignTable').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div> '
                },
                order: [
                    [1, 'asc']
                ],
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                ajax: {
                    url: '{{ route('assigned.table') }}',
                    data: {
                        project_id: project_id,
                        user_id: user_id
                    },
                    type: 'GET',
                },
                columns: [{
                        title: '<input type="checkbox" id="head_cb_assign" name="select_all_assign" value="1">',
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return '<input type="checkbox" class="cb_child_assign" value="' + data +
                                '">';
                        }
                    },
                    {
                        title: 'ID - No',
                        data: 'no',
                        name: 'no',
                    },
                    {
                        title: 'Title',
                        data: 'title',
                        name: 'title',
                        render: function(data, type, row) {
                            return '<span style="white-space:normal">' + data + "</span>";
                        }
                    },
                    {
                        title: 'Year',
                        data: 'year',
                        name: 'year',
                    },
                    {
                        title: 'Publication',
                        data: 'publication',
                        name: 'publication',
                        render: function(data, type, row) {
                            return '<span style="white-space:normal">' + data + "</span>";
                        }
                    },
                    {
                        title: 'Authors',
                        data: 'authors',
                        name: 'authors',
                        render: function(data, type, row) {
                            return '<span style="white-space:normal">' + data + "</span>";
                        }
                    },
                ],
                rowCallback: function(row, data, dataIndex) {
                    var rowId = data['id'];
                    console.log(rowId);
                    if ($.inArray(rowId, rows_selected2) !== -1) {
                        $(row).find('input[type="checkbox"]').prop('checked', true);
                        $(row).addClass('selected');
                    }
                },
            }).on('init.dt', function() {
                $('#AssignTable').wrap('<div class="dataTables_scroll" />');
            });

            $('#AssignTable tbody').on('click', '.cb_child_assign', function(e) {
                var $row = $(this).closest('tr');

                // Get row data
                var data = articleAssign.row($row).data();

                // Get row ID
                var rowId = data['id'];

                // Determine whether row ID is in the list of selected row IDs
                var index = $.inArray(rowId, rows_selected2);

                // If checkbox is checked and row ID is not in list of selected row IDs
                if (this.checked && index === -1) {
                    rows_selected2.push(rowId);
                    $('#delete_btn').prop('disabled', false);
                    // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
                } else if (!this.checked && index !== -1) {
                    rows_selected2.splice(index, 1);
                    if (rows_selected2.length == 0) {
                        $('#delete_btn').prop('disabled', true);
                    }
                }

                if (this.checked) {
                    $row.addClass('selected');
                } else {
                    $row.removeClass('selected');
                }

                // Update state of "Select all" control
                updateDataTableSelectAllCtrlAssign(articleAssign);

                // Prevent click event from propagating to parent
                e.stopPropagation();
            });

            // Handle click on table cells with checkboxes
            $('#AssignTable').on('click', 'tbody td, thead th:first-child', function(e) {
                $(this).parent().find('input[type="checkbox"]').trigger('click');
            });

            $('thead input[name="select_all_assign"]', articleAssign.table().container()).on('click', function(e) {
                if (this.checked) {
                    $('#AssignTable tbody input[type="checkbox"]:not(:checked)').trigger('click');
                } else {
                    $('#AssignTable tbody input[type="checkbox"]:checked').trigger('click');
                }

                // Prevent click event from propagating to parent
                e.stopPropagation();
            });

            articleNotAssign.on('draw', function() {
                // Update state of "Select all" control
                updateDataTableSelectAllCtrlAssign(articleAssign);
            });

            $('#rm_assign_form').on('submit', function(e) {
                e.preventDefault();
                var form = this;

                // Iterate over all selected checkboxes
                $.each(rows_selected2, function(index, rowId) {
                    // Create a hidden element
                    $(form).append(
                        $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'article_id[]')
                        .val(rowId)
                    );
                });

                var formData = new FormData(this);
                $.ajax({
                    url: '{{ route('assign.remove') }}',
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Article has been removed',
                            showConfirmButton: true,
                        }).then(isConfirmed => {
                            rows_selected2 = [];
                            $('#delete_btn').prop('disabled', true);
                            articleAssign.ajax.reload();
                            articleNotAssign.ajax.reload();
                        })
                    },
                    error: function(data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            showConfirmButton: true,
                        })
                    }
                });
            });
        });
    </script>
@endsection
