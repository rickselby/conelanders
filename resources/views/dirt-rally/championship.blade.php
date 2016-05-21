@extends('page')

@section('header')
    <div class="jumbotron">
        <h1>{{ $championship->name }}</h1>
    </div>
@endsection

@section('content')

    <div class="btn-group btn-group-lg" role="group">
        <a class="btn btn-primary"  role="button"
           href="{{ route('dirt-rally.standings.championship', $championship) }}">Driver Standings</a>
        <a class="btn btn-info"  role="button"
           href="{{ route('dirt-rally.nationstandings.championship', $championship) }}">Nation Standings</a>
        <a class="btn btn-info"  role="button"
           href="{{ route('dirt-rally.times.championship', $championship) }}">Total Times</a>
    </div>

@endsection