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
        processImageTag = '<img src="'+browseImage+'" alt="" />Salary Unprocessing';

        confirmTable = $('#updatedTableBody');
    });


function getEmployeeSalaryDetails(employeeId, month){
    var days = $('#days'+employeeId).val();
    var url = serverUrl+'pages/accounts/accounts_salary_employee.php?employeeId='+employeeId+'&month='+month;
    loadColorboxPage(url, 1000);
    return false;
}
function unProcessEmployeeSalary(employeeId){
    if(confirm('Do you really want to roll back employee salary ?')){
        showLoading('UnProcessing Salary For The Employee');
        $('#button'+employeeId).html(loaderImageTag);
        var data = 'employeeId='+employeeId+'&task=unProcessEmployeeSalary';
        $
            .ajax({
                url:formGlobalPath + "accounts/accounts_salary_rollback_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] == 1) {
                        moveDataToConfirmForm(employeeId);
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
    }
    return false;
}

function moveDataToConfirmForm(employeeId){
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
                        '<td>'+output[0]+'</td>'
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

