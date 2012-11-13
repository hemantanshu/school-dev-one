var valid = new validate();
$(document)
    .ready(
    function () {
        oTable = $('#groupEmployee').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });
        formGlobalPath = getFormGlobalPath();
    });

function showDatatable() {
    showTheDiv('displayDatatable');
}
function showHideDatatable() {
    toggleTheDiv('displayDatatable');
}

function getSearchDetails() {
    var data = $('#searchForm').serialize();
    data = 'task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_employee_search_form.php",
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
                    var viewImageLink = getButtonViewImage();
                    if (data[0][0] != 0) {
                        oTable.fnClearTable();
                        for (var i = 0; i < data.length; i++) {
                            oTable
                                .fnAddData([
                                data[i][1],
                                data[i][2],
                                data[i][3],
                                data[i][4],
                                data[i][5],
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + data[i][0]
                                    + "')\">" + viewImageLink + "Show Details</button>"]);
                        }
                        showDatatable();
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

function showRecordDetails(userId) {
    var url = serverUrl+"pages/utility/utl_user_profile.php?userId="+userId;
    loadPageIntoDisplay(url);
    return false;
}
