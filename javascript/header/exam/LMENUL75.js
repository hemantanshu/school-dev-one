var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var examinationId, maxMark, passMark, confirmTable, finalTable;

$(document)
    .ready(
    function () {
        populateInitialElements();
    });


function showHideEntryForm() {
    toggleTheDiv('entryForm');
}
function showHideConfirmForm(){
    toggleTheDiv('confirmForm');
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
                    $('#startDate').html(output[9]);
                    $('#endDate').html(output[10]);

                    maxMark = output[6];
                    passMark = output[7];
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


function processSubmitAction(candidateId, nextFocusElement){

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
    var data = 'task=submitMark&mark='+mark+'&examinationId='+examinationId+'&candidateId='+candidateId;
    $
        .ajax({
            url:formGlobalPath + "exam/exam_mark_submission_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] != 2){
                    $('#'+rowId).remove();
                    $('#'+nextFocusElement).focus();
                    moveDataToConfirmForm(candidateId, mark);
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
    var data = 'task=confirmMark&mark='+mark+'&examinationId='+examinationId+'&candidateId='+candidateId;
    $
        .ajax({
            url:formGlobalPath + "exam/exam_mark_submission_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] != 2){
                    $('#'+rowId).remove();
                    $('#'+nextFocusElement).focus();
                    moveDataToFinalForm(candidateId, mark);
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

function moveDataToConfirmForm(candidateId, score){
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
                                        '<th><input type="text" name="'+markId+'" id="'+markId+'" value="'+score+'" class="required numeric" size="15" onclick="processSubmitAction('+candidateId+')\" /></th>'+
                                        '<th><button type="button" class="negative" onclick="processConfirmAction(\''+candidateId+'\')">Submit & Confirm</th>'
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
    var data = 'task=finalConfirmation&examinationId='+examinationId;
    showLoading('Testing Data From Server');
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