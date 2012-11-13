var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var resultId, sectionId, sessionId, displayTable, printType, classId;
var candidateNameArray, imagePath, image, loaderImageTag;
$(document)
    .ready(
    function () {
        populateInitialElements();
        imagePath = schoolImageGlobalPath() + 'global/';
        image = imagePath+'ajax-loader.gif';
        loaderImageTag = '<img src="'+image+'" alt="" />Processing Result';
        candidateNameArray = new Array();
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
    showLoading('Loading Examination Details');
    sessionId = $('#sessionId').val();
    resultId = $('#resultId').val();
    sectionId = $('#sectionId').val();
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
                    fetchSetupUrls(resultId, sectionId, output[5]);
                }else{
                    loadPageIntoDisplay(serverUrl);
                }
                return false;
            }
        });
    endLoading();
    return false;
}

function startResultProcessing(){
    showLoading('Please Wait .. Processing Result For All Candidates');
	if (confirm('Do you really want to process result ?')) {
        var imagePath = schoolImageGlobalPath() + 'global/';
        var image = imagePath+'ajax-loader.gif';
        var loaderImageTag = '<img src="'+image+'" alt="" />Processing Result';
        var data = 'task=getCandidateIds&sectionId='+sectionId;
        $
            .ajax({
                url:formGlobalPath + "exam/exam_result_process_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (data) {
                    checkValidityOfOutput(data);
                    candidateNameArray = data;
                    hideTheDiv('totalResultProcess');
                    processCandidateResult();
                    endLoading();
                }
            });
    }
    return false;

}

function processResult4Candidate(candidateId){
    if (confirm('Do you really want to process the candidate result ?')) {
        candidateNameArray.push(candidateId);
        console.log(candidateNameArray);
        processCandidateResult();
        return false;
    }
}

function processCandidateResult(){
    var candidateId = candidateNameArray.pop();
    if(candidateId != undefined){
        var buttonName = 'button'+candidateId;
        $('#'+buttonName).html(loaderImageTag);
        showLoading('Processing Candidate Result');
        var data = 'task=processCandidateResult&resultId='+resultId+'&sectionId='+sectionId+'&candidateId='+candidateId;
        $
            .ajax({
                url:formGlobalPath + "exam/exam_result_process_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (data) {
                    checkValidityOfOutput(data);
                    $('#'+buttonName).html('Result Processed');
                    $('#'+buttonName).attr('class', 'positive');
                    processCandidateResult();
                    endLoading();
                }
            });
    }

    return false;
}

function showResultHistory4Candidate(candidateId){
    var url = serverUrl+'pages/exam/exam_result_process_recordv.php?candidateId='+candidateId+'&resultId='+resultId;
    loadColorboxPage(url, 800);
    return false;
}

