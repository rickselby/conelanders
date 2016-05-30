@foreach($championship['events'] AS $event)
    <div class="panel panel-default">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                @if ($event['event']->canBeReleased())
                    <a role="button" data-toggle="collapse" href="#ac-event-{{ $event['event']->id }}">
                        {{ $event['event']->name }}
                    </a> <span class="caret"></span>
                @else
                    {{ $event['event']->name }}
                @endif
                <span class="position pull-right">
                    {{ $event['event']->canBeReleased() ? $event['position'] : 'Not yet released' }}
                </span>
            </h4>
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
