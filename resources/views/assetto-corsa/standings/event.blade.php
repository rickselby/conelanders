@extends('page')

@section('content')

    @if(!$event->canBeReleased() && !Gate::check('assetto-corsa-admin'))
        @include('assetto-corsa.race-not-complete')
    @else
        @if (Gate::check('assetto-corsa-admin') && !$event->canBeReleased())
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title">Admin Only View</h3>
                </div>
                <div class="panel-body">
                    Only admins can see this page, the results have not yet been released
                </div>
            </div>
        @endif

        @foreach($event->sessions as $session)
            @if ($session->type == \App\Models\AssettoCorsa\AcSession::TYPE_RACE)
                @include('assetto-corsa.standings.session.race', ['session' => $session])
            @elseif ($session->type == \App\Models\AssettoCorsa\AcSession::TYPE_QUALIFYING)
                @include('assetto-corsa.standings.session.qualifying', ['session' => $session])
            @elseif ($session->type == \App\Models\AssettoCorsa\AcSession::TYPE_PRACTICE)
                @include('assetto-corsa.standings.session.practice', ['session' => $session])
            @endif
        @endforeach

    @endif

@endsection
