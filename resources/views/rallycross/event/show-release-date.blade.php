
<div class="panel {{ ($event->release) ? 'panel-success' : 'panel-danger' }}">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a role="button" data-toggle="collapse" href="#releaseDate">
                Release Date
            </a> <span class="caret"></span>
        </h3>
    </div>
    <div class="panel-collapse collapse {{ ($event->release) ? '' : 'in' }}" id="releaseDate" role="tabpanel">
        <div class="panel-body">
            {!! Form::model($event, ['route' => ['rallycross.championship.event.release-date', $event->championship, $event], 'method' => 'put', 'class' => 'form-horizontal']) !!}

            <div class="form-group">
                {!! Form::label('release', 'Release Results At:', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    <div class='input-group date' id='datetimepicker1'>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        {{ Form::text('release', null, ['class' => 'form-control']) }}
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
