@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{ route('dirt-rally.times.index') }}">Total Time</a></li>
        <li class="active">{{ $championship->name }}</li>
    </ol>
@endsection

@section('content')

    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Driver</th>
            @foreach($seasons AS $season)
                <th>
                    <a href="{{ route('dirt-rally.times.season', [$championship, $season]) }}" class="tablesorter-noSort">
                        {{ $season->name }}
                    </a>
                </th>
            @endforeach
            <th>Total Time</th>
        </tr>
        </thead>
        <tbody>
        @foreach($times AS $position => $detail)
            <tr>
                <th>{{ $position + 1 }}</th>
                <th>
                    <a href="{{ route('driver.show', $detail['driver']) }}">
                        {{ $detail['driver']->name }}
                    </a>
                </th>
                @foreach($seasons AS $season)
                    <td class="{{ $detail['dnss'][$season->id] ? 'danger' : '' }}">
                        @if ($season->isComplete())
                            {{ StageTime::toString($detail['seasons'][$season->id]) }}
                        @else
                            <em class="text-muted">
                                {{ StageTime::toString($detail['seasons'][$season->id]) }}
                            </em>
                        @endif
                    </td>
                @endforeach
                <td>{{ StageTime::toString($detail['total']) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @include('tablesorter')

    @include('dirt-rally.times.legend')

@endsection