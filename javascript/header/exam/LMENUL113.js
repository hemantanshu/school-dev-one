var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var resultId, sectionId, sessionId, classId;

$(document)
    .ready(
    function () {

        populateInitialElements();

        $("#attendanceOfficer")
            .autocomplete(
            formGlobalPath
                + "utility/utl_user_autocomplete.php?option=employeeTeacher",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#attendanceOfficer").result(
            function (event, data, formatted) {
                $("#attendanceOfficer_val").val(data[1]);
            });

        $("#remarksOfficer")
            .autocomplete(
            formGlobalPath
                + "utility/utl_user_autocomplete.php?option=employeeTeacher",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#remarksOfficer").result(
            function (event, data, formatted) {
                $("#remarksOfficer_val").val(data[1]);
            });

        $("#weightOfficer")
            .autocomplete(
            formGlobalPath
                + "utility/utl_user_autocomplete.php?option=employeeTeacher",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#weightOfficer").result(
            function (event, data, formatted) {
                $("#weightOfficer_val").val(data[1]);
            });

        $("#heightOfficer")
            .autocomplete(
            formGlobalPath
                + "utility/utl_user_autocomplete.php?option=employeeTeacher",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#heightOfficer").result(
            function (event, data, formatted) {
                $("#heightOfficer_val").val(data[1]);
            });

        $("#achievementOfficer")
            .autocomplete(
            formGlobalPath
                + "utility/utl_user_autocomplete.php?option=employeeTeacher",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#achievementOfficer").result(
            function (event, data, formatted) {
                $("#achievementOfficer_val").val(data[1]);
            });
        loadExtraMenuListing();
    });

function showInsertForm(){
    showTheDiv('insertForm');
}
function hideInsertForm(){
    hideTheDiv('insertForm');
}

function showDisplayForm(){
    showTheDiv('displayValue');
}
function hideDisplayForm(){
    hideTheDiv('displayValue');
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
                    showRecordDetails();
                    showDisplayForm();
                }else{
                    loadPageIntoDisplay(serverUrl);
                }
                return false;
            }
        });
    endLoading();
    return false;
}
function processInsertForm() {
    // preparing for ajax call
    var data = $('#insertForm').serialize();
    data = 'resultId='+resultId+'&sectionId='+sectionId+'&task=insertRecord&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "exam/exam_result_junior_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0] != 0) {
                    showRecordDetails();
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

function showRecordDetails() {
    showLoading("Fetching Data From Server");
    var data = 'task=getRecordIdDetails&resultId=' + resultId+'&sectionId='+sectionId;
    $.ajax({
        url:formGlobalPath + "exam/exam_result_junior_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                if(data[1] <= 0){
                    populateEditForm();
                    hideDisplayForm();
                    showInsertForm();
                }else{
                    $('#totalAttendanceDisplay').html(data[1]);
                    $('#totalMarksDisplay').html(data[2]);
                    $('#attendanceDateDisplay').html(data[4]);
                    $('#attendanceSubmissionDisplay').html(data[6]);
                    $('#remarksDateDisplay').html(data[8]);
                    $('#remarksSubmissionDisplay').html(data[10]);
                    $('#lastUpdateDateDisplay').html(data[11]);
                    $('#lastUpdatedByDisplay').html(data[12]);
                    $('#creationDateDisplay').html(data[13]);
                    $('#createdByDisplay').html(data[14]);

                    $('#weightDateDisplay').html(data[16]);
                    $('#weightSubmissionDisplay').html(data[18]);

                    $('#heightDateDisplay').html(data[20]);
                    $('#heightSubmissionDisplay').html(data[22]);

                    $('#achievementDateDisplay').html(data[24]);
                    $('#achievementSubmissionDisplay').html(data[26]);

                    showDisplayForm();
                    endLoading();
                }
            }else {
                handleNotification(
                    'Error While Processing Data, Please Try Again',
                    'error');
                endLoading();
            }
        }
    });
    return false;
}

function populateEditForm() {
    showLoading("Fetching Data From Server");
    var data = 'task=getRecordIdDetails&resultId=' + resultId+'&sectionId='+sectionId;
    $.ajax({
        url:formGlobalPath + "exam/exam_result_junior_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#totalAttendance').val(data[1]);
                $('#totalMarks').val(data[2]);
                $('#attendanceDate').val(data[3]);
                $('#attendanceOfficer_val').val(data[5]);
                $('#attendanceOfficer').val(data[6]);
                $('#remarksDate').val(data[7]);
                $('#remarksOfficer_val').val(data[9]);
                $('#remarksOfficer').val(data[10]);

                $('#weightDate').val(data[15]);
                $('#weightOfficer_val').val(data[17]);
                $('#weightOfficer').val(data[18]);

                $('#heightDate').val(data[19]);
                $('#heightOfficer_val').val(data[21]);
                $('#heightOfficer').val(data[22]);

                $('#achievementDate').val(data[23]);
                $('#achievementOfficer_val').val(data[25]);
                $('#achievementOfficer').val(data[26]);
                showInsertForm();
                hideDisplayForm();
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
