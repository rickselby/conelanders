@extends('page')

@section('content')

    @if ($event->importing)
        @include('dirt-rally.import-in-progress')
    @else

        <h2>Entrants</h2>

        @if ($event->last_import && !$event->isComplete())
            <p>Last update: {{ \Times::userTimezone($event->last_import) }}</p>
        @endif

        {!! Form::open(['route' => ['dirt-rally.championship.season.event.update-cars', $event->season->championship, $event->season, $event], 'class' => 'form-horizontal']) !!}

        <table class="table sortable table-bordered table-hover">
            <thead>
            <tr>
                <th>Driver</th>
                <th>Car</th>
            </tr>
            </thead>
            <tbody>
            @foreach($event->stages->first()->results AS $result)
            <tr>
                <th>{{ $result->driver->name }}</th>
                <td class="{{ !$result->car ? 'danger' : '' }}">
                    {!! Form::select(
                            'car['.$result->driver->id.']',
                             $cars->sortBy('name')->pluck('name', 'id'),
                              $result->car ? $result->car->id : null,
                               ['placeholder' => 'Pick a car...', 'class' => 'form-control']
                       ) !!}
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>

        <p>
            {!! Form::submit('Update Cars', ['class' => 'btn btn-primary']) !!}
        </p>

        {!! Form::close() !!}

    @endif {{-- importing test --}}

@endsection
