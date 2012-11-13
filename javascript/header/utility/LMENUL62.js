var valid = new validate();
$(document)
    .ready(
    function () {
        formGlobalPath = getFormGlobalPath();
        $("#university")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?type=UVSTY&option=yes",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#university").result(function (event, data, formatted) {
            $("#university_val").val(data[1]);
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

        // update form autocomplete portion
        $("#university_u")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?type=UVSTY&option=yes",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#university_u").result(function (event, data, formatted) {
            $("#university_uval").val(data[1]);
        });

        $("#city_u")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?type=CITYS&option=yes",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#city_u").result(function (event, data, formatted) {
            $("#city_uval").val(data[1]);
        });

        $("#state_u")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?type=STATE&option=yes",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#state_u").result(function (event, data, formatted) {
            $("#state_uval").val(data[1]);
        });

        $("#country_u")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?type=CNTRY&option=yes",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#country_u").result(function (event, data, formatted) {
            $("#country_uval").val(data[1]);
        });

    });

function processInsertForm() {
    var data = $('#insertForm').serialize();
    data = 'task=insertRecord&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_institute_details_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0] != 0) {
                    $.colorbox.close();
                    handleNotification(
                        'The institute details has been successfully inserted',
                        'error');
                    endLoading();
                } else {
                    handleNotification(
                        'Error While Inserting Institute Data, Please Try Again',
                        'error');
                    endLoading();
                }
            }
        });
    return false;
}

