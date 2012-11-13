var valid = new validate();
$(document)
    .ready(
    function () {
        oTable = $('#groupRecord').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });

        hideUpdateForm();
        hideDisplayPortion();
        showHideDatatable();
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

function showInsertForm() {
    showTheDiv('insertForm');
}
function hideInsertForm() {
    hideTheDiv('insertForm');
}

function toggleInsertForm() {
    toggleTheDiv('insertForm');
}
function showUpdateForm() {
    showTheDiv('updateForm');
}
function hideUpdateForm() {
    hideTheDiv('updateForm');
}

function showDisplayPortion() {
    showTheDiv('recordDetails');
}
function hideDisplayPortion() {
    hideTheDiv('recordDetails');
}
function showDatatable() {
    showTheDiv('groupMenus_s');
}
function showHideSearchForm() {
    toggleTheDiv('searchForm');
}
function showHideDatatable() {
    toggleTheDiv('groupMenus_s');
}

function resetFieldValue(fieldName) {
    $('#' + fieldName).attr('value', '');
}

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
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + viewImageLink + "Show Details</button>",
                        "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                            + output[0] + "', '" + aPos
                            + "')\">" + editImageLink + "Edit Details</button>" ]);
                    hideInsertForm();
                    hideUpdateForm();
                    showDatatable();
                    $('#insertReset').click();
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

function showRecordDetails(id, aPos) {
    showLoading("Fetchign Data From Server");
    data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "utility/utl_institute_details_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != '') {
                $('#schoolName_d').html(data[1]);
                $('#university_d').html(data[3]);
                $('#contactno_d').html(data[4]);
                $('#streetAddress_d').html(data[20]);
                $('#lastUpdateDateDisplay').html(data[5]);
                $('#lastUpdatedByDisplay').html(data[6]);
                $('#creationDateDisplay').html(data[7]);
                $('#createdByDisplay').html(data[8]);

                if (data[9] == 'y') {
                    $('#activeDisplay').html(
                        '<font class="green">Active</font>');
                    $('#dropRecord_d').show();
                    $('#dropRecord_d')
                        .attr(
                        'onclick',
                        'dropRecord(\'' + data[0] + '\', \'' + aPos
                            + '\')');
                    $('#activateRecord_d').hide();
                } else {
                    $('#activeDisplay').html(
                        '<font class="red">Inactive</font>');
                    $('#activateRecord_d').show();
                    $('#activateRecord_d').attr(
                        'onclick',
                        'activateRecord(\'' + data[0] + '\', \'' + aPos
                            + '\')');
                    $('#dropRecord_d').hide();
                }
                $('#editRecordButton').attr('onclick',
                    'editRecord(\'' + data[0] + '\', \'' + aPos + '\')');
                $('#recordId_d').val(data[0]);
                $('#rowPosition_d').val(aPos);

                hideUpdateForm();
                showDisplayPortion();
                hideInsertForm();
                endLoading();
            } else {
                handleNotification(
                    'Error While Processing Data, Please Try Again',
                    'error');
                endLoading();
            }
        }
    });
    return false;
}

function getSearchDetails() {
    var data = $('#searchForm').serialize();
    data = 'task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_institute_details_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0][0] == 1) {
                    oTable.fnClearTable();
                    hideInsertForm();
                    hideDisplayPortion();
                    handleNotification(
                        'No Data Fetched With The Given Inputs', 'info');
                    endLoading();
                } else {
                    if (data[0][0] != 0) {
                        var viewImageLink = getButtonViewImage();
                        var editImageLink = getButtonEditImage();
                        oTable.fnClearTable();
                        for (var i = 0; i < data.length; i++) {
                            oTable
                                .fnAddData([
                                data[i][1],
                                data[i][2],
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + viewImageLink + "Show Details</button>",
                                "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + editImageLink + "Edit Record</button>" ]);
                        }
                        showDatatable();
                        hideInsertForm();
                        hideDisplayPortion();
                        endLoading();
                    } else {
                        handleNotification(
                            'Error While Processing Data, Please Try Again',
                            'error');
                        endLoading();
                    }
                }
            }
        });
    return false;
}

