var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var activityId, maxMark, passMark, confirmTable, finalTable;

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
    activityId = $('#activityId').val();
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



function processConfirmAction(candidateId, nextFocusElement){
    var rowId = "table"+candidateId;
    var markId = "mark"+candidateId;
    showLoading("Checking Data Before Uploading");
    var mark = $('#'+markId).val();
    showLoading("Uploading Data To Server");
    var data = 'task=verify&mark='+mark+'&activityId='+activityId+'&candidateId='+candidateId;
    $
        .ajax({
            url:formGlobalPath + "exam/exam_agrade_verification_form.php",
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
                        moveDataToFinalForm(candidateId, output[0]);
                        endLoading();
                    }else{
                    	handleNotification('Server Error .. please try after sometime', 'error');
                        endLoading();
                    }
                    return false;
                }

            }
        });
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
                        '<th>'+score+'</th>'+
                        '</tr>';
                    finalTable.append(htmlTable);
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
    var data = 'task=finalConfirmation&activityId='+activityId;
    showLoading('Testing Data From Server');    
    $
        .ajax({
            url:formGlobalPath + "exam/exam_agrade_verification_form.php",
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