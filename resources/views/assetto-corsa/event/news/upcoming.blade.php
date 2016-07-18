@foreach($events AS $event)
    <li class="list-group-item">

        Results for sessions at
        <a href="{{ route('assetto-corsa.standings.event', [$event['event']->championship, $event['event']]) }}">{{ $event['event']->fullName }}</a>
        will be released:
        <ul>
        @foreach($event['sessions'] AS $session)
            <li>{{ $session->name }}</li>
        @endforeach
        </ul>
    </li>
@endforeach
