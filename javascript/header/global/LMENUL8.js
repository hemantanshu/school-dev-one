var valid = new validate();
$(document).ready(function () {
    oTable = $('#groupMenus').dataTable({
        "bJQueryUI":true,
        "sPaginationType":"full_numbers"
    });
    hideUpdateForm();
    hideDisplayPortion();
    showHideDatatable();
    formGlobalPath = getFormGlobalPath();
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
    showTheDiv('displayPortion');
}
function hideDisplayPortion() {
    hideTheDiv('displayPortion');
}
function showDatatable() {
    showTheDiv('displayDatatable');
}
function showHideSearchForm() {
    toggleTheDiv('searchForm');
}
function showHideDatatable() {
    toggleTheDiv('displayDatatable');
}

function checkShortCode() {
    var shortCode = $('#shortCode').val();
    if (shortCode.length != 5) {
        alert('The shortcode must be of exactly 5 characters');
        return false;
    }
    if (shortCode) {
        showLoading('Fetching Data From Server');
        data = 'shortCode=' + shortCode + '&task=checkCode';
        $
            .ajax({
                url:formGlobalPath + "global/glb_option_flag_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] == 0) {
                        alert("The shortcode is already in use, please select another");
                        $('#shortCode').val('');
                        $('#shortCode').focus();
                    }
                }
            });
        endLoading();
    }

    return false;
}
function processInsertForm() {
    //validation process
    var data = $('#insertForm').serialize();
    data = 'task=insertMenuUrl&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "global/glb_option_flag_form.php",
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
                    var browseImageLink = getButtonBrowseImage();
                    oTable
                        .fnAddData([
                        output[1],
                        output[2],
                        "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"showValuesAssociated('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + browseImageLink + "Browse Values</button>",
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showOptionFlagDetails('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + viewImageLink + "Show Details</button>",
                        "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editOptionFlag('"
                            + output[0] + "', '" + aPos
                            + "')\">" + editImageLink + "Edit Details</button>" ]);
                    hideInsertForm();
                    hideUpdateForm();
                    $('#insertReset').click();
                    showOptionFlagDetails(output[0], aPos);
                    showDatatable();
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

function getOptionSearchResults() {
    var data = $('#searchForm').serialize();
    data = 'task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "global/glb_option_flag_form.php",
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
                        var browseImageLink = getButtonBrowseImage();

                        oTable.fnClearTable();
                        for (var i = 0; i < data.length; i++) {
                            oTable
                                .fnAddData([
                                data[i][1],
                                data[i][2],
                                "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"showValuesAssociated('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + browseImageLink + "Browse Values</button>",
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showOptionFlagDetails('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + viewImageLink + "Show Details</button>",
                                "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editOptionFlag('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + editImageLink + "Edit Details</button>" ]);
                        }
                        hideInsertForm();
                        hideDisplayPortion();
                        showDatatable();
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

function showOptionFlagDetails(id, aPos) {
    showLoading("Fetchign Data From Server");
    data = 'task=getUrlIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "global/glb_option_flag_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#shortCode_dDisplay').html(data[0]);
                $('#optionName_dDisplay').html(data[1]);
                $('#description_dDisplay').html(data[2]);
                $('#lastUpdateDateDisplay').html(data[3]);
                $('#lastUpdatedByDisplay').html(data[4]);
                $('#creationDateDisplay').html(data[5]);
                $('#createdByDisplay').html(data[6]);
                if (data[7] == 'y') {
                    $('#activeDisplay').html(
                        '<font class="green">Active</font>');
                    $('#dropMenuUrl_d').show();
                    $('#dropMenuUrl_d').attr(
                        'onclick',
                        'dropOptionType(\'' + data[8] + '\', \'' + aPos
                            + '\')');
                    $('#activateMenuUrl_d').hide();
                } else {
                    $('#activeDisplay').html(
                        '<font class="red">Inactive</font>');
                    $('#activateMenuUrl_d').show();
                    $('#activateMenuUrl_d').attr(
                        'onclick',
                        'activateOptionType(\'' + data[8] + '\', \'' + aPos
                            + '\')');
                    $('#dropMenuUrl_d').hide();
                }
                $('#update_menu_button')
                    .attr(
                    'onclick',
                    'editOptionFlag(\'' + data[8] + '\', \'' + aPos
                        + '\')');
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

function editOptionFlag(id, aPos) {
    showLoading("Processing Data Into Update Form");
    data = 'task=getUrlIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "global/glb_option_flag_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#optionName_u').val(data[1]);
                $('#shortCode_u').val(data[0]);
                $('#sMenuDescription_u').val(data[2]);
                if (data[7] == 'y') {
                    $('#dropMenuUrl_u').show();
                    $('#dropMenuUrl_u').attr(
                        'onclick',
                        'dropOptionType(\'' + data[8] + '\', \'' + aPos
                            + '\')');
                    $('#activateMenuUrl_u').hide();
                } else {
                    $('#activateMenuUrl_u').show();
                    $('#activateMenuUrl_u').attr(
                        'onclick',
                        'activateOptionType(\'' + data[8] + '\', \'' + aPos
                            + '\')');
                    $('#dropMenuUrl_u').hide();
                }
                $('#valueId_u').val(data[8]);
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
    //validation process
    var id = $('#valueId_u').val();
    if (id) {
        var aPos = $('#rowPosition_u').val();
        aPos = parseInt(aPos);

        //preparing for ajax call
        var data = $('#updateForm').serialize();
        data = 'task=updateMenuUrl&' + data;
        showLoading("Uploading Data To Server");
        $
            .ajax({
                url:formGlobalPath + "global/glb_option_flag_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] != 0) {
                        var viewImageLink = getButtonViewImage();
                        var editImageLink = getButtonEditImage();
                        var browseImageLink = getButtonBrowseImage();
                        oTable
                            .fnUpdate(
                            [
                                output[1],
                                output[2],
                                "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"showValuesAssociated('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + browseImageLink + "Browse Values</button>",
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showOptionFlagDetails('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + viewImageLink + "Show Details</button>",
                                "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editOptionFlag('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + editImageLink + "Edit Details</button>" ],
                            aPos);
                        hideUpdateForm();
                        showOptionFlagDetails(output[0], aPos);
                        showDatatable();
                        endLoading();
                    } else {
                        handleNotification(
                            'Error While Updating Data, Please Try Again',
                            'error');
                        endLoading();
                    }
                }
            });
    }
    return false;
}

function dropOptionType(id, aPos) {
    if (confirm('Do you really want to delete ?')) {
        showLoading("Processing Data");
        data = 'task=dropId&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "global/glb_option_flag_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] != 0) {
                        showOptionFlagDetails(id, aPos);
                        hideUpdateForm();
                        endLoading();
                    } else {
                        handleNotification(
                            'Error In Dropping Assignment ... Try After Sometime',
                            'error');
                        endLoading();
                    }

                }
            });
    }
    return false;
}

function activateOptionType(id, aPos) {
    if (confirm('Do you really want to activate ?')) {
        showLoading("Processing Data");
        data = 'task=activateId&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "global/glb_option_flag_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] != 0) {
                        showOptionFlagDetails(id, aPos);
                        hideUpdateForm();
                        endLoading();
                    } else {
                        handleNotification(
                            'Error In Activating Assignment ... Try After Sometime',
                            'error');
                        endLoading();
                    }

                }
            });
    }
    return false;
}

function showValuesAssociated(id, aPos) {
    var path = schoolGlobalPath();
    path = path + "global/glb_option_values.php?optionTypeId=" + id;
    loadPageIntoDisplay(path);
}
