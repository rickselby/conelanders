@extends('page')

@section('header')
    <div class="page-header">
        <h1>Assetto Corsa Server Management</h1>
    </div>
@endsection

@section('content')

    <div class="alert alert-info">
        <strong>Server status:</strong>
        <span id="server-status">loading...</span>
    </div>
    
    <h3>Start/Stop Server</h3>

    <button id="start" type="button" class="btn btn-success">Start Server</button>
    <button id="stop" type="button" class="btn btn-danger">Stop Server</button>

    <h3>Update Server Configuration</h3>

    {!! Form::open(['route' => 'assetto-corsa.server.update-config', 'files' => true, 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('entry_list', 'Entry List', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            <div class="input-group">
                <label class="input-group-btn">
                    <span class="btn btn-primary">
                        <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>&nbsp;
                        Browse&hellip; <input name="entry_list" type="file" style="display: none;" multiple>
                    </span>
                </label>
                <input type="text" class="form-control" readonly>
            </div>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('server_cfg', 'Server Config', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            <div class="input-group">
                <label class="input-group-btn">
                    <span class="btn btn-primary">
                        <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>&nbsp;
                        Browse&hellip; <input name="server_cfg" type="file" style="display: none;" multiple>
                    </span>
                </label>
                <input type="text" class="form-control" readonly>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            {!! Form::submit('Upload new config files', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

    <script type="text/javascript">
        $( document ).ready(function() {

            getStatus();

            // We can attach the `fileselect` event to all file inputs on the page
            $(document).on('change', ':file', function() {
                var input = $(this);
                var numFiles = input.get(0).files ? input.get(0).files.length : 1;
                var label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [numFiles, label]);
            });

            $(':file').on('fileselect', function(event, numFiles, label) {

                var input = $(this).parents('.input-group').find(':text');
                var log = numFiles > 1 ? numFiles + ' files selected' : label;

                if( input.length ) {
                    input.val(log);
                }
            });

            $('#start').click(function() {
                $.ajax({
                    type: "POST",
                    url: "{{ route('assetto-corsa.server.start') }}",
                    async: true
                });
            })
            $('#stop').click(function() {
                $.ajax({
                    type: "POST",
                    url: "{{ route('assetto-corsa.server.stop') }}",
                    async: true
                });
            })

        });

        function getStatus() {
            $.ajax({
                type: "GET",
                url: "{{ route('assetto-corsa.server.status') }}",
                async: true,
                success: function(data) {
                    $('#server-status').html(data);
                    setTimeout(function(){getStatus();}, 10000);
                }
            });
        }

    </script>

@endsection
