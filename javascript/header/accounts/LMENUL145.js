$(document)
    .ready(
    function () {
        var oTables = $('#groupRecordsDirect').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers",
            "bSort":false,
            "iDisplayLength": 20,
            "sDom": '<"H"Tfr>t<"F"ip>',
            "oTableTools": {
                "sSwfPath": serverUrl+"media/swf/copy_csv_xls_pdf.swf"
            }
        });
    });