var valid = new validate();
var oTable;
$(document)
    .ready(
    function () {
        oTable = $('#totalMarkListing').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "sScrollX": "100%",
            "bScrollCollapse": true,
            "sDom": '<"H"Tfr>t<"F"ip>',
            "oTableTools": {
                "sSwfPath": serverUrl+"media/swf/copy_csv_xls_pdf.swf"
            }
        });
//        new FixedColumns( oTable, {
//            "iLeftColumns": 2,
//            "iLeftWidth": 500
//        } );
        fetchDatasheetData();
    });

function fetchDatasheetData() {
    var resultId = $('#resultIdGlobal').val();
    var sectionId = $('#sectionIdGlobal').val();

    var data = 'resultId='+resultId+'&sectionId='+sectionId+'&task=getResultSheetData';
    showLoading("Fetching Datasheet Data From Server");
    $
        .ajax({
            url:formGlobalPath + "exam/exam_excel_datasheet_junior_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0][0] == 1) {
                    oTable.fnClearTable();
                    handleNotification(
                        'No Data Fetched With The Given Inputs', 'info');
                    endLoading();
                } else {
                    if (data[0][0] != 0) {
                        oTable.fnClearTable();
                        for (var i = 0; i < data.length; i++) {
                            oTable
                                .fnAddData([
                                data[i]
                            ]);
                        }
                        endLoading();
                    } else {
                        handleNotification(
                            'Error While Processing Data, Please Try Again',
                            'error');
                        endLoading();
                    }
                }
            }
        });
    return false;
}