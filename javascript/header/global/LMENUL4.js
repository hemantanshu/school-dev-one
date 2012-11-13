var valid = new validate();

$(document)
    .ready(
    function () {
        oTable = $('#groupMenus').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });
        hideUpdateForm();
        hideDisplayPortion();
        showHideDatatable();
        formGlobalPath = getFormGlobalPath();

        $("#menuUrl").autocomplete(
            formGlobalPath
                + "global/glb_menu_form.php?type=menu_url",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:false
            });
        $("#menuUrl").result(function (event, data, formatted) {
            $("#menuUrl_val").val(data[1]);
        });

        $("#submenu")
            .autocomplete(
            formGlobalPath
                + "global/glb_menu_form.php?type=menu_submenu&submenuId=sdf",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:false
            });
        $("#submenu").result(function (event, data, formatted) {
            $("#submenu_val").val(data[1]);
        });

        $("#menuUrl_u").autocomplete(
            formGlobalPath
                + "global/glb_menu_form.php?type=menu_url",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:false
            });
        $("#menuUrl_u").result(function (event, data, formatted) {
            $("#menuUrl_u_val").val(data[1]);
        });

        $("#submenu_u")
            .autocomplete(
            formGlobalPath
                + "global/glb_menu_form.php?type=menu_submenu&submenuId=sdf",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:false
            });
        $("#submenu_u").result(function (event, data, formatted) {
            $("#submenu_u_val").val(data[1]);
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
    showTheDiv('displaySubmenu');
}
function hideDisplayPortion() {
    hideTheDiv('displaySubmenu');
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


function checkMenuName(formField, flag) {
    var formField = $('#' + formField + '');
    var value = formField.val();
    if (flag == 1) {
        var originalValue = $('#menuName_ui').val();
        if (value == originalValue)
            return false;
    } else
        var originalValue = '';

    if (value) {
        showLoading('Validating Data From Server');
        data = 'menu=' + value + '&task=authenticate';
        $
            .ajax({
                url:formGlobalPath + "global/glb_menu_top_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] == 0) {
                        alert("The Menu Name is already in use, please select another");
                        formField.val(originalValue);
                        formField.focus();
                    }
                }
            });
        endLoading();
    }
}

function processInsertForm() {
    //preparing for ajax call
    var data = $('#insertForm').serialize();
    data = 'task=insertMenuUrl&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "global/glb_menu_top_form.php",
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
                        output[4],
                        "<button type=\"button\" id=\"details\" class=\"regular view\" onclick=\"showMenuTopDetails('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + viewImageLink + "Show Details</button>",
                        "<button type=\"button\" id=\"edit\" class=\"positive edit\" onclick=\"editTopMenu('"
                            + output[0] + "', '" + aPos
                            + "')\">" + editImageLink + "Edit Details</button>" ]);
                    hideInsertForm();
                    hideUpdateForm();
                    $('#resetInsert').click();
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

