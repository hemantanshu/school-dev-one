
$(document).ready(function () {
	$(".date").dateinput({
        format: 'yyyy-mm-dd'
    });
	
    formGlobalPath = getFormGlobalPath();
    var userId = $('#userId').val();
    populateUserDetails();


    $("#religion")
        .autocomplete(
        formGlobalPath
            + "global/glb_option_search.php?option=yes&type=RELIG",
        {
            width:260,
            matchContains:true,
            mustMatch:false,
            selectFirst:false
        });
    $("#religion").result(function (event, data, formatted) {
        $("#religion_val").val(data[1]);
    });
    $("#nationality")
        .autocomplete(
        formGlobalPath
            + "global/glb_option_search.php?option=yes&type=NATON",
        {
            width:260,
            matchContains:true,
            mustMatch:false,
            selectFirst:false
        });
    $("#nationality").result(function (event, data, formatted) {
        $("#nationality_val").val(data[1]);
    });
    $("#recordShelve1")
        .autocomplete(
        formGlobalPath
            + "global/glb_option_search.php?option=yes&type=SHLVE",
        {
            width:260,
            matchContains:true,
            mustMatch:false,
            selectFirst:false
        });
    $("#recordShelve1").result(
        function (event, data, formatted) {
            $("#recordShelve1_val").val(data[1]);
        });
    $("#recordShelve2")
        .autocomplete(
        formGlobalPath
            + "global/glb_option_search.php?option=yes&type=SHLVE",
        {
            width:260,
            matchContains:true,
            mustMatch:false,
            selectFirst:false
        });
    $("#recordShelve2").result(
        function (event, data, formatted) {
            $("#recordShelve2_val").val(data[1]);

        });
    $("#recordShelve3")
        .autocomplete(
        formGlobalPath
            + "global/glb_option_search.php?option=yes&type=SHLVE",
        {
            width:260,
            matchContains:true,
            mustMatch:false,
            selectFirst:false
        });
    $("#recordShelve3").result(
        function (event, data, formatted) {
            $("#recordShelve3_val").val(data[1]);
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

    $("#department")
        .autocomplete(
        formGlobalPath
            + "global/glb_option_search.php?type=DEPTY&option=yes",
        {
            width:260,
            matchContains:true,
            mustMatch:false,
            selectFirst:false
        });
    $("#department").result(function (event, data, formatted) {
        $("#department_val").val(data[1]);
    });

    $("#employeeType")
        .autocomplete(
        formGlobalPath
            + "global/glb_option_search.php?type=EMPTY&option=yes",
        {
            width:260,
            matchContains:true,
            mustMatch:false,
            selectFirst:false
        });
    $("#employeeType").result(function (event, data, formatted) {
        $("#employeeType_val").val(data[1]);
    });

});

function populateUserDetails() {
    if (userId == "")
        loadPageIntoDisplay(serverUrl)
    var data = 'userId=' + userId + '&task=fetchRecord';
    showLoading("Fetching Data From Server");
    $.ajax({
        url:formGlobalPath + "utility/utl_user_employment_form.php",
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
                
                $('#employeeCode1').val(output[2]);
                populatePersonalInformation();
                endLoading();
            } else {
                loadPageIntoDisplay(serverUrl);
                endLoading();
            }
        }
    });
    return false;
}

function populatePersonalInformation() {
    var data = 'userId=' + userId + '&task=getPersonalInformation';
    showLoading("Fetching Data From Server");
    $.ajax({
        url:formGlobalPath + "utility/utl_user_personal_form.php",
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
                $('#bday').val(output[5]);
                $('#gender').val(output[6]);
                $('#marital').val(output[7]);
                $('#religion_val').val(output[8]);
                $('#religion').val(output[9]);
                $('#nationality_val').val(output[10]);
                $('#nationality').val(output[11]);
                $('#personalEmail').val(output[12]);
                $('#officialEmail').val(output[13]);
                $('#contactNo').val(output[14]);
                $('#aContactNo').val(output[15]);

                populateAddressDetails();
                endLoading();
            } else {
                loadPageIntoDisplay(serverUrl);
                endLoading();
            }
        }
    });
    return false;
}

function populateAddressDetails() {
    var data = 'userId=' + userId + '&task=getAddressDetails';
    showLoading("Fetching Data From Server");
    $.ajax({
        url:formGlobalPath + "utility/utl_user_personal_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (output) {
            checkValidityOfOutput(output);
            if (output[0] != 0) {
                $('#streetAddress1').val(output[1]);
                $('#streetAddress2').val(output[2]);
                $('#city_val').val(output[3]);
                $('#city').val(output[4]);
                $('#state_val').val(output[5]);
                $('#state').val(output[6]);
                $('#pincode').val(output[7]);
                $('#country_val').val(output[8]);
                $('#country').val(output[9]);
                populateRegistrationDetails();
                endLoading();
            } else {
                loadPageIntoDisplay(serverUrl);
                endLoading();
            }
        }
    });
    return false;
}
function populateRegistrationDetails() {
    var data = 'userId=' + userId + '&task=getRegistrationDetails';
    showLoading("Fetching Data From Server");
    $.ajax({
        url:formGlobalPath + "utility/utl_user_personal_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (output) {
            checkValidityOfOutput(output);
            if (output[0] != 0) {
                $('#recordShelve1_val').val(output[1]);
                if(output[1] != '')
                    $('#recordShelve1').val(output[2]);

                $('#recordShelve2_val').val(output[3]);
                if(output[3] != '')
                    $('#recordShelve2').val(output[4]);

                $('#recordShelve3_val').val(output[5]);
                if(output[5] != '')
                    $('#recordShelve3').val(output[6]);

                $('#department_val').val(output[7]);
                if(output[7] != '')
                    $('#department').val(output[8]);
                $('#employeeType_val').val(output[9]);
                if(output[8] != '')
                    $('#employeeType').val(output[10]);
                endLoading();
            } else {
                loadPageIntoDisplay(serverUrl);
                endLoading();
            }
        }
    });
    return false;
}

function processUpdateForm() {

    var data = $('#insertForm').serialize();
    data = data + '&userId=' + userId + '&task=updateEmployeeRecord';
    showLoading("Uploading Data To Server");
    $.ajax({
        url:formGlobalPath + "utility/utl_user_personal_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (output) {
            checkValidityOfOutput(output);
            if (output[0] != 0) {
                jAlert('The employee details has been successfully updated');
                var link = serverUrl + 'pages/utility/utl_user_profile.php?userId=' + userId;
                loadPageIntoDisplay(link);
                endLoading();
            } else {
                loadPageIntoDisplay(serverUrl);
                endLoading();
            }
        }
    });
    return false;
}

function resetFormFields() {
    populateUserDetails();
}