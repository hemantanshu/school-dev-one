var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var allowanceId, allowanceType, month;
var employeeIds, confirmTable;

var imagePath, loaderImage, browseImage, loaderImageTag, processImageTag;
$(document)
    .ready(
    function () {
        imagePath = schoolImageGlobalPath() + 'global/';
        loaderImage = imagePath+'ajax-loader.gif';
        browseImage = imagePath+'icons/b_insrow.png';
        loaderImageTag = '<img src="'+loaderImage+'" alt="" />Processing Allowance';
        processImageTag = '<img src="'+browseImage+'" alt="" />Add Direct Salary';

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

function processAllowanceForm(){
    month = $('#month').val();
    allowanceId = $('#allowance_val').val();

    if(month != undefined && allowanceId != undefined){
        loadAllowanceDetails();
    }
    return false;
}

function loadAllowanceDetails(){
    var data = 'allowanceId='+allowanceId+'&month='+month+'&task=fetchAllowanceDetails';
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
                    $('#accountHeadName').html(output[1]);
                    $('#allowanceType').html(output[2]);
                    $('#monthName').html(output[4]);

                    allowanceType = output[3];

                    showPageDisplay();
                    hideChoiceListing();

                    getEmployeeIds();

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
        var rowId = 'row'+employeeId;

        var insertedAmount = 'insertedAmount'+employeeId;
        var employeeAmount = 'employeeAmount'+employeeId;

        var remarksInsert = 'remarks'+employeeId;
        var remarksActual = 'remarksActual'+employeeId;

        $('#'+buttonId).html(loaderImageTag);

        var data = 'employeeId='+employeeId+'&allowanceId='+allowanceId+'&month='+month+'&task=getAllowanceAmount';
        $
            .ajax({
                url:formGlobalPath + "accounts/accounts_employee_allowance_direct_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] != 0) {
                        var eAmount = allowanceType == 'c' ? output[1] : (0 - output[1]);

                        $('#'+insertedAmount).val(eAmount);
                        $('#'+employeeAmount).val(eAmount);

                        $('#'+remarksInsert).val(output[2]);
                        $('#'+remarksActual).val(output[2]);

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
function insertEmployeeDirectSalary(employeeId, nextEmployeeId) {
    // preparing for ajax call

    var nextInsertedAmount = 'insertedAmount'+nextEmployeeId;
    var insertedAmount = 'insertedAmount'+employeeId;
    var employeeAmount = 'employeeAmount'+employeeId;

    var remarksInsert = 'remarks'+employeeId;
    var remarksActual = 'remarksActual'+employeeId;

    var insertAmountValue = $('#'+insertedAmount).val();
    var employeeAmountValue = $('#'+employeeAmount).val();

    var remarks = $('#'+remarksInsert).val();
    var remarksActualValue = $('#'+remarksActual).val();


    employeeAmountValue = allowanceType == 'c' ? employeeAmountValue : (0 - employeeAmountValue);

    if((insertAmountValue != employeeAmountValue || remarks != remarksActual) && (insertAmountValue != undefined && remarks != undefined)){
        if(insertAmountValue === 0){
            dropRecord(employeeId, allowanceId, month);
        }else{
            insertAmountValue = allowanceType == 'c' ? insertAmountValue : (0 - insertAmountValue);
            processMasterSalaryUpdate(employeeId, allowanceId, month, insertAmountValue, remarks);
        }
    }else{
        moveDataToConfirmForm(employeeId, insertAmountValue, remarks);
    }
    $('#'+nextInsertedAmount).focus();
    return false;
}

function processMasterSalaryUpdate(employeeId, allowanceId, month, amount, remarks){
    var data = 'employeeId='+employeeId+'&allowanceId='+allowanceId+'&amount='+amount+'&month='+month+'&remarks='+remarks+'&task=insertRecord&';
    var buttonName = 'button'+employeeId

    $('#'+buttonName).html(processImageTag);
    showLoading("Processing Employee Allowance Update");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_employee_allowance_direct_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0] != 0) {
                    moveDataToConfirmForm(employeeId, amount, remarks);
                } else {
                    handleNotification(
                        'Error While Inserting Data, Please Try Again',
                        'error');
                    endLoading();
                }
            }
        });
}

function moveDataToConfirmForm(employeeId, amount, remarks){
    var rowId = 'row'+employeeId;
    var insertedAmount = allowanceType == 'c' ? amount : (0 - amount);
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
                        '<th>'+remarks+'</th>' +
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
function dropRecord(employeeId, allowanceId, month) {
    if (confirm('Do you really want to delete ?')) {
        showLoading("Processing Data");
        var data = 'task=dropRecord&employeeId=' + employeeId + '&month='+month+'&allowanceId='+allowanceId;
        $
            .ajax({
                url:formGlobalPath + "accounts/accounts_employee_allowance_direct_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] != 0) {
                        moveDataToConfirmForm(employeeId, 0, '');
                        endLoading();
                    } else {
                        handleNotification(
                            'Error In Dropping Direct Insertion ... Try After Sometime',
                            'error');
                        endLoading();
                    }

                }
            });
    }
    return false;
}
// Code for Drop value Ends Here
