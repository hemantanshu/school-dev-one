var formGlobalPath = getFormGlobalPath();


$(document)
    .ready(
    function () {
        populateInitialElements();
    });

function populateInitialElements(){
    var activityId = $('#activityId').val();
    var data = 'task=getActivityDetails&activityId='+activityId;
    $
        .ajax({
            url:formGlobalPath + "exam/exam_agrade_submission_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] != 0){
                    $('#resultName').html(output[1]);
                    $('#sessionName').html(output[0]);
                    $('#assessmentName').html(output[2]);
                    $('#activityName').html(output[3]);
                    $('#className').html(output[4]);
                    $('#sectionName').html(output[5]);
                    $('#subjectName').html(output[6]);
                    $('#endDate').html(output[7]);

                    confirmTable = $('#confirmTable');
                    finalTable = $('#finalTable');
                    endLoading();
                }else{
                    loadPageIntoDisplay(serverUrl);
                    endLoading();
                }
                return false;
            }
        });
    return false;
}
