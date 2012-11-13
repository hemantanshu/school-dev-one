var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var resultId, sectionId, sessionId, classId, resultSectionId;
var confirmTable;
$(document)
    .ready(
    function () {
        populateInitialElements();
        confirmTable = $('#mainTable');
    });


function populateInitialElements(){
    sessionId = $('#sessionId').val();
    resultId = $('#resultId').val();
    sectionId = $('#sectionId').val();
    resultSectionId = $('#resultSectionId').val();
    showLoading('Loading Examination Details');
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

function processConfirmAction(candidateId, nextFocusFormElement){
    var rowId = "table"+candidateId;
    var markId = "mark"+candidateId;
    var mark = $('#'+markId).val();
    if(mark != ''){
        showLoading("Uploading Data To Server");
        var data = 'task=confirmData&data='+mark+'&resultSectionId='+resultSectionId+'&candidateId='+candidateId;
        $
            .ajax({
                url:formGlobalPath + "exam/exam_weight_entry_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if(output[0] != 2){
                        moveDataToFinalForm(candidateId, mark);
                        $('#'+nextFocusFormElement).focus();
                        $('#'+rowId).remove();
                        endLoading();
                    }else{
                        handleNotification('System Error.. Please Try After Some Time', 'error');
                        endLoading();
                    }
                    return false;
                }
            });
    }

    return false;
}

function moveDataToFinalForm(candidateId, score){
    var data = 'task=getCandidateDetails&candidateId='+candidateId;
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
                    var tableId = 'table'+candidateId;
                    var markId = 'mark'+candidateId;
                    var htmlTable = '<tr class="odd" id="'+tableId+'">' +
                        '<th>'+output[1]+'</th>' +
                        '<th>'+output[2]+'</th>' +
                        '<th>'+output[3]+'</th>' +
                        '<th><input type="text" name="'+markId+'" id="'+markId+'" value="'+score+'" size="10" /></th>'+
                        '<th><button type="button" class="negative" onclick="processConfirmAction(\''+candidateId+'\')">Submit & Confirm</th>'+
                        '</tr>';
                    confirmTable.append(htmlTable);
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

function checkFinalConfirmation(id){
    var data = 'task=finalConfirmation&resultSectionId='+resultSectionId+'&sectionId='+sectionId;
    showLoading('Testing Data From Server');
    $
        .ajax({
            url:formGlobalPath + "exam/exam_weight_entry_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] != 0){
                    if(output[0] == 1){
                        handleNotification('You have successfully submitted mark', 'success');
                        $('#finalConfirmationButton').remove();
                        var taskId = 'taskNotification'+id;
                        $('#'+taskId).remove();
                        endLoading();
                        return false;
                    }else{
                        alert('There are few candidates whose entries are still left. please fill up those');
                        handleNotification('There are few candidates whose entries are still left. please fill up those', 'error');
                        endLoading();
                        return false;
                    }

                }else{
                    loadPageIntoDisplay(serverUrl);
                    endLoading();
                }
                return false;
            }
        });
    return false;
}