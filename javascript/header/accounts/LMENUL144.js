var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var employeeId, oTable;
$(document)
    .ready(
    function () {
        oTable = $('#groupRecords').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });

        loadLoanTypeNames();
    });

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

function changeEmployeeName(){
    if(employeeId != undefined){
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

function checkEmployeeChange(){
    employeeId = $('#employee_val').val();
    if(employeeId != ''){
        loadEmployeePersonalDetails();
        hideDisplayPortion();
        hideUpdateForm();
        hideChoiceListing();
        showPageDisplay();
    }
    return false;
}

function loadLoanTypeNames(){
    var data = 'search_type=all&hint=&task=search';
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_loan_type_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if(data[0][0] == 1){
                    handleNotification('No Loan Type Record Exists', 'info');
                    endLoading();
                }else{
                    if (data[0][0] != 0) {
                        var options = '<option value="">All Loans</option>';
                        for (var i = 0; i < data.length; i++) {
                            options = options + '<option value="'+data[i][0]+'">'+data[i][1]+'</option>';
                        }
                        $('#loanTypeHint').html(options);
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

function getSearchResults() {
    var data = $('#searchForm').serialize();
    data = 'task=searchRecord&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_loan_record_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                console.log(data);
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
                    if (data[0][0] != 0) {
                        oTable.fnClearTable();
                        var browseImageLink = getButtonBrowseImage();
                        for (var i = 0; i < data.length; i++) {
                            oTable
                                .fnAddData([
                                data[i][1],
                                data[i][2],
                                data[i][3],
                                data[i][4],
                                "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"showLoanStatement('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + browseImageLink + " View Statement</button>",
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + viewImageLink + " Show Details</button>"
                                 ]);
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

function showRecordDetails(id, aPos) {
    showLoading("Fetching Data From Server");
    var data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "accounts/accounts_loan_record_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != '') {
                $('#employeeNameDisplay').html(data[1]);
                $('#employeeCodeDisplay').html(data[2]);
                $('#loanTypeDisplay').html(data[3]);
                $('#loanSanctionDateDisplay').html(data[4]);
                $('#loanAmountDisplay').html(data[5]);
                $('#installmentAmountDisplay').html(data[6]);
                $('#interestTypeDisplay').html(data[7]);
                $('#paymentModeDisplay').html(data[8]);
                $('#flexiInstallmentDisplay').html(data[9]);
                $('#interestDisplay').html(data[10]);

                $('#lastUpdateDateDisplay').html(data[11]);
                $('#lastUpdatedByDisplay').html(data[12]);
                $('#creationDateDisplay').html(data[13]);
                $('#createdByDisplay').html(data[14]);
                if (data[15] == 'y') {
                    $('#activeDisplay').html(
                        '<font class="green">Active</font>');
                    $('#editRecordButton').show();
                    $('#editRecordButton').attr('onclick',
                        'editRecord(\'' + data[0] + '\', \'' + aPos + '\')');
                } else {
                    $('#activeDisplay').html(
                        '<font class="red">Inactive</font>');
                    $('#editRecordButton').hide();
                }
                $('#valueId_d').val(data[0]);
                $('#rowPosition_d').val(aPos);
                showDisplayPortion();
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
    var url = serverUrl+'pages/accounts/accounts_loan_edit.php?loanAccountId='+id;
    loadPageIntoDisplay(url);
    return false;
}


function showLoanStatement(id, aPos){
    var url = serverUrl+'pages/accounts/accounts_loan_statement.php?loanAccountId='+id;
    //loadPageIntoDisplay(url);
    loadColorboxPage(url, 1000);
    return false;
}
