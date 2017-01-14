@extends('page')
@push('stylesheets')
<link href="{{ route('races.championship.entrant.css', $championship) }}" rel="stylesheet" />
@endpush

@section('content')

    <h2>Team Standings</h2>

    @if (\RacesChampionships::shownBeforeRelease($championship))
        @include('unreleased')
    @endif

    @foreach($points AS $size => $table)
        <h3>{{ $size }} car teams</h3>

        @include('races.standings.team-table')

    @endforeach

@endsection
