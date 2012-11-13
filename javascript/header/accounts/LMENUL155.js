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

function printConsolidatedSlip4Month(month){
    showLoading("Fetching Salary Details");
    var data = 'month='+month+'&task=printConsolidatedSlip';
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_consolidated_slip_form.php",
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

function printConsolidatedSlip(){
    var month = $('#month').val();
    completePageDisplay.html('');
    printConsolidatedSlip4Month(month);
    changeDate();
}

