@extends('layouts.main')
@section('container')

<!-- CSS -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

<div class="container">
<!-- HTML -->
    <a data-fancybox="gallery" href="{{$src}}">
    <img class="img-fluid" src="{{$src}}" alt="Gambar 1" />
    </a>
</div>



@endsection