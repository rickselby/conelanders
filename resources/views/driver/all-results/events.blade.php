@foreach($season['events'] AS $event)
    <div class="panel panel-default">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">

                @if ($event['event']->isComplete())
                    <a role="button" data-toggle="collapse" href="#event-{{ $event['event']->id }}">
                        {{ $event['event']->name }}
                        <span class="caret"></span>
                    </a>
                @else
                    {{ $event['event']->name }}
                @endif

                <span class="position pull-right">
                    @if ($event['result'])
                        {{ $event['result']->position }}
                    @else
                        In Progress
                    @endif
                </span>

            </h4>
        </div>
        @if ($event['event']->isComplete())
            <div id="event-{{ $event['event']->id }}" class="panel-collapse collapse" role="tabpanel">
                <div class="panel-body">

                    @include('driver.all-results.stages')

                </div>
            </div>
        @endif
    </div>
@endforeach
