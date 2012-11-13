var valid = new validate();
$(document)
    .ready(
    function () {
        oTable = $('#groupRecords').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });
        formGlobalPath = getFormGlobalPath();
        classGlobal = $('#class_global').val();

        getClassSessionDetails();
        getSearchResults();
    });

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


function getSearchResults() {
    var data = $('#searchForm').serialize();
    data = 'classId=' + classGlobal + '&task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_class_candidate_form.php",
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
                    var browseButton = '', editButton = '';
                    if (data[0][0] != 0) {
                        oTable.fnClearTable();
                        for (var i = 0; i < data.length; i++) {
                            if(data[i][6] == 1)
                                editButton = "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                    + data[i][0] + "', '" + data[i][1]
                                    + "')\">" + editImageLink + "Change Subjects</button>";
                            else
                                editButton = '';

                            oTable
                                .fnAddData([
                                data[i][2],
                                data[i][3],
                                data[i][4],
                                data[i][5],
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + data[i][0]
                                    + "', '"
                                    + data[i][1]
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
            }
        });
    return false;
}

function showRecordDetails(id, candidateId) {
    getCandidateDetails(id, candidateId);
    getCandidateOptionalSubjectDetails(id, candidateId);
    getCandidateCompulsorySubjectDetails(id, candidateId);
}

function getCandidateDetails(id, candidateId){
    showLoading("Fetching Candidate Details From Server");
    var data = 'task=getCandidateDetails&candidateId=' + candidateId;
    $.ajax({
        url:formGlobalPath + "utility/utl_candidate_profile_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != '') {
                $('#candidateName').html(data[0]);
                $('#registrationNumber').html(data[1]);
                $('#dob').html(data[2]);
                $('#gender').html(data[3]);
                $('#pEmail').html(data[4]);
                $('#oEmail').html(data[5]);
                $('#mobileNo').html(data[6]);
                $('#contactNo').html(data[7]);
                $('#address').html(data[8]);
                endLoading();
            }
        }
    });
    return false;
}

function getCandidateOptionalSubjectDetails(id, candidateId) {
    showLoading("Fetching Subject Details From Server");
    var data = 'classId='+classGlobal+'&task=getRecordIdDetails&type=o&candidateId=' + candidateId;
    $.ajax({
        url:formGlobalPath + "utility/utl_class_candidate_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != '') {
                $('#optionalSubject').html(data[0]);
                endLoading();
            }
        }
    });
    return false;
}

function getCandidateCompulsorySubjectDetails(id, candidateId) {
    showLoading("Fetching Subject Details From Server");
    var data = 'classId='+classGlobal+'&task=getRecordIdDetails&type=c&candidateId=' + candidateId;
    $.ajax({
        url:formGlobalPath + "utility/utl_class_candidate_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != '') {
                $('#compulsorySubject').html(data[0]);
                if(data[1] == 1){
                    $('#editRecordButton').attr('onclick',
                        'editRecord(\'' + id + '\', \'' + candidateId + '\')');
                }else
                    $('#editRecordButton').hide();
                showDisplayPortion();
                endLoading();
            }
        }
    });
    return false;
}

function editRecord(id, candidateId){
    var link = serverUrl +'pages/utility/utl_candidate_subject.php?candidateId='+candidateId+'&mappingId='+id;
    loadPageIntoDisplay(link);
    return false;
}

function bulkSubjectEntry(){
    var link = serverUrl+'pages/utility/utl_class_candidate_subject_view.php?classId='+classGlobal;
    loadPageIntoDisplay(link);
    return false;
}