@foreach($championships AS $championship)
    <li class="list-group-item">
        The
        <a href="{{ route('assetto-corsa.standings.championship', $championship) }}">{{ $championship->shortName }} Championship</a>
        is now complete.
    </li>
@endforeach
