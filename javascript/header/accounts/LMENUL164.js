var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var oTable, allowanceId;

$(document)
    .ready(
    function () {
        oTable = $('#groupRecords').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });
        populateInitialElements();

    });


function showDisplayPortion() {
    showTheDiv('displayRecord');
}
function hideDisplayPortion() {
    hideTheDiv('displayRecord');
}
function showDatatable() {
    showTheDiv('displayDatatable');
}
function showHideSearchForm() {
    toggleTheDiv('searchForm');
}
function showHideDatatable() {
    toggleTheDiv('displayDatatable');
}

function populateInitialElements() {
    allowanceId = $('#allowanceId').val();
    getSearchResults();
    var data = 'task=fetchAllowanceDetails&allowanceId=' + allowanceId;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_allowance_formulae_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0] != 0) {
                    $('#allowanceName').html((data[0]));
                    $('#accountHead').html((data[1]));

                    endLoading();
                } else {
                    handleNotification(
                        'No such allowance exists',
                        'error');
                    loadPageIntoDisplay(serverUrl);
                    endLoading();
                }
            }
        });
    return false;
}


// Code For Check Of Input Errors

function getSearchResults() {
    var data = $('#searchForm').serialize();
    data = 'allowanceId=' + allowanceId + '&task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_allowance_formulae_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {

                checkValidityOfOutput(data);
                if (data[0][0] == 1) {
                    oTable.fnClearTable();
                    hideDisplayPortion();
                    handleNotification(
                        'No Data Fetched With The Given Inputs', 'info');
                    endLoading();
                } else {
                    var viewImageLink = getButtonViewImage();                    
                    if (data[0][0] == 1) {
                        handleNotification('No Data Exists For The Given Combination', 'info');
                        endLoading();
                    } else {
                        if (data[0][0] != 0) {
                            oTable.fnClearTable();
                            for (var i = 0; i < data.length; i++) {
                                oTable
                                    .fnAddData([
                                    data[i][1],
                                    data[i][2],
                                    data[i][3],
                                    "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                        + data[i][0]
                                        + "', '"
                                        + i
                                        + "')\">" + viewImageLink + " Show Details</button>" ]);
                            }
                            hideDisplayPortion();
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
            }
        });
    return false;
}

function showRecordDetails(id, aPos) {
    showLoading("Fetching Data From Server");
    var data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "accounts/accounts_allowance_formulae_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#value_d').html(data[1]);
                $('#dependent_d').html(data[2]);
                $('#type_d').html(data[3]);

                $('#lastUpdateDateDisplay').html(data[4]);
                $('#lastUpdatedByDisplay').html(data[5]);
                $('#creationDateDisplay').html(data[6]);
                $('#createdByDisplay').html(data[7]);
                if (data[8] == 'y') {
                    $('#activeDisplay').html(
                        '<font class="green">Active</font>');
                } else {
                    $('#activeDisplay').html(
                        '<font class="red">Inactive</font>');
                }

                showDisplayPortion();
                hideInsertForm();
                endLoading();
            } else {
                handleNotification(
                    'Error While Processing Data, Please Try Again',
                    'error');
                endLoading();
            }
        }
    });
    return false;
}