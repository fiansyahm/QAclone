@extends('layouts.main')
@section('container')
<!-- CSS -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

<h1 class="text-center">Hubungan Antar Penulis</h1>
<div class="row">
    <div class="col-md-6">
    <h1 class="text-center mt-5">Graph Penulis</h1>
        <div class="container text-center">
        <!-- HTML -->
            <a data-fancybox="gallery" href="{{$src}}">
            <img class="img-fluid" src="{{$src}}" alt="Gambar 1"  id="my-image"/>
            </a>

            <script>
                document.getElementById('my-image').onerror = function() {
                    this.onerror = null;
                    this.src = 'https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif?20170503175831';
                };
            </script>

        </div>
    </div>
    <div class="col-md-6">
        <h1 class="text-center mt-5">Tabel Ranking Penulis</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Author</th>
                    <th>Rank</th>
                </tr>
            </thead>
            <tbody>
                @for($i = 0; $i < 20 && $i < count($author_ranks); $i++)
                <tr>
                    <th scope="row">{{$i+1}}</th>
                    <td>{{ $author_ranks[$i][0] }}</td>
                    <td>{{ $author_ranks[$i][1] }}</td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection