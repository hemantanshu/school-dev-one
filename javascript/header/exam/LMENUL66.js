var valid = new validate();
$(document)
    .ready(
    function () {
        oTable = $('#groupRecords').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });
        formGlobalPath = getFormGlobalPath();
        getSearchResults();
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
    showTheDiv('displayRecord');
}
function hideDisplayPortion() {
    hideTheDiv('displayRecord');
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


function processInsertForm() {
// preparing for ajax call
    var data = $('#insertForm').serialize();
    data = 'task=insertRecord&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "exam/exam_grading_type_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] != 0){
                    aPos = oTable.fnGetNodes().length;
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    var browseImageLink = getButtonBrowseImage();
                    oTable
                        .fnAddData([
                        output[1],
                        output[2],
                        "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"showGradeOptions('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + browseImageLink + "Browse Grade Options</button>",
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + viewImageLink + "Show Details</button>",
                        "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                            + output[0] + "', '" + aPos
                            + "')\">" + editImageLink + "Edit Details</button>"]);
                    hideUpdateForm();
                    showDatatable();
                    $('#insertReset').click();
                    endLoading();
                }else{
                    handleNotification(
                        'Error While Inserting Data, Please Try Again',
                        'error');
                    endLoading();
                }
            }
        });
    return false;
}

function getSearchResults() {
    var data = $('#searchForm').serialize();
    data = 'task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "exam/exam_grading_type_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                
                checkValidityOfOutput(data);
                if (data[0][0] == 1) {
                    oTable.fnClearTable();
                    hideDisplayPortion();
                    handleNotification(
                        'No Data Fetched With The Given Inputs', 'info');
                    endLoading();
                } else {
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    var browseImageLink = getButtonBrowseImage();
                    var editButton;
                    if (data[0][0] != 0) {
                        oTable.fnClearTable();
                        for (var i = 0; i < data.length; i++) {
                            oTable
                                .fnAddData([
                                data[i][1],
                                data[i][2],
                                "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"showGradeOptions('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + browseImageLink + "Browse Grade Options</button>",
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + viewImageLink + "Show Details</button>",
                                "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                    + data[i][0] + "', '" + i
                                    + "')\">" + editImageLink + "Edit Details</button>"]);
                        }
                        hideInsertForm();
                        hideDisplayPortion();
                        showDatatable();
                        endLoading();
                    } else {
                        handleNotification(
                            'Error While Processing Data, Please Try Again',
                            'error');
                        endLoading();
                    }
                }
                endLoading();
            }
        });
    return false;
}

function showRecordDetails(id, aPos) {
    showLoading("Fetching Data From Server");
    var data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "exam/exam_grading_type_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[1] != '') {
                $('#gradingType_d').html(data[1]);
                $('#totalGrades_d').html(data[2]);
                $('#lastUpdateDateDisplay').html(data[3]);
                $('#lastUpdatedByDisplay').html(data[4]);
                $('#creationDateDisplay').html(data[5]);
                $('#createdByDisplay').html(data[6]);
                if (data[7] == 'y') {
                    $('#activeDisplay').html(
                        '<font class="green">Active</font>');
                    $('#dropRecord_d').show();
                    $('#dropRecord_d')
                        .attr(
                        'onclick',
                        'dropRecord(\'' + data[0] + '\', \'' + aPos
                            + '\')');
                    $('#activateRecord_d').hide();
                } else {
                    $('#activeDisplay').html(
                        '<font class="red">Inactive</font>');
                    $('#activateRecord_d').show();
                    $('#activateRecord_d').attr(
                        'onclick',
                        'activateRecord(\'' + data[0] + '\', \'' + aPos
                            + '\')');
                    $('#dropRecord_d').hide();
                }
                $('#editRecordButton').attr('onclick',
                    'editRecord(\'' + data[0] + '\', \'' + aPos + '\')');
                $('#valueId_d').val(data[0]);
                $('#rowPosition_d').val(aPos);
                hideUpdateForm();
                showDisplayPortion();
                hideInsertForm();
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

// code for edit details begins
function editRecord(id, aPos) {
    showLoading("Processing Data Into Update Form");
    var data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "exam/exam_grading_type_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#gradingType_u').val(data[1]);
                if (data[7] == 'y') {
                    $('#dropRecord_u').show();
                    $('#dropRecord_u')
                        .attr(
                        'onclick',
                        'dropRecord(\'' + data[0] + '\', \'' + aPos
                            + '\')');
                    $('#activateRecord_u').hide();
                } else {
                    $('#activateRecord_u').show();
                    $('#activateRecord_u').attr(
                        'onclick',
                        'activateRecord(\'' + data[0] + '\', \'' + aPos
                            + '\')');
                    $('#dropRecord_u').hide();
                }
                $('#valueId_u').val(data[0]);
                $('#rowPosition_u').val(aPos);
                hideDisplayPortion();
                hideInsertForm();
                showUpdateForm();
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
function processUpdateForm() {
    // validation process
    var id = $('#valueId_u').val();
    if (id) {
        var aPos = $('#rowPosition_u').val();
        aPos = parseInt(aPos);
        // preparing for ajax call
        var data = $('#updateForm').serialize();
        data = 'task=updateRecord&' + data;
        showLoading("Uploading Data To Server");
        $
            .ajax({
                url:formGlobalPath + "exam/exam_grading_type_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] != 0) {
                        var viewImageLink = getButtonViewImage();
                        var editImageLink = getButtonEditImage();
                        var browseImageLink = getButtonBrowseImage();
                        oTable
                            .fnUpdate(
                            [
                                output[1],
                                output[2],
                                "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"showGradeOptions('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + browseImageLink + "Browse Grade Options</button>",
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + viewImageLink + "Show Details</button>",
                                "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                    + output[0] + "', '" + aPos
                                    + "')\">" + editImageLink + "Edit Details</button>"],
                            aPos);
                        hideUpdateForm();
                        showRecordDetails(output[0], aPos);
                        showDatatable();
                        endLoading();
                    } else {
                        handleNotification(
                            'Error While Updating Data, Please Try Again',
                            'error');
                        endLoading();
                    }
                }
            });
    }
    return false;
}
// Code For Update Form Ends here

// Code For Drop Value Begins here
function dropRecord(id, aPos) {
    if (confirm('Do you really want to delete ?')) {
        showLoading("Processing Data");
        data = 'task=dropRecord&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "exam/exam_grading_type_form.php",
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
                            'Error In Dropping Grading Type ... Try After Sometime',
                            'error');
                        endLoading();
                    }

                }
            });
    }
    return false;
}
// Code for Drop value Ends Here

// Code For Activate Value Begins Here
function activateRecord(id, aPos) {
    if (confirm('Do you really want to activate ?')) {
        showLoading("Processing Data");
        data = 'task=activateRecord&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "exam/exam_grading_type_form.php",
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
                            'Error In Activating Grading Type ... Try After Sometime',
                            'error');
                        endLoading();
                    }

                }
            });
    }
    return false;
}

function showGradeOptions(id, aPos){
    var link = serverUrl+'pages/exam/exam_grading_options.php?gradeId='+id;
    loadPageIntoDisplay(link);
    return false;
}