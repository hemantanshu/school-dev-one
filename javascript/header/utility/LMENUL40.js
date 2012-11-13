var valid = new validate();
$(document)
    .ready(
    function () {
        oTable = $('#groupRecord').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers",
            "bSort":false

        });

        userId = $('#userId').val();
        hideUpdateForm();
        hideDisplayPortion();
        formGlobalPath = getFormGlobalPath();
        populateCandidateDetails();
        getSearchDetails();

        $('.addInstitute').popupWindow({
            windowURL:'utl_institute_details_v.php',
            height:500,
            width:800,
            top:50,
            left:50
        });


        $("#institute")
            .autocomplete(
            formGlobalPath
                + "utility/utl_autocomplete_search.php?option=institute",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:true
            });
        $("#institute").result(function (event, data, formatted) {
            $("#institute_val").val(data[1]);
        });

        $("#institute_u")
            .autocomplete(
            formGlobalPath
                + "utility/utl_autocomplete_search.php?option=institute",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:true
            });
        $("#institute_u").result(function (event, data, formatted) {
            $("#institute_uval").val(data[1]);
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
    showTheDiv('displayPortion');
}
function hideDisplayPortion() {
    hideTheDiv('displayPortion');
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


function populateCandidateDetails() {
    if (userId == "")
        loadPageIntoDisplay(serverUrl);
    var data = 'userId=' + userId + '&task=fetchRecord';
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_user_education_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    $('#candidateName').html(output[1])
                    $('#registrationNumber').html(output[2]);
                    $('#registeredEmail').html(output[3]);
                    $('#designation').html(output[4]);
                    endLoading();
                } else {
                    loadPageIntoDisplay(serverUrl);
                    endLoading();
                }
            }
        });

    return false;
}


function processInsertForm() {
    var data = $('#insertForm').serialize();
    data = 'userId=' + userId + '&task=insertRecord&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_user_education_form.php",
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
                    oTable
                        .fnAddData([
                        output[1],
                        output[2],
                        output[3],
                        output[4],
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + viewImageLink + "Show Details</button>",
                        "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                            + output[0] + "', '" + aPos
                            + "')\">" + editImageLink + "Edit Details</button>" ]);
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

function showRecordDetails(id, aPos) {
    showLoading("Fetching Data From Server");
    data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "utility/utl_user_education_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != '') {
                $('#institute_d').html(data[1]);
                $('#university_d').html(data[3]);
                $('#level_d').html(data[4]);
                $('#year_d').html(data[5]);
                $('#score_d').html(data[6]);
                $('#markType_d').html(data[7]);
                $('#lastUpdateDateDisplay').html(data[8]);
                $('#lastUpdatedByDisplay').html(data[9]);
                $('#creationDateDisplay').html(data[10]);
                $('#createdByDisplay').html(data[11]);
                $('#comments_d').html(data[14]);

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
                $('#editRecordButton').attr('onclick',
                    'editRecord(\'' + data[0] + '\', \'' + aPos + '\')');
                $('#recordId_d').val(data[0]);
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

function getSearchDetails() {
    var data = $('#searchForm').serialize();
    data = 'userId=' + userId + '&task=searchDetails&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_user_education_form.php",
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
                    if (data[0][0] != 0) {
                        oTable.fnClearTable();
                        var viewImageLink = getButtonViewImage();
                        var editImageLink = getButtonEditImage();
                        for (var i = 0; i < data.length; i++) {
                            oTable
                                .fnAddData([
                                data[i][1],
                                data[i][2],
                                data[i][3],
                                data[i][4],
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + viewImageLink + "Show Details</button>",
                                "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + editImageLink + "Edit Record</button>" ]);
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

// code for edit detaisl begins
function editRecord(id, aPos) {
    showLoading("Processing Data Into Update Form");
    data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "utility/utl_user_education_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#institute_u').val(data[1]);
                $('#institute_uval').val(data[2]);
                $('#level_u').val(data[4]);
                $('#year_u').val(data[5]);
                $('#score_u').val(data[6]);
                $('#markType_u').val(data[13]);
                $('#comments_u').val(data[14]);
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
                $('#recordId_u').val(data[0]);
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
    var aPos = $('#rowPosition_u').val();
    aPos = parseInt(aPos);
    // preparing for ajax call
    var data = $('#updateForm').serialize();
    data = 'task=updateRecord&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_user_education_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    oTable
                        .fnUpdate(
                        [
                            output[1],
                            output[2],
                            output[3],
                            output[4],
                            "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                + output[0]
                                + "', '"
                                + aPos
                                + "')\">" + viewImageLink + "Show Details</button>",
                            "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                + output[0]
                                + "', '"
                                + aPos
                                + "')\">" + editImageLink + "Edit Details</button>" ],
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

    return false;
}

// Code For Drop Value Begins here
function dropRecord(id, aPos) {
    if (confirm('Do you really want to delete ?')) {
        showLoading("Processing Data");
        data = 'task=dropRecord&id=' + id;
        $
            .ajax({
                url:formGlobalPath
                    + "utility/utl_user_education_form.php",
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
                            'Error In Dropping Education Details ... Try After Sometime',
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
                url:formGlobalPath
                    + "utility/utl_user_education_form.php",
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
                            'Error In Activating Education Details ... Try After Sometime',
                            'error');
                        endLoading();
                    }

                }
            });
    }
    return false;
}

function addNewInstitute(){
    var link = serverUrl+'pages/utility/utl_institute_insert.php';
    loadColorboxPage(link, 900);
    return false;
}