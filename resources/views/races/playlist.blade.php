<h2>Assetto Corsa</h2>

{!! Form::open(['route' => ['races.playlists'], 'class' => 'form-horizontal']) !!}

@foreach($sessions AS $session)
    <div class="form-group">
        {!! Form::label('playlist['.$session->id.']', $session->fullName, ['class' => 'col-sm-6 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::text('playlist['.$session->id.']', $session->playlist ? $session->playlist->link : '', ['class' => 'form-control']) !!}
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
