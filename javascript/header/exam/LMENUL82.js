var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var resultId, sectionId, sessionId, classId, assessmentId;

$(document)
    .ready(
    function () {
        oTable = $('#groupRecords').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });
        $(".date").dateinput({
            format: 'yyyy-mm-dd'
        });
        populateInitialElements();
        getSearchResults();

        $("#markSubmissionOfficer_i")
            .autocomplete(
            formGlobalPath
                + "utility/utl_user_autocomplete.php?option=employeeTeacher",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#markSubmissionOfficer_i").result(
            function (event, data, formatted) {
                $("#markSubmissionOfficer_ival").val(data[1]);
            });

        $("#markSubmissionOfficer_u")
            .autocomplete(
            formGlobalPath
                + "utility/utl_user_autocomplete.php?option=employeeTeacher",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#markSubmissionOfficer_u").result(
            function (event, data, formatted) {
                $("#markSubmissionOfficer_uval").val(data[1]);
            });

        $("#markVerificationOfficer_i")
            .autocomplete(
            formGlobalPath
                + "utility/utl_user_autocomplete.php?option=employeeTeacher",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#markVerificationOfficer_i").result(
            function (event, data, formatted) {
                $("#markVerificationOfficer_ival").val(data[1]);
            });

        $("#markVerificationOfficer_u")
            .autocomplete(
            formGlobalPath
                + "utility/utl_user_autocomplete.php?option=employeeTeacher",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#markVerificationOfficer_u").result(
            function (event, data, formatted) {
                $("#markVerificationOfficer_uval").val(data[1]);
            });

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

function populateInitialElements(){
    assessmentId = $('#assessmentId').val();
    showLoading('Loading Assessment Details');
    var data = 'task=getAssessmentDetails&assessmentId='+assessmentId;
    $
        .ajax({
            url:formGlobalPath + "exam/exam_assessment_subject_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] != 0){
                    $('#resultName').html(output[1]);
                    $('#sessionName').html(output[0]);
                    $('#assessmentName').html(output[2]);
                    $('#markingType').html(output[3]);
                    $('#className').html(output[4]);
                    $('#sectionName').html(output[5]);

                    resultId = output[7];
                    classId = output[8];
                    sectionId = output[9];
                    sessionId = output[6];
                    populateSubjectNames();
                }else{
                    loadPageIntoDisplay(serverUrl);
                }
                return false;
            }
        });
    endLoading();
    return false;
}

function populateSubjectNames(){
    var data = 'task=getClassAssignedSubjects&classId='+classId;
    $
        .ajax({
            url:formGlobalPath + "exam/exam_examination_dates_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0][0] == 1) {
                    jAlert("No subject definition has been added to this class. please add one..");
                    var url = serverUrl+'pages/utility/utl_class_subject.php?classId='+classId;
                } else {
                    if (data[0][0] != 0) {
                        var options = '';
                        for (var i = 0; i < data.length; i++) {
                            options = options + '<option value="'+data[i][0]+'">'+data[i][1]+' '+data[i][2]+'</option>';
                        }
                        $('#subjectName_i').html(options);
                    } else {
                        handleNotification(
                            'Error While Processing Data, Please Try Again',
                            'error');
                        endLoading();
                    }
                }
            }
        });
}


