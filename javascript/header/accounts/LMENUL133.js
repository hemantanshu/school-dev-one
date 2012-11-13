var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var employeeIds, completePageDisplay;

$(document)
    .ready(    		
    function () {
    	$('#bankTransferDetails').dataTable( {
    		"bJQueryUI":true,
            "sPaginationType":"full_numbers",
	        "sDom": 'T<"clear">lfrtip',
	        "oTableTools": {
	            "sSwfPath": serverUrl+"media/swf/copy_csv_xls_pdf.swf"
	        }
	    } );		
        completePageDisplay = $('#completePageDisplay');
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

function changeDate(){
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

function printBankSlip(month){
    showLoading("Fetching Salary Details For Bank Transfer");
    var data = 'month='+month+'&task=printBankSlip';
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_salary_bank_slip_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'html',
            success:function (output) {
                checkValidityOfOutput(output);
                completePageDisplay.append(output);
                printCashSlip(month);
            }
        });
    return false;
}

function printCashSlip(month){
    showLoading("Fetching Salary Details For Cash Payment");
    var data = 'month='+month+'&task=printCashSlip';
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_salary_bank_slip_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'html',
            success:function (output) {
                checkValidityOfOutput(output);
                completePageDisplay.append(output);
                printChequeSlip(month);
            }
        });
    return false;
}

function printChequeSlip(month){
    showLoading("Fetching Salary Details For Cheque / Draft Payment");
    var data = 'month='+month+'&task=printChequeSlip';
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_salary_bank_slip_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'html',
            success:function (output) {
                checkValidityOfOutput(output);
                completePageDisplay.append(output);
                endLoading();
            }
        });
    return false;
}

function printPaymentPaySlip(){
    var month = $('#month').val();
    completePageDisplay.html('');
    printBankSlip(month);
    changeDate();
}

