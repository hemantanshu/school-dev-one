var valid = new validate();
$(document)
    .ready(
    function () {
        oTable = $('#groupSubjects').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });
        formGlobalPath = getFormGlobalPath();

        candidateGlobal = $('#candidate_global').val();
        mappingGlobal = $('#mapping_global').val();

        checkFormEditableOption();
        getCandidateDetails();
        getSearchResults();
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


function checkFormEditableOption(){
    var data = 'classId=' + classGlobal + '&task=checkSessionEditable';
    $
        .ajax({
            url:formGlobalPath + "utility/utl_class_details_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0] == 0) {
                    $('#updateForm').remove();
                    $('#editRecordButton').remove();
                }
            }
        });
    return false;
}

function getCandidateDetails(){
    var data = 'candidateId=' + candidateGlobal + '&mappingId='+mappingGlobal+'&task=getCandidateClassDetails';
    $
        .ajax({
            url:formGlobalPath + "utility/utl_candidate_subject_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0] != 0) {
                    $('#candidateName').html(data[0]);
                    $('#registrationNumber').html(data[1]);
                    $('#class').html(data[2]);
                    $('#section').html(data[3]);
                    $('#session').html(data[4]);

                    showTheDiv('sessionRecord');
                }
            }
        });
    return false;
}
function getSearchResults() {
    var data = 'candidateId=' + candidateGlobal + '&mappingId='+mappingGlobal+'&task=search';
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_candidate_subject_form.php",
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
                    var editButton;
                    if (data[0][0] != 0) {
                        oTable.fnClearTable();
                        for (var i = 0; i < data.length; i++) {
                            if(data[i][5] == 1)
                                editButton ="<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                    + data[i][0] + "', '" + i
                                    + "')\">" + editImageLink + "Edit Details</button>";
                            else
                                editButton = ' - ';
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

                                editButton]);
                        }
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
    var data = 'candidateId=' + candidateGlobal + '&mappingId='+mappingGlobal+'&task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "utility/utl_candidate_subject_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != '') {
                $('#subjectType_d').html(data[1])
                $('#type').html(data[2])
                $('#subjectName').html(data[3])
                $('#subjectCode').html(data[4])
                $('#lastUpdateDateDisplay').html(data[5]);
                $('#lastUpdatedByDisplay').html(data[6]);
                $('#creationDateDisplay').html(data[7]);
                $('#createdByDisplay').html(data[8]);
                if (data[9] == 'y') {
                    $('#activeDisplay').html(
                        '<font class="green">Active</font>');
                } else {
                    $('#activeDisplay').html(
                        '<font class="red">Inactive</font>');
                }
                if(data[10] == 1){
                    $('#editRecordButton').attr('onclick',
                        'editRecord(\'' + data[0] + '\', \'' + aPos + '\')');
                }
                $('#valueId_d').val(data[0]);
                $('#rowPosition_d').val(aPos);

                hideUpdateForm();
                showDisplayPortion();
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
    var data = 'candidateId=' + candidateGlobal + '&mappingId='+mappingGlobal+'&task=getRecordEditDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "utility/utl_candidate_subject_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 1) {
                $('#subject_u').html(data[0]);
                $('#type_u').html(data[1]);
                $('#subject').html(data[3]);

                $('#valueId_u').val(id);
                $('#associationId_u').val(data[2]);
                $('#rowPosition_u').val(aPos);
                hideDisplayPortion();
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
        data = 'candidateId=' + candidateGlobal + '&mappingId='+mappingGlobal+'&task=updateRecord&' + data;
        showLoading("Uploading Data To Server");
        $
            .ajax({
                url:formGlobalPath + "utility/utl_candidate_subject_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (data) {
                    checkValidityOfOutput(data);
                    if (data[0] != 0) {
                        var viewImageLink = getButtonViewImage();
                        var editImageLink = getButtonEditImage();
                        var editButton;
                        if(data[5] == 1)
                            editButton ="<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                + data[0] + "', '" + aPos
                                + "')\">" + editImageLink + "Edit Details</button>";
                        else
                            editButton = ' - ';
                        oTable
                            .fnUpdate([
                            data[1],
                            data[2],
                            data[3],
                            data[4],
                            "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                + data[0]
                                + "', '"
                                + aPos
                                + "')\">" + viewImageLink + "Show Details</button>",

                            editButton], aPos);
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
// Code For Update Form Ends here