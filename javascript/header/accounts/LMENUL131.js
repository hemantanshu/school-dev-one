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


function showChoiceListing(){
    showTheDiv('choiceListing');
}
function hideChoiceListing(){
    hideTheDiv('choiceListing');
}

function showPageDisplay(){
    showTheDiv('completePageDisplay');
}

function hidePageDisplay(){
    hideTheDiv('completePageDisplay');
}

function showInsertForm() {
    showTheDiv('insertForm');
}
function hideInsertForm() {
    hideTheDiv('insertForm');
}
function toggleInsertForm() {
    toggleTheDiv('insertForm');
}
function showUpdateForm() {
    showTheDiv('updateForm');
}
function hideUpdateForm() {
    hideTheDiv('updateForm');
}
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

function changeAllowanceName(){
    if(allowanceId != undefined){
        var choiceListing = $('#choiceListing');
        if(choiceListing.is(':visible')){
            hideChoiceListing();
            showPageDisplay();
        }
        else{
            showChoiceListing();
            hidePageDisplay();
        }
    }
}

function processAllowanceForm(){
    allowanceId = $('#allowance').val();
    if(allowanceId != undefined){
        populateAllowanceDetails();
        loadAllowanceOptions();
        changeAllowanceName();
    }
    return false;
}

function populateInitialElements() {
    var data = 'task=fetchFundIds';
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_fund_formulae_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0][0] != 0) {
                    var options = '';
                    if(data[0][0] != 1){
                        for (var i = 0; i < data.length; i++) {
                            options = options + '<option value="' + data[i][0] + '">' + data[i][1] + '</option>';
                        }
                        $('#allowance').html((options));
                    }
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

function populateAllowanceDetails() {
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
                    loadAllowanceOptions();
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

function loadAllowanceOptions() {
    var data = 'task=fetchAllowanceOptions&allowanceId=';
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
                if (data[0][0] != 0) {
                    var options = '<option value="">Absolute Sum</option>';
                    if(data[0][0] != 1){
                        for (var i = 0; i < data.length; i++) {
                            options = options + '<option value="' + data[i][0] + '">' + data[i][1] + '</option>';
                        }
                    }
                    $('#dependent_i').html(options);
                    $('#dependent_u').html(options);

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

// Code For Check Of Input Errors
function processInsertForm() {
    // preparing for ajax call
    var data = $('#insertForm').serialize();
    data = 'allowanceId=' + allowanceId + '&task=insertRecord&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_fund_formulae_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0] != 0) {
                    aPos = oTable.fnGetNodes().length;
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    oTable
                        .fnAddData([
                        data[1],
                        data[2],
                        data[3],
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                            + data[0]
                            + "', '"
                            + aPos
                            + "')\">" + viewImageLink + "Show Details</button>",
                        "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                            + data[0]
                            + "', '"
                            + aPos
                            + "')\">" + editImageLink + "Edit Details</button>" ]);
                    hideInsertForm();
                    hideDisplayPortion();
                    showDatatable();
                    endLoading();
                    $('#insertReset').click();
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

function getSearchResults() {
    var data = $('#searchForm').serialize();
    data = 'allowanceId=' + allowanceId + '&task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_fund_formulae_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {

                checkValidityOfOutput(data);
                if (data[0][0] == 1) {
                    oTable.fnClearTable();
                    hideInsertForm();
                    hideDisplayPortion();
                    handleNotification(
                        'No Data Fetched With The Given Inputs', 'info');
                    endLoading();
                } else {
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
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
                                        + "')\">" + viewImageLink + " Show Details</button>",
                                    "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                        + data[i][0]
                                        + "', '"
                                        + i
                                        + "')\">" + editImageLink + "Edit Details</button>" ]);
                            }
                            hideInsertForm();
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
        url:formGlobalPath + "accounts/accounts_fund_formulae_form.php",
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
                    $('#dropRecord_d').show();
                    $('#dropRecord_d')
                        .attr(
                        'onclick',
                        'dropRecord(\'' + data[0] + '\', \'' + aPos
                            + '\')');
                    $('#activateRecord_d').hide();
                } else {
                    $('#activeDisplay').html(
                        '<font class="red">Inactive</font>');
                    $('#activateRecord_d').show();
                    $('#activateRecord_d').attr(
                        'onclick',
                        'activateRecord(\'' + data[0] + '\', \'' + aPos
                            + '\')');
                    $('#dropRecord_d').hide();
                }
                $('#editRecordButton').attr('onclick',
                    'editRecord(\'' + data[0] + '\', \'' + aPos + '\')');

                $('#valueId_d').val(data[0]);
                $('#rowPosition_d').val(aPos);
                hideUpdateForm();
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
function editRecord(id, aPos) {
    showLoading("Processing Data Into Update Form");
    data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "accounts/accounts_fund_formulae_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#value_u').val(data[1]);
                $('#dependent_u').val(data[9]);
                $('#type_u').val(data[10]);
                if (data[8] == 'y') {
                    $('#dropRecord_u').show();
                    $('#dropRecord_u')
                        .attr(
                        'onclick',
                        'dropRecord(\'' + data[0] + '\', \'' + aPos
                            + '\')');
                    $('#activateRecord_u').hide();
                } else {
                    $('#activateRecord_u').show();
                    $('#activateRecord_u').attr(
                        'onclick',
                        'activateRecord(\'' + data[0] + '\', \'' + aPos
                            + '\')');
                    $('#dropRecord_u').hide();
                }
                $('#valueId_u').val(data[0]);
                $('#rowPosition_u').val(aPos);

                hideDisplayPortion();
                hideInsertForm();
                showUpdateForm();
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
function processUpdateForm() {
    // validation process
    var id = $('#valueId_u').val();
    if (id) {
        var aPos = $('#rowPosition_u').val();
        aPos = parseInt(aPos);
        // preparing for ajax call
        var data = $('#updateForm').serialize();
        data = 'task=updateRecord&' + data;
        showLoading("Uploading Data To Server");
        $
            .ajax({
                url:formGlobalPath + "accounts/accounts_fund_formulae_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (data) {
                    checkValidityOfOutput(data);
                    if (data[0] != 0) {
                        var viewImageLink = getButtonViewImage();
                        var editImageLink = getButtonEditImage();
                        oTable
                            .fnUpdate(
                            [
                                data[1],
                                data[2],
                                data[3],
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + data[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + viewImageLink + "Show Details</button>",
                                "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                    + data[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + editImageLink + "Edit Details</button>" ],
                            aPos);
                        hideUpdateForm();
                        showRecordDetails(data[0], aPos);
                        endLoading();
                    } else {
                        handleNotification(
                            'Error While Updating Data, Please Try Again',
                            'error');
                        endLoading();
                    }
                }
            });
    }
    return false;
}
// Code For Update Form Ends here

// Code For Drop Value Begins here
function dropRecord(id, aPos) {
    if (confirm('Do you really want to delete ?')) {
        showLoading("Processing Data");
        var data = 'task=dropRecord&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "accounts/accounts_fund_formulae_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] != 0) {
                        showRecordDetails(id, aPos);
                        hideUpdateForm();
                        endLoading();
                    } else {
                        handleNotification(
                            'Error In Dropping Allowance Combination Details ... Try After Sometime',
                            'error');
                        endLoading();
                    }

                }
            });
    }
    return false;
}
// Code for Drop value Ends Here

// Code For Activate Value Begins Here
function activateRecord(id, aPos) {
    if (confirm('Do you really want to activate ?')) {
        showLoading("Processing Data");
        var data = 'task=activateRecord&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "accounts/accounts_fund_formulae_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] != 0) {
                        showRecordDetails(id, aPos);
                        hideUpdateForm();
                        endLoading();
                    } else {
                        handleNotification(
                            'Error In Activating Allowance Combination Details ... Try After Sometime',
                            'error');
                        endLoading();
                    }
                }
            });
    }
    return false;
}