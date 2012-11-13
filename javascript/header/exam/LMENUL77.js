var formGlobalPath = getFormGlobalPath();


$(document)
    .ready(
    function () {
        populateInitialElements();
        loadExtraMenuListing();
    });


function populateInitialElements(){
    var examinationId = $('#examinationId').val();
    var data = 'task=getExaminationDetails&examinationId='+examinationId;
    $
        .ajax({
            url:formGlobalPath + "exam/exam_mark_submission_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] != 0){
                    $('#examinationName').html(output[0]);
                    $('#sessionName').html(output[1]);
                    $('#className').html(output[2]);
                    $('#sectionName').html(output[8]);
                    $('#subjectName').html(output[4]+'  '+output[3]);
                    $('#subjectCombination').html(output[5]);
                    $('#startDate').html(output[9]);
                    $('#endDate').html(output[11]);
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
