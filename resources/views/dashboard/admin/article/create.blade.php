@extends('layouts.main')

@section('container')
    <h1>Add New Article</h1>
    <hr>
    <a href="/dashboard/admin/project/{{ encrypt($project_id) }}"><button type="button" class="btn btn-secondary mb-2">
            <ion-icon name="arrow-back"></ion-icon> Back
        </button></a>

    <div class="card" id="body">
        <div class="card-header">
            <h6 class="mb-0">New Article</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('article.store') }}" class="row g-3" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="project_id" value="{{ $project_id }}">
                @csrf
                <div class="col-12">
                    <label for="kode_artikel" class="form-label">Article Code</label>
                    <small class="text-danger">*required</small>
                    <input class="form-control @error('kode_artikel') is-invalid @enderror" type="text"
                        placeholder="ex: A1" name="kode_artikel" aria-label="default input example"
                        value="{{ old('kode_artikel') }}" required>
                    @error('kode_artikel')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="file" class="form-label">Upload File</label>
                    <small class="text-danger">*pdf only</small>
                    <input class="form-control @error('file') is-invalid @enderror" type="file" id="formFile"
                        name="file" value="{{ old('file') }}" accept="application/pdf">
                    @error('file')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <button id="clearFile" type="button" class="btn alert-danger btn-sm mt-1" disabled>
                        <ion-icon name="close-circle"></ion-icon> Clear Choosen File
                    </button>
                </div>
                <div class="col-6">
                    <label for="link" class="form-label">or Insert Link</label>
                    <input class="form-control @error('link') is-invalid @enderror" type="text" name="link"
                        id="link" placeholder="ex: https://www.google.com">
                    @error('link')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button id="previewBtn" type="button" class="btn btn-primary mt-3  justify-content-center"
                    data-bs-toggle="modal" data-bs-target="#exampleModal" disabled>
                    No Preview Available
                </button>
                <a href="#" target="_blank" id="linkBtn"
                    class=" text-white btn btn-primary mt-3 justify-content-center d-none">
                    Go To Link
                </a>
                <button id="linkNoValid" type="button" class="btn btn-primary mt-3  justify-content-center d-none"
                    disabled>Link Not Valid</button>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Preview File</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
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
                <div class="col-12">
                    <label for="title" class="form-label">Title</label>
                    <small class="text-danger">*required</small>
                    <input class="form-control @error('title') is-invalid @enderror" type="text"
                        placeholder="Article Title" name="title" aria-label="default input example"
                        value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="publication" class="form-label">Publication</label>
                    <small class="text-danger">*required</small>
                    <input class="form-control @error('publication') is-invalid @enderror" type="text"
                        placeholder="Publication" name="publication" aria-label="default input example"
                        value="{{ old('publication') }}" required>
                    @error('publication')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="index" class="form-label">Index Journal</label>
                    <input class="form-control" type="text" placeholder="Index" name="index"
                        aria-label="default input example" value="{{ old('index') }}">
                </div>
                <div class="col-6">
                    <label for="quartile" class="form-label">Quartile</label>
                    <input class="form-control" type="text" placeholder="If Scopus" name="quartile"
                        aria-label="default input example" value="{{ old('quartile') }}">
                </div>
                <div class="col-6">
                    <label for="year" class="form-label">Year</label>
                    <small class="text-danger">*required</small>
                    <input class="form-control @error('year') is-invalid @enderror" type="text" placeholder="YYYY"
                        name="year" aria-label="default input example" id="year" value="{{ old('year') }}"
                        required autocomplete="off">
                    @error('year')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="authors" class="form-label">Authors</label>
                    <small class="text-danger">*required</small>
                    <input class="form-control @error('authors') is-invalid @enderror" type="text"
                        placeholder="Authors" name="authors" aria-label="default input example"
                        value="{{ old('authors') }}" required>
                    @error('authors')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="nation_first_author" class="form-label">Nation First Author</label>
                    <select name="nation_first_author" id="nation_author" class="form-control">
                        <option></option>
                        @foreach ($countries as $country)
                            <option value="{{ $country['name']['common'] }}"
                                {{ old('nation_first_author') == $country['name']['common'] ? 'selected' : '' }}>
                                {{ $country['name']['common'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label for="abstract" class="form-label">Abstract</label>
                    <textarea rows="10" name="abstract" class="form-control" placeholder="Abstract" spellcheck="false"
                        data-ms-editor="true">{{ old('abstract') }}</textarea>
                </div>
                <div class="col-12">
                    <label for="keywords" class="form-label">Keywords</label>
                    <textarea rows="5" name="keywords" class="form-control" placeholder="Keyword" spellcheck="false"
                        data-ms-editor="true">{{ old('keywords') }}</textarea>
                </div>
                <div class="col-6">
                    <label for="language" class="form-label">Language of Article</label>
                    <small class="text-danger">*required</small>
                    <input class="form-control @error('language') is-invalid @enderror" type="text"
                        placeholder="Language" name="language" aria-label="default input example"
                        value="{{ old('language') }}" required>
                    @error('language')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="article_type" class="form-label">Type of Article</label>
                    <small class="text-danger">*required</small>
                    <select class="form-select @error('article_type') is-invalid @enderror" name="article_type"
                        aria-label="Default select example" required>
                        <option disabled selected>Select Type</option>
                        <option value="journal" {{ old('article_type') == 'journal' ? 'selected' : '' }}>Journal</option>
                        <option value="proceeding" {{ old('article_type') == 'proceeding' ? 'selected' : '' }}>Proceeding
                        </option>
                    </select>
                    @error('article_type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="publisher" class="form-label">Publisher</label>
                    <small class="text-danger">*required</small>
                    <input class="form-control @error('publisher') is-invalid @enderror" type="text"
                        placeholder="Publisher" name="publisher" aria-label="default input example"
                        value="{{ old('publisher') }}" required>
                    @error('publisher')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="references_ori" class="form-label">References Original</label>
                    <textarea rows="10" name="references_ori" class="form-control" placeholder="References Original"
                        spellcheck="false" data-ms-editor="true">{{ old('references_ori') }}</textarea>
                </div>
                <div class="col-12">
                    <label for="references_filter" class="form-label">References Filter</label>
                    <textarea rows="5" name="references_filter" class="form-control" placeholder="References Filter"
                        spellcheck="false" data-ms-editor="true">{{ old('references_filter') }}</textarea>
                </div>
                <div class="col-4">
                    <label for="cited" class="form-label">Cited</label>
                    <small class="text-danger">*required</small>
                    <input class="form-control @error('cited') is-invalid @enderror" type="number" placeholder="Cited"
                        name="cited" aria-label="default input example" value="{{ old('cited') }}" required>
                    @error('cited')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-4">
                    <label for="cited_gs" class="form-label">Cited Google Scholar</label>
                    <small class="text-danger">*required</small>
                    <input class="form-control @error('cited_gs') is-invalid @enderror" type="number"
                        placeholder="Cited" name="cited_gs" aria-label="default input example"
                        value="{{ old('cited_gs') }}" required>
                    @error('cited_gs')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-4">
                    <label for="cited_other" class="form-label">Citing Other Article</label>
                    <small class="text-danger">*required</small>
                    <input class="form-control @error('cited_other') is-invalid @enderror" type="text"
                        placeholder="Cited" name="cited_other" aria-label="default input example"
                        value="{{ old('cited_other') }}" required>
                    @error('cited_other')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="keyword" class="form-label">Keyword</label>
                    <small class="text-danger">*required</small>
                    <input class="form-control @error('keyword') is-invalid @enderror" type="text"
                        placeholder="Keyword" name="keyword" aria-label="default input example"
                        value="{{ old('keyword') }}" required>
                    @error('keyword')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="edatabase" class="form-label">Edatabase</label>
                    <small class="text-danger">*required</small>
                    <input class="form-control @error('edatabase') is-invalid @enderror" type="text"
                        placeholder="Database 1" name="edatabase" aria-label="default input example"
                        value="{{ old('edatabase') }}" required>
                    @error('edatabase')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="edatabase2" class="form-label">Edatabase 2</label>
                    <input class="form-control" type="text" placeholder="Database 2" name="edatabase2"
                        aria-label="default input example" value="{{ old('edatabase2') }}">
                </div>

                {{-- Make button on the bottom right corner --}}
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <ion-icon name="save"></ion-icon> Save Article
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function isValidUrl(url) {
            const urlPattern = /^(https?|ftp):\/\/[^\s/$.?#].[^\s]*$/i;
            return urlPattern.test(url);
        }
        $(function() {
            $('#nation_author').select2({
                placeholder: "Select Nation",
                allowClear: true
            });
            $('#year').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                autoclose: true,
                orientation: "bottom",
                container: '#body'
            });

            $('input[name="file"]').change(function() {
                var file = $(this).prop('files')[0];
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#pdf_preview').attr('src', e.target.result);
                }

                if (file == undefined) {
                    $('input[name="link"]').prop('disabled', false);
                    $('#previewBtn').prop('disabled', true);
                    $('#previewBtn').text('No Preview Available');
                } else {
                    $('input[name="link"]').prop('disabled', true);
                    $('#previewBtn').prop('disabled', false);
                    $('#previewBtn').text('Preview File');
                    $('#clearFile').prop('disabled', false);
                    reader.readAsDataURL(file);
                }
            });

            $('#link').on('keyup', function() {
                var link = $(this).val();
                if (link == '') {
                    $('input[name="file"]').prop('disabled', false);
                    $('#linkBtn').addClass('d-none');
                    $('#linkNoValid').addClass('d-none');
                    $('#previewBtn').removeClass('d-none');
                } else {
                    $('input[name="file"]').prop('disabled', true);
                    $('#previewBtn').addClass('d-none');
                    if (isValidUrl(link)) {
                        $('#linkBtn').attr('href', link);
                        $('#linkBtn').removeClass('d-none');
                        $('#linkNoValid').addClass('d-none');
                    } else {
                        $('input[name="file"]').prop('disabled', false);
                        $('#linkBtn').addClass('d-none');
                        $('#previewBtn').addClass('d-none');
                        $('#linkNoValid').removeClass('d-none');
                    }
                }
            })

            $('#clearFile').on('click', function() {
                $('input[name="file"]').val('');
                $('#clearFile').prop('disabled', true);
                $('#pdf_preview').attr('src', '');
                $('input[name="link"]').prop('disabled', false);
                $('#previewBtn').prop('disabled', true);
                $('#previewBtn').text('No Preview Available');
            })
        })
    </script>
@endsection
