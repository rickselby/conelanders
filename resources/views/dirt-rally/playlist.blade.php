<h2>Dirt Rally</h2>

{!! Form::open(['route' => ['dirt-rally.playlists'], 'class' => 'form-horizontal']) !!}

@foreach($events AS $event)
    <div class="form-group">
        {!! Form::label('playlist['.$event->id.']', $event->fullName, ['class' => 'col-sm-6 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::text('playlist['.$event->id.']', $event->playlist ? $event->playlist->link : '', ['class' => 'form-control']) !!}
        </div>
    </div>
@endforeach

<div class="form-group">
    <div class="col-sm-6"></div>
    <div class="col-sm-6">
        {!! Form::submit('Update Playlists', ['class' => 'btn btn-primary']) !!}
    </div>
</div>

{!! Form::close() !!}
