@foreach($events AS $event)
    <li class="list-group-item">

        <a href="{{ route('dirt-rally.standings.event', [$event->season->championship, $event->season, $event]) }}">{{ $event->fullName }}</a>
        is now complete.
    </li>
@endforeach
