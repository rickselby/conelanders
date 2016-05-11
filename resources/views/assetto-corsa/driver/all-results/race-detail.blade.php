<div class="panel panel-default">
    <table class="table">
    <tbody>
        <tr>
            <th>Qualified</th>
            <td class="col-sm-2 text-right">
                @if ($race['qualifying']['toBest'])
                    + {{ Times::toString($race['qualifying']['toBest']) }}
                @else
                    -
                @endif
            </td>
            <td class="col-xs-1 text-right position">
                {{ $race['result']->qualifying_position }}
            </td>
        </tr>
        <tr>
            <th>Race</th>
            <td class="col-sm-2 text-right">
                @if ($race['result']->race_behind)
                    + {{ Times::toString($race['result']->race_behind) }}
                @else
                    -
                @endif
            </td>
            <td class="col-xs-1 text-right position">
                {{ $race['result']->race_position }}
            </td>
        </tr>
        <tr>
            <th>Fastest Lap</th>
            <td class="col-sm-2 text-right">
                @if ($race['results']['toBestLap'])
                    + {{ Times::toString($race['results']['toBestLap']) }}
                @else
                    -
                @endif
            </td>
            <td class="col-xs-1 text-right position">
                {{ $race['result']->race_fastest_lap_position }}
            </td>
        </tr>
    </tbody>
    </table>
</div>
