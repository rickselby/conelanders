@extends('page')

@section('content')

    <h2>Categories</h2>

    <p>
        <a class="btn btn-small btn-info" href="{{ route('races.category.create') }}">Add a new category</a>
    </p>

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Name</th>
            <th>Championships</th>
        </tr>
        </thead>
        <tbody>
        @forelse($categories AS $category)
            <tr>
                <td>
                    <a href="{{ route('races.category.show', $category) }}">
                        {{ $category->name }}
                    </a>
                </td>
                <td>{{ count($category->championships) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2">No categories</td>
            </tr>
        @endforelse
        </tbody>
    </table>

@endsection