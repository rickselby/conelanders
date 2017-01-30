@extends('page')

@section('content')

    <h1>{{ $session->name }}: {{ $session->cars->pluck('short_name')->implode(', ') }}</h1>

    <div class="table-responsive">
        <table class="table sortable table-condensed">
            <thead>
            <tr>
                <th>Pos</th>
                <th>Driver</th>
                @if ($session->cars->count() > 1)
                    <th data-sorter="false">Car</th>
                @endif
                @if ($sectors > 1)
                    @for($i = 1; $i <= $sectors; $i++)
                        <th>Sector {{ $i }}</th>
                    @endfor
                @endif
                <th data-sorter="false">Laptime</th>
                <th data-sorter="false">Gap to 1st</th>
                <th data-sorter="false">Gap ahead</th>
            </tr>
            </thead>
            <tbody>
            @foreach($results AS $entrant)
                <tr>
                    <th>{{ $entrant->position }}</th>
                    <th>
                        @include('ac-hotlap.driver.name', ['driver' => $entrant->driver])
                    </th>
                    @if ($session->cars->count() > 1)
                        <td style="white-space: nowrap">{{ $entrant->car->short_name ?: '??' }}</td>
                    @endif
                    @if ($sectors > 1)
                        @for($i = 0; $i < $sectors; $i++)
                            <td>{{ isset($entrant->sectors[$i]) ? Times::toString($entrant->sectors[$i]) : '' }}</td>
                        @endfor
                    @endif
                    <td class="time">
                        <strong>
                            {{ $entrant->time ? Times::toString($entrant->time) : '' }}
                        </strong>
                    </td>
                    <td class="time">{{ ($entrant->timeBehindFirst > 0) ? '+'.Times::toString($entrant->timeBehindFirst) : '-' }}</td>
                    <td class="time">{{ ($entrant->timeBehindAhead > 0) ? '+'.Times::toString($entrant->timeBehindAhead) : '-' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
