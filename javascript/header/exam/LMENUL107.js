var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var resultId, sessionId, classId, sectionId, oTable;

$(document)
    .ready(
    function () {
        populateInitialElements();
        loadExtraMenuListing();

        $("#markSubmissionOfficer_i")
            .autocomplete(
            formGlobalPath
                + "utility/utl_user_autocomplete.php?option=employeeTeacher",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#markSubmissionOfficer_i").result(
            function (event, data, formatted) {
                $("#markSubmissionOfficer_ival").val(data[1]);
            });

        $("#markVerificationOfficer_i")
            .autocomplete(
            formGlobalPath
                + "utility/utl_user_autocomplete.php?option=employeeTeacher",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#markVerificationOfficer_i").result(
            function (event, data, formatted) {
                $("#markVerificationOfficer_ival").val(data[1]);
            });




    });

function showFormElements(){
    showTheDiv('insertForm');
}
function hideFormElements(){
    hideTheDiv('insertForm');
}



function populateInitialElements(){
    sessionId = $('#sessionId').val();
    resultId = $('#resultId').val();
    sectionId = $('#sectionId').val();
    showLoading('Loading Result Details');
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
                }else{
                    loadPageIntoDisplay(serverUrl);
                }
                return false;
            }
        });
    endLoading();
    return false;
}

function processCopyForm(){
    hideFormElements();
    var toCopyResultId = $('#resultNameId').val();
    showLoading('Fetching Data From Server');
    var data = 'task=copyResultAssessment&copyResultId='+toCopyResultId+'&resultId='+resultId+'&sectionId='+sectionId;
    $
        .ajax({
            url:formGlobalPath + "exam/exam_assessment_copy_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] != 0){
                    if(output[0][0] == 0){
                        jAlert('The result has already assessment setup done');
                        loadPageIntoDisplay(serverUrl);
                        endLoading();
                    }else{
                        if(output[0][0] == 1){
                            handleNotification('No assessment record exists for the given result, please select another', 'warning');
                            endLoading();
                        }else{
                            var htmlSection = '';
                            for(var i = 0; i < output.length; ++i){
                                htmlSection = htmlSection+' '+output[i][1]+' | ';
                            }
                            $('#assessmentNames').html(htmlSection);
                            $('#toCopyResultId').val(toCopyResultId);
                            showFormElements();
                            endLoading();
                        }
                    }
                }else{
                    handleNotification('System is facing some issues, please try after some time', 'error');
                    endLoading();
                }
            }
        });
    return false;
}

function processInsertForm(){
    var data = $('#insertForm').serialize();
    showLoading('Processing Data From Server');
    data = 'task=insertRecord&resultId='+resultId+'&sectionId='+sectionId+'&'+data;
    $
        .ajax({
            url:formGlobalPath + "exam/exam_assessment_copy_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] != 0){
                    jAlert('The result assessment has been copied');
                    var url = serverUrl+'pages/exam/exam_result_assessment.php?resultId='+resultId+'&sectionId='+sectionId+'&sessionId='+sessionId;
                    loadPageIntoDisplay(url);
                    endLoading();
                }else{
                    handleNotification('System is facing some issues, please try after some time', 'error');
                    endLoading();
                }
            }
        });
    return false;
}
