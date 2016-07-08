@extends('page')

@section('content')

    @if ($event->importing)
        @include('dirt-rally.import-in-progress')
    @else

        <h2>Event Results</h2>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Event not yet complete</h3>
            </div>
            <div class="panel-body">
                This event has not finished; results will change as drivers complete their runs
            </div>
        </div>

        @if ($event->last_import)
            <p>Last update: {{ $event->last_import->toDayDateTimeString() }} UTC</p>
        @endif

        <table class="table sortable table-bordered table-hover">
            <thead>
            <tr>
                <th>Pos.</th>
                <th>Driver</th>
                @foreach($event->stages AS $stage)
                <th>
                    SS{{ $stage->order }}
                </th>
                @endforeach
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($results AS $result)
            <tr>
                <th>{{ $result['position'] }}</th>
                <th>
                    <a href="{{ route('driver.show', $result['driver']) }}">
                        {{ $result['driver']->name }}
                    </a>
                </th>
                @foreach($event->stages AS $stage)
                <td class="time  {{ \Positions::colour(isset($result['stagePositions'][$stage->id]) ? $result['stagePositions'][$stage->id] : null) }}">
                    {{ Times::toString($result['stage'][$stage->order]) }}
                </td>
                @endforeach
                <td class="time">{{ Times::toString($result['total']) }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>

    @endif {{-- importing test --}}

@endsection
