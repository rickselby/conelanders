@foreach($season['events'] AS $event)
    <div class="panel panel-default">
        <div class="panel-heading container-fluid" role="tab">
            <div class="row">
                <div class="col-xs-4">
                    @if ($event['event']->isComplete())
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" href="#event-{{ $event['event']->id }}">
                                {{ $event['event']->name }}
                                <span class="caret"></span>
                            </a>
                        </h4>
                    @else
                        {{ $event['event']->name }}
                    @endif
                </div>
                <div class="col-xs-4">
                    <a class="event-results"
                       href="{{ route('dirt-rally.standings.event', [$championship['championship'], $season['season'], $event['event']]) }}">
                        View event results
                    </a>
                </div>
                <div class="col-xs-4 text-right position">
                    <span class="position pull-right">
                        {{ $event['result'] ? $event['result']->position : 'In Progress' }}
                </span>
                </div>
            </div>

            <h4 class="panel-title">



            </h4>
        </div>
        @if ($event['event']->isComplete())
            <div id="event-{{ $event['event']->id }}" class="panel-collapse collapse" role="tabpanel">
                <div class="panel-body">

                    @include('dirt-rally.driver.all-results.stages')

                </div>
            </div>
        @endif
    </div>
@endforeach
