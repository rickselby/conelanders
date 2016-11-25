@foreach($events AS $event)
    <li class="list-group-item">
        Results for <a href="{{ route('rallycross.results.event', [$event->championship, $event]) }}">{{ $event->fullName }}</a>
        have been released.
    </li>
@endforeach
