@extends('page')

@section('header')
    <div class="page-header">
        <h1>
            <a href="{{ route('season.show', ['id' => $event->season->id]) }}">{{ $event->season->name }}</a>:
            {{ $event->name }}
        </h1>
    </div>
@endsection

@section('content')

    <h2>Stages</h2>
    <ul>
        @forelse($event->stages AS $stage)
            <li>
                <a href="{{ route('season.event.stage.show', ['season_id' => $event->season->id, 'event_id' => $event->id, 'stage_id' => $stage->id]) }}">
                    {{ $stage->name }}
                </a>
            </li>
        @empty
            <li>No stages</li>
        @endforelse
    </ul>

    <a class="btn btn-small btn-info"
       href="{{ route('season.event.stage.create', ['season_id' => $event->season->id, 'event_id' => $event->id]) }}">Add a stage</a>

@endsection