@extends('page')

@section('content')

    <h2>{{ $session->name }} :: {{ \App\Models\AssettoCorsa\AcSession::getTypes()[$session->type] }}</h2>

    {!! Form::open(['route' => ['assetto-corsa.championship.event.session.destroy', $session->event->championship, $session->event, $session], 'method' => 'delete', 'class' => 'form-inline']) !!}
    <a class="btn btn-small btn-warning"
       href="{{ route('assetto-corsa.championship.event.session.edit', [$session->event->championship, $session->event, $session]) }}">Edit Session</a>
    {!! Form::submit('Delete Session', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}

    <br />

    @include('assetto-corsa.session.show-file')

    @include('assetto-corsa.session.show-entrants')

    @if ($session->type == \App\Models\AssettoCorsa\AcSession::TYPE_RACE)
        @include('assetto-corsa.session.show-started')
    @endif

    @include('assetto-corsa.session.show-points')

    @if ($session->type == \App\Models\AssettoCorsa\AcSession::TYPE_RACE)
        @include('assetto-corsa.session.show-fastest-lap-points')
    @endif

    @include('assetto-corsa.session.show-release-date')

@endsection