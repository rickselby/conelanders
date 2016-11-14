@foreach($championships AS $championship)
    <li class="list-group-item">
        The
        <a href="{{ route('races.standings.drivers', $championship) }}">{{ $championship->shortName }} Championship</a>
        is now complete.
    </li>
@endforeach
