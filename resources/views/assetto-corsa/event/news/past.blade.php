@foreach($events AS $event)
    <li class="list-group-item">

        Results for sessions at
        <a href="{{ route('assetto-corsa.standings.event', [$event['event']->championship, $event['event']]) }}">{{ $event['event']->fullName }}</a>
        have been released:
        <ul>
        @foreach($event['sessions'] AS $session)
            <li>{{ $session->name }}</li>
        @endforeach
        </ul>

        @if ($event['event']->completeAt == $event['sessions'][0]->release)
            The event is now complete, and results are available.
        @else
            The event results have not yet been released; there are more session results to come.
        @endif
    </li>
@endforeach
