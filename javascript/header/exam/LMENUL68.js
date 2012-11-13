var valid = new validate();
var formGlobalPath = getFormGlobalPath();
$(document)
    .ready(
    function () {
        oTable = $('#groupRecords').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });
        populateCurrentSession();
        $("#session")
            .autocomplete(
            formGlobalPath
                + "global/glb_class_session_form.php?session=true&type=class",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:false
            });
        $("#session").result(
            function (event, data, formatted) {
                $("#session_val").val(data[1]);
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
function changeActiveSession(){
    var choiceListing = $('#choiceListing');
    if(choiceListing.is(':visible')){
        choiceListing.slideUp();
        showTheDiv('completePageDisplay');
    }
    else{
        hideTheDiv('completePageDisplay');
        choiceListing.slideDown();
    }
}

function checkSessionChange(){
    var sessionId = $('#session_val').val();
    var sessionName = $('#session').val();
    $('#sessionNameDisplay').html(sessionName);
    if(sessionId == '')
        populateCurrentSession();
    else{
        var data = 'task=isSessionEditable&sessionId='+sessionId;
        $
            .ajax({
                url:formGlobalPath + "global/glb_class_session_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] == 0) {
                        hideInsertForm();
                        hideUpdateForm();
                        $('#toggleInsert').hide();

                        //getSearchResults();
                    }
                }
            });
    }
    changeActiveSession();
}

function populateCurrentSession(){
    var data = 'task=getCurrentSession';
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
                    $('#session_val').val(output[0]);
                    $('#session').val(output[1]);
                    $('#sessionNameDisplay').html(output[1]);

                    getSearchResults();
                } else {
                    jAlert('The system is facing some problem, please login again');
                    logOutUserLoggedUser();
                }
            }
        });
    return false;
}

// Code For Check Of Input Errors
function processInsertForm() {
    // preparing for ajax call
    var data = $('#insertForm').serialize();
    var sessionId = $('#session_val').val();
    data = 'sessionId='+sessionId+'&task=insertRecord&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "exam/exam_examination_definitions_form.php",
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
                    var browseImageLink = getButtonBrowseImage();
                    oTable
                        .fnAddData([
                        output[1],
                        output[2],
                        "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"showExaminationDates('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + browseImageLink + "Set Exam Date</button>",
                        "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"setupClassRecord('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + browseImageLink + "Class Setup</button>",
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showProgressView('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + browseImageLink + "Progress View</button>",
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + viewImageLink + "View Details</button>" ]);
                    hideInsertForm();
                    hideUpdateForm();
                    showDatatable();
                    $('#insertReset').click();
                    endLoading();
                } else {
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
    var sessionId = $('#session_val').val();
    data = 'sessionId='+sessionId+'&task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "exam/exam_examination_definitions_form.php",
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
                    var browseImageLink = getButtonBrowseImage();
                    if (data[0][0] != 0) {

                        oTable.fnClearTable();
                        for (var i = 0; i < data.length; i++) {
                            if(data[i][3] == 1)

                            oTable
                                .fnAddData([
                                data[i][1],
                                data[i][2],
                                "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"showExaminationDates('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + browseImageLink + "Exam Dates</button>",
                                "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"setupClassRecord('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + browseImageLink + "Class Setup</button>",
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showProgressView('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + browseImageLink + "Progress View</button>",
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + viewImageLink + "View Details</button>" ]);
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
    data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "exam/exam_examination_definitions_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            
            checkValidityOfOutput(data);
            if (data[1] != '') {
                $('#examName_d').html(data[1]);
                $('#examDescription_d').html(data[2]);
                $('#startDate_d').html(data[3]);
                $('#endDate_d').html(data[4]);
                $('#lastUpdateDateDisplay').html(data[5]);
                $('#lastUpdatedByDisplay').html(data[6]);
                $('#creationDateDisplay').html(data[7]);
                $('#createdByDisplay').html(data[8]);
                if (data[9] == 'y') {
                    $('#activeDisplay').html(
                        '<font class="green">Active</font>');
                    if(data[10] == 1){
                        $('#dropRecord_d').show();
                        $('#dropRecord_d')
                            .attr(
                            'onclick',
                            'dropRecord(\'' + data[0] + '\', \'' + aPos
                                + '\')');
                        $('#activateRecord_d').hide();
                    }else{
                        $('#activateRecord_d').hide();
                        $('#dropRecord_d').hide();
                    }
                } else {
                    $('#activeDisplay').html(
                        '<font class="red">Inactive</font>');
                    if(data[10] == 1){
                        $('#activateRecord_d').show();
                        $('#activateRecord_d').attr(
                            'onclick',
                            'activateRecord(\'' + data[0] + '\', \'' + aPos
                                + '\')');
                        $('#dropRecord_d').hide();
                    }else{
                        $('#activateRecord_d').hide();
                        $('#dropRecord_d').hide();
                    }
                }

                $('#showExaminationDateButtonD').attr(
                    'onclick',
                    'showExaminationDates(\'' + data[0] + '\', \'' + aPos
                        + '\')');

                if(data[10] == 1){
                    $('#editRecordButton').attr('onclick',
                        'editRecord(\'' + data[0] + '\', \'' + aPos + '\')');
                }else{
                    $('#editRecordButton').hide();
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
        url:formGlobalPath + "exam/exam_examination_definitions_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[10] == 1) {
                $('#examName_u').val(data[1]);
                $('#examDescription_u').val(data[2]);
                $('#startDate_u').val(data[11]);
                $('#endDate_u').val(data[12]);
                if (data[9] == 'y') {
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
                $('#showExaminationDateButtonD').attr(
                    'onclick',
                    'showExaminationDates(\'' + data[0] + '\', \'' + aPos
                        + '\')');


                $('#valueId_u').val(data[0]);
                $('#rowPosition_u').val(aPos);
                $('#associationId_u').val(data[19]);

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
                url:formGlobalPath + "exam/exam_examination_definitions_form.php",
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
                                "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"showExaminationDates('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + browseImageLink + "Exam Dates</button>",
                                "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"setupClassRecord('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + browseImageLink + "Class Setup</button>",
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showProgressView('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + browseImageLink + "Progress View</button>",
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + viewImageLink + "View Details</button>" ],
                            aPos);
                        hideUpdateForm();
                        showRecordDetails(output[0], aPos);
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
                url:formGlobalPath + "exam/exam_examination_definitions_form.php",
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
                url:formGlobalPath + "exam/exam_examination_definitions_form.php",
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


function showExaminationDates(id, aPos){
    var sessionId = $('#session_val').val();
    var link = serverUrl + 'pages/exam/exam_class_record.php?examinationId=' + id+'&sessionId='+sessionId;
    loadColorboxPage(link, 500);
    return false;
}

function setupClassRecord(id, aPos){
    var sessionId = $('#session_val').val();
    var link = serverUrl + 'pages/exam/exam_examination_class.php?examinationId=' + id+'&sessionId='+sessionId;
    loadPageIntoDisplay(link);
    return false;
}

function showProgressView(id, aPos){
    var link = serverUrl + 'pages/exam/exam_examination_progress.php?examinationId=' + id;
    loadPageIntoDisplay(link);
    return false;
}

// Code For Activate Value Ends Here

