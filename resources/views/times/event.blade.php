@extends('page')

@section('header')
    <div class="page-header">
        <h1>
            <a href="{{ route('times.index') }}">Total Time</a>:
            <a href="{{ route('times.season', [$event->season->id]) }}">{{ $event->season->name }}</a>:
            {{ $event->name }}
        </h1>
    </div>
@endsection

@section('content')

    @if ($event->importing)
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">Import in progress</h3>
            </div>
            <div class="panel-body">
                The results for this event are currently being imported; they will be available once the import is complete.
            </div>
        </div>
    @else

        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Pos.</th>
                <th>Driver</th>
                @foreach($event->stages AS $stage)
                    <th>
                        <a href="{{ route('times.stage', [$event->season->id, $event->id, $stage->id]) }}">
                            {{ $stage->name }}
                        </a>
                    </th>
                @endforeach
                <th>Overall</th>
            </tr>
            </thead>
            <tbody>
            @foreach($times['times'] AS $position => $detail)
            <tr>
                <th>{{ $position + 1 }}</th>
                <th>{{ $detail['driver']->name }}</th>
                @foreach($event->stages AS $stage)
                    <td class="{{ isset($detail['worst'][$stage->order]) ? 'text-muted' : '' }}">
                        {{ StageTime::toString($detail['stageTimes'][$stage->order]) }}
                    </td>
                @endforeach
                <td>{{ StageTime::toString($detail['total']) }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>

    @endif {{-- importing test --}}

@endsection