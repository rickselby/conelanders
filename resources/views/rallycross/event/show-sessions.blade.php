
<p>
    <a class="btn btn-small btn-info"
       href="{{ route('rallycross.championship.event.session.create', [$event->championship, $event]) }}">Add a new session</a>
</p>

@if (count($event->sessions) == 0 && count($otherEvents))
    {!! Form::open(['route' => ['rallycross.championship.event.copy-sessions', $event->championship, $event], 'class' => 'form-horizontal']) !!}
    <div class="form-group">
        {!! Form::label('from-event', 'Copy sessions from', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-9">
            {!! Form::select('from-event', $otherEvents, null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-3"></div>
        <div class="col-sm-9">
            {!! Form::submit('Copy Sessions', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>
    {!! Form::close() !!}
@endif


<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>Name</th>
        <th>Heat?</th>
    </tr>
    </thead>
    <tbody id="sessions">
    @forelse($event->sessions AS $session)
        <tr class="{{ $session->canBeReleased() ? '' : 'info' }}" data-id="{{ $session->id }}">
            <td>
                <span class="glyphicon glyphicon-menu-hamburger sort-handle" aria-hidden="true"></span>
                <a href="{{ route('rallycross.championship.event.session.show', [$event->championship, $event, $session]) }}">
                    {{ $session->name }}
                </a>
            </td>
            <td>
                {{ $session->heat ? 'Yes' : '' }}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="2">No sessions</td>
        </tr>
    @endforelse
    </tbody>
</table>

<script type="text/javascript">
    $("#sessions").sortable({
        store: {
            get: function(sortable) {
                return [];
            },
            set: function(sortable) {
                console.log(sortable.toArray());
                $.post(
                        "{{ route('rallycross.championship.event.sort-sessions', [$event->championship, $event]) }}",
                        {
                            order: sortable.toArray()
                        }
                );
            }
        }
    });
</script>
