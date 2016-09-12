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
