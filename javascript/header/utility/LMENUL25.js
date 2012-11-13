var valid = new validate();
$(document)
    .ready(
    function () {
        formGlobalPath = getFormGlobalPath();
        $("#religion")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?option=yes&type=RELIG",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
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
                mustMatch:true,
                selectFirst:false
            });
        $("#nationality").result(function (event, data, formatted) {
            $("#nationality_val").val(data[1]);
        });
        $("#classAdmitted")
            .autocomplete(
            formGlobalPath
                + "utility/utl_autocomplete_search.php?option=class",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:false
            });
        $("#classAdmitted").result(
            function (event, data, formatted) {
                $("#classAdmitted_val").val(data[1]);
            });
        $("#sectionAdmitted")
            .autocomplete(
            formGlobalPath
                + "utility/utl_autocomplete_search.php?option=section",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:false
            });
        $("#sectionAdmitted").result(
            function (event, data, formatted) {
                $("#sectionAdmitted_val").val(data[1]);
            });
        $("#allottedHouse")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?option=yes&type=HOUSE",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:false
            });
        $("#allottedHouse").result(
            function (event, data, formatted) {
                $("#allottedHouse_val").val(data[1]);
            });
        $("#recordShelve1")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?option=yes&type=SHLVE",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
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
                mustMatch:true,
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
                mustMatch:true,
                selectFirst:false
            });
        $("#recordShelve3").result(
            function (event, data, formatted) {
                $("#recordShelve3_val").val(data[1]);
            });

    });


function populateSectionDetails() {
    $('#sectionAdmitted').removeAttr('readonly');
    $('#allottedHouse').removeAttr('readonly');

    $('#sectionAdmitted').val('');
    $('#allottedHouse').val('');

    classId = $('#classAdmitted_val').val();
    $.cookie('classId_globalVars', classId, { expires:7, path:'/' });
}

function populateFormElements() {
    //@todo related form field activations has to be done here
}

// Code For Check Of Input Errors
function processInsertForm() {
    // preparing for ajax call
    var data = $('#insertForm').serialize();
    data = 'task=insertRecord&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_candidate_registration_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    alert("The Candidate Has Been Successfully Enrolled, Please fill up additional details form");
                    var link = "pages/utility/utl_candidate_profile.php?candidateId=" + output[1];
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

