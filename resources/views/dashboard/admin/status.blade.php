@extends('layouts.main')
@section('container')
    <h1>Article Status</h1>
    <hr>

    <a href="/dashboard/admin/project"><button type="button" class="btn btn-secondary mb-2">
            <ion-icon name="arrow-back"></ion-icon> Back
        </button>
    </a>

    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="d-flex justify-content-end div_select">
                    <select class="select_status" name="status">
                        <option disabled selected>-- Choose Status --</option>
                        <option value="all">All Article</option>
                        <option value="not_assign">Not Assign</option>
                        <option value="part_assessed">Partially Assessed</option>
                        <option value="full_assessed">Fully Assessed</option>
                        <option value="not_assessed">Not Assessed</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table id="article_status_table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID - No</th>
                            <th>Article</th>
                            <th>User Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($articles as $article)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $article->id }} - {{ $article->no }}</td>
                                <td><span style="white-space: normal;">{{ $article->title }}</span></td>
                                <td>
                                    @if ($article->article_user->count() == 0)
                                        <span class="d-flex justify-content-center badge alert-danger">Not Assign</span>
                                    @else
                                        <a href="javascript:;" id="user_status"
                                            class="d-flex justify-content-center badge alert-primary" data-bs-toggle="modal"
                                            data-bs-target="#userModal"
                                            data-no="{{ $article->no }}"
                                            data-id="{{ $article->id }}"
                                            data-title="{{ $article->title }}"
                                            data-pid="{{ decrypt(request()->pid) }}">
                                            <ion-icon name="eye-sharp"></ion-icon>&nbsp;Show
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- User Modal --}}
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>User</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="userTable">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var table = $('#article_status_table').DataTable();
        $('.select_status').select2({
            width: '25%',
            // turn off the searching
            placeholder: 'Choose Status',
            minimumResultsForSearch: Infinity,
        });

        $('.select_status').on('change', function() {
            var status = $(this).val();
            var project_id = {{ decrypt(request()->pid) }};
            console.log(project_id);

            $.ajax({
                url: '{{ route('find.status') }}',
                method: 'GET',
                data: {
                    status: status,
                    project_id: project_id
                },
                success: function(data) {
                    table.clear().draw();
                    let no = 1;
                    for (let index = 0; index < data.length; index++) {
                        table.row.add([
                            no,
                            data[index].id + ' - ' + data[index].no,
                            '<span style="white-space: normal;">' + data[index].title +
                            '</span>',
                            data[index].article_user.length == 0 ?
                            '<span class="badge alert-danger d-flex justify-content-center">Not Assign</span>' :
                            '<a href="javascript:;" id="user_status"\
                                class="d-flex justify-content-center badge alert-primary" data-bs-toggle="modal"\
                                data-bs-target="#userModal"\
                                data-id_no="' + data[index].id + ' - ' + data[index].no + '"\
                                data-id="' + data[index].id + '"\
                                data-pid="' + project_id + '">\
                                <ion-icon name="eye-sharp"></ion-icon> Show\
                            </a>'
                        ]).draw();
                        no++;
                    }
                }
            })
        })

        table.on('click', 'tbody #user_status', function() {
            var no = $(this).data('no');
            var title = $(this).data('title');
            var article_id = $(this).data('id');
            var project_id = $(this).data('pid');

            $('.modal-title').html('User Status Article<br>' + no + ' - ' + title);

            $.ajax({
                url: '{{ route('find.userArticle') }}',
                type: 'GET',
                data: {
                    article_id: article_id,
                    project_id: project_id
                },
                success: function(data) {
                    console.log(data);
                    $('#userTable').empty();
                    let no = 1;
                    for (let index = 0; index < data.length; index++) {
                        $('#userTable').append(
                            '<tr>' +
                            '<td>' + no + '</td>' +
                            '<td>' + data[index].user.name + '</td>' +
                            '<td>' + (data[index].is_assessed == true ?
                                '<span class="badge alert-success">Assessed</span>' :
                                '<span class="badge alert-warning">Not Assessed</span>') + '</td>' +
                            '</tr>'
                        );
                        no++;
                    }
                }
            })
        })
    </script>
@endsection
