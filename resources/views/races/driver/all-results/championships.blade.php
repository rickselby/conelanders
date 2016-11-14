@foreach($acResults['all'] AS $championship)
    <div class="panel panel-default">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" href="#ac-championship-{{ $championship['championship']->id }}">
                    {{ $championship['championship']->name }}
                </a> <span class="caret"></span>
                <span class="position pull-right">{{ $championship['position'] ?: 'In Progress' }}</span>
            </h4>
        </div>
        <div id="ac-championship-{{ $championship['championship']->id }}" class="panel-collapse collapse" role="tabpanel">
            <div class="panel-body">

                @include('races.driver.all-results.events')

            </div>
        </div>
    </div>
@endforeach