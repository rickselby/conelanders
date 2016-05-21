@extends('page')

@section('header')
    <div class="jumbotron">
        <h1>Dirt Rally</h1>
    </div>
@endsection

@section('content')

    @if ($currentChampionship)
        <h2>Current Championship: {{ $currentChampionship->name }}</h2>
        <div class="btn-group btn-group-lg" role="group">
            <a class="btn btn-primary"  role="button"
               href="{{ route('dirt-rally.standings.championship', $currentChampionship) }}">Driver Standings</a>
            <a class="btn btn-info"  role="button"
               href="{{ route('dirt-rally.nationstandings.championship', $currentChampionship) }}">Nation Standings</a>
            <a class="btn btn-info"  role="button"
               href="{{ route('dirt-rally.times.championship', $currentChampionship) }}">Total Times</a>
            <a class="btn btn-info"  role="button"
               href="{{ route('dirt-rally.championship.show', $currentChampionship) }}">Results</a>
        </div>
    @endif

    @if (count($completeChampionships))
        <h2>Previous Championships</h2>
        @foreach($completeChampionships AS $championship)
            <h3>{{ $championship->name }}</h3>
            <div class="btn-group" role="group">
                <a class="btn btn-primary"  role="button"
                   href="{{ route('dirt-rally.standings.championship', $championship) }}">Driver Standings</a>
                <a class="btn btn-info"  role="button"
                   href="{{ route('dirt-rally.nationstandings.championship', $championship) }}">Nation Standings</a>
                <a class="btn btn-info"  role="button"
                   href="{{ route('dirt-rally.times.championship', $championship) }}">Total Times</a>
                <a class="btn btn-info"  role="button"
                   href="{{ route('dirt-rally.championship.show', $championship) }}">Results</a>
            </div>
        @endforeach
    @endif

@endsection