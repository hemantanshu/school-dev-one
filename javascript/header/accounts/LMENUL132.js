var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var employeeIds, completePageDisplay;

$(document)
    .ready(
    function () {
        completePageDisplay = $('#completePageDisplay');
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

function printSlip(month){
    var employeeId = employeeIds.pop();
    if(employeeId != undefined){
        showLoading("Fetching Employee Salary Data From Server");
        var data = 'employeeId='+employeeId+'&month='+month+'&task=printSlip';
        $
            .ajax({
                url:formGlobalPath + "accounts/accounts_print_pay_slip_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'html',
                success:function (output) {
                    checkValidityOfOutput(output);
                    completePageDisplay.append(output);
                    printSlip(month);
                }
            });
        return false;
    }else{
        endLoading();
        return false;
    }

    return false;
}

function printEmployeePaySlip(){
    var employeeId = $('#employee_val').val();
    if(employeeId != undefined){  
    	var month = $('#monthEmployee').val();
    	employeeIds = new Array();
    	employeeIds.push(employeeId);
    	console.log(employeeIds);
        completePageDisplay.html('');
        changeEmployeeName();
        printSlip(month);
        endLoading();
    }
}

function printDepartmentEmployeePaySlip(){
    var departmentId = $('#departmentName').val();
    var month = $('#monthDepartment').val();
    var data = 'departmentId='+departmentId+'&month='+month+'&task=fetchDepartmentEmployees';
    showLoading("Fetching Employee Data From Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_print_pay_slip_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] == 1){
                    alert('No Employee Exists For The Given Combination');
                    endLoading();
                }else{
                    employeeIds = output;
                    completePageDisplay.html('');
                    changeEmployeeName();
                    printSlip(month);
                    endLoading();
                }
            }
        });
    return false;
}

function printEmployeeTypeEmployeePaySlip(){
    var employeeType = $('#employeeType').val();
    var month = $('#monthType').val();
    var data = 'employeeType='+employeeType+'&month='+month+'&task=fetchEmployeeTypeEmployeeIds';
    showLoading("Fetching Employee Data From Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_print_pay_slip_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] == 1){
                    alert('No Employee Exists For The Given Combination');
                    endLoading();
                }else{
                    employeeIds = output;
                    completePageDisplay.html('');
                    changeEmployeeName();
                    printSlip(month);
                    endLoading();
                }
            }
        });
    return false;
}

function printAllEmployeePaySlip(){
    var month = $('#monthAll').val();
    var data = 'month='+month+'&task=fetchAllEmployeeIds';
    showLoading("Fetching Employee Data From Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_print_pay_slip_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] == 1){
                    alert('No Employee Exists For The Given Combination');
                    endLoading();
                }else{
                    employeeIds = output;
                    completePageDisplay.html('');
                    changeEmployeeName();
                    printSlip(month);
                    endLoading();
                }
            }
        });
    return false;
}



