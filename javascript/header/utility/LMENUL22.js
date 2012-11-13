var valid = new validate();
var formGlobalPath = getFormGlobalPath();
$(document)
    .ready(
    function () {
        oTable = $('#groupClasses').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });
        populateCurrentSession();

        $("#roomAllocated")
            .autocomplete(
            formGlobalPath
                + "utility/utl_autocomplete_search.php?option=room",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:false
            });
        $("#roomAllocated").result(
            function (event, data, formatted) {
                $("#roomAllocated_val").val(data[1]);
            });
        $("#roomAllocated_u")
            .autocomplete(
            formGlobalPath
                + "utility/utl_autocomplete_search.php?option=room",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:false
            });
        $("#roomAllocated_u").result(
            function (event, data, formatted) {
                $("#roomAllocated_uval").val(data[1]);
            });

        $("#classCoordinator")
            .autocomplete(
            formGlobalPath
                + "utility/utl_user_autocomplete.php?option=employeeTeacher",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#classCoordinator").result(
            function (event, data, formatted) {
                $("#classCoordinator_val").val(data[1]);
            });

        $("#classCoordinator_u")
            .autocomplete(
            formGlobalPath
                + "utility/utl_user_autocomplete.php?option=employeeTeacher",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#classCoordinator_u").result(
            function (event, data, formatted) {
                $("#classCoordinator_uval").val(data[1]);
            });

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
    if(sessionId == '')
        populateCurrentSession();
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
            url:formGlobalPath + "utility/utl_class_details_form.php",
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
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showCandidateMappings('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + browseImageLink + "Candidates</button>",
                        "<button type=\"button\" id=\"details\" class=\"negative details\" onclick=\"showSubjectMappings('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + browseImageLink + "Subjects</button>",
                        "<button type=\"button\" id=\"details\" class=\"negative details\" onclick=\"showSubjectMappings('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + browseImageLink + "Sections</button>",
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + viewImageLink + "Details</button>",
                        "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                            + output[0] + "', '" + aPos
                            + "')\">" + editImageLink + "Edit</button>" ]);
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
            url:formGlobalPath + "utility/utl_class_details_form.php",
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
                                var editButton = "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + editImageLink + "Edit</button>";
                            else
                                var editButton = '';
                            oTable
                                .fnAddData([
                                data[i][1],
                                data[i][2],
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showCandidateMappings('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + browseImageLink + "Candidates</button>",
                                "<button type=\"button\" id=\"details\" class=\"negative details\" onclick=\"showSubjectMappings('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + browseImageLink + "Subjects</button>",
                                "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"showSectionDetails('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + browseImageLink + "Sections</button>",
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + viewImageLink + "Details</button>",
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
        });
    return false;
}

function showRecordDetails(id, aPos) {
    showLoading("Fetching Data From Server");
    data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "utility/utl_class_details_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {            
            checkValidityOfOutput(data);
            if (data[1] != '') {
                $('#className_dDisplay').html(data[1]);
                $('#capacity_dDisplay').html(data[2]);
                $('#sectionName_d').html(data[3]);
                $('#roomName_dDisplay').html(data[4]);
                $('#roomNo_dDisplay').html(data[5]);
                $('#floorNo_dDisplay').html(data[6]);
                $('#buildingName_dDisplay').html(data[7]);
                $('#seatingCapacityN_dDisplay').html(data[8]);

                $('#classCoordinator_d').html(data[11]);
                $('#level_d').html(data[12]);

                $('#lastUpdateDateDisplay').html(data[13]);
                $('#lastUpdatedByDisplay').html(data[14]);
                $('#creationDateDisplay').html(data[15]);
                $('#createdByDisplay').html(data[16]);
                if (data[17] == 'y') {
                    $('#activeDisplay').html(
                        '<font class="green">Active</font>');
                    if(data[18] == 1){
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
                    if(data[18] == 1){
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

                $('#showSectionButtonD').attr(
                    'onclick',
                    'showSectionDetails(\'' + data[0] + '\', \'' + aPos
                        + '\')');
                $('#showSubjectButtonD').attr(
                    'onclick',
                    'showSubjectMappings(\'' + data[0] + '\', \'' + aPos
                        + '\')');
                $('#showCandidateButtonD').attr(
                    'onclick',
                    'showCandidateMappings(\'' + data[0] + '\', \'' + aPos
                        + '\')');

                if(data[18] == 1){
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
        url:formGlobalPath + "utility/utl_class_details_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[18] == 1) {
                $('#className_u').val(data[1]);
                $('#sectionName_u').val(data[3]);
                $('#studentCap_u').val(data[2]);
                $('#roomAllocated_uval').val(data[15]);
                $('#roomAllocated_u').val(data[4]);

                $('#classCoordinator_uval').val(data[21]);
                $('#classCoordinator_u').val(data[11]);
                $('#level_u').val(data[12]);

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
                $('#showSectionButtonU').attr(
                    'onclick',
                    'showSectionDetails(\'' + data[0] + '\', \'' + aPos
                        + '\')');
                $('#showSubjectButtonU').attr(
                    'onclick',
                    'showSubjectMappings(\'' + data[0] + '\', \'' + aPos
                        + '\')');
                $('#showCandidateButtonU').attr(
                    'onclick',
                    'showCandidateMappings(\'' + data[0] + '\', \'' + aPos
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
                url:formGlobalPath + "utility/utl_class_details_form.php",
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
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showCandidateMappings('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + browseImageLink + "Candidates</button>",
                                "<button type=\"button\" id=\"details\" class=\"negative details\" onclick=\"showSubjectMappings('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + browseImageLink + "Subjects</button>",
                                "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"showSectionDetails('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + browseImageLink + "Sections</button>",
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + viewImageLink + "Details</button>",
                                "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + editImageLink + "Edit</button>" ],
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
                url:formGlobalPath + "utility/utl_class_details_form.php",
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
                url:formGlobalPath + "utility/utl_class_details_form.php",
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

function showSectionDetails(id, aPos) {
    var link = serverUrl + 'pages/utility/utl_class_section.php?classId=' + id;
    loadPageIntoDisplay(link);
    return false;
}

function showSubjectMappings(id, aPos) {
    var link = serverUrl + 'pages/utility/utl_class_subject.php?classId=' + id;
    loadPageIntoDisplay(link);
    return false;
}

function showCandidateMappings(id, aPos){
    var link = serverUrl + 'pages/utility/utl_class_candidate.php?classId=' + id;
    loadPageIntoDisplay(link);
    return false;
}
// Code For Activate Value Ends Here

