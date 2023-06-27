@extends('layouts.main')
@section('container')
<div class="container">
    <h1 class="text-center">Tabel Perhitungan</h1>
    <div class="table-responsive">
    <table class="table">
            <thead>
                <tr>
                    <th class="text-center" scope="row" rowspan="{{count($rank)}}">Iterasi\Penulis</th>
                    @foreach ($authors as $i)
                        <th scope="col">{{$i}}</th>
                    @endforeach 
                </tr>
            </thead>
            <tbody>
            <?php
                $val=1;
            ?>
                @foreach ($ranktable as $users)
                <tr>
                    @if ($val < count($rank) )
                        <th>{{$val++}}</th>
                    @else
                        <th>Rank</th>
                    @endif
                    

                    @foreach ($users as $user)    
                        <td>{{ $user}}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <h1 class="text-center mt-5">Tabel Ranking Penulis</h1>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Author</th>
                <th>Rank</th>
                <th>Nation</th>
            </tr>
        </thead>
        <tbody>
            @for($i = 0; $i < 20 && $i < count($author_ranks); $i++)
            <tr>
                <th scope="row">{{ $author_ranks[$i][0] }}</th>
                <td>{{ $author_ranks[$i][1] }}</td>
                <td>{{ $author_ranks[$i][2] }}</td>
                <td>{{ $author_ranks[$i][3] }}</td>
            </tr>
            @endfor
        </tbody>
    </table>

</div>
@endsection