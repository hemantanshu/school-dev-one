var formGlobalPath = getFormGlobalPath();


$(document)
    .ready(
    function () {
        populateInitialElements();
        loadExtraMenuListing();
    });


function populateInitialElements(){
    var examinationId = $('#examinationIdGlobal').val();
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
                    $('#examinationNameDirect').html(output[0]);
                    $('#sessionNameDirect').html(output[1]);
                    $('#classNameDirect').html(output[2]);
                    $('#sectionNameDirect').html(output[8]);
                    $('#subjectNameDirect').html(output[4]+'  '+output[3]);
                    $('#subjectCombinationDirect').html(output[5]);
                    $('#startDateDirect').html(output[9]);
                    $('#endDateDirect').html(output[11]);
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
