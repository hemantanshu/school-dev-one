var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var error = 0;

$(document)
    .ready(
    function () {
        oTable = $('#recordListing').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });
        getLatestDate();
    });

function showInsertForm() {
    getLatestDate();
    showTheDiv('insertForm');
    $('#sessionName').focus();
}
function hideInsertForm() {
    hideTheDiv('insertForm');
}
function toggleInsertForm() {
    if (!$('#insertForm').is(':visible')) {
        $('#sessionName').focus();
        getLatestDate();
    }
    toggleTheDiv('insertForm');
}
function showUpdateForm() {
    $('#sessionName_u').focus();
    showTheDiv('updateForm');

}
function hideUpdateForm() {
    hideTheDiv('updateForm');
}
function showDisplayPortion() {
    showTheDiv('displayPortion');
}
function hideDisplayPortion() {
    hideTheDiv('displayPortion');
}
function showHideSearchForm() {
    toggleTheDiv('searchForm');
}
function showHideDatatable() {
    toggleTheDiv('tabulatedRecords');
}
function showDatatable() {
    showTheDiv('tabulatedRecords');
}
function hideDatatable() {
    hideTheDiv('tabulatedRecords');
}

//Code For Check Of Input Errors
function processInsertForm() {
    var data = $('#insertForm').serialize();
    data = 'task=insertRecord&' + data;

    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "global/glb_class_session_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] == 1) {
                    aPos = oTable.fnGetNodes().length;
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    oTable
                        .fnAddData([
                            output[2],
                            output[3],
                            output[4],
                            "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                + output[1]
                                + "', '"
                                + aPos
                                + "')\">" + viewImageLink + "Show Details</button>",
                            "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                + output[1] + "', '" + aPos
                                + "')\">" + editImageLink + "Edit Details</button>" ]);
                    $('#insertReset').click();
                    hideInsertForm();
                    showDatatable();
                    endLoading();
                } else{
                    if(output[0] == 2){
                        for (var i = 3; i <= output[2]; i++) {
                            $('#'+output[i]+'').val('');
                        }
                        endLoading();
                        jAlert(output[1]);
                        $('#insertSubmit').click();
                    }else {
                        handleNotification(
                            'There are some issues with the form submission, please try after some time',
                            'error');
                        endLoading();
                    }
                }
            }
        });
    return false;
}
//code For Inserting New value Name Ends Here

//Code for Search Begins Here
function getSearchDetails() {
    var data = $('#searchForm').serialize();
    data = 'task=searchRecord&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "global/glb_class_session_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0][0] == 1) {
                    oTable.fnClearTable();
                    hideDatatable();
                    handleNotification('No Record Found With The Given Inputs', 'error');
                    endLoading();
                } else {
                    if (data[0][0] != 0) {
                        oTable.fnClearTable();
                        var viewImageLink = getButtonViewImage();
                        var editImageLink = getButtonEditImage();

                        for (var i = 0; i < data.length; i++) {
                            if(data[i][4] == 1)
                                var button = "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + editImageLink + "Edit Record</button>"
                            else
                                var button = '';
                            oTable
                                .fnAddData([
                                data[i][1],
                                data[i][2],
                                data[i][3],
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + viewImageLink + "Show Details</button>",
                                button
                                 ]);
                        }
                        hideInsertForm();
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
        });
    return false;
}
//Code For Search Ends Here

//Code For Show Details Begins Here
function showRecordDetails(id, aPos) {
    showLoading("Fetching Data From Server");
    var data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "global/glb_class_session_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                var updateButton = $('#updateButton');

                $('#sessionName_d').html(data[1]);
                $('#sessionId').html(data[0]);
                $('#startDate_d').html(data[3]);
                $('#endDate_d').html(data[5]);
                $('#lastUpdateDateDisplay').html(data[6]);
                $('#lastUpdatedByDisplay').html(data[7]);
                $('#creationDateDisplay').html(data[8]);
                $('#createdByDisplay').html(data[9]);

                if (data[10] == 'y') {
                    $('#activeDisplay').html(
                        '<font class="green">Active</font>');
                    updateButton.attr('onclick',
                        'editRecord(\'' + data[0] + '\', \'' + aPos + '\')');
                    updateButton.show();
                } else {
                    $('#activeDisplay').html(
                        '<font class="red">Inactive</font>');
                    updateButton.hide();
                }
                $('#valueId_d').val(data[0]);
                $('#position_d').val(aPos);
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
//code for show details ends here

//code for edit detaisl begins
function editRecord(id, aPos) {
    showLoading("Processing Data Into Update Form");
    data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "global/glb_class_session_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#sessionName_u').val(data[1]);
                $('#startDate_u').val(data[2]);
                $('#endDate_u').val(data[4]);
                $('#valueId_u').val(id);
                $('#position_u').val(aPos);
                showUpdateForm();
                hideDisplayPortion();
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

//Code For Update Form Begins Here
function processUpdateForm() {
    //validation process
    var id = $('#valueId_u').val();
    if (id) {
        var aPos = $('#position_u').val();
        aPos = parseInt(aPos);
        //preparing for ajax call
        var data = $('#updateForm').serialize();
        data = 'task=updateRecord&' + data;
        showLoading("Uploading Data To Server");
        $
            .ajax({
                url:formGlobalPath + "global/glb_class_session_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] == 1) {
                        var viewImageLink = getButtonViewImage();
                        var editImageLink = getButtonEditImage();
                        oTable.fnUpdate(
                            [
                                output[2],
                                output[3],
                                output[4],
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + output[1]
                                    + "', '"
                                    + aPos
                                    + "')\">" + viewImageLink + "Show Details</button>",
                                "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                    + output[1] + "', '" + aPos
                                    + "')\">" + editImageLink + "Edit Details</button>" ]
                            , aPos);
                        hideUpdateForm();
                        showRecordDetails(id, aPos);
                        showDatatable();
                        endLoading();
                    } else{
                        if(output[0] == 3){
                            handleNotification('No data changes made in the form', 'info');
                            endLoading();
                            hideUpdateForm();
                            showRecordDetails(id, aPos);
                        }else{
                            if(output[0] == 2){
                                for (var i = 3; i <= output[2]; i++) {
                                    $('#'+output[i]+'').val('');
                                }
                                endLoading();
                                jAlert(output[1]);
                            }else {
                                handleNotification(
                                    'There are some issues with the form submission, please try after some time',
                                    'error');
                                endLoading();
                            }
                        }
                    }
                }
            });
    }
    return false;
}
//Code For Update Form Ends here

//Code For Drop Value Begins here
function dropRecord(id, aPos) {
    if (confirm('Do you really want to delete ?')) {
        showLoading("Processing Data");
        var data = 'task=dropRecord&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "global/glb_class_session_form.php",
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
                            'Error In Dropping Top Menu ... Try After Sometime',
                            'error');
                        endLoading();
                    }

                }
            });
    }
    return false;
}
//Code for Drop value Ends Here

//Code For Activate Value Begins Here
function activateRecord(id, aPos) {
    if (confirm('Do you really want to activate ?')) {
        showLoading("Processing Data");
        var data = 'task=activateRecord&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "global/glb_class_session_form.php",
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
                            'Error In Activating Top Menu ... Try After Sometime',
                            'error');
                        endLoading();
                    }

                }
            });
    }
    return false;
}

function getLatestDate() {
    var data = 'task=getStartDate';
    $
        .ajax({
            url:formGlobalPath + "global/glb_class_session_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    $('#startDate').val(output[0]);
                } else {
                    endLoading();
                }
            }
        });
    return false;
}
