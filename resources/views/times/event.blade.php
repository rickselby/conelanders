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
        @include('import-in-progress')
    @elseif(!$event->isComplete())
        @include('event-not-complete')
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

        @include('tablesorter')

        @include('times.legend')

    @endif {{-- importing test --}}

@endsection