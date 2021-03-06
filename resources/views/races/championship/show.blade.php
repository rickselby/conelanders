@extends('page')

@section('header')
    <div class="page-header">
        <h1>Championship: {{ $championship->name }}</h1>
    </div>
@endsection

@section('content')

    @can('update', $championship)
    {!! Form::open(['route' => ['races.category.championship.destroy', $championship->category, $championship], 'method' => 'delete', 'class' => 'form-inline']) !!}
        <a class="btn btn-small btn-warning"
           href="{{ route('races.category.championship.edit', [$championship->category, $championship]) }}">Edit championship</a>
        {!! Form::submit('Delete championship', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}
    @endcan

    @can('manage-admins', $championship)
        <h3>Championship Admins</h3>

        <div class="container-fluid">
            <ul class="list-group list-group-condensed">
                @forelse($championship->admins AS $user)
                    <li class="row list-group-item">
                        <div class="col-sm-9">
                            {{ $user->name }}
                        </div>
                        <div class="col-sm-3 text-right">
                            {!! Form::open(['route' => ['races.category.championship.admin.destroy', $championship->category, $championship, $user], 'method' => 'delete', 'class' => 'form-inline']) !!}
                            {!! Form::submit('Delete', array('class' => 'btn btn-danger btn-xs')) !!}
                            {!! Form::close() !!}
                        </div>
                    </li>
                @empty
                    <li class="list-group-item">
                        No admins specified
                    </li>
                @endforelse
            </ul>
        </div>

        {!! Form::open(['route' => ['races.category.championship.admin.store', $championship->category, $championship], 'class' => 'form-horizontal']) !!}
        <div class="form-group form-group-sm">
            <div class="col-sm-4">
                {!! Form::select('user', $users->pluck('name', 'id'), null, ['class' => 'form-control']) !!}
            </div>
            <div class="col-sm-8">
                {!! Form::submit('Add user as admin', array('class' => 'btn btn-success btn-sm')) !!}
            </div>
        </div>
        {!! Form::close() !!}

    @endcan

    <h2>Entrants</h2>

    <div class="btn-group" role="group">
        <a class="btn btn-small btn-primary"
           href="{{ route('races.championship.entrant.index', $championship) }}">Manage Entrants</a>
        <a class="btn btn-small btn-primary"
           href="{{ route('races.championship.team.index', $championship) }}">Manage Teams</a>
    </div>

    <h2>Events</h2>
    <p>
        <a class="btn btn-small btn-info"
           href="{{ route('races.championship.event.create', $championship) }}">Add a new event</a>
    </p>

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Name</th>
            <th>Sessions</th>
            <th>Scheduled Time</th>
            <th>Full Results Released?</th>
        </tr>
        </thead>
        <tbody>
        @forelse($championship->events AS $event)
            <tr class="{{ $event->canBeReleased() ? '' : 'info' }}">
                <td>
                    <a href="{{ route('races.championship.event.show', [$championship, $event]) }}">
                        {{ $event->name }}
                    </a>
                </td>
                <td>{{ count($event->sessions) }}</td>
                <td>{{ \Times::userTimezone($event->time) }}</td>
                <td>
                    @if ($event->canBeReleased())
                        Yes
                    @elseif ($event->completeAt)
                        {{ \Times::userTimezone($event->completeAt) }}
                    @else
                        No
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No events</td>
            </tr>
        @endforelse
        </tbody>
    </table>

@endsection