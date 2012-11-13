var valid = new validate();
$(document)
    .ready(
    function () {
        oTable = $('#groupRecords').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers",
            "bSort":false

        });
        formGlobalPath = getFormGlobalPath();
        populateCandidateDetails();
        getSearchResults();
        loadUserUsername();

        $("#userGroup")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?option=yes&type=USRGP",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:false
            });
        $("#userGroup").result(function (event, data, formatted) {
            $("#userGroup_val").val(data[1]);
        });



    });

function populateCandidateDetails() {
    var userId = $('#userId').val();
    if (userId == "")
        loadPageIntoDisplay(serverUrl);
    var data = 'userId=' + userId + '&task=fetchRecord';
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_user_education_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    $('#candidateName').html(output[1])
                    $('#registrationNumber').html(output[2]);
                    $('#registeredEmail').html(output[3]);
                    $('#designation').html(output[4]);
                    endLoading();
                } else {
                    loadPageIntoDisplay(serverUrl);
                    endLoading();
                }
            }
        });

    return false;
}

function loadUserUsername(){
    data = 'userId=' + userId + '&task=userName';
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_user_password_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    $('#userName').val(output[1]);
                } else {
                    handleNotification(
                        'Error While Inserting Data, Please Try Again',
                        'error');
                    endLoading();
                }
            }
        });
    return false;
}

function processUserForm(){
    var data = $('#userForm').serialize();
    data = 'userId=' + userId + '&task=userRecord&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_user_password_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    if(output[0] == 1){
                        alert('The Username is not available, please chose another one');
                    }else{
                        alert('The Username has been set.');
                        endLoading();
                        return false;
                    }
                    endLoading();
                } else {
                    handleNotification(
                        'Error While Inserting Data, Please Try Again',
                        'error');
                    endLoading();
                }
            }
        });
    return false;
}

function processInsertForm() {
    var data = $('#insertForm').serialize();
    data = 'userId=' + userId + '&task=insertRecord&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_user_password_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    if(output[0] == 1){
                        alert('Both The Password Donot Match. Please Try Again');
                    }else{
                        alert('The Password Has Been Successfully Set.');
                        var url = serverUrl+"pages/utility/utl_user_profile.php?userId="+userId;
                        loadPageIntoDisplay(url);
                        return false;
                    }
                    endLoading();
                } else {
                    handleNotification(
                        'Error While Inserting Data, Please Try Again',
                        'error');
                    endLoading();
                }
            }
        });
    return false;
}

function processAssignmentForm() {
    var data = $('#assignmentForm').serialize();
    data = 'userId=' + userId + '&task=assignRecord&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_user_password_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {

                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    aPos = oTable.fnGetNodes().length;
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    oTable
                        .fnAddData([
                        output[1],
                        output[2],
                        output[3],
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + viewImageLink + "Show Details</button>"]);
                    $('#assignmentReset').click();
                    endLoading();
                } else {
                    handleNotification(
                        'Error While Inserting Data, Please Try Again',
                        'error');
                    endLoading();
                }
            }
        });
    return false;
}

function getSearchResults() {
    var data = 'userId=' + userId + '&task=search';
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_user_password_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {

                checkValidityOfOutput(data);
                if (data[0][0] == 1) {
                    oTable.fnClearTable();
                    handleNotification(
                        'No Data Fetched With The Given Inputs', 'info');
                    endLoading();
                } else {
                    var viewImageLink = getButtonViewImage();
                    if (data[0][0] != 0) {
                        oTable.fnClearTable();
                        for (var i = 0; i < data.length; i++) {
                            oTable
                                .fnAddData([
                                data[i][1],
                                data[i][2],
                                data[i][3],
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + viewImageLink + "Show Details</button>"]);
                        }
                        endLoading();
                        $('#assignReset').click();
                    } else {
                        handleNotification(
                            'Error While Processing Data, Please Try Again',
                            'error');
                        endLoading();
                    }
                }
                endLoading();
            }
        });
    return false;
}
