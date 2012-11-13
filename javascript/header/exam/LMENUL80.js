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
        populateMarkingOptions();
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

function populateMarkingOptions(){
    var data = 'task=search&hint=&search_type=1';
    $
        .ajax({
            url:formGlobalPath + "exam/exam_result_type_form.php",
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
                $('#markingType_i').html(options);
                $('#markingType_u').html(options);
            }
        });
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
            url:formGlobalPath + "exam/exam_result_definitions_form.php",
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
                        //output[2],
                        "<button type=\"button\" id=\"details\" class=\"details\" onclick=\"showResultSetup('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + browseImageLink + "Result Setup</button>",
                        "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"showResultClasses('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + browseImageLink + "Mark Setup</button>",
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"setupClassResult('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + browseImageLink + "Class Setup</button>",
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"resultProgressView('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + browseImageLink + "View Progress</button>",    
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + viewImageLink + "View Details</button>"
                         ]);
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
            url:formGlobalPath + "exam/exam_result_definitions_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0][0] == 1) {
                    endLoading();
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
                                //data[i][2],
                                "<button type=\"button\" id=\"details\" class=\"details\" onclick=\"showResultSetup('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + browseImageLink + "Result Setup</button>",
                                "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"showResultClasses('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + browseImageLink + "Mark Setup</button>",
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"setupClassResult('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + browseImageLink + "Class Setup</button>",
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"resultProgressView('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + browseImageLink + "View Progress</button>",    
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
        url:formGlobalPath + "exam/exam_result_definitions_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[1] != '') {
                $('#resultName_d').html(data[1]);
                $('#resultDescription_d').html(data[2]);
                $('#scoringType_d').html(data[11]);
                $('#displayName_d').html(data[3]);
                $('#lastUpdateDateDisplay').html(data[5]);
                $('#lastUpdatedByDisplay').html(data[6]);
                $('#creationDateDisplay').html(data[7]);
                $('#createdByDisplay').html(data[8]);
                if (data[4] == 'y') {
                    $('#activeDisplay').html(
                        '<font class="green">Active</font>');
                    if(data[9] == 1){
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
                    if(data[9] == 1){
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
                    'showResultClasses(\'' + data[0] + '\', \'' + aPos
                        + '\')');

                if(data[9] == 1){
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
        url:formGlobalPath + "exam/exam_result_definitions_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[9] == 1) {
                $('#resultName_u').val(data[1]);
                $('#resultDescription_u').val(data[2]);
                $('#displayName_u').val(data[3]);
                $('#markingType_u').val(data[10]);
                if (data[4] == 'y') {
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
                    'showResultClasses(\'' + data[0] + '\', \'' + aPos
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
                url:formGlobalPath + "exam/exam_result_definitions_form.php",
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
                                //output[2],
                                "<button type=\"button\" id=\"details\" class=\"details\" onclick=\"showResultSetup('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + browseImageLink + "Result Setup</button>",
                                "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"showResultClasses('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + browseImageLink + "Mark Setup</button>",
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"setupClassResult('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + browseImageLink + "Class Setup</button>",
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"resultProgressView('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + browseImageLink + "View Progress</button>",    
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
                url:formGlobalPath + "exam/exam_result_definitions_form.php",
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
                url:formGlobalPath + "exam/exam_result_definitions_form.php",
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

function showResultClasses(id, aPos){
    var sessionId = $('#session_val').val();
    var link = serverUrl + 'pages/exam/exam_class_record.php?examinationId=' + id+'&sessionId='+sessionId+'&flag=0';
    loadColorboxPage(link, 500);
    return false;
}

function showResultSetup(id, aPos){
    var sessionId = $('#session_val').val();
    var link = serverUrl + 'pages/exam/exam_result_setup.php?resultId=' + id+'&sessionId='+sessionId;
    loadPageIntoDisplay(link);
    return false;
}

function setupClassResult(id, aPos){
    var sessionId = $('#session_val').val();
    var link = serverUrl + 'pages/exam/exam_result_class.php?resultId=' + id+'&sessionId='+sessionId;
    loadPageIntoDisplay(link);
    return false;
}

function resultProgressView(id, aPos){
	var link = serverUrl + 'pages/exam/exam_result_progress.php?resultId=' + id;
    loadPageIntoDisplay(link);
    return false;
}


// Code For Activate Value Ends Here

