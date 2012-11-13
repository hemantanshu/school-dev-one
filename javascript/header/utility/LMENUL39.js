var valid = new validate();
$(document)
    .ready(
    function () {
        formGlobalPath = getFormGlobalPath();

        userId = $('#userId').val();
        guardianType = $('#guardianType').val();

        populateEmployeeDetails();
        loadGuardianDetails();
        loadGuardianAddressDetails();

        $("#occupation")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?type=PROFS&option=yes",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#occupation").result(function (event, data, formatted) {
            $("#occupation_val").val(data[1]);
        });

        $("#city")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?type=CITYS&option=yes",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#city").result(function (event, data, formatted) {
            $("#city_val").val(data[1]);
        });

        $("#state")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?type=STATE&option=yes",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#state").result(function (event, data, formatted) {
            $("#state_val").val(data[1]);
        });

        $("#country")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?type=CNTRY&option=yes",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#country").result(function (event, data, formatted) {
            $("#country_val").val(data[1]);
        });


    });
function resetFieldValue(fieldName) {
    $('#' + fieldName).attr('value', '');
}

function populateEmployeeDetails() {
    if (userId == "")
        loadPageIntoDisplay(serverUrl);
    var data = 'userId=' + userId + '&task=fetchRecord';
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_user_guardian_form.php",
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

function loadGuardianDetails() {
    //loading the guardian data
    showLoading("Fetching Details Of The Guardian");
    data = 'userId=' + userId + '&guardianType=' + guardianType + '&task=fetchGuardianRecord';
    $
        .ajax({
            url:formGlobalPath + "utility/utl_user_guardian_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    $('#salutation').val(output[1]);
                    $('#firstName').val(output[2]);
                    $('#middleName').val(output[3]);
                    $('#lastName').val(output[4]);
                    $('#mobileNo').val(output[5]);
                    $('#landlineNo').val(output[6]);
                    $('#emailId').val(output[7]);
                    $('#occupation').val(output[8]);
                    $('#occupation_val').val(output[9]);
                    $('#remarks').val(output[10]);
                    endLoading();
                } else {
                    handleNotification('No data against this guardian exists, please fill in the form', 'info');
                    endLoading();
                }
            }
        });
    return false;
}

function loadGuardianAddressDetails() {
    //loading the address data
    showLoading("Fetching Guardian Address Details");
    data = 'userId=' + userId + '&guardianType=' + guardianType + '&task=fetchAddress';
    $
        .ajax({
            url:formGlobalPath + "utility/utl_user_guardian_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    $('#streetAddress1').val(output[1]);
                    $('#streetAddress2').val(output[2]);
                    $('#city_val').val(output[4]);
                    $('#city').val(output[3]);
                    $('#state_val').val(output[6]);
                    $('#state').val(output[5]);
                    $('#pincode').val(output[7]);
                    $('#country_val').val(output[9]);
                    $('#country').val(output[8]);
                    endLoading();
                } else {
                    endLoading();
                }
            }
        });
    return false;
}

function copyAddressDetails(guardianType) {
    //loading the address data
    showLoading("Fetching Guardian Address Details");
    var data = 'userId=' + userId + '&guardianType=' + guardianType + '&task=fetchAddress';
    $
        .ajax({
            url:formGlobalPath + "utility/utl_user_guardian_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    $('#streetAddress1').val(output[1]);
                    $('#streetAddress2').val(output[2]);
                    $('#city_val').val(output[4]);
                    $('#city').val(output[3]);
                    $('#state_val').val(output[6]);
                    $('#state').val(output[5]);
                    $('#pincode').val(output[7]);
                    $('#country_val').val(output[9]);
                    $('#country').val(output[8]);
                    endLoading();
                } else {
                    handleNotification('No data against this address exists, please fill in the form', 'warning');
                    endLoading();
                }
            }
        });
    return false;
}

function processUpdateForm() {
    var data = $('#updateForm').serialize();
    data = 'userId=' + userId + '&guardianType=' + guardianType + '&task=updateRecord&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_user_guardian_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    jAlert('Employee guardian details has been successfully updated');
                    var link = serverUrl + 'pages/utility/utl_user_profile.php?userId=' + userId;
                    loadPageIntoDisplay(link)
                    endLoading();
                } else {
                    loadPageIntoDisplay(serverUrl);
                    endLoading();
                }
            }
        });
    return false;
}

function copyCorrespondenceAddressDetails(addressId){
    showLoading("Fetching Guardian Address Details");
    data = 'addressId=' + addressId + '&task=fetchCandidateAddress';
    $
        .ajax({
            url:formGlobalPath + "utility/utl_candidate_gaurdian_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    $('#streetAddress1').val(output[1]);
                    $('#streetAddress2').val(output[2]);
                    $('#city_val').val(output[4]);
                    $('#city').val(output[3]);
                    $('#state_val').val(output[6]);
                    $('#state').val(output[5]);
                    $('#pincode').val(output[7]);
                    $('#country_val').val(output[9]);
                    $('#country').val(output[8]);
                    endLoading();
                } else {
                    handleNotification('No data against this address exists, please fill in the form', 'warning');
                    endLoading();
                }
            }
        });
    return false;
}