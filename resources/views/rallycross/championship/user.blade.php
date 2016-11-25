<h2>RallyCross</h2>

    <ul class="list-group">
        @forelse($championships AS $championship)
            <li class="list-group-item {{ $championship->isComplete() ? '' : 'list-group-item-info' }}">
                <a href="{{ route('rallycross.championship.show', $championship) }}">
                    {{ $championship->name }}
                </a>
            </li>
        @empty
            <li class="list-group-item">
                No championships.
            </li>

        @endforelse
    </ul>
