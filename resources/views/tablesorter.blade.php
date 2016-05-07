<script type="text/javascript">
    $.tablesorter.addParser({
        id: "HoursMinutesSecondsMilliseconds",
        is: function (s) {
            return /^((\d{1,2}:)?\d)?\d:\d{2}.\d{3}$/.test(s);
        }, format: function (s) {
            return s.replace(/[:.]/g, '');
        }, type: "numeric"
    });

    $("table.sortable").tablesorter({
        theme : "bootstrap",
        widthFixed: true,
        headerTemplate : '{content} {icon}',
        widgets : [ "uitheme", "saveSort" ],
        sortList: [[0,0]],
        sortRestart: true,
    });
</script>
