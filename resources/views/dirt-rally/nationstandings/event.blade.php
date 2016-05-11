@extends('page')

@section('content')

    @if ($event->importing)
        @include('dirt-rally.import-in-progress')
    @elseif(!$event->isComplete())
        @include('dirt-rally.event-not-complete')
    @else

        <table class="table sortable table-bordered table-hover">
            <thead>
            <tr>
                <th>Pos.</th>
                <th>Nation</th>
                <th data-sortInitialOrder="desc">Total Points</th>
                <th>Drivers</th>
                <th data-sortInitialOrder="desc">Average Points</th>
            </tr>
            </thead>
            <tbody>
            @foreach($points AS $position => $detail)
            <tr>
                <th>{{ $detail['position'] }}</th>
                <th>
                    <img src="{{ route('nation.image', $detail['entity']) }}" alt="{{ $detail['entity']->name }}" />
                    {{ $detail['entity']->name }}
                </th>
                <td class="points">{{ $detail['total']['sum'] }}</td>
                <td class="text-center">{{ count($detail['points']) }}</td>
                <td class="points">{{ round($detail['total']['points'], 2) }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>

    @endif {{-- importing test --}}

@endsection
