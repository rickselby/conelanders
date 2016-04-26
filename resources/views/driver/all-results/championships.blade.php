@foreach($results['all'] AS $championship)
    <div class="panel panel-default">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" href="#championship-{{ $championship['championship']->id }}">
                    {{ $championship['championship']->name }}
                </a> <span class="caret"></span>
                <span class="position pull-right">{{ $championship['position'] ?: 'In Progress' }}</span>
            </h4>
        </div>
        <div id="championship-{{ $championship['championship']->id }}" class="panel-collapse collapse" role="tabpanel">
            <div class="panel-body">

                @include('driver.all-results.seasons')

            </div>
        </div>
    </div>
@endforeach