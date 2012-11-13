var imagePath = schoolImageGlobalPath();

$(document).ready(function () {
    oTable = $('#groupHistory').dataTable({
        "bJQueryUI":true,
        "sPaginationType":"full_numbers"
    });
    formGlobalPath = getFormGlobalPath();
    candidateId = $('#candidateId').val();
    additionalMenuDisplay.show();
    getRegistrationData();

});

function hideRegistrationData() {
    hideTheDiv('registration');
}
function showRegistrationData() {
    showTheDiv('registration');
}
function hideGuardianData() {
    hideTheDiv('gaurdian');
}
function showGuardianData() {
    showTheDiv('gaurdian');
}
function hideEducationHistory() {
    hideTheDiv('educationHistory');
}
function showEducationHistory() {
    showTheDiv('educationHistory');
}


function getRegistrationData() {
    var data = 'candidateId=' + candidateId + '&task=registrationDetails';
    showLoading("Fetching Data From Server");
    $.ajax({
        url:formGlobalPath + "utility/utl_candidate_profile_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (output) {
            checkValidityOfOutput(output);
            if (output[0] != 0) {
                $('#registrationId').html(output[1]);
                $('#entranceId').html(output[2]);
                $('#admittedClass').html(output[3]);
                $('#registrationDate').html(output[4]);
                $('#house').html(output[5]);
                $('#record1').html(output[6]);
                $('#record2').html(output[7]);
                $('#record3').html(output[8]);

                endLoading();
            } else {
                loadPageIntoDisplay(serverUrl);
                endLoading();
            }
        }
    });
    showRegistrationData();
}
function getGuardianData() {
    var gaurdianType = $('#gaurdianType').val();
    var data = 'candidateId=' + candidateId
        + '&task=gaurdianDetails&gaurdianType=' + gaurdianType;
    showLoading("Fetching Data From Server");
    $.ajax({
        url:formGlobalPath + "utility/utl_candidate_profile_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (output) {
            checkValidityOfOutput(output);
            if (output[0] == 1) {
                endLoading();
                hideTheDiv('guardianInsideData');
                handleNotification(
                    'No Data Exists For The Given Gaurdian Type', 'info');
            } else {
                if (output[0] != 0) {
                    $('#gaurdianName').html(output[0]);
                    $('#gaurdianTypeVal').html(output[1]);
                    $('#emailId').html(output[2]);
                    $('#occupation').html(output[3]);
                    $('#gMobileNo').html(output[4]);
                    $('#gContactNo').html(output[5]);
                    $('#gAddress').html(output[6]);
                    showTheDiv('guardianInsideData');
                    endLoading();
                } else {
                    endLoading();
                }
            }
        }
    });
    showGuardianData();
    return false;
}

function getEducationData() {
    var data = 'candidateId=' + candidateId + '&task=educationDetails';
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath
                + "utility/utl_candidate_profile_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0][0] == 1) {
                    oTable.fnClearTable();
                    handleNotification(
                        'No Education History Of The Candidate Is Present',
                        'info');
                    endLoading();
                } else {
                    if (data[0][0] != 0) {
                        oTable.fnClearTable();
                        for (var i = 0; i < data.length; i++) {
                            oTable
                                .fnAddData([ data[i][0],
                                data[i][1], data[i][2],
                                data[i][3], data[i][4] ]);
                        }
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
    showEducationHistory();
    return false;
}

function editRegistrationData() {
    var link = serverUrl + 'pages/utility/utl_candidate_personal.php?candidateId=' + candidateId;
    loadPageIntoDisplay(link);
}

function editGuardianData() {
    var gaurdianType = $('#gaurdianType').val();
    var link = serverUrl + 'pages/utility/utl_candidate_gaurdian.php?candidateId=' + candidateId + '&guardianId=' + gaurdianType;
    loadPageIntoDisplay(link);
}
function editEducationHistory() {
    var link = serverUrl + 'pages/utility/utl_candidate_education.php?candidateId=' + candidateId;
    loadPageIntoDisplay(link);
}

function getUserPhotograph(){
    var link = serverUrl + 'pages/utility/utl_user_photograph.php?candidateId='+candidateId;
    loadColorboxPage(link, 700);
    return false;
}

