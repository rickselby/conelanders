@foreach($categories AS $cat)
    <h2>{{ $cat['category']->name }}</h2>

    <ul class="list-group">

        @forelse($cat['championships'] AS $championship)
            <li class="list-group-item {{ $championship->isComplete() ? '' : 'list-group-item-info' }}">
                <a href="{{ route('races.category.championship.show', [$championship->category, $championship]) }}">
                    {{ $championship->name }}
                </a>
            </li>
        @empty
            <li class="list-group-item">
                No championships.
            </li>

        @endforelse
    </ul>

@endforeach
