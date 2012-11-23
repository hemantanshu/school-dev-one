function showSubjectMark(examinationDateId){
    var url = serverUrl+'pages/exam/exam_mark_status.php?examinationId='+examinationDateId;
    loadColorboxPage(url, 1000);
    //loadPageIntoDisplay(url);
    return false;
}