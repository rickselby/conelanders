@extends('page')

@push('stylesheets')
<link href="{{ route('races.championship.entrant.css', $championship) }}" rel="stylesheet" />
@endpush

@section('content')

    <h2>Entrants</h2>

    <p>
        <a class="btn btn-small btn-info" href="{{ route('races.championship.entrant.create', $championship) }}">Add another entrant</a>
    </p>

    @if (count($championship->teams))
        @include('races.championship.entrant.index-teams')
    @else
        @include('races.championship.entrant.table', ['entrants' => $championship->entrants()])
    @endif

@endsection