var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var resultId, sectionId;

$(document)
    .ready(
    function () {
        populateInitialElements();
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
