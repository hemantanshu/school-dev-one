var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var resultId, sectionId, oTable;

$(document)
    .ready(
    function () {
        oTable = $('#groupRecords').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });
        populateInitialElements();

        $("#submissionOfficer")
            .autocomplete(
            formGlobalPath
                + "utility/utl_user_autocomplete.php?option=employeeTeacher",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#submissionOfficer").result(
            function (event, data, formatted) {
                $("#submissionOfficer_val").val(data[1]);
            });
    });

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
    resultId = $('#resultIdGlobal').val();
    sectionId = $('#sectionIdGlobal').val();
    var data = 'task=resultDetails&resultId='+resultId+'&sectionId='+sectionId;
    $
        .ajax({
            url:formGlobalPath + "exam/exam_result_subjectc_grade_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] != 0){
                    $('#resultName').html(output[0]);
                    $('#className').html(output[1]);
                    $('#resultType').html(output[2]);
                    $('#resultDescription').html(output[3]);

                    fetchSetupUrls(resultId, sectionId, output[4]);
                    getSearchResults();
                    endLoading();
                }else{
                    handleNotification('Server Error .. Try After sme time', 'error');
                    endLoading();
                }
                return false;
            }
        });
    return false;
}

function getSearchResults() {
    var data = $('#searchForm').serialize();
    data = 'resultId='+resultId+'&sectionId='+sectionId+'&task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "exam/exam_result_sections_setup_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                console.log(data);
                if (data[0][0] == 1) {
                    oTable.fnClearTable();
                    hideDisplayPortion();
                    handleNotification(
                        'No Data Fetched With The Given Inputs', 'info');
                    endLoading();
                } else {
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    var editButton;
                    if (data[0][0] != 0) {
                        oTable.fnClearTable();
                        for (var i = 0; i < data.length; i++) {
                            if(data[i][2] == 0){
                                oTable
                                    .fnAddData([
                                    data[i][1],
                                    'Static Data',
                                    "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showStaticRecordDetails('"
                                        + data[i][0]
                                        + "', '"
                                        + i
                                        + "')\">" + viewImageLink + "Show Details</button>",
                                    "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editStaticRecord('"
                                        + data[i][0] + "', '" + i
                                        + "')\">" + editImageLink + "Edit Details</button>"]);
                            }else{
                                oTable
                                    .fnAddData([
                                    data[i][1],
                                    'Submission Data',
                                    "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                        + data[i][0]
                                        + "', '"
                                        + i
                                        + "')\">" + viewImageLink + "Show Details</button>",
                                    "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                        + data[i][0] + "', '" + i
                                        + "')\">" + editImageLink + "Edit Details</button>"]);
                            }
                        }
                        showTheDiv('displayDatatable');
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

function showStaticRecordDetails(id, aPos){
    showLoading("Fetching Data From Server");
    var data = 'task=getStaticRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "exam/exam_result_sections_setup_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#fieldName').html(data[1]);
                $('#fieldData').html(data[2]);

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
                    'editStaticRecord(\'' + data[0] + '\', \'' + aPos + '\')');

                $('#valueId_d').val(data[0]);
                $('#rowPosition_d').val(aPos);
                hideUpdateForm();
                $('#submissionDataDetails').hide();
                showTheDiv('staticDataDetails');
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

function showRecordDetails(id, aPos){
    showLoading("Fetching Data From Server");
    var data = 'task=getSubmissionRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "exam/exam_result_sections_setup_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#submissionFieldName').html(data[1]);
                $('#submissionDate_d').html(data[3]);
                $('#submissionOfficer_d').html(data[5]);

                $('#lastUpdateDateDisplay').html(data[6]);
                $('#lastUpdatedByDisplay').html(data[7]);
                $('#creationDateDisplay').html(data[8]);
                $('#createdByDisplay').html(data[9]);
                if (data[10] == 'y') {
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
                $('#staticDataDetails').hide();
                showTheDiv('submissionDataDetails');
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

function editRecord(id, aPos){
    showLoading("Processing Data Into Update Form");
    var data = 'task=getSubmissionRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "exam/exam_result_sections_setup_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#submissionData').html(data[1]);
                $('#submissionDate').val(data[2]);
                $('#submissionOfficer').val(data[5]);
                $('#submissionOfficer_val').val(data[4]);

                $('#valueId_u').val(data[0]);

                showTheDiv('updateForm');
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

function editStaticRecord(id, aPos){
    showLoading("Processing Data Into Update Form");
    var data = 'task=getSubmissionRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "exam/exam_result_sections_setup_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#staticFieldName').html(data[1]);
                $('#staticData').val(data[2]);

                $('#valueId_su').val(data[0]);

                showTheDiv('insertForm');
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

function processUpdateForm(){
    var id = $('#valueId_u').val();
    if (id) {
        var data = $('#updateForm').serialize();
        data = 'task=updateSubmissionRecord&' + data;
        showLoading("Uploading Data To Server");
        $
            .ajax({
                url:formGlobalPath + "exam/exam_result_sections_setup_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (data) {
                    checkValidityOfOutput(data);
                    if (data[0] != 0) {
                        hideTheDiv('updateForm');
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

function processStaticForm(){
    var id = $('#valueId_su').val();
    if (id) {
        var data = $('#insertForm').serialize();
        data = 'task=updateStaticRecord&' + data;
        showLoading("Uploading Data To Server");
        $
            .ajax({
                url:formGlobalPath + "exam/exam_result_sections_setup_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (data) {
                    checkValidityOfOutput(data);
                    if (data[0] != 0) {
                        hideTheDiv('insertForm');
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

