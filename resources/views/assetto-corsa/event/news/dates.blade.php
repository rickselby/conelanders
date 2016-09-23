@foreach($events AS $event)
    <li class="list-group-item">
        <a href="{{ route('assetto-corsa.results.event', [$event->championship, $event]) }}">
            {{ $event->fullName }}
        </a>
        is taking place.
    </li>
@endforeach
