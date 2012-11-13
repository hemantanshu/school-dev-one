var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var gradeOptions, resultId, sectionId;
var finalTable;

$(document)
    .ready(
    function () {
        populateInitialElements();
        finalTable = $('#finalTable');
    });


function fetchSetupUrls(resultId, sectionId, resultTypeId){
    showLoading('Loading Result Setup Urls');
    var data = 'task=fetchResultTypeUrl&resultTypeId='+resultTypeId;
    $
        .ajax({
            url:formGlobalPath + "exam/exam_result_type_urls_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                var loadUrl = '';
                if (data[0][0] != 0) {
                    for (var i = 0; i < data.length; i++) {
                        var url = data[i][1]+'?resultId='+resultId+'&sectionId='+sectionId;
                        loadUrl += '<li><a href="#" class="bookmarkedMenuListing" onclick="loadPageIntoDisplay(\''+url+'\')"><img src="images/global/b_usredit.png" alt="" />'+data[i][0]+'</a></li>';
                    }
                    extraMenuListing.html(loadUrl);
                    additionalMenuDisplay.show();
                }
            }
        });
    endLoading();
    return false;
}

function populateInitialElements(){
    resultId = $('#resultIdGlobal').val();
    sectionId = $('#sectionIdGlobal').val();
    var data = 'task=resultDetails&resultId='+resultId+'&sectionId='+sectionId;
    $
        .ajax({
            url:formGlobalPath + "exam/exam_result_subjectc_grade_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] != 0){
                    $('#resultName').html(output[0]);
                    $('#className').html(output[1]);
                    $('#resultType').html(output[2]);
                    $('#resultDescription').html(output[3]);

                    fetchSetupUrls(resultId, sectionId, output[4]);
                    endLoading();
                }else{
                    handleNotification('Server Error .. Try After sme time', 'error');
                    endLoading();
                }
                return false;
            }
        });
    return false;
}

function processSubmitAction(subjectCount){
    var rowId = "row"+subjectCount;

    var subjectName = "subject"+subjectCount;
    var subjectId = $('#'+subjectName).val();
    var componentName = "component"+subjectCount;
    var componentId = $('#'+componentName).val();

    var gradeAssigned = "grade"+subjectCount;
    var gradeId = $('#'+gradeAssigned).val();

    var nextFocusElement = "gradeAssigned"+(subjectCount+1);
    showLoading("Submitting Data To Server");
    var data = 'task=submitSubjectComponentGrade&resultId='+resultId+'&sectionId='+sectionId+'&gradeId='+gradeId+'&subjectId='+subjectId+'&componentId='+componentId;
    $
        .ajax({
            url:formGlobalPath + "exam/exam_datasheet_subjectc_grade_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] != 2){
                    $('#'+rowId).remove();
                    $('#'+nextFocusElement).focus();
                    moveDataToFinalForm(output[0]);
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

function moveDataToFinalForm(subjectComponentGradeId){
    showLoading('Fetching Record Details');
    var data = 'task=getSubjectComponentDetails&subjectComponentGradeId='+subjectComponentGradeId;
    $
        .ajax({
            url:formGlobalPath + "exam/exam_result_subjectc_grade_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] != 0){
                    var htmlTable = '<tr class="odd">' +
                        '<th>'+output[0]+'</th>' +
                        '<th>'+output[1]+'</th>' +
                        '<th>'+output[2]+'</th>' +
                        '<th>'+output[3]+'</th>'+
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
