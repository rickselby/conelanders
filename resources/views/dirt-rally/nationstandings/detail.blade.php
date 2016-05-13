@extends('page')

@section('content')

    @if ($event->importing)
        @include('dirt-rally.import-in-progress')
    @elseif(!$event->isComplete())
        @include('dirt-rally.event-not-complete')
    @else

        <h3>
            @include('nation.image', $nation)
            {{ $nation->name }}
        </h3>

        <table class="table sortable table-bordered table-hover">
            <thead>
            <tr>
                <th>Pos.</th>
                <th>Driver</th>
                @foreach($event->stages AS $stage)
                    <th colspan="2">
                        {{ count($event->stages) > 4 ? $stage->order : $stage->name }}
                    </th>
                @endforeach
                <th colspan="2">Overall</th>
                <th data-sortInitialOrder="desc">Total Points</th>
            </tr>
            </thead>
            <tbody>
            @foreach($results AS $detail)
                <tr>
                    <th>{{ $detail['position'] }}</th>
                    <th>
                        <a href="{{ route('driver.show', $detail['entity']) }}">
                            {{ $detail['entity']->name }}
                        </a>
                    </th>
                    @foreach($event->stages AS $stage)
                        <td class="time text-muted">{{ Times::toString($detail['stageTimes'][$stage->id]) }}</td>
                        <td class="points">{{ $detail['stagePoints'][$stage->id] or '' }}</td>
                    @endforeach
                    <td class="time text-muted">{{ Times::toString($detail['total']['time']) }}</td>
                    <td class="points">{{ $detail['eventPoints'] }}</td>
                    <td class="points">{{ $detail['total']['points'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    @endif {{-- importing test --}}

@endsection
