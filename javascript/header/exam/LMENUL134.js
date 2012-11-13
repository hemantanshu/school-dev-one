var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var examinationId, maxMark, passMark;

$(document)
    .ready(
    function () {
        populateInitialElements();
    });


function showHideEntryForm() {
    toggleTheDiv('entryForm');
}
function showHideFinalForm() {
    toggleTheDiv('finalForm');
}

function populateInitialElements(){
    examinationId = $('#examinationId').val();
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

                    maxMark = output[6];
                    passMark = output[7];
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

function processConfirmAction(candidateId, nextFocusElement){
    var rowId = "table"+candidateId;
    var markId = "mark"+candidateId;
    showLoading("Checking Data Before Uploading");
    var mark = $('#'+markId).val();
    if(mark == ""){
        alert("The mark cannot be blank");
        $('#'+markId).focus();
        endLoading();
        return false;
    }

    var newMark = parseFloat(mark);
    if(newMark > maxMark && mark != 'abs'){
        alert("The mark submitted cannot be greater than the max mark : "+ maxMark);
        $('#'+markId).focus();
        endLoading();
        return false;
    }
    showLoading("Uploading Data To Server");
    var data = 'task=verify&mark='+mark+'&examinationId='+examinationId+'&candidateId='+candidateId;
    $
        .ajax({
            url:formGlobalPath + "exam/exam_mark_verification_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output == 0){
                    handleNotification('Server Error .. please try after sometime', 'error');
                    return false;
                }else{
                    if(output[0] != 2){
                        $('#'+rowId).remove();
                        $('#'+nextFocusElement).focus();
                        alert('The candidate Mark Has Been Successfully Updated');
                        endLoading();
                    }else{
                        loadPageIntoDisplay(serverUrl);
                        endLoading();
                    }
                    return false;
                }

            }
        });
    return false;
}