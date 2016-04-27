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

    {!! Form::open(['route' => ['dirt-rally.points-system.points', $system], 'class' => 'form-horizontal']) !!}

    <table class="table">
        <thead>
        <tr>
            <th>Position</th>
            <th>Event Points</th>
            <th>Stage Points</th>
        </tr>
        </thead>
        <tbody>
        @for($i = 1; $i <= max(count($points['event']), count($points['stage']), 49) + 1; $i++)
            <tr>
                <th>{{ $i }}</th>
                <td>
                    <input type="number" name="event[{{ $i }}]" value="{{ $points['event'][$i] or '' }}" />
                </td>
                <td>
                    <input type="number" name="stage[{{ $i }}]" value="{{ $points['stage'][$i] or '' }}" />
                </td>
            </tr>
        @endfor
        </tbody>
    </table>

    {!! Form::submit('Update points', ['class' => 'btn btn-primary']) !!}

    {!! Form::close() !!}

@endsection