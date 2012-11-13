var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var employeeIds, confirmTable;
var imagePath, loaderImage, browseImage, loaderImageTag, processImageTag;
$(document)
    .ready(
    function () {
        imagePath = schoolImageGlobalPath() + 'global/';
        loaderImage = imagePath+'ajax-loader.gif';
        browseImage = imagePath+'icons/Create.png';
        loaderImageTag = '<img src="'+loaderImage+'" alt="" />Fetching Salary';
        processImageTag = '<img src="'+browseImage+'" alt="" />Process Salary';

        confirmTable = $('#updatedTableBody');
        getEmployeeIds();
    });


function getEmployeeSalaryDetails(employeeId){
    var days = $('#days'+employeeId).val();
    var url = serverUrl+'pages/accounts/accounts_salary_process_view.php?employeeId='+employeeId+'&days='+days;
    loadColorboxPage(url, 800);
    return false;
}

function getEmployeeIds(){
    showLoading('Fetching Employee Names From Server');
    var data = 'task=getEmployeeIds';
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_salary_process_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    employeeIds = output;
                    populateSalaryDetails();
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
function populateSalaryDetails(){
    var employeeId = employeeIds.pop();
    if(employeeId != undefined){
        showLoading('Populating Salary Details For The Employee');

        var buttonId = 'button'+employeeId;
        var button = $('#'+buttonId);
        button.html(loaderImageTag);
        var data = 'employeeId='+employeeId+'&task=getEmployeeSalaryAmount';
        $
            .ajax({
                url:formGlobalPath + "accounts/accounts_salary_process_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] != undefined) {
                        $('#'+employeeId).html(output[0]);
                        button.html(processImageTag);
                        endLoading();
                        populateSalaryDetails();
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

function processEmployeeSalary(employeeId){
    var workingDays = $('#days'+employeeId).val();
    var paymentMode = $('#payment'+employeeId).val();

    showLoading('Processing Salary For The Employee');
    $('#button'+employeeId).html(loaderImageTag);
    var data = 'employeeId='+employeeId+'&workingDays='+workingDays+'&paymentMode='+paymentMode+'&task=processEmployeeSalary';
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_salary_process_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != undefined) {
                    moveDataToConfirmForm(employeeId, output[0], workingDays, output[1]);
                    endLoading();
                } else {
                    $('#button'+employeeId).html(processImageTag);
                    handleNotification(
                        'Error Fetching Data From Server',
                        'error');
                    endLoading();
                }
            }
        });
    return false;
}

function moveDataToConfirmForm(employeeId, amount, workingDays, paymentMode){
    var rowId = 'row'+employeeId;
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
                        '<td>'+output[0]+'</td>' +
                        '<th>'+workingDays+'</th>' +
                        '<th>'+paymentMode+'</th>' +
                        '<th>'+amount+'</th>' +
                        '</tr>';
                    confirmTable.append(htmlTable);
                    $('#'+rowId).remove();
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

