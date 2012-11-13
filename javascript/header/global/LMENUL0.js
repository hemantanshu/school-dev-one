$(document).ready(function(){
    var oTable = $('#groupRecords').dataTable({
        'bJQueryUI':true,
        'sPaginationType':'full_numbers',
        "aaSorting": [[ 1, "desc" ]]
    });
});