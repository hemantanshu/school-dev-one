var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var examinationId, sectionId, sessionId, classId;

$(document)
    .ready(
    function () {
        oTable = $('#groupRecords').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });
        populateInitialElements();        
        populateMarkingOptions();

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
    sessionId = $('#sessionId').val();
    examinationId = $('#examinationId').val();
    sectionId = $('#sectionId').val();
    showLoading('Loading Examination Details');
    var data = 'task=getClassExaminationSessionDetails&sessionId='+sessionId+'&examinationId='+examinationId+'&sectionId='+sectionId;
    $
        .ajax({
            url:formGlobalPath + "exam/exam_examination_dates_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] != 0){
                    $('#examinationName').html(output[1]);
                    $('#sessionName').html(output[0]);
                    $('#className').html(output[2]);
                    $('#sectionName').html(output[3]);
                    classId = output[4];
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

                        populateSubjectComponent();
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

function populateSubjectComponent(){
    var subjectId = $('#subjectName_i').val();
    var data = 'task=getSubjectComponents&subjectId='+subjectId;
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
                	$('#subjectComponent_i').html('');
                } else {
                    if (data[0][0] != 0) {
                        var options = '<option value=""></option>';
                        for (var i = 0; i < data.length; i++) {
                            options = options + '<option value="'+data[i][0]+'">'+data[i][1]+'</option>';
                        }
                        $('#subjectComponent_i').html(options);
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

function populateMarkingOptions(){
    var data = 'task=search&hint=&search_type=1';
    $
        .ajax({
            url:formGlobalPath + "exam/exam_grading_type_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                var options = '<option value="">Absolute Marking</option>';
                if (data[0][0] == 1) {
                    ;
                } else {
                    if (data[0][0] != 0) {

                        for (var i = 0; i < data.length; i++) {
                            options = options + '<option value="'+data[i][0]+'">'+data[i][1]+'</option>';
                        }
                    } else {
                        handleNotification(
                            'Error While Processing Data, Please Try Again',
                            'error');
                        endLoading();
                    }
                }
                $('#markingType_i').html(options);
                $('#markingType_u').html(options);
            }
        });
}

function checkMarkingType(){
    var markingType = $('#markingType_i').val();
    if(markingType != "")
        $('#markingScoreInsert').hide();
    else
        $('#markingScoreInsert').show();
}

// Code For Check Of Input Errors
function processInsertForm() {
    // preparing for ajax call
    var data = $('#insertForm').serialize();
    data = 'sessionId='+sessionId+'&classId='+classId+'&examinationId='+examinationId+'&sectionId='+sectionId+'&task=insertRecord&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "exam/exam_examination_dates_form.php",
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
                        data[3],
                        data[4],
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
    data = 'sessionId='+sessionId+'&sectionId='+sectionId+'&examinationId='+examinationId+'&task=search&' + data;
    showLoading("Fetching Data From Server");
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
                                if(data[i][5] == 1)
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
                                    data[i][3],
                                    data[i][4],
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
    var data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "exam/exam_examination_dates_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[1] != '') {
            	$('#examinationName_d').html(data[1]);
            	$('#examinationDate_d').html(data[2]);
            	$('#subjectName_d').html(data[3]);
            	$('#subjectComponent_d').html(data[4]);
            	$('#examinationTime_d').html(data[5]);
            	$('#examinationDuration_d').html(data[6]);
            	$('#markingType_d').html(data[7]);
            	$('#credits_d').html(data[8]);
            	$('#markSubmissionDate_d').html(data[9]);
            	$('#markSubmissionOfficer_d').html(data[10]);
            	$('#markVerificationDate_d').html(data[11]);
            	$('#markVerificationOfficer_d').html(data[12]);

                $('#lastUpdateDateDisplay').html(data[13]);
                $('#lastUpdatedByDisplay').html(data[14]);
                $('#creationDateDisplay').html(data[15]);
                $('#createdByDisplay').html(data[16]);
                if (data[17] == 'y') {
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
                
                if(data[22] != undefined){
                	$('#maxMarkDisplay').show();
                	$('#maxMark_d').html(data[27]);
                	$('#passMark_d').html(data[28]);                	
                }else{
                	$('#maxMarkDisplay').hide();
                }

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
    	url:formGlobalPath + "exam/exam_examination_dates_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
            	$('#examinationName_u').val(data[1]);
            	$('#examinationDate_u').val(data[19]);
            	$('#subjectName_u').val(data[3]);
            	$('#subjectComponent_u').val(data[4]);
            	$('#examinationTime_u').val(data[5]);
            	$('#examinationDuration_u').val(data[6]);
            	$('#markingType_u').val(data[22]);
            	$('#credit_u').val(data[8]);
            	$('#maxMark_u').val(data[27]);
            	$('#passMark_u').val(data[28]);
            	$('#markSubmissionDate_u').val(data[23]);
            	$('#markSubmissionOfficer_u').val(data[10]);
            	$('#markSubmissionOfficer_uval').val(data[24]);
            	$('#markVerificationDate_u').val(data[25]);
            	$('#markVerificationOfficer_u').val(data[12]);
            	$('#markVerificationOfficer_uval').val(data[26]);
            	                
                if (data[17] == 'y') {
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
                url:formGlobalPath + "exam/exam_examination_dates_form.php",
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
                                data[3],
                                data[4],
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
                url:formGlobalPath + "exam/exam_examination_dates_form.php",
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
// Code for Drop value Ends Here

// Code For Activate Value Begins Here
function activateRecord(id, aPos) {
    if (confirm('Do you really want to activate ?')) {
        showLoading("Processing Data");
        data = 'task=activateRecord&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "exam/exam_examination_dates_form.php",
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
    var link = serverUrl + 'pages/exam/exam_mark_status.php?examinationId=' + id;
    loadPageIntoDisplay(link);
    endLoading();
    return false;
}
// Code For Activate Value Ends Here

