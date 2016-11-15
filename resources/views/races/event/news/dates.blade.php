@foreach($events AS $event)
    <li class="list-group-item">
        <a href="{{ route('races.results.event', [$event->championship->category, $event->championship, $event]) }}">
            {{ $event->fullName }}
        </a>
        is taking place.
    </li>
@endforeach
