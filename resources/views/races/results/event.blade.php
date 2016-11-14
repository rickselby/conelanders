@extends('page')

@push('stylesheets')
<link href="{{ route('races.championship.entrant.css', $event->championship) }}" rel="stylesheet" />
@endpush

@section('content')

    @if (!$event->canBeReleased() && \RacesEvent::canBeShown($event))
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
            @foreach($event->sessions AS $session)
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
                @include('races.results.summary.summary')
            </div>
            @foreach($event->sessions as $session)
                <div role="tabpanel" class="tab-pane" id="session-{{ $session->id }}">
                    @include('races.results.session.session')
                </div>
            @endforeach
        </div>
    </div>

@endsection
