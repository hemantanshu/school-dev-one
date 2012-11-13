function showSubjectMark(examinationDateId){
    var url = serverUrl+'pages/exam/exam_mark_status.php?examinationId='+examinationDateId;
    loadPageIntoDisplay(url);
    return false;
}