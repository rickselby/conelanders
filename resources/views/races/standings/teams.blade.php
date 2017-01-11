@extends('page')
@push('stylesheets')
    <link href="{{ route('races.championship.entrant.css', $championship) }}" rel="stylesheet" />
@endpush

@section('content')

    <h2>Team Standings</h2>

    @if (\RacesChampionships::shownBeforeRelease($championship))
        @include('unreleased')
    @endif

    @include('races.standings.team-table', ['table' => $points])

@endsection
