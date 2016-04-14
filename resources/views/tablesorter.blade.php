<script type="text/javascript">
    $.tablesorter.addParser({
        id: "dayHoursMinutes",
        is: function (s) {
            return /^[\d:.]+$/.test(s);
        }, format: function (s) {
            return s.replace(/[:.]/g, '');
        }, type: "numeric"
    });

    $("table").tablesorter({
        theme : "bootstrap",
        widthFixed: true,
        headerTemplate : '{content} {icon}',
        widgets : [ "uitheme", "saveSort" ],
        sortList: [[0,0]],
        sortRestart: true,
    });
</script>
