@extends('page')

@section('content')

    @if (!$event->canBeReleased() && \RXEvent::canBeShown($event))
        @include('unreleased')
    @endif

    <div>
        {{-- Tabs --}}
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#summary" aria-controls="summary" role="tab" data-toggle="tab">
                    Summary
                </a>
            </li>
            @foreach($event->heats AS $session)
                <li role="presentation">
                    <a href="#session-{{ $session->id }}" aria-controls="{{ $session->name }}" role="tab" data-toggle="tab">
                        {{ $session->name }}
                    </a>
                </li>
            @endforeach
            <li role="presentation">
                <a href="#heats" aria-controls="summary" role="tab" data-toggle="tab">
                    Heats Results
                </a>
            </li>
            @foreach($event->notHeats AS $session)
                <li role="presentation">
                    <a href="#session-{{ $session->id }}" aria-controls="{{ $session->name }}" role="tab" data-toggle="tab">
                        {{ $session->name }}
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- Content --}}
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="summary">
                @include('rallycross.results.summary.summary')
            </div>
            <div role="tabpanel" class="tab-pane" id="heats">
                @include('rallycross.results.heats.summary')
            </div>
            @foreach($event->sessions as $session)
                <div role="tabpanel" class="tab-pane" id="session-{{ $session->id }}">
                    @include('rallycross.results.session.session')
                </div>
            @endforeach
        </div>
    </div>

@endsection
