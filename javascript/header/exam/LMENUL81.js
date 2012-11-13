var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var resultId, sessionId, classId, sectionId, oTable;

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

function fetchSetupUrls(resultId, sectionId, resultTypeId){
    showLoading('Loading Result Setup Urls');
    var data = 'task=fetchResultTypeUrl&resultTypeId='+resultTypeId;
    $
        .ajax({
            url:formGlobalPath + "exam/exam_result_type_urls_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                var loadUrl = '';
                if (data[0][0] != 0) {
                    for (var i = 0; i < data.length; i++) {
                        var url = data[i][1]+'?resultId='+resultId+'&sectionId='+sectionId;
                        loadUrl += '<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay(\''+url+'\')"><img src="images/global/b_usredit.png" alt="" />'+data[i][0]+'</a></li>';
                    }
                    extraMenuListing.html(loadUrl);
                    additionalMenuDisplay.show();
                }
            }
        });
    endLoading();
    return false;
}

function populateInitialElements(){
    sessionId = $('#sessionId').val();
    resultId = $('#resultId').val();
    sectionId = $('#sectionId').val();
    showLoading('Loading Examination Details');
    var data = 'task=getResultSessionDetails&sessionId='+sessionId+'&resultId='+resultId+'&sectionId='+sectionId;
    $
        .ajax({
            url:formGlobalPath + "exam/exam_result_assessment_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] != 0){
                    $('#resultName').html(output[1]);
                    $('#sessionName').html(output[0]);
                    $('#className').html(output[2]);
                    $('#sectionName').html(output[3]);
                    
                    classId = output[4];
                    fetchSetupUrls(resultId, sectionId, output[5]);
                }else{
                    loadPageIntoDisplay(serverUrl);
                }
                return false;
            }
        });
    endLoading();
    return false;
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
                var options = '';
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
                $('#markingType').html(options);
                $('#markingType_u').html(options);
            }
        });
}

// Code For Check Of Input Errors
function processInsertForm() {
    // preparing for ajax call
    var data = $('#insertForm').serialize();
    data = 'sessionId='+sessionId+'&classId='+classId+'&resultId='+resultId+'&sectionId='+sectionId+'&task=insertRecord&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "exam/exam_result_assessment_form.php",
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
                        "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"setAssessmentSubjects('"
                            + data[0]
                            + "', '"
                            + aPos
                            + "')\">" + browseImageLink + "Assessment Subject</button>",
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
    data = 'sessionId='+sessionId+'&sectionId='+sectionId+'&resultId='+resultId+'&task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "exam/exam_result_assessment_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0][0] == 1) {
                    oTable.fnClearTable();
                    $('#copyAssessment').show();
                    hideInsertForm();
                    hideDisplayPortion();
                    handleNotification(
                        'No Data Fetched With The Given Inputs', 'info');
                    endLoading();
                } else {
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    $('#copyAssessment').remove();
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
                                    "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"setAssessmentSubjects('"
                                        + data[i][0]
                                        + "', '"
                                        + i
                                        + "')\">" + browseImageLink + "Assessment Subject</button>",
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
        url:formGlobalPath + "exam/exam_result_assessment_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            
            checkValidityOfOutput(data);
            if (data[1] != '') {
                $('#assessmentName_d').html(data[1]);
                $('#order_d').html(data[2]);
                $('#markingType_d').html(data[11]);
                $('#lastUpdateDateDisplay').html(data[3]);
                $('#lastUpdatedByDisplay').html(data[4]);
                $('#creationDateDisplay').html(data[5]);
                $('#createdByDisplay').html(data[6]);
                if (data[9] == 'y') {
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

                if(data[8] == 1)
                    $('#editRecordButton').attr('onclick',
                        'editRecord(\'' + data[0] + '\', \'' + aPos + '\')');

                $('#valueId_d').val(data[0]);
                $('#rowPosition_d').val(aPos);
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
        url:formGlobalPath + "exam/exam_result_assessment_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[10] == 1) {
                $('#assessmentName_u').val(data[1]);
                $('#order_u').val(data[2]);
                $('#markingType_u').val(data[12]);
                if (data[7] == '9') {
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
                url:formGlobalPath + "exam/exam_result_assessment_form.php",
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
                                "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"setAssessmentSubjects('"
                                    + data[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + browseImageLink + "Assessment Subject</button>",
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + data[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + viewImageLink + "Show Details</button>",
                                "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                    + data[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + editImageLink + "Edit Details</button>"],
                            aPos);
                        hideUpdateForm();
                        showRecordDetails(data[0], aPos);
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



// Code For Drop Value Begins here
function dropRecord(id, aPos) {
    if (confirm('Do you really want to delete ?')) {
        showLoading("Processing Data");
        data = 'task=dropRecord&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "exam/exam_result_assessment_form.php",
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
                url:formGlobalPath + "exam/exam_result_assessment_form.php",
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
function setAssessmentSubjects(id, aPos){
    var link = serverUrl + 'pages/exam/exam_assessment_subject.php?accessmentId=' + id;
    loadPageIntoDisplay(link);
    endLoading();
    return false;
}

function copyAssessmentRecord(){
    var link = serverUrl + 'pages/exam/exam_assessment_copy.php?resultId=' + resultId+'&sectionId='+sectionId+'&sessionId='+sessionId;
    loadPageIntoDisplay(link);
    endLoading();
    return false;
}
// Code For Activate Value Ends Here

