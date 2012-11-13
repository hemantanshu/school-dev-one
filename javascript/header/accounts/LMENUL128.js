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

        $("#employee")
            .autocomplete(
            formGlobalPath
                + "utility/utl_user_autocomplete.php?option=employeeTeacher",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#employee").result(
            function (event, data, formatted) {
                $("#employee_val").val(data[1]);
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
        populateEmployeeSalaryDetails();
        $('#employeeSalary').html('');
        hideChoiceListing();
        showPageDisplay();
    }
    return false;
}

function loadEmployeePersonalDetails(){
    var data = 'employeeId='+employeeId+'&task=fetchEmployeeDetails';
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_employee_master_salary_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    $('#employeeName').html(output[0]);
                    $('#employeeCode').html(output[1]);
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

function populateEmployeeSalaryDetails(){
    showLoading('Fetching Details From Server');
    var data = 'employeeId='+employeeId+'&task=getSalaryDetails';
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_fake_payslip_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'html',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output != 0) {
                    $('#employeeSalary').html(output);
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



