@extends('page')

@push('stylesheets')
<link href="{{ route('assetto-corsa.championship-css', $event->championship) }}" rel="stylesheet" />
@endpush

@section('content')

    @if (!$event->canBeReleased() && \ACEvent::canBeShown($event))
        @include('unreleased')
    @endif

    <div>
        {{-- Tabs --}}
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#summary" aria-controls="summary" role="tab" data-toggle="tab">
                    Driver Summary
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
                @include('assetto-corsa.results.summary.driver')
            </div>
            @foreach($event->sessions as $session)
                <div role="tabpanel" class="tab-pane" id="session-{{ $session->id }}">
                @if (count($session->entrants) && (\ACSession::canBeShown($session) || Gate::check('assetto-corsa-admin')))

                    @if (Gate::check('assetto-corsa-admin') && !$session->canBeReleased())
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title">Admin Only View</h3>
                            </div>
                            <div class="panel-body">
                                Only admins and entrants can see this page, these results have not yet been released.
                                @if ($session->release)
                                    They will be released on {{ \Times::userTimezone($session->release) }}
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($session->playlist)
                        <div class="pull-right">
                            @include('playlist.button', ['playlist' => $session->playlist])
                        </div>
                    @endif

                    @if ($session->type == \App\Models\AssettoCorsa\AcSession::TYPE_RACE)
                        @include('assetto-corsa.results.session.race', ['session' => $session])
                    @elseif ($session->type == \App\Models\AssettoCorsa\AcSession::TYPE_QUALIFYING)
                        @include('assetto-corsa.results.session.qualifying', ['session' => $session])
                    @elseif ($session->type == \App\Models\AssettoCorsa\AcSession::TYPE_PRACTICE)
                        @include('assetto-corsa.results.session.practice', ['session' => $session])
                    @endif

                @else
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ $session->name }} results not yet available</h3>
                        </div>
                        <div class="panel-body">
                            @if ($session->release)
                                The results for this session will be released on {{ \Times::userTimezone($session->release) }}
                            @else
                                The results for this session are not yet available.
                            @endif
                        </div>
                    </div>
                @endif
                </div>
            @endforeach
        </div>
    </div>

@endsection
