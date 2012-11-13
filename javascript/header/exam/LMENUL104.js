
$(document)
    .ready(
    function () {
        loadExtraMenuListing();
    });

function showAssessmentMark(activityId){
    var url = serverUrl+'pages/exam/exam_agrade_status.php?activityId='+activityId;
    loadPageIntoDisplay(url);
    return false;
}