<div class="panel panel-default">
    <table class="table">
        @foreach($event['stages'] AS $stage)
            <tr>
                <th>{{ $stage['stage']->name }}</th>
                <td class="col-sm-2 text-right">
                    @if ($stage['result']->behind && !$stage['result']->dnf)
                        + {{ Times::toString($stage['result']->behind) }}
                    @else
                        -
                    @endif
                </td>
                <td class="col-xs-1 text-right position">
                    @if ($stage['result']->dnf)
                        DNF
                    @else
                        {{ $stage['result']->position }}
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
</div>
