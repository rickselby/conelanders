
<div class="panel panel-default {{ \ACSession::hasResults($session) ? 'panel-success' : 'panel-danger' }}">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a role="button" data-toggle="collapse" href="#results">
                {{ \ACSession::hasResultsFile($session) ? '' : 'Upload ' }} Results File
            </a> <span class="caret"></span>
        </h3>
    </div>
    <div class="panel-collapse collapse {{ \ACSession::hasResults($session) ? '' : 'in' }}" id="results" role="tabpanel">
        <div class="panel-body">

            @if (\ACSession::hasResultsFile($session))
            <h4>Rescan current file</h4>
            <a role="button" class="btn btn-small btn-primary"
               href="{{ route('assetto-corsa.championship.event.session.results-scan', [$session->event->championship, $session->event, $session]) }}">Rescan Results</a>
            <h4>Upload a new results file (this will clear everything already uploaded)</h4>
            @endif

            {!! Form::open(['route' => ['assetto-corsa.championship.event.session.results-upload', $session->event->championship, $session->event, $session], 'files' => true, 'class' => 'form-horizontal']) !!}
            <label class="btn btn-info" for="file-selector">
                <input id="file-selector" name="file" type="file" style="display:none;">
                <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
                &nbsp;
                Select JSON File
            </label>
            {!! Form::submit('Upload', array('class' => 'btn btn-primary')) !!}
            {!! Form::close() !!}
        </div>
    </div>
</div>