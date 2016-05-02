@extends('page')

@section('header')
    <div class="page-header">
        <h1>{{ $system->name }} Points System</h1>
    </div>
@endsection

@section('content')

    {!! Form::open(['route' => ['dirt-rally.points-system.destroy', $system], 'method' => 'delete', 'class' => 'form-inline']) !!}
    <a class="btn btn-small btn-warning"
       href="{{ route('dirt-rally.points-system.edit', $system) }}">Edit System</a>
    {!! Form::submit('Delete System', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}

    <div class="col-xs-4 col-xs-offset-4">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>Position</th>
                <th>Event Points</th>
                <th>Stage Points</th>
            </tr>
            </thead>
            <tbody>
            @for($i = 1; $i <= max(count($points['event']), count($points['stage'])); $i++)
                <tr>
                    <th>{{ $i }}</th>
                    <td>
                        {{ $points['event'][$i] or '' }}
                    </td>
                    <td>
                        {{ $points['stage'][$i] or '' }}
                    </td>
                </tr>
            @endfor
            </tbody>
        </table>
    </div>

@endsection