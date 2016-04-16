@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li><a href="{{ route('times.index') }}">Total Time</a></li>
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
                    <a href="{{ route('times.season', [$championship->id, $season->id]) }}">
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
                <th>{{ $detail['driver']->name }}</th>
                @foreach($seasons AS $season)
                    <td class="{{ $detail['dnss'][$season->id] ? 'danger' : '' }}">
                        {{ StageTime::toString($detail['seasons'][$season->id]) }}
                    </td>
                @endforeach
                <td>{{ StageTime::toString($detail['total']) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @include('tablesorter')

    @include('times.legend')

@endsection