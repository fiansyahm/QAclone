@extends('layouts.main')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/formpart.css') }}">
@endsection
@section('container')
    <h1>Assessed Article</h1>
    <hr>

    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="d-flex justify-content-end div_select">
                    <select class="select_project" name="project">
                        <option disabled selected>-- Select Project --</option>
                        <option value="all">All Project</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table id="assessment_table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID - No</th>
                            <th>Title</th>
                            <th>Project Name</th>
                            <th>Year</th>
                            <th>Publication</th>
                            <th>Authors</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal for Score Button --}}
    <div class="modal fade" id="modalScore" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title-score" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="score_table" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Question</th>
                                    <th>Answer</th>
                                </tr>
                            </thead>
                            <tbody id="scoreData">
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

    {{-- Modal for edit button --}}
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="editCloseButton"></button>
                </div>
                <div class="modal-body">
                    <a id="previewFile" class="btn btn-sm btn-secondary mb-2" target="_blank"><ion-icon name="document-attach"></ion-icon> Preview Article</a>
                    <a id="previewLink" class="btn btn-sm btn-secondary mb-2" target="_blank"><ion-icon name="link"></ion-icon> Article Link</a>
                    <span id="noPreview" class="badge alert-secondary mb-2">No Preview Available</span>
                    <button class="btn btn-sm btn-primary mb-2" data-bs-target="#detailArticleModal" data-bs-toggle="modal" data-bs-dismiss="modal" id="detailArticleBtn"><ion-icon name="search-circle"></ion-icon> View Article Detail</button>
                    <div class="progress">
                        <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="50"
                            class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar"
                            style="width: 0%"></div>
                    </div>
                    <div id="qbox-container">
                        <form action="/dashboard/reviewer/assessment/update" class="needs-validation assessment_form"
                            id="form-wrapper" method="post" name="form-wrapper" novalidate>
                            @csrf
                            <input type="hidden" name="project_id" id="project_id">
                            <input type="hidden" name="article_id" id="article_id">
                            <div id="steps-container">
                                @foreach ($questionaires as $question)
                                    <div class="step">
                                        <h3>{{ $question->name }}</h3>
                                        <h4>{{ $question->question }}</h4>
                                        <input type="hidden" name="questionaire_id[]" value="{{ $question->id }}">
                                        <div class="form-check ps-0 q-box">
                                            <div class="q-box__question">
                                                <input class="form-check-input question__input"
                                                    id="q_{{ $loop->iteration }}_pos" name="{{ $question->name }}"
                                                    type="radio" value="1">
                                                <label class="form-check-label question__label"
                                                    for="q_{{ $loop->iteration }}_pos">{{ $question->pos_answer }}</label>
                                            </div>
                                            <div class="q-box__question">
                                                <input class="form-check-input question__input"
                                                    id="q_{{ $loop->iteration }}_net" name="{{ $question->name }}"
                                                    type="radio" value="0">
                                                <label class="form-check-label question__label"
                                                    for="q_{{ $loop->iteration }}_net">{{ $question->net_answer }}</label>
                                            </div>
                                            <div class="q-box__question">
                                                <input class="form-check-input question__input"
                                                    id="q_{{ $loop->iteration }}_neg" name="{{ $question->name }}"
                                                    type="radio" value="-1">
                                                <label class="form-check-label question__label"
                                                    for="q_{{ $loop->iteration }}_neg">{{ $question->neg_answer }}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="step">
                                    <div class="mt-1">
                                        <div class="closing-text">
                                            <h4>Assessment Selesai! Apakah Anda Yakin Ingin Mengubah Penilaian Anda?</h4>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Question</th>
                                                        <th>Answer</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($questionaires as $item)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $item->question }}</td>
                                                            <td id="summary{{ $loop->iteration }}"></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <p>Click tombol submit untuk melanjutkan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="q-box__buttons">
                                <button id="prev-btn" type="button">Previous</button>
                                <button id="next-btn" type="button">Next</button>
                                <button id="submit-btn" type="submit">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailArticleModal" data-bs-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="detailArticleTitle"></h5>
              <button type="button" class="btn-close" data-bs-target="#exampleModal" data-bs-toggle="modal" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="form-label">
                            <label>Publisher</label>
                            <input type="text" class="form-control" id="publisher" readonly>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-label">
                            <label>Publication</label>
                            <input type="text" class="form-control" id="publication" readonly>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-label">
                            <label>Year</label>
                            <input type="text" class="form-control" id="year" readonly>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-label">
                            <label>Type</label>
                            <input type="text" class="form-control" id="type" readonly>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-label">
                            <label>E-Database</label>
                            <input type="text" class="form-control" id="edatabase" readonly>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-label">
                            <label>E-Database 2</label>
                            <input type="text" class="form-control" id="edatabase_2" readonly>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-label">
                            <label>Keyword</label>
                            <input type="text" class="form-control" id="keyword" readonly>
                        </div>
                    </div>
                    <div class="col-md-8 mb-2">
                        <div class="form-label">
                            <label>Keywords</label>
                            <input type="text" class="form-control" id="keywords" readonly>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-label">
                            <label>Index</label>
                            <input type="text" class="form-control" id="index" readonly>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-label">
                            <label>Quartile</label>
                            <input type="text" class="form-control" id="quartile" readonly>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-label">
                            <label>Authors</label>
                            <textarea name="" id="authors" readonly class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-label">
                            <label>Nation First Author</label>
                            <input type="text" class="form-control" id="nation_first_author" readonly>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-label">
                            <label>Cited</label>
                            <input type="text" class="form-control" id="cited" readonly>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-label">
                            <label>Cited Google Scholar</label>
                            <input type="text" class="form-control" id="cited_gs" readonly>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-label">
                            <label>Language</label>
                            <input type="text" class="form-control" id="language" readonly>
                        </div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="form-label">
                            <label>Citing</label>
                            <ul id="citing"></ul>
                        </div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="form-label">
                            <label>Original References</label>
                            <textarea class="form-control" id="references_ori" readonly rows="10"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="form-label">
                            <label>References Filter</label>
                            <textarea class="form-control" id="references_filter" readonly rows="10"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="form-label">
                            <label>Abstract</label>
                            <textarea class="form-control" id="abstract" readonly rows="10"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-primary" data-bs-target="#exampleModal" data-bs-toggle="modal" data-bs-dismiss="modal">Back to Assessment</button>
            </div>
          </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        // Inisiasi Select2
        $(document).ready(function() {
            $('.select_project').select2({
                width: '50%',
            });
        })

        // Inisiasi DataTable
        var table = $('#assessment_table').DataTable({
            processing: true,
            serverSide: true,
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
            },
            ajax: '{{ route('assessed.table') }}',
            columns: [{
                    data: 'no',
                    name: 'no'
                },
                {
                    data: 'title',
                    name: 'title',
                    render: function(data, type, row) {
                        return '<span style="white-space: normal;">' + data + '</span>"';
                    }
                },
                {
                    data: 'project_name',
                    name: 'project_name'
                },
                {
                    data: 'year',
                    name: 'year'
                },
                {
                    data: 'publication',
                    name: 'publication',
                    render: function(data, type, row) {
                        return '<span style="white-space: normal;">' + data + '</span>"';
                    }
                },
                {
                    data: 'authors',
                    name: 'authors',
                    render: function(data, type, row) {
                        return '<span style="white-space: normal;">' + data + '</span>"';
                    }
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]
        });

        // Filter Data berdasarkan Project
        $('.select_project').on('change', function() {
            var project_id = $(this).val();

            $('#assessment_table').DataTable().destroy();
            $('#assessment_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('assessed.table') }}',
                    data: {
                        project_id: project_id
                    }
                },
                columns: [{
                        data: 'no',
                        name: 'no',
                    },
                    {
                        data: 'title',
                        name: 'title',
                        render: function(data, type, row) {
                            return '<span style="white-space: normal;">' + data + '</span>"';
                        }
                    },
                    {
                        data: 'project_name',
                        name: 'project_name'
                    },
                    {
                        data: 'year',
                        name: 'year'
                    },
                    {
                        data: 'publication',
                        name: 'publication',
                        render: function(data, type, row) {
                            return '<span style="white-space: normal;">' + data + '</span>"';
                        }
                    },
                    {
                        data: 'authors',
                        name: 'authors',
                        render: function(data, type, row) {
                            return '<span style="white-space: normal;">' + data + '</span>"';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });
        })

        // Score Modal
        table.on('click', '.scoreArticle', function() {
            var title = $(this).data('title');
            var no = $(this).data('no');
            $('.modal-title-score').html('Score For<br>' + no + ' - ' + title)
            var article_id = $(this).data('id');
            $.ajax({
                url: '{{ route('reviewer.score') }}',
                type: 'GET',
                data: {
                    article_id: article_id
                },
                dataType: 'JSON',
                success: function(result){
                    var html = '';
                    var no = 1;

                    $.each(result, function(key, value){
                        var pos_answer = value.pos_answer;
                        var net_answer = value.net_answer;
                        var neg_answer = value.neg_answer;
                        var answer = '';
                        $.each(value.article_user_questionaire, function(key, value){
                            if (value.article_user == null) {
                                answer += '';
                            }
                            else {
                                if (value.score == 1) {
                                    answer += pos_answer;
                                }
                                else if (value.score == 0) {
                                    answer += net_answer;
                                }
                                else if (value.score == -1) {
                                    answer += neg_answer;
                                }
                            }
                        });
                        // sum += value.article_user_questionaire.score;
                        html += '<tr>';
                        html += '<td>' + no++ + '</td>';
                        html += '<td><span style="white-space: normal;">' + value.question + '</span></td>';
                        html += '<td><span style="white-space: normal;">' + answer + '</span></td>';
                        html += '</tr>';
                    });
                    $('#scoreData').html(html);
                },
                error: function(error){
                    console.log(error);
                }
            })
        });

        // Ambil edit score
        table.on('click', '#btn_edit_assessment', function(){
            let article_id = $(this).data('article_id');

            $.ajax({
                url: '{{ route('reviewer.editScore') }}',
                method: "GET",
                data: {
                    article_id: article_id
                },
                success: function(data){
                    for (let index = 0; index < data.length; index++) {
                        no = index + 1;
                        if (data[index].article_user_questionaire[0].score == 1) {
                            $('#q_' + no + '_pos').prop('checked', true);
                            $('#summary' + no).text(data[index].pos_answer);
                        }
                        else if(data[index].article_user_questionaire[0].score == 0) {
                            $('#q_' + no + '_net').prop('checked', true);
                            $('#summary' + no).text(data[index].net_answer);
                        }
                        else if(data[index].article_user_questionaire[0].score == -1) {
                            $('#q_' + no + '_neg').prop('checked', true);
                            $('#summary' + no).text(data[index].neg_answer);
                        }
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        });

        // Modal Edit
        table.on('click', '#btn_edit_assessment', function(event) {
            $('#exampleModal').modal('show');
            var id = $(this).data('article_id');
            var title = $(this).data('title');
            var link = $(this).data('link');
            var file = $(this).data('file');
            var no = $(this).data('no');
            var project_id = $(this).data('project_id');

            // add to modal title
            $('#exampleModal').find('.modal-title').html('Assess Article<br>' + no + ' - ' + title);
            $('#exampleModal').find('#article_id').val(id);
            $('#exampleModal').find('#project_id').val(project_id);

            if (link == '' && file == '') {
                $('#previewLink').addClass('d-none');
                $('#previewFile').addClass('d-none');
                $('#noPreview').removeClass('d-none');
            }
            else if (link == '' && file != '') {
                $('#previewLink').addClass('d-none');
                $('#previewFile').removeClass('d-none');
                $('#noPreview').addClass('d-none');
                var urlFile = '{{ URL::asset('storage/article/') }}' + '/' + file;
                $('#previewFile').attr('href', urlFile);
            }
            else if (link != '' && file == '') {
                $('#previewLink').removeClass('d-none');
                $('#previewFile').addClass('d-none');
                $('#noPreview').addClass('d-none');
                $('#previewLink').attr('href', link);
            }
        });

        let step = document.getElementsByClassName('step');
        let prevBtn = document.getElementById('prev-btn');
        let nextBtn = document.getElementById('next-btn');
        let submitBtn = document.getElementById('submit-btn');
        let form = document.getElementsByTagName('form')[0];
        let preloader = document.getElementById('preloader-wrapper');
        let bodyElement = document.querySelector('body');
        let succcessDiv = document.getElementById('success');

        let current_step = 0;
        const stepCount = {{ count($questionaires) }}
        step[current_step].classList.add('d-block');
        if (current_step == 0) {
            prevBtn.classList.add('d-none');
            submitBtn.classList.add('d-none');
            nextBtn.classList.add('d-inline-block');
        }

        const progress = (value) => {
            document.getElementsByClassName('progress-bar')[0].style.width = `${value}%`;
        }

        nextBtn.addEventListener('click', () => {
            // if radio button is not checked then show alert
            if (step[current_step].querySelector('input[type="radio"]:checked') == null) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select an answer!',
                });
                return false;
            }
            current_step++;
            let previous_step = current_step - 1;
            if ((current_step > 0) && (current_step <= stepCount)) {
                prevBtn.classList.remove('d-none');
                prevBtn.classList.add('d-inline-block');
                step[current_step].classList.remove('d-none');
                step[current_step].classList.add('d-block');
                step[previous_step].classList.remove('d-block');
                step[previous_step].classList.add('d-none');
                if (current_step == stepCount) {
                    submitBtn.classList.remove('d-none');
                    submitBtn.classList.add('d-inline-block');
                    nextBtn.classList.remove('d-inline-block');
                    nextBtn.classList.add('d-none');
                }
            } else {
                if (current_step > stepCount) {
                    form.onsubmit = () => {
                        return true
                    }
                }
            }
            progress((100 / stepCount) * current_step);
        });


        prevBtn.addEventListener('click', () => {
            if (current_step > 0) {
                current_step--;
                let previous_step = current_step + 1;
                prevBtn.classList.add('d-none');
                prevBtn.classList.add('d-inline-block');
                step[current_step].classList.remove('d-none');
                step[current_step].classList.add('d-block');
                step[previous_step].classList.remove('d-block');
                step[previous_step].classList.add('d-none');
                if (current_step < stepCount) {
                    submitBtn.classList.remove('d-inline-block');
                    submitBtn.classList.add('d-none');
                    nextBtn.classList.remove('d-none');
                    nextBtn.classList.add('d-inline-block');
                    prevBtn.classList.remove('d-none');
                    prevBtn.classList.add('d-inline-block');
                }
            }

            if (current_step == 0) {
                prevBtn.classList.remove('d-inline-block');
                prevBtn.classList.add('d-none');
            }
            progress((100 / stepCount) * current_step);
        });

        //on close modal progress bar reset
        $('#editCloseButton').on('click', function() {
            $('#exampleModal').modal('hide');
            progress(0);
            current_step = 0;
            step[current_step].classList.add('d-block');
            step[current_step].classList.remove('d-none');
            nextBtn.classList.remove('d-none');
            nextBtn.classList.add('d-inline-block');

            for (let i = 1; i < stepCount + 1; i++) {
                step[i].classList.add('d-none');
                step[i].classList.remove('d-block');
            }

            let radioBtns = document.querySelectorAll('input[type="radio"]');
            radioBtns.forEach((radioBtn) => {
                radioBtn.checked = false;
            });
            if (current_step == 0) {
                prevBtn.classList.add('d-none');
                submitBtn.classList.add('d-none');
                nextBtn.classList.add('d-inline-block');
            }
        });

        for (let i = 1; i <= stepCount; i++) {
            $('#q_' + i + '_pos').on('click', function() {
                // get the text of the label
                let label = $(this).parent().text();
                $('#summary' + i + '').text(label);
            });
            $('#q_' + i + '_net').on('click', function() {
                let label = $(this).parent().text();
                $('#summary' + i + '').text(label);
            });
            $('#q_' + i + '_neg').on('click', function() {
                let label = $(this).parent().text();
                $('#summary' + i + '').text(label);
            });
        }

        $('.assessment_form').on('submit', function(e){
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: '{{ route('reviewer.updateScore') }}',
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data){
                    $('#exampleModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Assessment submitted successfully!',
                        showConfirmButton: true,
                    }).then(isConfirmed => {
                        table.ajax.reload();
                    });
                },
                error: function(error){
                    console.log(error);
                }
            });
        })

        $('#detailArticleBtn').on('click', function(){
            var article_id = $('#article_id').val();
            var project_id = $('#project_id').val();
            $.ajax({
                url: '{{ route('find.detailArticle') }}',
                type: 'GET',
                data: {
                    article_id: article_id,
                    project_id: project_id
                },
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                    var title = data.article.title;
                    var id_no = data.article.id + ' - ' + data.article.no;
                    var citing = data.citing;
                    var citing_new = data.citing_new;

                    $('#detailArticleTitle').html(id_no + '<br>' + title);
                    $('#publisher').val(data.article.publisher);
                    $('#publication').val(data.article.publication);
                    // foreach authors
                    var authors = '';
                    $.each(data.authors, function(key, value){
                        authors += '- ' + value + '\n';
                    });
                    $('#authors').val(authors);
                    $('#authors').attr('rows', data.authors.length);
                    $('#year').val(data.article.year);
                    $('#type').val(data.article.type);
                    $('#edatabase').val(data.article.edatabase);
                    $('#edatabase_2').val(data.article.edatabase_2);
                    $('#keyword').val(data.article.keyword);
                    $('#keywords').val(data.article.keywords);
                    $('#index').val(data.article.index);
                    $('#quartile').val(data.article.quartile);
                    $('#nation_first_author').val(data.article.nation_first_author);
                    $('#cited').val(data.article.cited);
                    $('#cited_gs').val(data.article.cited_gs);
                    $('#language').val(data.article.language);
                    var citing = '';
                    $.each(data.citing, function(key, value){
                        citing += '<li><a href="/dashboard/reviewer/article/detail/' + value.encrypted_id + '" target="_blank">' + value.title + '</a></li>';
                    });
                    $('#citing').html(citing);
                    // $('#citing').attr('rows', data.citing.length);
                    $('#references_ori').val(data.article.references_ori);
                    $('#references_filter').val(data.article.references_filter);
                    $('#abstract').val(data.article.abstracts);
                }
            })
        })
    </script>
@endsection