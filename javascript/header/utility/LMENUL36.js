var imagePath = schoolImageGlobalPath();
$(document).ready(function () {
    oTable = $('#groupHistory').dataTable({
        "bJQueryUI":true,
        "sPaginationType":"full_numbers"
    });
    oSeminarTable = $('#seminarTable').dataTable({
        "bJQueryUI":true,
        "sPaginationType":"full_numbers"
    });
    oEmploymentTable = $('#employmentTable').dataTable({
        "bJQueryUI":true,
        "sPaginationType":"full_numbers"
    });
    oDesignationTable = $('#designationTable').dataTable({
        "bJQueryUI":true,
        "sPaginationType":"full_numbers"
    });

    $('.openPopUpWindows').popupWindow({
        height:500,
        width:800,
        top:50,
        left:50
    });

    formGlobalPath = getFormGlobalPath();
    userId = $('#userId').val();
    getRegistrationDetails();
    loadExtraMenuListing();
});

function showRegistrationDetails() {
    showTheDiv('registration');
}
function hideRegistrationDetails() {
    hideTheDiv('registration');
}
function showGuardianDetails() {
    showTheDiv('guardian');
}
function hideGuardianDetails() {
    hideTheDiv('guardian');
}
function showEducationDetails() {
    showTheDiv('education');
}
function hideEducationDetails() {
    hideTheDiv('education');
}
function showSeminarDetails() {
    showTheDiv('seminar');
}
function hideSeminarDetails() {
    hideTheDiv('seminar');
}
function showEmploymentDetails() {
    showTheDiv('employment');
}
function hideEmploymentDetails() {
    hideTheDiv('employment');
}
function showDesignationDetails() {
    showTheDiv('designation');
}
function hideDesignationDetails() {
    hideTheDiv('designation');
}

function getRegistrationDetails() {
    var data = 'userId=' + userId + '&task=registrationDetails';
    showLoading("Fetching Data From Server");
    $.ajax({
        url:formGlobalPath + "utility/utl_user_profile_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (output) {
            checkValidityOfOutput(output);
            if (output[0] != 0) {
                $('#employeeCode').html(output[1]);
                $('#applicationId').html(output[2]);
                $('#joiningDate').html(output[3]);
                $('#record1').html(output[4]);
                $('#record2').html(output[5]);
                $('#record3').html(output[6]);
                $('#department').html(output[7]);
                $('#employeeType').html(output[8]);
                endLoading();
                showRegistrationDetails();
            } else {
                loadPageIntoDisplay(serverUrl);
                endLoading();
            }
        }
    });
    return false;
}
function getGuardianDetails() {
    var gaurdianType = $('#gaurdianType').val();
    var data = 'userId=' + userId + '&task=gaurdianDetails&gaurdianType='
        + gaurdianType;
    showLoading("Fetching Data From Server");
    $.ajax({
        url:formGlobalPath + "utility/utl_user_profile_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (output) {
            checkValidityOfOutput(output);
            if (output[0] == 1) {
                endLoading();
                showGuardianDetails();
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
                    endLoading();
                    showGuardianDetails();
                    showTheDiv('guardianInsideData');
                    hideRegistrationDetails();
                } else {
                    loadPageIntoDisplay(serverUrl);
                    endLoading();
                }
            }
        }
    });
    return false;
}

