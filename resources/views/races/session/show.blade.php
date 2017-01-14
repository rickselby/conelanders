@extends('page')

@section('header')
    <div class="page-header">
        <h1>{{ $session->fullName }}</h1>
    </div>
@endsection

@section('content')

    <h2>{{ $session->name }} :: {{ \App\Models\Races\RacesSession::getTypes()[$session->type] }}</h2>

    {!! Form::open(['route' => ['races.championship.event.session.destroy', $session->event->championship, $session->event, $session], 'method' => 'delete', 'class' => 'form-inline']) !!}
    <a class="btn btn-small btn-warning"
       href="{{ route('races.championship.event.session.edit', [$session->event->championship, $session->event, $session]) }}">Edit Session</a>
    {!! Form::submit('Delete Session', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}

    <br />

    @include('races.session.show-file')

    @include('races.session.show-entrants')

    @if ($session->type == \App\Models\Races\RacesSession::TYPE_RACE)
        @include('races.session.show-started')
    @endif

    @include('races.session.show-points')

    @include('races.session.show-penalties')

    @if ($session->type == \App\Models\Races\RacesSession::TYPE_RACE)
        @include('races.session.show-fastest-lap-points')
    @endif

    @include('races.session.show-release-date')

@endsection