var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var oTable;

$(document)
    .ready(
    function () {
        oTable = $('#groupRecords').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });
        getSearchResults();
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


// Code For Check Of Input Errors

function getSearchResults() {
    var data = $('#searchForm').serialize();
    data = 'task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_allowance_details_form.php",
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
                    if(data[0][0] == 1){
                        handleNotification('No Data Exists For The Given Combination', 'info');
                        endLoading();
                    }else{
                        if (data[0][0] != 0) {
                            oTable.fnClearTable();
                            var browseImageLink = getButtonBrowseImage();
                            for (var i = 0; i < data.length; i++) {
                                oTable
                                    .fnAddData([
                                    data[i][1],
                                    "<button type=\"button\" id=\"details\" class=\"negative details\" onclick=\"computeFormulae('"
                                        + data[i][0]
                                        + "', '"
                                        + i
                                        + "')\">" + browseImageLink + "Compute Formulae</button>",
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
        url:formGlobalPath + "accounts/accounts_allowance_details_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#allowanceName_d').html(data[1]);
                $('#accountHeadName_d').html(data[2]);
                $('#allowUpdate_d').html(data[3]);
                $('#allowRound_d').html(data[4]);
                $('#allowFraction_d').html(data[5]);
                $('#contributoryFund_d').html(data[6]);
                $('#computeFormulae_d').html(data[7]);


                $('#lastUpdateDateDisplay').html(data[8]);
                $('#lastUpdatedByDisplay').html(data[9]);
                $('#creationDateDisplay').html(data[10]);
                $('#createdByDisplay').html(data[11]);
                if (data[12] == 'y') {
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

// code for Edit begins


function computeFormulae(id, aPos){
    var link = serverUrl+'pages/accounts/accounts_allowance_formulae_view.php?allowanceId='+id;
    loadPageIntoDisplay(link);
    return false;
}