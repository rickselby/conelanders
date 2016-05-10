@foreach($championship['races'] AS $race)
    <div class="panel panel-default">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                @if ($race['race']->canBeReleased())
                    <a role="button" data-toggle="collapse" href="#ac-race-{{ $race['race']->id }}">
                        {{ $race['race']->name }}
                    </a> <span class="caret"></span>
                @else
                    {{ $race['race']->name }}
                @endif
                <span class="position pull-right">
                    {{ $race['race']->canBeReleased() ? $race['result']->race_position : 'In Progress' }}
                </span>
            </h4>
        </div>
        @if ($race['race']->canBeReleased())
            <div id="ac-race-{{ $race['race']->id }}" class="panel-collapse collapse" role="tabpanel">
                <div class="panel-body">

                    @include('assetto-corsa.driver.all-results.race-detail')

                </div>
            </div>
        @endif
    </div>
@endforeach
