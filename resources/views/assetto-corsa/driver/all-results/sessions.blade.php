@foreach($event['sessions'] AS $session)
    <div class="panel panel-default">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                @if ($session['session']->type == \App\Models\AssettoCorsa\AcSession::TYPE_RACE)
                    <a role="button" data-toggle="collapse" href="#ac-session-{{ $session['session']->id }}">
                        {{ $session['session']->name }}
                    </a> <span class="caret"></span>
                @else
                    {{ $session['session']->name }}
                @endif
                <span class="col-xs-1 pull-right" style="text-align: right;">
                    {{ $event['event']->canBeReleased() ? $session['position'] : 'Not yet released' }}
                </span>
                <span class="col-xs-2 pull-right" style="text-align: right;">
                    @if ($session['result']->dnf)
                        DNF
                    @elseif ($session['result']->dsq)
                        DSQ
                    @elseif ($session['result']->timeBehindFirst)
                        + {{ Times::toString($session['result']->timeBehindFirst) }}
                    @else
                        -
                    @endif
                </span>
            </h4>
        </div>
        @if ($session['session']->type == \App\Models\AssettoCorsa\AcSession::TYPE_RACE)
            <div id="ac-session-{{ $session['session']->id }}" class="panel-collapse collapse" role="tabpanel">
                <div class="panel-body">

                    <div class="panel panel-default">
                        <table class="table">
                            <tr>
                                <th>Fastest Lap</th>
                                <td class="col-sm-2 text-right">
                                    @if ($session['fastestLap']['result']->timeBehindFirst)
                                        + {{ Times::toString($session['fastestLap']['result']->timeBehindFirst) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="col-xs-1 text-right position">
                                    {{ $session['fastestLap']['result']->position }}
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
        @endif
    </div>
@endforeach
        
