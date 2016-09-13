@extends('page')

@push('stylesheets')
<link href="{{ route('assetto-corsa.championship.entrant.css', $championship) }}" rel="stylesheet" />
@endpush

@section('content')

    <h2>Entrants</h2>

    <p>
        <a class="btn btn-small btn-info" href="{{ route('assetto-corsa.championship.entrant.create', $championship) }}">Add another entrant</a>
    </p>

    @if (count($championship->teams))
        @include('assetto-corsa.championship.entrant.index-teams')
    @else
        @include('assetto-corsa.championship.entrant.table', ['entrants' => $championship->entrants()])
    @endif

@endsection