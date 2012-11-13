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
        $("#employeeId")
            .autocomplete(
            formGlobalPath
                + "utility/utl_user_autocomplete.php?option=employeeTeacher",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#employeeId").result(
            function (event, data, formatted) {
                $("#employeeId_val").val(data[1]);
            });

        loadBankNames();
        loadInterestTypes();
        loadLoanTypes();
        loadPaymentTypes();
    });




function showInsertForm() {
    showTheDiv('insertForm');
}
function hideInsertForm() {
    hideTheDiv('insertForm');
}
function toggleInsertForm() {
    toggleTheDiv('insertForm');
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

function loadLoanTypes() {
    var data = 'task=fetchLoanTypes';
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_loan_sanction_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0][0] != 0) {
                    var options;
                    if(data[0][0] != 1){
                        for (var i = 0; i < data.length; i++) {
                            options = options + '<option value="' + data[i][0] + '">' + data[i][1] + '</option>';
                        }
                    }
                    $('#loanType').html(options);
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

function loadBankNames() {
    var data = 'task=fetchBankNames';
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_loan_sanction_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0][0] != 0) {
                    var options;
                    if(data[0][0] != 1){
                        for (var i = 0; i < data.length; i++) {
                            options = options + '<option value="' + data[i][0] + '">' + data[i][1] + '</option>';
                        }
                    }
                    $('#bankName').html(options);
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

function loadInterestTypes() {
    var data = 'task=fetchInterestTypes';
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_loan_sanction_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0][0] != 0) {
                    var options;
                    if(data[0][0] != 1){
                        for (var i = 0; i < data.length; i++) {
                            options = options + '<option value="' + data[i][0] + '">' + data[i][1] + '</option>';
                        }
                    }
                    $('#interestType').html(options);
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

function loadPaymentTypes() {
    var data = 'task=fetchPaymentTypes';
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_loan_sanction_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0][0] != 0) {
                    var options;
                    if(data[0][0] != 1){
                        for (var i = 0; i < data.length; i++) {
                            options = options + '<option value="' + data[i][0] + '">' + data[i][1] + '</option>';
                        }
                    }
                    $('#paymentMode').html(options);
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

function checkPaymentType(){
    var paymentType = $('#paymentMode').val();
    if(paymentType == 'RESER16')
        showTheDiv('chequeDetails');
    else
        hideTheDiv('chequeDetails');
}


// Code For Check Of Input Errors
function processInsertForm() {
    // preparing for ajax call
    var amount = $('#amount').val();
    var installment = $('#installment').val();
    var loanDate = $('#loanDate').val();
    var interest = $('#interest').val();
    var chequeNumber = $('#chequeNumber').val();
    var paymentMode = $('#paymentMode').val();

    var installmentAmount = amount / installment;

    var data = $('#insertForm').serialize();
    data += '&task=fetchInputTypes';
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_loan_sanction_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0] != 0) {
                    $('#employeeName_d').html(data[0]);
                    $('#employeeCode_d').html(data[1]);
                    $('#loanType_d').html(data[2]);
                    $('#sanctionDate_d').html(loanDate);
                    $('#loanAmount_d').html(amount);
                    $('#repaymentMonth_d').html(data[3]);
                    $('#interestApplicable_d').html(interest);
                    $('#interestType_d').html(data[4]);
                    $('#totalInstallments_d').html(installment);
                    $('#installmentAmount_d').html(installmentAmount);
                    $('#paymentMode_d').html(data[5]);
                    $('#flexiInstallment_d').html(data[6]);
                    $('#bankName_d').html(data[7]);
                    $('#chequeNumber_d').html(chequeNumber);

                    if(paymentMode != 'RESER16')
                        $('#chequeDetails_d').hide();

                    hideInsertForm();
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

function getSearchResults() {
    var data = $('#searchForm').serialize();
    data = 'task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_loan_type_form.php",
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
                                    data[i][4],
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

// code for Edit begins
function editRecord(id, aPos) {
    hideDisplayPortion();
    showInsertForm();
}
function processUpdateForm() {
    var data = $('#insertForm').serialize();
    data += '&task=insertRecord';
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_loan_sanction_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0] != 0) {
                    alert('New Loan Sanctioned');
                    refreshThePage();
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
// Code For Update Form Ends here

// Code For Drop Value Begins here
function dropRecord(id, aPos) {
    if (confirm('Do you really want to delete ?')) {
        showLoading("Processing Data");
        var data = 'task=dropRecord&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "accounts/accounts_loan_type_form.php",
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
                            'Error In Dropping Loan Type Details ... Try After Sometime',
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
                url:formGlobalPath + "accounts/accounts_loan_type_form.php",
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
                            'Error In Activating Loan Type Details ... Try After Sometime',
                            'error');
                        endLoading();
                    }
                }
            });
    }
    return false;
}