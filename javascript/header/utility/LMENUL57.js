var valid = new validate();
$(document)
    .ready(
    function () {
        oTable = $('#groupSubjects').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });
        formGlobalPath = getFormGlobalPath();
        classGlobal = $('#class_global').val();
        getClassSessionDetails();


        $("#subjectName").autocomplete(formGlobalPath + "utility/utl_subject_details_form.php?option=subject", {
            width:260,
            matchContains:true,
            mustMatch:false,
            selectFirst:false
        });
        $("#subjectName").result(function (event, data, formatted) {
            $("#subjectName_val").val(data[1]);
        });

        $("#subjectName_u")
            .autocomplete(
            formGlobalPath
                + "utility/utl_subject_details_form.php?option=subject",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#subjectName_u").result(
            function (event, data, formatted) {
                $("#subjectName_uval").val(data[1]);
            });

        checkFormEditableOption();
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

function checkFormEditableOption(){
    var data = 'classId=' + classGlobal + '&task=checkSessionEditable';
    $
        .ajax({
            url:formGlobalPath + "utility/utl_class_details_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] == 0) {
                    $('#insertForm').remove();
                    $('#updateForm').remove();
                    $('#editRecordButton').remove();
                    $('#activateRecord_d').remove();
                    $('#dropRecord_d').remove();
                    $('#toggleInsert').remove();
                }
            }
        });
    return false;
}

function getClassSessionDetails(){
    var data = $('#insertForm').serialize();
    data = 'classId=' + classGlobal + '&task=getClassSessionDetails';
    $
        .ajax({
            url:formGlobalPath + "utility/utl_class_details_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {                
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    $('#session_d').html(output[0]);
                    $('#class_d').html(output[1]);
                    showTheDiv('sessionRecord');
                }
            }
        });
    return false;
}

// Code For Check Of Input Errors
function processInsertForm() {
// preparing for ajax call
    var data = $('#insertForm').serialize();
    data = 'classId=' + classGlobal + '&task=insertRecord&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_class_subject_form.php",
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
                    var browseButton = ' - ', editButton = ' - ';

                    if(output[4] == 1)
                        browseButton = "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"browseRecordDetails('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + browseImageLink + "Subject Lookup</button>";
                    if(output[5] == 1)
                        editButton = "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                            + output[0] + "', '" + aPos
                            + "')\">" + editImageLink + "Edit Details</button>";

                    oTable
                        .fnAddData([
                        output[1],
                        output[2],
                        output[3],
                        browseButton,
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + viewImageLink + "Show Details</button>",
                        editButton]);
                    //hideInsertForm();
                    hideUpdateForm();
                    showDatatable();
                    $('#insertReset').click();
                    $('#subject').focus();
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
    data = 'classId=' + classGlobal + '&task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_class_subject_form.php",
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
                    var browseButton = '', editButton = '';
                    if (data[0][0] != 0) {
                        oTable.fnClearTable();
                        for (var i = 0; i < data.length; i++) {
                            if(data[i][4] == 1)
                                browseButton = "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"browseRecordDetails('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + browseImageLink + "Subject Lookup</button>";
                            else
                                browseButton = '';
                            if(data[i][5] == 1)
                                editButton = "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                    + data[i][0] + "', '" + i
                                    + "')\">" + editImageLink + "Edit Details</button>";
                            else
                                editButton = '';

                            oTable
                                .fnAddData([
                                data[i][1],
                                data[i][2],
                                data[i][3],
                                browseButton,
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + viewImageLink + "Show Details</button>",
                                editButton]);
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
            }
        });
    return false;
}

function showRecordDetails(id, aPos) {
    showLoading("Fetching Data From Server");
    var data = 'classId='+classGlobal+'&task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "utility/utl_class_subject_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[1] != '') {
                $('#subject_d').html(data[1]);
                $('#type_d').html(data[3]);
                $('#subjectName_d').html(data[5]);
                $('#count').html(data[6]);
                $('#lastUpdateDateDisplay').html(data[7]);
                $('#lastUpdatedByDisplay').html(data[8]);
                $('#creationDateDisplay').html(data[9]);
                $('#createdByDisplay').html(data[10]);
                if (data[11] == 'y') {
                    $('#activeDisplay').html(
                        '<font class="green">Active</font>');
                    if(data[13] == 1){
                        $('#dropRecord_d').show();
                        $('#dropRecord_d')
                            .attr(
                            'onclick',
                            'dropRecord(\'' + data[0] + '\', \'' + aPos
                                + '\')');
                        $('#activateRecord_d').hide();
                    }
                } else {
                    $('#activeDisplay').html(
                        '<font class="red">Inactive</font>');
                    if(data[13] == 1){
                        $('#activateRecord_d').show();
                        $('#activateRecord_d').attr(
                            'onclick',
                            'activateRecord(\'' + data[0] + '\', \'' + aPos
                                + '\')');
                        $('#dropRecord_d').hide();
                    }
                }
                if(data[13] != 1){
                    $('#dropRecord_d').hide();
                    $('#activateRecord_d').hide();
                }else{
                    $('#editRecordButton').attr('onclick',
                        'editRecord(\'' + data[0] + '\', \'' + aPos + '\')');
                }
                if(data[12] == 1)
                    $('#showSubjectButtonD').attr(
                        'onclick',
                        'browseRecordDetails(\'' + data[0] + '\', \'' + aPos
                            + '\')');

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

// code for edit detaisl begins
function editRecord(id, aPos) {
    showLoading("Processing Data Into Update Form");
    data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "utility/utl_class_subject_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[13] == 1) {
                $('#subject_u').val(data[1]);
                $('#type_u').val(data[2]);
                $('#subjectName_uval').val(data[4]);
                $('#subjectName_u').val(data[5]);
                if (data[11] == 'y') {
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
                $('#associationId').val(data[14]);
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
        data = 'classId='+classGlobal+'&task=updateRecord&' + data;
        showLoading("Uploading Data To Server");
        $
            .ajax({
                url:formGlobalPath + "utility/utl_class_subject_form.php",
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
                        var browseButton = '', editButton = '';

                        if(output[4] == 1)
                            browseButton = "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"browseRecordDetails('"
                                + output[0]
                                + "', '"
                                + aPos
                                + "')\">" + browseImageLink + "Subject Lookup</button>";
                        if(output[5] == 1)
                            editButton = "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                + output[0] + "', '" + aPos
                                + "')\">" + editImageLink + "Edit Details</button>";
                        oTable
                            .fnUpdate(
                            [
                                output[1],
                                output[2],
                                output[3],
                                browseButton,
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + viewImageLink + "Show Details</button>",
                                editButton],
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
                url:formGlobalPath + "utility/utl_class_subject_form.php",
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
                            'Error In Dropping Class Subject ... Try After Sometime',
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
                url:formGlobalPath + "utility/utl_class_subject_form.php",
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
                            'Error In Activating Class Subject ... Try After Sometime',
                            'error');
                        endLoading();
                    }

                }
            });
    }
    return false;
}

function browseRecordDetails(id, aPos){
    var link = serverUrl +'pages/utility/utl_class_subject_map.php?subjectDetailsId='+id+'&classId='+classGlobal;
    loadPageIntoDisplay(link);
    return false;
}