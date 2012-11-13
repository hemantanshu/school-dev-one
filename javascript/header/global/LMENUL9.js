var valid = new validate();
$(document).ready(function () {
    oTable = $('#groupValues').dataTable({
        "bJQueryUI":true,
        "sPaginationType":"full_numbers"
    });
    hideUpdateForm();
    hideDisplayPortion();
    showHideDatatable();
    getOptionValueSearchDetails();
    formGlobalPath = getFormGlobalPath();
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
function showUpdateForm() {
    showTheDiv('updateForm');
}
function hideUpdateForm() {
    hideTheDiv('updateForm');
}

function showDisplayPortion() {
    showTheDiv('displayValue');
}
function hideDisplayPortion() {
    hideTheDiv('displayValue');
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

//For Inserting New Value Name
//Checking for Input Errors
function processMainForm() {
    //preparing for ajax call
    var optionTypeId = $('#optionType_glb').val();
    var data = $('#insertForm').serialize();
    data = 'optionId_glb=' + optionTypeId + '&task=insertOptionValue&' + data;
    showLoading("Uploading Data To Server");
    $.ajax({
        url:formGlobalPath + "global/glb_option_values_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (output) {
            checkValidityOfOutput(output);
            if (output[0] != 0) {
                aPos = oTable.fnGetNodes().length;
                var viewImageLink = getButtonViewImage();
                var editImageLink = getButtonEditImage();
                oTable.fnAddData(
                    [
                        output[0],
                        output[1],
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showOptionValueDetails('" + output[0] + "', '" + aPos + "')\">" + viewImageLink + "Show Details</button>",
                        "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editOptionValue('"
                            + output[0] + "', '" + aPos + "')\">" + editImageLink + "Edit Details</button>"
                    ]);
                hideInsertForm();
                hideUpdateForm();
                showDatatable();
                $('#insertReset').click();
                endLoading();
            } else {
                handleNotification('Error While Inserting Data, Please Try Again', 'error');
                endLoading();
            }
        }
    });
    return false;
}
//code For Inserting New value Name Ends Here

//Code for Search Begins Here
function getOptionValueSearchDetails() {
    var optionTypeId = $('#optionType_glb').val();
    var data = $('#searchForm').serialize();
    data = 'optionId_glb=' + optionTypeId + '&task=search&' + data;
    showLoading("Fetching Data From Server");
    $.ajax({
        url:formGlobalPath + "global/glb_option_values_form.php",
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
                handleNotification('No Data Fetched With The Given Inputs', 'info');
                endLoading();
            } else {
                if (data[0][0] != 0) {
                    oTable.fnClearTable();
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    for (var i = 0; i < data.length; i++) {
                        oTable.fnAddData(
                            [
                                data[i][0],
                                data[i][1],
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showOptionValueDetails('" + data[i][0] + "', '" + i + "')\">" + viewImageLink + "Show Details</button>",
                                "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editOptionValue('" + data[i][0] + "', '" + i + "')\">" + editImageLink + "Edit Details</button>"
                            ]);
                    }
                    hideInsertForm();
                    hideDisplayPortion();
                    showDatatable();
                    endLoading();
                }
                else {
                    handleNotification('Error While Processing Data, Please Try Again', 'error');
                    endLoading();
                }
            }
        }
    });
    return false;
}
//Code For Search Ends Here

//Portion For Show Details Begins Here
function showOptionValueDetails(id, aPos) {
    showLoading("Fetchign Data From Server");
    data = 'task=getOptionValueDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "global/glb_option_values_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#optionId').html(data[0]);
                $('#valueName_dDisplay').html(data[1]);
                $('#totalDisplay').html(data[2]);
                $('#lastUpdateDateDisplay').html(data[2]);
                $('#lastUpdatedByDisplay').html(data[3]);
                $('#creationDateDisplay').html(data[4]);
                $('#createdByDisplay').html(data[5]);
                if (data[6] == 'y') {
                    $('#activeDisplay').html('<font class="green">Active</font>');
                    $('#dropOptionValue_d').show();
                    $('#dropOptionValue_d').attr('onclick', 'dropOptionValue(\'' + data[0] + '\', \'' + aPos + '\')');
                    $('#activateOptionValue_d').hide();
                }
                else {
                    $('#activeDisplay').html('<font class="red">Inactive</font>');
                    $('#activateOptionValue_d').show();
                    $('#activateOptionValue_d').attr('onclick', 'activateOptionValue(\'' + data[0] + '\', \'' + aPos + '\')');
                    $('#dropOptionValue_d').hide();
                }
                $('#update_value_button').attr('onclick', 'editOptionValue(\'' + data[0] + '\', \'' + aPos + '\')');
                $('#valueId_d').val(data[0]);
                $('#rowPosition_d').val(aPos);
                hideUpdateForm();
                showDisplayPortion();
                hideInsertForm();
                endLoading();
            }
            else {
                handleNotification('Error While Processing Data, Please Try Again', 'error');
                endLoading();
            }
        }
    });
    return false;
}
//code for show details ends here

