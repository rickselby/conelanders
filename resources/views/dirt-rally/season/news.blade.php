@foreach($seasons AS $season)
    <li class="list-group-item">
        The <a href="{{ route('dirt-rally.standings.season', [$season->championship, $season]) }}">{{ $season->shortName }} season</a>
            is now complete.
    </li>
@endforeach