function getEducationDetails() {
    var data = 'userId=' + userId + '&task=educationDetails';
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_user_profile_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0][0] == 1) {
                    oTable.fnClearTable();
                    handleNotification(
                        'No Education History Of The Employee Is Present',
                        'info');
                    showEducationDetails();
                    endLoading();
                } else {
                    var viewImageLink = getButtonBrowseImage();
                    if (data[0][0] != 0) {
                        oTable.fnClearTable();
                        for (var i = 0; i < data.length; i++) {
                            oTable
                                .fnAddData([ data[i][0],
                                data[i][1], data[i][2],
                                data[i][3], data[i][4],
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick='openPopupWindow(\"utl_user_education_v.php?educationId=" + data[i][5] + "\")'>" + viewImageLink + "Show Details</button>"]
                            );
                        }
                        showEducationDetails();
                        hideRegistrationDetails();
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

function getSeminarDetails() {
    var data = 'userId=' + userId + '&task=seminarDetails';
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_user_profile_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0][0] == 1) {
                    oTable.fnClearTable();
                    handleNotification(
                        'No Details Regarding Seminar For This User Is Present',
                        'info');
                    showSeminarDetails();
                    endLoading();
                } else {
                    if (data[0][0] != 0) {
                        var viewImageLink = getButtonBrowseImage();
                        oSeminarTable.fnClearTable();
                        for (var i = 0; i < data.length; i++) {
                            oSeminarTable
                                .fnAddData([
                                data[i][1],
                                data[i][2],
                                data[i][3],
                                data[i][4] + ' Days',
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick='openPopupWindow(\"utl_user_seminar_v.php?seminarId=" + data[i][0] + "\")'>" + viewImageLink + "Show Details</button>"]);
                        }
                        showSeminarDetails();
                        hideRegistrationDetails();
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
function getEmploymentDetails() {
    var data = 'userId=' + userId + '&task=employmentDetails';
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_user_profile_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0][0] == 1) {
                    oTable.fnClearTable();
                    handleNotification(
                        'No Details Regarding Employment Background For This User Is Present',
                        'info');
                    showEmploymentDetails();
                    endLoading();
                } else {
                    if (data[0][0] != 0) {
                        oEmploymentTable.fnClearTable();
                        var viewImageLink = getButtonBrowseImage();
                        for (var i = 0; i < data.length; i++) {
                            var instituteLink = "<p onclick='openPopupWindow(\"utl_institute_details_v.php?instituteId=" + data[i][5] + "\")'>" + data[i][1] + "</p>";
                            oEmploymentTable
                                .fnAddData([
                                data[i][2],
                                data[i][3],
                                instituteLink,
                                data[i][4],
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick='openPopupWindow(\"utl_user_employment_v.php?employmentId=" + data[i][0] + "\")'>" + viewImageLink + "Show Details</button>"
                            ]);
                        }
                        showEmploymentDetails();
                        hideRegistrationDetails();
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
function getDesignationDetails() {
    var data = 'userId=' + userId + '&task=designationDetails';
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_user_profile_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0][0] == 1) {
                    oTable.fnClearTable();
                    handleNotification(
                        'No Details Regarding Designation For This User Is Present',
                        'info');
                    showDesignationDetails();
                    endLoading();
                } else {
                    if (data[0][0] != 0) {
                        oDesignationTable.fnClearTable();
                        var viewImageLink = getButtonBrowseImage();
                        for (var i = 0; i < data.length; i++) {
                            oDesignationTable
                                .fnAddData([
                                data[i][1],
                                data[i][2],
                                data[i][3],
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick='openPopupWindow(\"utl_user_designation_v.php?designationId=" + data[i][0] + "\")'>" + viewImageLink + "Show Details</button>" ]);
                        }
                        showDesignationDetails();
                        hideRegistrationDetails();
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

function editRegistrationDetails() {
    var link = serverUrl + 'pages/utility/utl_user_personal.php?userId=' + userId;
    loadPageIntoDisplay(link);
}
function editGuardianDetails() {
    var gaurdianType = $('#gaurdianType').val();
    var link = serverUrl + 'pages/utility/utl_user_guardian.php?userId=' + userId + '&guardianId=' + gaurdianType;
    loadPageIntoDisplay(link);
}
function editDesignationDetails() {
    var link = serverUrl + 'pages/utility/utl_user_designation.php?userId=' + userId;
    loadPageIntoDisplay(link);
}
function editEducationDetails() {
    var link = serverUrl + 'pages/utility/utl_user_education.php?userId=' + userId;
    loadPageIntoDisplay(link);
}
function editSeminarDetails() {
    var link = serverUrl + 'pages/utility/utl_user_seminar.php?userId=' + userId;
    loadPageIntoDisplay(link);
}
function editEmploymentDetails() {
    var link = serverUrl + 'pages/utility/utl_user_employment.php?userId=' + userId;
    loadPageIntoDisplay(link);
}

function openPopupWindow(url) {
    var link = serverUrl + 'pages/utility/' + url;
    loadColorboxPage(link, 900);
}
function getPhotographDetails(){
    var link = serverUrl + 'pages/utility/utl_user_photograph.php?candidateId=' + userId;
    loadColorboxPage(link, 700);
}