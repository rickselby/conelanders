@extends('page')

@section('header')
    <div class="page-header">
        <h1>{{ $system->name }} Points System</h1>
    </div>
@endsection

@section('content')

    {!! Form::open(['route' => ['assetto-corsa.points-system.destroy', $system], 'method' => 'delete', 'class' => 'form-inline']) !!}
    <a class="btn btn-small btn-warning"
       href="{{ route('assetto-corsa.points-system.edit', $system) }}">Edit System</a>
    {!! Form::submit('Delete System', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}

    <div class="col-xs-4 col-xs-offset-4">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>Position</th>
                <th>Race Points</th>
                <th>Laps Points</th>
            </tr>
            </thead>
            <tbody>
            @for($i = 1; $i <= max(count($points['race']), count($points['laps'])); $i++)
                <tr>
                    <th>{{ $i }}</th>
                    <td>
                        {{ $points['race'][$i] or '' }}
                    </td>
                    <td>
                        {{ $points['laps'][$i] or '' }}
                    </td>
                </tr>
            @endfor
            </tbody>
        </table>
    </div>

@endsection