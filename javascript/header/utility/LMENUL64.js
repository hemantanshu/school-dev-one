var subjectType = new Array();
var optionalSubjectCount;
var sessionId;
$(document)
    .ready(
    function () {
        formGlobalPath = getFormGlobalPath();
        classGlobal = $('#class_global').val();

        getClassSessionDetails();
        loadExtraMenuListing();
        
        optionalSubjectCount = $('#optionalSubjects').val();

        for(var i =0 ; i < optionalSubjectCount; ++i)
            subjectType[i] = $('#subjectGlobal'+i+'').val();
    });

function getClassSessionDetails(){
    var data = $('#insertForm').serialize();
    data = 'classId=' + classGlobal + '&task=getClassSessionDetails';
    $
        .ajax({
            url:formGlobalPath + "utility/utl_class_details_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    sessionId = output[2];
                    $('#session_d').html(output[0]);
                    $('#class_d').html(output[1]);
                    showTheDiv('sessionRecord');
                }
            }
        });
    return false;
}

function updateCandidateSubjects(candidateId){
    var newSubjectAssigned, subjectAssigned, data;

    for(var i = 0; i <optionalSubjectCount; ++i){
        newSubjectAssigned = $('#'+candidateId+'subject'+i+'').val();
        subjectAssigned = $('#'+candidateId+'assigned'+i+'').val();

        if(newSubjectAssigned != subjectAssigned){
            //sending data to the server for new insertion
            showLoading('Assigning Subject To The Candidate');
            data = 'candidateId='+candidateId+'&sessionId='+sessionId+'&subjectType='+subjectType[i]+'&subject='+newSubjectAssigned+'&task=updateSubjectDetails';
            $
                .ajax({
                    url:formGlobalPath + "utility/utl_class_candidate_subject_form.php",
                    type:"POST",
                    data:data,
                    cache:false,
                    dataType:'html',
                    success:function (data) {
                        checkValidityOfOutput(data);
                        if (data[0] == 0) {
                            handleNotification('Problem assigning subject to candidate', 'error');
                        }
                    }
                });
            endLoading();
        }
    }
    $('#'+candidateId+'').remove();
    handleNotification('The candidate has been assigned these subjects', 'success');
    return false;
}