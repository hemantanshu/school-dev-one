var valid = new validate();
$(document)
    .ready(
    function () {
        formGlobalPath = getFormGlobalPath();
        $("#rank")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?option=yes&type=RANKS",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#rank").result(function (event, data, formatted) {
            $("#rank_val").val(data[1]);
        });
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


function populateFormElements() {
    // @todo related form field activations has to be done here
}

function checkEmployeeCode() {
    var employeeCode = $('#employeeCode').val();
    if (employeeCode == "")
        return true;
    var data = 'task=checkEmployeeCode&employeeCode=' + employeeCode;
    showLoading("Fetching Data To Server");
    $
        .ajax({
            url:formGlobalPath
                + "utility/utl_user_registration_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] == 0) {
                    alert("The Employee Code Has Already Been Assigned To Another Employee, Please Chose Another ...");
                    $('#employeeCode').val('');
                    $('#employeeCode').focus();
                }
            }
        });
    endLoading();
    return false;
}

// Code For Check Of Input Errors
function processInsertForm() {
    // preparing for ajax call
    var data = $('#insertForm').serialize();
    data = 'task=insertRecord&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath
                + "utility/utl_user_registration_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    alert("The Employee Has Been Successfully Enrolled");
                    var link = serverUrl + 'pages/utility/utl_user_profile.php?userId=' + output[0];
                    loadPageIntoDisplay(link);
                    endLoading();
                } else {
                    handleNotification(
                        'Error While Inserting Data, Consult System Admin',
                        'error');
                    endLoading();
                }
            }
        });
    return false;
}