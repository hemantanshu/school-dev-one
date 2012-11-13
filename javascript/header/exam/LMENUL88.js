var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var resultId, sectionId, sessionId, classId, resultSectionId;
var confirmTable, optionDetails;
$(document)
    .ready(
    function () {
        populateInitialElements();
        getOptionValues();
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

function processConfirmAction(candidateId, nextFocusElement){
    var rowId = "table"+candidateId;
    var markId = "mark"+candidateId;
    var mark = $('#'+markId).val();
    showLoading("Uploading Data To Server");
    var data = 'task=confirmRemarks&remarks='+mark+'&resultSectionId='+resultSectionId+'&candidateId='+candidateId;
    $
        .ajax({
            url:formGlobalPath + "exam/exam_remarks_entry_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] != 2){
                    moveDataToFinalForm(candidateId, mark);
                    $('#'+nextFocusElement).focus();
                    $('#'+rowId).remove();
                    endLoading();
                }else{
                    handleNotification('System Error.. Please Try After Some Time', 'error');
                    endLoading();
                }
                return false;
            }
        });
    return false;
}

function loadOptions(remarks){
    var options = '';
    for(i = 0; i < optionDetails.length; ++i){
        if(optionDetails[i][0] == remarks)
            options = options + '<option value="'+optionDetails[i][0]+'" selected="selected">'+optionDetails[i][1]+'</option>';
        else
            options = options + '<option value="'+optionDetails[i][0]+'">'+optionDetails[i][1]+'</option>';
    }
    return options;
}

function getOptionValues(){
    var data = 'task=getOptions';
    $
        .ajax({
            url:formGlobalPath + "exam/exam_remarks_entry_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {                
                checkValidityOfOutput(output);
                if(output[0] != 0){
                    optionDetails = output;
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
                        '<th><select name="'+markId+'" id="'+markId+'" style=\"width: 350px\">' +
                        loadOptions(score)
                        '</select></th>'+
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