//code for edit detaisl begins
function editOptionValue(id, aPos) {
    showLoading("Processing Data Into Update Form");
    data = 'task=getOptionValueDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "global/glb_option_values_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#valueName_u').val(data[1]);
                if (data[6] == 'y') {
                    $('#dropOptionValue_u').show();
                    $('#dropOptionValue_u').attr('onclick', 'dropOptionValue(\'' + data[0] + '\', \'' + aPos + '\')');
                    $('#activateOptionValue_u').hide();
                }
                else {
                    $('#activateOptionValue_u').show();
                    $('#activateOptionValue_u').attr('onclick', 'activateOptionValue(\'' + data[0] + '\', \'' + aPos + '\')');
                    $('#dropOptionValue_u').hide();
                }
                $('#valueId_u').val(data[0]);
                $('#rowPosition_u').val(aPos);
                hideDisplayPortion();
                hideInsertForm();
                showUpdateForm();
                endLoading();
            }
            else {
                handleNotification('Error While Processing Data, Please Try Again', 'error');
                endLoading();
            }
        }
    });
    return false;
}
//code for edit details ends here

//Code For Update Form Begins Here
function processUpdateForm() {
    //validation process
    var id = $('#valueId_u').val();
    if (id) {

        var value_name = $('#valueName_u');
        var aPos = $('#rowPosition_u').val();
        aPos = parseInt(aPos);
        //preparing for ajax call
        var data = $('#updateForm').serialize();
        data = 'task=updateOptionValue&' + data;
        showLoading("Uploading Data To Server");
        $.ajax({
            url:formGlobalPath + "global/glb_option_values_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    oTable.fnUpdate([
                        output[0],
                        output[1],
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showOptionValueDetails('" + output[0] + "', '" + aPos + "')\">" + viewImageLink + "Show Details</button>",
                        "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editOptionValue('" + output[0] + "', '" + aPos + "')\">" + editImageLink + "Edit Details</button>"
                    ], aPos);
                    hideUpdateForm();
                    showOptionValueDetails(output[0], aPos);
                    endLoading();
                } else {
                    handleNotification('Error While Updating Data, Please Try Again', 'error');
                    endLoading();
                }
            }
        });
    }
    return false;
}
//Code For Update Form Ends here

//Code For Drop Value Begins here
function dropOptionValue(id, aPos) {
    if (confirm('Do you really want to delete ?')) {
        showLoading("Processing Data");
        data = 'task=dropId&id=' + id;
        $.ajax({
            url:formGlobalPath + "global/glb_option_values_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    showOptionValueDetails(id, aPos);
                    hideUpdateForm();
                    endLoading();
                } else {
                    handleNotification('Error In Dropping Top Menu ... Try After Sometime', 'error');
                    endLoading();
                }

            }
        });
    }
    return false;
}
//Code for Drop value Ends Here

//Code For Activate Value Begins Here
function activateOptionValue(id, aPos) {
    if (confirm('Do you really want to activate ?')) {
        showLoading("Processing Data");
        data = 'task=activateId&id=' + id;
        $.ajax({
            url:formGlobalPath + "global/glb_option_values_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    showOptionValueDetails(id, aPos);
                    hideUpdateForm();
                    endLoading();
                } else {
                    handleNotification('Error In Activating Top Menu ... Try After Sometime', 'error');
                    endLoading();
                }

            }
        });
    }
    return false;
}
//Code For Activate Value Ends Here


function processChangeForm() {
    var error = false;
    var errorMessage = '';

    var pValue_val = $('#pValue_val');
    var cValue_val = $('#cValue_val');
}

function showValuesAssociated(id, aPos) {
    window.location = "./glb_option_values.php?optionTypeId=" + id;
}


