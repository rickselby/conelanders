<div class="panel {{ ($session->release) ? 'panel-success' : 'panel-danger' }}">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a role="button" data-toggle="collapse" href="#releaseDate">
                Release Date
            </a> <span class="caret"></span>
        </h3>
    </div>
    <div class="panel-collapse collapse {{ ($session->release) ? '' : 'in' }}" id="releaseDate" role="tabpanel">
        <div class="panel-body">
            {!! Form::open(['route' => ['assetto-corsa.championship.event.session.release-date', $session->event->championship, $session->event, $session], 'method' => 'put', 'class' => 'form-horizontal']) !!}

            <div class="form-group">
                {!! Form::label('release', 'Release Results At:', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-sm-10">
                    <div class='input-group date' id='datetimepicker1'>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        <input name="release" type='text' class="form-control" value="{{ $session->release ? $session->release->format('jS F Y, H:i') : '' }}" />
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
