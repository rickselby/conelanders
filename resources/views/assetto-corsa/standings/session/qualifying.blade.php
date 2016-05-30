<div class="panel panel-default">
    <div class="panel-heading" role="tab">
        <h4 class="panel-title">
            <a role="button" data-toggle="collapse" href="#ac-session-{{ $session->id }}">
                {{ $session->name }}
            </a>
            <span class="caret"></span>
        </h4>
    </div>
    <div id="ac-session-{{ $session->id }}" class="panel-collapse collapse" role="tabpanel">
        <div class="panel-body">

            @include('assetto-corsa.standings.session.lap-table', ['lapTimes' => \ACResults::fastestLaps($session)] )

        </div>
    </div>
</div>