function getMenuTopSearchDetails() {
    var data = $('#searchForm').serialize();
    data = 'task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "global/glb_menu_top_form.php",
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
                        oTable.fnClearTable();
                        var viewImageLink = getButtonViewImage();
                        var editImageLink = getButtonEditImage();
                        for (var i = 0; i < data.length; i++) {
                            oTable
                                .fnAddData([
                                data[i][1],
                                data[i][2],
                                data[i][3],
                                data[i][4],
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showMenuTopDetails('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + viewImageLink + "Show Details</button>",
                                "<button type=\"button\" id=\"edit\" class=\"positive edit\" onclick=\"editTopMenu('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + editImageLink + "Edit Details</button>" ]);
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

function showMenuTopDetails(id, aPos) {
    showLoading("Fetchign Data From Server");
    data = 'task=getUrlIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "global/glb_menu_top_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                if (data[6] == 'y')
                    redirect = "<font class=\"red\">Blank Page</font>";
                else
                    redirect = "<font class=\"green\">Same Page</font>";
                if (data[7] == 'y')
                    authentication = "<font class=\"green\">Enabled</font>";
                else
                    authentication = "<font class=\"red\">Disabled</font>";
                if (data[4] != null)
                    submenu = data[4];
                else
                    submenu = "<font class=\"red\">Not Assigned</font>";

                $('#topMenuIdDisplay').html(data[0]);
                $('#menuNameDisplay').html(data[1]);
                $('#urlNameDisplay').html(data[2]);
                $('#urlDisplay').html(data[3]);
                $('#submenuNameDisplay').html(submenu);
                $('#priorityDisplay').html(data[5]);
                $('#redirectDisplay').html(redirect);
                $('#authenticationDisplay').html(authentication);
                $('#lastUpdateDateDisplay').html(data[8]);
                $('#lastUpdatedByDisplay').html(data[9]);
                $('#creationDateDisplay').html(data[10]);
                $('#createdByDisplay').html(data[11]);
                $('#descriptionDisplay').html(data[13]);
                if (data[12] == 'y') {
                    $('#activeDisplay').html(
                        '<font class="green">Active</font>');
                    $('#dropMenuUrl_d').show();
                    $('#dropMenuUrl_d').attr(
                        'onclick',
                        'dropTopMenu(\'' + data[0] + '\', \'' + aPos
                            + '\')');
                    $('#activateMenuUrl_d').hide();
                } else {
                    $('#activeDisplay').html(
                        '<font class="red">Inactive</font>');
                    $('#activateMenuUrl_d').show();
                    $('#activateMenuUrl_d').attr(
                        'onclick',
                        'activateTopMenu(\'' + data[0] + '\', \'' + aPos
                            + '\')');
                    $('#dropMenuUrl_d').hide();
                }
                $('#update_menu_button').attr('onclick',
                    'editTopMenu(\'' + data[0] + '\', \'' + aPos + '\')');
                $('#menu_id_d').val(data[0]);
                $('#position_d').val(aPos);
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

function editTopMenu(id, aPos) {
    showLoading("Processing Data Into Update Form");
    data = 'task=getUrlIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "global/glb_menu_top_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#menuName_u').val(data[1]);
                $('#menuName_ui').val(data[1]);
                $('#menuUrl_u_val').val(data[14]);
                $('#menuUrl_u').val(data[2]);
                $('#menuDescription_u').val(data[13]);
                $('#submenu_u_val').val(data[15]);
                $('#submenu_u').val(data[4]);
                $('#authentication_u').val(data[7]);
                $('#redirect_u').val(data[6]);
                $('#priority_u').val(data[5]);
                if (data[12] == 'y') {
                    $('#dropMenuUrl_u').show();
                    $('#dropMenuUrl_u').attr(
                        'onclick',
                        'dropTopMenu(\'' + data[0] + '\', \'' + aPos
                            + '\')');
                    $('#activateMenuUrl_u').hide();
                } else {
                    $('#activateMenuUrl_u').show();
                    $('#activateMenuUrl_u').attr(
                        'onclick',
                        'activateTopMenu(\'' + data[0] + '\', \'' + aPos
                            + '\')');
                    $('#dropMenuUrl_u').hide();
                }
                $('#menuId_u').val(data[0]);
                $('#position_u').val(aPos);
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

function processTopMenuUpdate() {
    //validation process
    var id = $('#menuId_u').val();
    if (id) {
        var aPos = $('#position_u').val();
        aPos = parseInt(aPos);
        //preparing for ajax call
        var data = $('#updateForm').serialize();
        data = 'task=updateMenuUrl&' + data;
        showLoading("Uploading Data To Server");
        $
            .ajax({
                url:formGlobalPath + "global/glb_menu_top_form.php",
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
                                output[3],
                                output[4],
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showMenuTopDetails('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + viewImageLink + "Show Details</button>",
                                "<button type=\"button\" id=\"edit\" class=\"positive edit\" onclick=\"editTopMenu('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + editImageLink + "Edit Details</button>" ],
                            aPos);
                        hideUpdateForm();
                        showMenuTopDetails(output[0], aPos);
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

function dropTopMenu(id, aPos) {
    if (confirm('Do you really want to delete ?')) {
        showLoading("Processing Data");
        data = 'task=dropId&id=' + id;
        $
            .ajax({
                url:formGlobalPath
                    + "global/glb_menu_top_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] != 0) {
                        showMenuTopDetails(id, aPos);
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

function activateTopMenu(id, aPos) {
    if (confirm('Do you really want to activate ?')) {
        showLoading("Processing Data");
        data = 'task=activateId&id=' + id;
        $
            .ajax({
                url:formGlobalPath
                    + "global/glb_menu_top_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] != 0) {
                        showMenuTopDetails(id, aPos);
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
