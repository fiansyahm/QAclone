@extends('layouts.main')
@section('container')
    <h1>{{ $article->title }}</h1>
    <hr>

    <div class="card">
        @if ($article->file != null)
            <div class="card-header">
                <a href="{{ URL::asset('storage/article/' . $article->file) }}" target="_blank" class="btn btn-sm btn-secondary"><ion-icon name="document-attach"></ion-icon>Preview File</a>
            </div>
        @elseif ($article->link != null)
            <div class="card-header">
                <a href="{{ $article->link }}" target="_blank" class="btn btn-sm btn-secondary"><ion-icon name="link"></ion-icon>Go To Link</a>
            </div>
        @else
            <div class="card-header">
                <span id="noPreview" class="badge alert-secondary mb-2">No Preview Available</span>
            </div>
        @endif
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <div class="form-label">
                        <label>Publisher</label>
                        <input type="text" class="form-control" id="publisher" value="{{ $article->publisher }}" readonly>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="form-label">
                        <label>Publication</label>
                        <input type="text" class="form-control" id="publication" value="{{ $article->publication }}" readonly>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-label">
                        <label>Year</label>
                        <input type="text" class="form-control" id="year" value="{{ $article->year }}" readonly>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-label">
                        <label>Type</label>
                        <input type="text" class="form-control" id="type" value="{{ $article->type }}" readonly>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-label">
                        <label>E-Database</label>
                        <input type="text" class="form-control" id="edatabase" value="{{ $article->edatabase }}" readonly>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-label">
                        <label>E-Database 2</label>
                        <input type="text" class="form-control" id="edatabase_2" value="{{ $article->edatabase_2 }}" readonly>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="form-label">
                        <label>Keyword</label>
                        <input type="text" class="form-control" id="keyword" value="{{ $article->keyword }}" readonly>
                    </div>
                </div>
                <div class="col-md-8 mb-2">
                    <div class="form-label">
                        <label>Keywords</label>
                        <input type="text" class="form-control" id="keywords" value="{{ $article->keywords }}" readonly>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="form-label">
                        <label>Index</label>
                        <input type="text" class="form-control" id="index" value="{{ $article->index }}" readonly>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="form-label">
                        <label>Quartile</label>
                        <input type="text" class="form-control" id="quartile" value="{{ $article->quartile }}" readonly>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="form-label">
                        <label>Authors</label>
                        <input type="text" class="form-control" id="authors" value="{{ $article->authors }}" readonly>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="form-label">
                        <label>Nation First Author</label>
                        <input type="text" class="form-control" id="nation_first_author" value="{{ $article->nation_first_author }}" readonly>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="form-label">
                        <label>Cited</label>
                        <input type="text" class="form-control" id="cited" value="{{ $article->cited }}" readonly>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="form-label">
                        <label>Cited Google Scholar</label>
                        <input type="text" class="form-control" id="cited_gs" value="{{ $article->cited_gs }}" readonly>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="form-label">
                        <label>Language</label>
                        <input type="text" class="form-control" id="language" value="{{ $article->language }}" readonly>
                    </div>
                </div>
                <div class="col-md-12 mb-2">
                    <div class="form-label">
                        <label>Citing</label>
                        <ul id="citing">
                            @foreach ($citing as $item)
                                <li><a href="/dashboard/reviewer/article/detail/{{ $item->encrypted_id }}" target="_blank">{{ $item->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-12 mb-2">
                    <div class="form-label">
                        <label>Original References</label>
                        <textarea class="form-control" id="references_ori" readonly rows="10">{{ $article->references_ori }}</textarea>
                    </div>
                </div>
                <div class="col-md-12 mb-2">
                    <div class="form-label">
                        <label>References Filter</label>
                        <textarea class="form-control" id="references_filter" readonly rows="10">{{ $article->references_filter }}</textarea>
                    </div>
                </div>
                <div class="col-md-12 mb-2">
                    <div class="form-label">
                        <label>Abstract</label>
                        <textarea class="form-control" id="abstract" readonly rows="10">{{ $article->abstracts }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection