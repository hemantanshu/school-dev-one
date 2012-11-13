var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var allowanceId, allowanceType;
var employeeIds, confirmTable;

var imagePath, loaderImage, browseImage, loaderImageTag, processImageTag;
$(document)
    .ready(
    function () {
        imagePath = schoolImageGlobalPath() + 'global/';
        loaderImage = imagePath+'ajax-loader.gif';
        browseImage = imagePath+'icons/create.png';
        loaderImageTag = '<img src="'+loaderImage+'" alt="" />Processing Allowance';
        processImageTag = '<img src="'+browseImage+'" alt="" />Update Master Salary';

        confirmTable = $('#updatedTableBody');

        $("#allowance")
            .autocomplete(
            formGlobalPath
                + "accounts/accounts_details_autocomplete.php?option=allowanceName",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#allowance").result(
            function (event, data, formatted) {
                $("#allowance_val").val(data[1]);
            });
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

function checkAllowanceChange(){
    allowanceId = $('#allowance_val').val();
    if(allowanceId != undefined){
        loadAllowanceDetails();
        hideChoiceListing();
        showPageDisplay();
        getEmployeeIds();
    }
    return false;
}

function copyCalculatedAmount(employeeId){
    var calculatedAmount = $('#calculatedAmount'+employeeId).val();
    $('#insertedAmount'+employeeId).val(calculatedAmount);
}

function nullifyAmount(employeeId){
    var nullAmount = 0;
    $('#insertedAmount'+employeeId).val(nullAmount);
}

function loadAllowanceDetails(){
    var data = 'allowanceId='+allowanceId+'&task=fetchAllowanceDetails';
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_allowance_formulae_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    $('#allowanceName').html(output[0]);
                    $('#allowanceType').html(output[2]);
                    allowanceType = output[3];

                    endLoading();
                } else {
                    handleNotification(
                        'Error Fetching Data From Server',
                        'error');
                    endLoading();
                }
            }
        });
    return false;
}

function getEmployeeIds(){
    showLoading('Fetching Employee Details From Server');
    var data = 'task=fetchEmployeeIds';
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_employee_allowance_bulk_update_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    employeeIds = output;
                    confirmTable.html('');
                    populateAllowanceFundDetails();
                    endLoading();
                } else {
                    handleNotification(
                        'Error Fetching Data From Server',
                        'error');
                    endLoading();
                }
            }
        });
}

function populateAllowanceFundDetails(){
    var employeeId = employeeIds.pop();
    if(allowanceId != undefined && employeeId != undefined){
        showLoading('Populating Allowance Details For The Employee');
        var buttonId = 'button'+employeeId;
        var insertedAmount = 'insertedAmount'+employeeId;
        var calculatedAmount = 'calculatedAmount'+employeeId;
        var employeeAmount = 'employeeAmount'+employeeId;
        var rowId = 'row'+employeeId;
        $('#'+buttonId).html(loaderImageTag);
        var data = 'employeeId='+employeeId+'&allowanceId='+allowanceId+'&task=getAllowanceAmount';
        $
            .ajax({
                url:formGlobalPath + "accounts/accounts_employee_allowance_bulk_update_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] != 0) {
                        var eAmount = allowanceType == 'c' ? output[1] : (0 - output[1]);
                        var cAmount = allowanceType == 'c' ? output[2] : (0 - output[2]);

                        $('#'+insertedAmount).val(eAmount);
                        $('#'+employeeAmount).val(eAmount);

                        $('#'+calculatedAmount).val(cAmount);
                        $('#'+employeeId).html(cAmount);

                        $('#'+buttonId).html(processImageTag);
                        $('#'+rowId).slideDown();

                        endLoading();
                        populateAllowanceFundDetails();
                    } else {
                        handleNotification(
                            'Error Fetching Data From Server',
                            'error');
                        endLoading();
                    }
                }
            });
    }
    return false;
}

// Code For Check Of Input Errors
function updateEmployeeMasterSalary(employeeId, nextEmployeeId) {
    // preparing for ajax call
    var insertedAmount = 'insertedAmount'+employeeId;
    var nextInsertedAmount = 'insertedAmount'+nextEmployeeId;
    var calculatedAmount = 'calculatedAmount'+employeeId;
    var employeeAmount = 'employeeAmount'+employeeId;

    var insertAmountValue = $('#'+insertedAmount).val();
    var calculatedAmountValue = $('#'+calculatedAmount).val();
    var employeeAmountValue = $('#'+employeeAmount).val();

    insertAmountValue = allowanceType == 'c' ? insertAmountValue : (0 - insertAmountValue);
    calculatedAmountValue = allowanceType == 'c' ? calculatedAmountValue : (0 - calculatedAmountValue);
    employeeAmountValue = allowanceType == 'c' ? employeeAmountValue : (0 - employeeAmountValue);

    if(insertAmountValue != employeeAmountValue){
        if(insertAmountValue === 0){
            dropRecord(employeeId, allowanceId);
        }else{
            processMasterSalaryUpdate(employeeId, allowanceId, insertAmountValue, calculatedAmountValue);
        }
    }else{
        moveDataToConfirmForm(employeeId, insertAmountValue, calculatedAmountValue, 'Not Updated');
    }
    $('#'+nextInsertedAmount).focus();
    return false;
}

function processMasterSalaryUpdate(employeeId, allowanceId, amount, calculatedAmount){
    var data = 'employeeId='+employeeId+'&allowanceId='+allowanceId+'&amount='+amount+'&calculatedAmount='+calculatedAmount+'&task=insertRecord&';
    var buttonName = 'button'+employeeId
    $('#'+buttonName).html(processImageTag);
    showLoading("Processing Employee Allowance Update");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_employee_allowance_bulk_update_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0] != 0) {
                    moveDataToConfirmForm(employeeId, amount, calculatedAmount, data[1]);
                } else {
                    handleNotification(
                        'Error While Inserting Data, Please Try Again',
                        'error');
                    endLoading();
                }
            }
        });
}

function moveDataToConfirmForm(employeeId, amount, calculatedAmount, overRidden){
    var rowId = 'row'+employeeId;
    var insertedAmount = allowanceType == 'c' ? amount : (0 - amount);
    var calculatedAmount = allowanceType == 'c' ? calculatedAmount : (0- calculatedAmount);
    var data = 'task=fetchEmployeeDetails&employeeId='+employeeId;
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_employee_master_salary_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] != 0){
                    var htmlTable = '<tr class="odd">' +
                        '<th>'+output[1]+'</th>' +
                        '<th>'+output[0]+'</th>' +
                        '<th>'+insertedAmount+'</th>' +
                        '<th>'+calculatedAmount+'</th>' +
                        '<th>'+overRidden+'</th>' +
                    '</tr>';
                    confirmTable.append(htmlTable);
                    $('#'+rowId).slideUp();
                    endLoading();
                }else{
                    loadPageIntoDisplay(serverUrl);
                    endLoading();
                }
                return false;
            }
        });
    return false;
}
// Code For Drop Value Begins here
function dropRecord(employeeId, allowanceId) {
    if (confirm('Do you really want to delete ?')) {
        showLoading("Processing Data");
        var data = 'task=dropRecord&employeeId=' + employeeId + '&allowanceId='+allowanceId;
        $
            .ajax({
                url:formGlobalPath + "accounts/accounts_employee_allowance_bulk_update_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] != 0) {
                        moveDataToConfirmForm(employeeId, 0, 0);
                        endLoading();
                    } else {
                        handleNotification(
                            'Error In Dropping Top Menu ... Try After Sometime',
                            'error');
                        endLoading();
                    }

                }
            });
    }
    return false;
}
// Code for Drop value Ends Here