// code for edit detaisl begins
function editRecord(id, aPos) {
    showLoading("Processing Data Into Update Form");
    data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "utility/utl_institute_details_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#collegeName_u').val(data[1]);
                $('#university_uval').val(data[2]);
                $('#university_u').val(data[3]);
                $('#contactno_u').val(data[4]);
                $('#streetAddress1_u').val(data[11]);
                $('#streetAddress2_u').val(data[12]);
                $('#city_uval').val(data[14]);
                $('#city_u').val(data[13]);
                $('#state_uval').val(data[16]);
                $('#state_u').val(data[15]);
                $('#pincode_u').val(data[17]);
                $('#country_uval').val(data[19]);
                $('#country_u').val(data[18]);

                if (data[9] == 'y') {
                    $('#dropRecord_u').show();
                    $('#dropRecord_u')
                        .attr(
                        'onclick',
                        'dropRecord(\'' + data[0] + '\', \'' + aPos
                            + '\')');
                    $('#activateRecord_u').hide();
                } else {
                    $('#activateRecord_u').show();
                    $('#activateRecord_u').attr(
                        'onclick',
                        'activateRecord(\'' + data[0] + '\', \'' + aPos
                            + '\')');
                    $('#dropRecord_u').hide();
                }
                $('#recordId_u').val(data[0]);
                $('#rowPosition_u').val(aPos);
                hideDisplayPortion();
                hideInsertForm();
                showUpdateForm();
                endLoading();
            } else {
                handleNotification(
                    'Error While Processing Data, Please Try Again',
                    'error');
                endLoading();
            }
        }
    });
    return false;
}

function processUpdateForm() {
    var aPos = $('#rowPosition_u').val();
    aPos = parseInt(aPos);
    // preparing for ajax call
    var data = $('#updateForm').serialize();
    data = 'task=updateRecord&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_institute_details_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    oTable
                        .fnUpdate(
                        [
                            output[1],
                            output[2],
                            "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                + output[0]
                                + "', '"
                                + aPos
                                + "')\">" + viewImageLink + "Show Details</button>",
                            "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                + output[0]
                                + "', '"
                                + aPos
                                + "')\">" + editImageLink + "Edit Details</button>" ],
                        aPos);
                    hideUpdateForm();
                    showDatatable();
                    showRecordDetails(output[0], aPos);
                    endLoading();
                } else {
                    handleNotification(
                        'Error While Updating Data, Please Try Again',
                        'error');
                    endLoading();
                }
            }
        });

    return false;
}

// Code For Drop Value Begins here
function dropRecord(id, aPos) {
    if (confirm('Do you really want to delete ?')) {
        showLoading("Processing Data");
        data = 'task=dropRecord&id=' + id;
        $
            .ajax({
                url:formGlobalPath
                    + "utility/utl_institute_details_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] != 0) {
                        showRecordDetails(id, aPos);
                        hideUpdateForm();
                        endLoading();
                    } else {
                        handleNotification(
                            'Error In Dropping Top Menu ... Try After Sometime',
                            'error');
                        endLoading();
                    }

                }
            });
    }
    return false;
}
// Code for Drop value Ends Here

// Code For Activate Value Begins Here
function activateRecord(id, aPos) {
    if (confirm('Do you really want to activate ?')) {
        showLoading("Processing Data");
        data = 'task=activateRecord&id=' + id;
        $
            .ajax({
                url:formGlobalPath
                    + "utility/utl_institute_details_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] != 0) {
                        showRecordDetails(id, aPos);
                        hideUpdateForm();
                        endLoading();
                    } else {
                        handleNotification(
                            'Error In Activating Top Menu ... Try After Sometime',
                            'error');
                        endLoading();
                    }

                }
            });
    }
    return false;
}