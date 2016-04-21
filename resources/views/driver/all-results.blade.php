@foreach($results AS $championship)
    <div class="panel panel-default">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" href="#championship-{{ $championship['championship']->id }}">
                    {{ $championship['championship']->name }} <span class="caret"></span>
                </a>
                <span class="badge pull-right">x</span>
            </h4>
        </div>
        <div id="championship-{{ $championship['championship']->id }}" class="panel-collapse collapse" role="tabpanel">
            <div class="panel-body">

            @foreach($championship['seasons'] AS $season)
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" href="#season-{{ $season['season']->id }}">
                                {{ $season['season']->name }} <span class="caret"></span>
                            </a>
                            <span class="badge pull-right">x</span>
                        </h4>
                    </div>
                    <div id="season-{{ $season['season']->id }}" class="panel-collapse collapse" role="tabpanel">
                        <div class="panel-body">

                        @foreach($season['events'] AS $event)
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" href="#event-{{ $event['event']->id }}">
                                            {{ $event['event']->name }} <span class="caret"></span>
                                        </a>
                                        <span class="badge pull-right">{{ $event['result']->position }}</span>
                                    </h4>
                                </div>
                                <div id="event-{{ $event['event']->id }}" class="panel-collapse collapse" role="tabpanel">
                                    <div class="panel-body">

                                        <div class="panel panel-default">
                                            <table class="table">
                                                @foreach($event['stages'] AS $stage)
                                                    <tr>
                                                        <td>{{ $stage['stage']->name }}</td>
                                                        <td>
                                                            <span class="badge pull-right">
                                                                @if ($stage['result']->dnf)
                                                                    DNF
                                                                @else
                                                                    {{ $stage['result']->position }}
                                                                @endif
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach

                        </div>
                    </div>
                </div>
            @endforeach

            </div>
        </div>
    </div>
@endforeach