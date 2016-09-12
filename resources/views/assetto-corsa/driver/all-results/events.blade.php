@foreach($championship['events'] AS $event)
    <div class="panel panel-default">
        <div class="panel-heading container-fluid" role="tab">
            <div class="row">
                <div class="col-xs-4">
                    @if ($event['event']->canBeReleased())
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" href="#ac-event-{{ $event['event']->id }}">
                                {{ $event['event']->name }}
                            </a> <span class="caret"></span>
                        </h4>
                    @else
                        {{ $event['event']->name }}
                    @endif
                </div>
                <div class="col-xs-4">
                    <a class="event-results"
                       href="{{ route('assetto-corsa.results.event', [$championship['championship'], $event['event']]) }}">
                        View event results
                    </a>
               </div>
                <div class="col-xs-4 text-right position">
                    {{ $event['event']->canBeReleased() ? $event['position'] : 'Not yet released' }}
                </div>
            </div>
        </div>
        @if ($event['event']->canBeReleased())
            <div id="ac-event-{{ $event['event']->id }}" class="panel-collapse collapse" role="tabpanel">
                <div class="panel-body">

                    @include('assetto-corsa.driver.all-results.sessions')

                </div>
            </div>
        @endif
    </div>
@endforeach