// Code For Check Of Input Errors
function processInsertForm() {
    // preparing for ajax call
    var data = $('#insertForm').serialize();
    data = 'sessionId='+sessionId+'&classId='+classId+'&resultId='+resultId+'&assessmentId='+assessmentId+'&sectionId='+sectionId+'&task=insertRecord&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "exam/exam_assessment_subject_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0] != 0) {
                    aPos = oTable.fnGetNodes().length;
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    var browseImageLink = getButtonBrowseImage();
                    oTable
                        .fnAddData([
                        data[1],
                        data[2],
                        "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"showMarkProgress('"
                            + data[0]
                            + "', '"
                            + aPos
                            + "')\">" + browseImageLink + "Check Marks</button>",
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                            + data[0]
                            + "', '"
                            + aPos
                            + "')\">" + viewImageLink + "Show Details</button>",
                        "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                            + data[0]
                            + "', '"
                            + aPos
                            + "')\">" + editImageLink + "Edit Details</button>" ]);
                    hideInsertForm();
                    hideDisplayPortion();
                    showDatatable();
                    endLoading();
                    $('#insertReset').click();
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

function getSearchResults() {
    var data = $('#searchForm').serialize();
    data = 'assessmentId='+assessmentId+'&task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "exam/exam_assessment_subject_form.php",
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
                    handleNotification(
                        'No Data Fetched With The Given Inputs', 'info');
                    endLoading();
                } else {
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    if(data[0][0] == 1){
                        handleNotification('No Data Exists For The Given Combination', 'notification');
                        endLoading();
                    }else{
                        if (data[0][0] != 0) {
                            oTable.fnClearTable();
                            var browseImageLink = getButtonBrowseImage();
                            for (var i = 0; i < data.length; i++) {
                                if(data[i][3] == 1)
                                    var editButton = "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                        + data[i][0]
                                        + "', '"
                                        + i
                                        + "')\">" + editImageLink + "Edit Details</button>";
                                else
                                    var editButton = '';

                                oTable
                                    .fnAddData([
                                    data[i][1],
                                    data[i][2],
                                    "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"showMarkProgress('"
                                        + data[i][0]
                                        + "', '"
                                        + i
                                        + "')\">" + browseImageLink + "Check Marks</button>",
                                    "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                        + data[i][0]
                                        + "', '"
                                        + i
                                        + "')\">" + viewImageLink + " Show Details</button>",
                                    editButton ]);
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
            }
        });
    return false;
}

function showRecordDetails(id, aPos) {
    showLoading("Fetching Data From Server");
    data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "exam/exam_assessment_subject_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            
            checkValidityOfOutput(data);
            if (data[1] != '') {
                $('#activityName_d').html(data[1]);
                $('#activityOrder_d').html(data[2]);
                $('#subjectName_d').html(data[3]);
                $('#markSubmissionDate_d').html(data[4]);
                $('#markSubmissionOfficer_d').html(data[5]);
                $('#markVerificationDate_d').html(data[6]);
                $('#markVerificationOfficer_d').html(data[7]);

                $('#lastUpdateDateDisplay').html(data[8]);
                $('#lastUpdatedByDisplay').html(data[9]);
                $('#creationDateDisplay').html(data[10]);
                $('#createdByDisplay').html(data[11]);
                if (data[12] == 'y') {
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

                if(data[18] == 1)
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

// code for Edit begins
function editRecord(id, aPos) {
    showLoading("Processing Data Into Update Form");
    data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "exam/exam_assessment_subject_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            
            checkValidityOfOutput(data);
            if (data[13] == 1) {
                $('#activityName_u').val(data[1]);
                $('#activityOrder_u').val(data[2]);
                $('#subjectName_u').val(data[3]);
                $('#markSubmissionDate_u').val(data[15]);
                $('#markSubmissionOfficer_u').val(data[5]);
                $('#markSubmissionOfficer_uval').val(data[16]);
                $('#markVerificationDate_u').val(data[17]);
                $('#markVerificationOfficer_u').val(data[7]);
                $('#markVerificationOfficer_uval').val(data[18]);

                if (data[12] == 'y') {
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
                url:formGlobalPath + "exam/exam_assessment_subject_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (data) {
                    checkValidityOfOutput(data);
                    if (data[0] != 0) {
                        var viewImageLink = getButtonViewImage();
                        var editImageLink = getButtonEditImage();
                        var browseImageLink = getButtonBrowseImage();
                        oTable
                            .fnUpdate(
                            [
                                data[1],
                                data[2],
                                "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"showMarkProgress('"
                                    + data[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + browseImageLink + "Check Marks</button>",
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + data[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + viewImageLink + "Show Details</button>",
                                "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                    + data[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + editImageLink + "Edit Details</button>" ],
                            aPos);
                        hideUpdateForm();
                        showRecordDetails(data[0], aPos);
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
                url:formGlobalPath + "exam/exam_assessment_subject_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'html',
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
// Code for Drop value Ends Here

// Code For Activate Value Begins Here
function activateRecord(id, aPos) {
    if (confirm('Do you really want to activate ?')) {
        showLoading("Processing Data");
        data = 'task=activateRecord&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "exam/exam_assessment_subject_form.php",
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
function showMarkProgress(id, aPos){
    var link = serverUrl + 'pages/exam/exam_agrade_status.php?activityId=' + id;
    loadPageIntoDisplay(link);
    endLoading();
    return false;
}
// Code For Activate Value Ends Here

