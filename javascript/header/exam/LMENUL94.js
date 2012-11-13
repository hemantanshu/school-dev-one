var valid = new validate();
var formGlobalPath = getFormGlobalPath();
$(document)
    .ready(
    function () {
        oTable = $('#groupRecords').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });
        loadDataDetails();
    });

function loadDataDetails() {
    var resultId = $('#resultId_direct').val();
    var candidateId = $('#candidateId_direct').val();
    var data = 'task=getResultProcessingRecords&candidateId=' + candidateId + '&resultId=' + resultId;

    $
        .ajax({
            url:formGlobalPath + "exam/exam_result_process_recordv_form.php",
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
                                data[i][0],
                                data[i][1]]);
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
}
