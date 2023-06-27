@extends('layouts.main')

@section('container')
    <input type="hidden" id="project_id" value="{{ request()->id }}">
    <h1>Article Management {{ $project->project->project_name }}</h1>
    <hr />

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <a href="/dashboard/admin/project"><button type="button" class="btn btn-secondary mb-2">
            <ion-icon name="arrow-back"></ion-icon> Back
        </button>
    </a>


    <div class="card">
        <div class="card-header">
            <div class="col">
                <a href="/dashboard/admin/article/create?id={{ encrypt($project->project->id) }}"><button type="button"
                        class="btn btn-sm btn-success px-5 mb-2">
                        <ion-icon name="add-circle-outline"></ion-icon>Add Article
                    </button></a>
                <a href="/article/download"><button type="button" class="btn btn-sm btn-secondary px-5 mb-2">
                        <ion-icon name="document-outline"></ion-icon>Excel Template
                    </button></a>
                <button type="button" class="btn btn-sm btn-primary px-5 mb-2" id="import_excel" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">
                    <ion-icon name="cloud-upload-outline"></ion-icon>Import Excel
                </button>
                {{-- <a href="/exportResult/{{ request()->id }}"><button type="button" class="btn btn-sm btn-dark px-5 mb-2" data-project_id="{{ request()->id }}"
                    id="export_excel">
                    <ion-icon name="download-outline"></ion-icon>Export Score
                </button></a> --}}
                <button type="button" class="btn btn-sm btn-dark px-5 mb-2" data-project_id="{{ request()->id }}"
                    id="export_excel">
                    <ion-icon name="download-outline"></ion-icon>Export Score
                </button>
            </div>
            {{-- make select option in the corner right of the card --}}
            <div class="row">
                <div class="col-md-6">
                    <select class="form-select form-select-sm" aria-label="Default select example" name="edatabase"
                        id="edatabase">
                        <option selected disabled>Select Database</option>
                        @foreach ($article_db as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="article_table" class="table table-striped table-bordered" width="100%">
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
                </table>
            </div>
        </div>
    </div>

    <h1>Assessment Management</h1>
    <hr>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="assessment_table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Article Assigned</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal for score --}}
    <div class="modal fade" id="modalScore" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title-score" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table id="score_table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Question</th>
                                            <th>User</th>
                                            <th>Score</th>
                                            <th>Sum</th>
                                        </tr>
                                    </thead>
                                    <tbody id="scoreData">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="bar_chart_question"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal For Import -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_import_excel" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="hidden" name="project_id" value="{{ $project->project->id }}">
                            <label for="formFile" class="form-label">Import Excel</label>
                            <input class="form-control" type="file" id="formFile" name="excel_file">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal for detail article --}}
    <div class="modal fade" id="articleModal" tabindex="-1" aria-labelledby="articleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="article-modal-title" id="articleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="score_table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID - No</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="articleData">
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

    {{-- Modal for preview file --}}
    <div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title-file" id="fileModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="pdf_preview" src="" width="100%" height="400px"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal For Add File -->
    <div class="modal fade" id="addFileModal" tabindex="-1" aria-labelledby="addFileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title-addFile" id="addFileModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="addFileForm" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <input type="hidden" name="article_id" id="article_id">
                        <div>
                            <label for="formFile" class="form-label">Upload File</label>
                            <input class="form-control" type="file" id="formFile" name="file"
                                accept="application/pdf">
                            <button id="clearFile" type="button" class="btn alert-danger btn-sm mt-1" disabled>
                                <ion-icon name="close-circle"></ion-icon> Clear Choosen File
                            </button>
                        </div>
                        <div class="row mt-2">
                            <span class="d-flex justify-content-center">Or</span>
                        </div>
                        <div>
                            <label for="formFile" class="form-label">Input Link</label>
                            <input class="form-control" type="text" id="formFile" name="link"
                                placeholder="ex: https://www.google.com">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="submitBtn" class="btn btn-primary">Add File</button>
                            <button type="button" id="linkNoValidBtn" class="btn btn-primary d-none" disabled>Link Not
                                Valid</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var project_name = '{{ $project->project->project_name }}';
        $('#export_excel').on('click', function(){
            Swal.fire({
                title: 'Export Score?',
                text: 'Are you sure you want to export score for ' + project_name + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Export'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Exporting...',
                        html: 'Please wait while exporting score.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                    });
                    var project_id = $(this).data('project_id');
                    $.ajax({
                        url: '/exportResult',
                        type: 'GET',
                        data: {
                            project_id: project_id,
                        },
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function(response) {
                            Swal.close();
                            var blob = new Blob([response], {
                                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                            });
                            var link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = 'Score For ' + project_name + '.xlsx';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        },
                    })
                }
            })
        })
        $('#edatabase').select2();
        $('#form_import_excel').on('submit', function(e) {
            e.preventDefault();
            console.log('test');
            $('#exampleModal').modal('hide');

            // show loading sweeralert
            Swal.fire({
                title: 'Importing...',
                html: 'Please wait while importing article.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                },
            });

            // post with ajax
            $.ajax({
                url: '{{ route('article.import') }}',
                type: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(result) {
                    console.log(result);
                    Swal.close();
                    Swal.fire({
                        title: 'Success!',
                        text: 'Article has been imported.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(isConfirmed => {
                        table.ajax.reload();
                        //reset form
                        $('#form_import_excel')[0].reset();
                    })
                },
                error: function(result) {
                    console.log(result);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Article failed to import.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    })
                }
            });
        });

        var table = $('#article_table').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: '{{ route('article.table', $project->project->id) }}',
                type: 'GET',
            },
            language: {
                processing: '<div class="spinner-border text-primary" role="status"> <span class="visually-hidden">Loading...</span></div> '
            },
            columns: [{
                    title: 'ID - No',
                    data: 'no',
                    name: 'no',
                },
                {
                    title: 'Title',
                    data: 'title',
                    name: 'title',
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
                },
                {
                    title: 'Authors',
                    data: 'authors',
                    name: 'authors',
                },
                {
                    title: 'File',
                    data: 'article_file',
                    name: 'article_file',
                    orderable: false,
                    searchable: false,
                },
                {
                    title: 'Action',
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                },
            ],
            columnDefs: [{
                targets: [0, 1, 2, 3, 4, 5],
                className: 'text-center'
            }],
        }).on('init.dt', function() {
            $('#article_table').wrap('<div class="dataTables_scroll" />')
        });


        table.on('click', '.deleteArticle', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var project_id = $(this).data('project_id');
            Swal.fire({
                title: 'Delete Article',
                text: 'Are you sure you want to delete this article?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleting...',
                        html: 'Please wait while deleting article.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                    });
                    $.ajax({
                        url: '/deleteArticle',
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id,
                            project_id: project_id,
                        },
                        dataType: 'json',
                        success: function(response) {
                            Swal.close();
                            console.log(response);
                            if (response.error != null || response.error != undefined) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.error,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Article has been deleted.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(isConfirmed => {
                                    table.ajax.reload();
                                    assessment_table.ajax.reload();
                                })
                            }
                        },
                        error: function(result) {
                            console.log(result);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Article failed to delete.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            })
                        }
                    });
                }
            })
        });

        var options = {
            series: [],
            chart: {
                type: 'bar',
                height: 350,
                stacked: true,
                stackType: '100%'
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    dataLabels: {
                        total: {
                            enabled: true,
                            offsetX: 0,
                            style: {
                                fontSize: '13px',
                                fontWeight: 900
                            }
                        }
                    }
                },
            },
            title: {
                text: 'Article Score'
            },
        };
        var bar_question_chart = new ApexCharts(document.querySelector("#bar_chart_question"), options);
        bar_question_chart.render();

        table.on('click', '.scoreArticle', function() {
            var title = $(this).data('title');
            var no = $(this).data('no');
            $('.modal-title-score').html('Score For<br>' + no + ' - ' + title)
            var article_id = $(this).data('id');
            $.ajax({
                url: '{{ route('article.score') }}',
                type: 'GET',
                data: {
                    article_id: article_id
                },
                dataType: 'JSON',
                success: function(result) {
                    var html = '';
                    var no = 1;
                    var total = 0;

                    $.each(result, function(key, value) {
                        var sum = 0;
                        var name = '';
                        var score = '';
                        // console.log(value.article_user_questionaire[0].article_user.user.name);
                        $.each(value.article_user_questionaire, function(key, value) {
                            if (value.article_user == null) {
                                name += '';
                                score += '';
                                sum += 0;
                            } else {
                                sum += value.score;
                                name += value.article_user.user.name + '<br>';
                                score += value.score + '<br>';
                            }
                        });
                        // sum += value.article_user_questionaire.score;
                        html += '<tr>';
                        html += '<td>' + no++ + '</td>';
                        html += '<td style="white-space:normal">' + value.question + '</td>';
                        html += '<td class="name">' + name + '</td>';
                        html += '<td class="text-center">' + score + '</td>';
                        html += '<td class="text-center">' + sum + '</td>';
                        html += '</tr>';
                        total += sum;
                    });
                    html += '<tr>';
                    html += '<td colspan="3" class="text-center">Total</td>';
                    html += '<td colspan="2" class="text-center">' + total + '</td>';
                    html += '</tr>';
                    $('#scoreData').html(html);
                },
                error: function(error) {
                    console.log(error);
                }
            });

            $.ajax({
                url: '{{ route('find.articleScore') }}',
                type: 'GET',
                data: {
                    article_id: article_id
                },
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                    bar_question_chart.updateOptions({
                        series: [{
                                name: 'Positive',
                                data: data.pos_answer_question
                            },
                            {
                                name: 'Neutral',
                                data: data.net_answer_question
                            },
                            {
                                name: 'Negative',
                                data: data.neg_answer_question
                            }
                        ],
                        stroke: {
                            width: 1,
                            colors: ['#fff']
                        },
                        xaxis: {
                            categories: data.question_name,
                            labels: {
                                formatter: function(val) {
                                    return val
                                }
                            }
                        },
                        yaxis: {
                            title: {
                                text: undefined
                            },
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return val
                                }
                            },
                            marker: {
                                fillColors: ['#008FFB', '#00E396', '#FF0000']
                            }
                        },
                        fill: {
                            opacity: 1,
                            colors: ['#008FFB', '#00E396', '#FF0000']
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'left',
                            offsetX: 40,
                            markers: {
                                fillColors: ['#008FFB', '#00E396', '#FF0000']
                            }
                        }
                    })
                }
            })
        });

        var assessment_table = $('#assessment_table').DataTable({
            //no column sorting and searching false
            serverSide: true,
            processing: true,
            language: {
                processing: '<div class="spinner-border text-primary" role="status"> <span class="visually-hidden">Loading...</span></div> '
            },
            ajax: {
                url: '{{ route('assignment.table', $project->project->id) }}',
                type: 'GET',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                },
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'article',
                    name: 'article',
                    render: function(data, type, row) {
                        return '<div class="text-center">' + data + '</div>';
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                }
            ],
        }).on('init.dt', function() {
            $('#assessment_table').wrap('<div class="dataTables_scroll" />')
        });

        assessment_table.on('click', '#showArticle', function() {
            var name = $(this).data('name');
            $('.article-modal-title').text(name + ' Assigned Article');
            var user_id = $(this).data('id');
            var project_id = $(this).data('project');
            console.log(user_id);

            $.ajax({
                url: '{{ route('article.show') }}',
                type: 'GET',
                data: {
                    user_id: user_id,
                    project_id: project_id
                },
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                    $('#articleData').empty();
                    for (let index = 0; index < data.length; index++) {
                        $('#articleData').append(
                            '<tr>' +
                            '<td>' + data[index].article.id + ' - ' + data[index].article.no +
                            '</td>' +
                            '<td style="white-space:normal;">' + data[index].article.title +
                            '</td>' +
                            '<td>' + (data[index].is_assessed == true ?
                                '<span class="badge alert-success">Assessed</span>' :
                                '<span class="badge alert-danger">Not Assessed</span>') +
                            '</td>' +
                            '</tr>'
                        );
                    }
                }
            })
        })

        table.on('click', '#filePreview', function() {
            var title = $(this).data('title');
            var no = $(this).data('no');
            $('.modal-title-file').html('File For<br>' + no + ' - ' + title);
            var file = $(this).data('file');
            $('#pdf_preview').attr('src', file);
        })

        table.on('click', '#addFileBtn', function() {
            var title = $(this).data('title');
            var no = $(this).data('no');
            var id = $(this).data('id');
            $('.modal-title-addFile').html('Add File For<br>' + no + ' - ' + title);
            $('#article_id').val(id);
        })

        $('input[name="file"]').change(function() {
            var file = $(this).prop('files')[0];

            if (file != undefined) {
                $('input[name="link"]').prop('disabled', true);
                $('#clearFile').prop('disabled', false);
            } else {
                $('input[name="link"]').prop('disabled', false);
                $('#clearFile').prop('disabled', true);
            }
        })

        function isValidUrl(url) {
            const urlPattern = /^(https?|ftp):\/\/[^\s/$.?#].[^\s]*$/i;
            return urlPattern.test(url);
        }

        $('input[name="link"]').on('keyup', function() {
            var link = $(this).val();

            if (link != '') {
                $('input[name="file"]').prop('disabled', true);
                if (isValidUrl(link)) {
                    $('#submitBtn').removeClass('d-none')
                    $('#linkNoValidBtn').addClass('d-none')
                } else {
                    $('#submitBtn').addClass('d-none')
                    $('#linkNoValidBtn').removeClass('d-none')
                }
            } else {
                $('input[name="file"]').prop('disabled', false);
                $('#submitBtn').removeClass('d-none')
                $('#linkNoValidBtn').addClass('d-none')
            }
        })

        $('#clearFile').on('click', function() {
            $('input[name="file"]').val('');
            $('input[name="link"]').prop('disabled', false);
            $('#clearFile').prop('disabled', true);
        })

        $('#addFileForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            Swal.fire({
                title: 'Uploading File',
                html: 'Please wait while we upload your file',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                },
            });
            $.ajax({
                url: '{{ route('article.addFile') }}',
                type: 'POST',
                data: formData,
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    $('#addFileModal').modal('hide');
                    table.ajax.reload();
                    Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'File Added Successfully',
                    })
                    $('#addFileForm')[0].reset();
                    $('input[name="file"]').prop('disabled', false);
                    $('#clearFile').prop('disabled', true);
                    $('#submitBtn').removeClass('d-none')
                    $('#linkNoValidBtn').addClass('d-none')
                    $('input[name="link"]').prop('disabled', false);
                },
                error: function(error) {
                    console.log(error);
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong',
                    })
                }
            })
        })

        $('#edatabase').on('change', function() {
            var edatabase = $(this).val();

            $.ajax({
                url: '{{ route('article.table', $project->project->id) }}',
                type: 'GET',
                data: {
                    edatabase: edatabase,
                },
                dataType: 'JSON',
                success: function(data) {
                    table.ajax.url('{{ route('article.table', $project->project->id) }}?edatabase=' +
                        edatabase).load();
                }
            })
        })
    </script>
@endsection
