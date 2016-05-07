@extends('page')

@section('content')

    {!! Form::open(['route' => ['assetto-corsa.championship.race.destroy', $race->championship, $race], 'method' => 'delete', 'class' => 'form-inline']) !!}
        <a class="btn btn-small btn-warning"
           href="{{ route('assetto-corsa.championship.race.edit', [$race->championship, $race]) }}">Edit Race</a>
        {!! Form::submit('Delete Race', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}

    <br />

    <div class="panel {{ \ACResults::hasQualifying($race) ? 'panel-success' : 'panel-danger' }}">
        <div class="panel-heading">
            <h3 class="panel-title">
                <a role="button" data-toggle="collapse" href="#qualifyingResults">
                    Upload Qualifying Results
                </a> <span class="caret"></span>
            </h3>
        </div>
        <div class="panel-collapse collapse {{ \ACResults::hasQualifying($race) ? '' : 'in' }}" id="qualifyingResults" role="tabpanel">
            <div class="panel-body">
                {!! Form::open(['route' => ['assetto-corsa.championship.race.qualifying-results-upload', $race->championship, $race], 'files' => true, 'class' => 'form-horizontal']) !!}
                <label class="btn btn-info" for="my-file-selector">
                    <input id="my-file-selector" name="file" type="file" style="display:none;">
                    <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
                    &nbsp;
                    Select JSON File
                </label>
                {!! Form::submit('Upload', array('class' => 'btn btn-primary')) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="panel {{ \ACResults::hasRace($race) ? 'panel-success' : 'panel-danger' }}">
        <div class="panel-heading">
            <h3 class="panel-title">
                <a role="button" data-toggle="collapse" href="#raceResults">
                    Upload Race Results
                </a> <span class="caret"></span>
            </h3>
        </div>
        <div class="panel-collapse collapse {{ \ACResults::hasRace($race) ? '' : 'in' }}" id="raceResults" role="tabpanel">
            <div class="panel-body">
                {!! Form::open(['route' => ['assetto-corsa.championship.race.race-results-upload', $race->championship, $race], 'files' => true, 'class' => 'form-horizontal']) !!}
                <label class="btn btn-info" for="my-file-selector-2">
                    <input id="my-file-selector-2" name="file" type="file" style="display:none;">
                    <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
                    &nbsp;
                    Select JSON File
                </label>
                {!! Form::submit('Upload', array('class' => 'btn btn-primary')) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="panel {{ count($race->entrants) ? 'panel-success' : 'panel-danger' }}">
        <div class="panel-heading">
            <h3 class="panel-title">
                <a role="button" data-toggle="collapse" href="#entrants">
                    Entrants
                </a> <span class="caret"></span>
            </h3>
        </div>
        <div class="panel-collapse collapse {{ count($race->entrants) ? '' : 'in' }}" id="entrants" role="tabpanel">
            <div class="panel-body">
                @if (count($race->entrants))
                    {!! Form::open(['route' => ['assetto-corsa.championship.race.update-entrants', $race->championship, $race], 'class' => 'form-horizontal']) !!}
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Driver</th>
                            <th>Car</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($race->entrants AS $entrant)
                            <tr>
                                <th>{{ $entrant->championshipEntrant->driver->name }}</th>
                                <td>
                                    {!! Form::text('car['.$entrant->id.']', $entrant->car, ['class' => 'form-control']) !!}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!! Form::submit('Update Entrants', array('class' => 'btn btn-primary')) !!}
                    {!! Form::close() !!}
                @else
                    <p>No entrants yet - please upload results</p>
                @endif
            </div>
        </div>
    </div>

    <div class="panel {{ ($race->release) ? 'panel-success' : 'panel-danger' }}">
        <div class="panel-heading">
            <h3 class="panel-title">
                <a role="button" data-toggle="collapse" href="#releaseDate">
                    Release Date
                </a> <span class="caret"></span>
            </h3>
        </div>
        <div class="panel-collapse collapse {{ ($race->release) ? '' : 'in' }}" id="releaseDate" role="tabpanel">
            <div class="panel-body">
                {!! Form::open(['route' => ['assetto-corsa.championship.race.update-release-date', $race->championship, $race], 'files' => true, 'class' => 'form-horizontal']) !!}

                <div class="form-group">
                    {!! Form::label('release', 'Release Results At:', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        <div class='input-group date' id='datetimepicker1'>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <input name="release" type='text' class="form-control" value="{{ $race->release ? $race->release->format('jS F Y, H:i') : '' }}" />
                        </div>
                        <p class="help-block">Time in UTC</p>
                        <script type="text/javascript">
                            $(function () {
                                $('#datetimepicker1').datetimepicker({
                                    sideBySide: true,
                                    format: "Do MMMM YYYY, HH:mm"
                                });
                            });
                        </script>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-10">
                        {!! Form::submit('Update Release Date', ['class' => 'btn btn-primary']) !!}
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection