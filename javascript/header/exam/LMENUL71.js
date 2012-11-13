$(document)
    .ready(
    function () {
    });

function processClassSelection() {
    var sectionId = $('#sectionId').val();
    var sessionId = $('#session_val').val();
    var examinationId = $('#examinationId_direct').val();
    var flag = $('#flag_direct').val();
    if(flag == 1)
        var url = serverUrl+'/pages/exam/exam_examination_dates.php?sessionId='+sessionId+'&examinationId='+examinationId+'&sectionId='+ sectionId;
    else
        var url = serverUrl+'/pages/exam/exam_result_assessment.php?sessionId='+sessionId+'&resultId='+examinationId+'&sectionId='+ sectionId;
    loadPageIntoDisplay(url);
    $.colorbox.close();
    return false;
}
