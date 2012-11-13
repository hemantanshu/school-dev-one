var valid = new validate();
$(document).ready(function () {
    oTable = $('#groupMenus').dataTable({
        "bJQueryUI":true,
        "sPaginationType":"full_numbers"
    });
    getMenuUrlSearchDetails();
    formGlobalPath = getFormGlobalPath();

    viewImageLink = getButtonViewImage();
    editImageLink = getButtonEditImage();
    browseImageLink = getButtonBrowseImage();

});
function hideUpdateForm() {
    hideTheDiv('updateForm');
}
function showUpdateForm() {
    showTheDiv('updateForm');
}
function hideInsertForm() {
    hideTheDiv('insertForm');
}
function showInsertForm() {
    showTheDiv('insertForm');
}
function hideDisplayPortion() {
    hideTheDiv('displaySubmenu');
}
function showDisplayPortion() {
    showTheDiv('displaySubmenu');
}
function showDataTable() {
    showTheDiv('groupMenusM');
}
function showHideInsertForm() {
    toggleTheDiv('insertForm');
}
function showHideDatatable() {
    toggleTheDiv('groupMenusM');
}
function showHideSearchForm() {
    toggleTheDiv('searchForm');
}


function checkMenuName(formField, flag) {
    var formField = $('#' + formField + '');
    var value = formField.val();
    if (flag == 1) {
        var originalValue = $('#sMenuName_ui').val();
        if (value == originalValue)
            return false;
    } else
        var originalValue = '';

    if (value) {
        showLoading('Validating Data From Server');
        data = 'menu=' + value + '&task=authenticate';
        $.ajax({
            url:formGlobalPath + "global/glb_menu_submenu_form.php",
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

    return false;
}

function showSubmenuUrl(id, aPos) {
    var path = schoolGlobalPath();
    var url = path + "global/glb_menu_submenu_url.php?submenuId=" + id;
    loadPageIntoDisplay(url);
    return false;
}
function processInsertForm() {
    //preparing for ajax call
    var data = $('#insertForm').serialize();
    data = 'task=insertMenuUrl&' + data;
    showLoading("Uploading Data To Server");
    $.ajax({
        url:formGlobalPath + "global/glb_menu_submenu_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (output) {
            checkValidityOfOutput(output);
            if (output[0] != 0) {
                aPos = oTable.fnGetNodes().length;
                oTable.fnAddData(
                    [
                        output[2],
                        output[3],
                        "<button type=\"button\" id=\"details\" class=\"positive browse\" onclick=\"showSubmenuUrl('"
                            + output[1] + "', '" + aPos + "')\">" + browseImageLink + "URL Associated</button>",
                        "<button type=\"button\" id=\"details\" class=\"regular view\" onclick=\"showSubmenuDetails('"
                            + output[1] + "', '" + aPos + "')\">" + viewImageLink + "Show Details</button>",
                        "<button type=\"button\" id=\"edit\" class=\"regular edit\" onclick=\"editSubmenuDetails('"
                            + output[1] + "', '" + aPos + "')\">" + editImageLink + "Edit Details</button>"
                    ]);
                $('#insertReset').click();
                endLoading();
            } else {
                handleNotification('Error While Inserting Data, Please Try Again', 'error');
                endLoading();
            }
        }
    });
    return false;
}

function getMenuUrlSearchDetails() {
    var data = $('#searchForm').serialize();
    data = 'task=search&' + data;
    showLoading("Fetching Data From Server");
    $.ajax({
        url:formGlobalPath + "global/glb_menu_submenu_form.php",
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
                handleNotification('No Data Fetched With The Given Inputs', 'info');
                endLoading();
            } else {
                if (data[0][0] != 0) {
                    oTable.fnClearTable();
                    for (var i = 0; i < data.length; i++) {
                        oTable.fnAddData(
                            [
                                data[i][1],
                                data[i][2],
                                "<button type=\"button\" id=\"details\" class=\"positive browse\" onclick=\"showSubmenuUrl('"
                                    + data[i][0] + "', '" + i + "')\">" + browseImageLink + "URL Associated</button>",
                                "<button type=\"button\" id=\"details\" class=\"regular view\" onclick=\"showSubmenuDetails('"
                                    + data[i][0] + "', '" + i + "')\">" + viewImageLink + "Show Details</button>",
                                "<button type=\"button\" id=\"edit\" class=\"regular edit\" onclick=\"editSubmenuDetails('"
                                    + data[i][0] + "', '" + i + "')\">" + editImageLink + "Edit Details</button>"
                            ]);
                    }
                    hideInsertForm();
                    hideDisplayPortion();
                    hideUpdateForm();
                    showDataTable();
                    endLoading();
                }
                else {
                    handleNotification('Error While Processing Data, Please Try Again', 'error');
                    endLoading();
                }
            }
        }
    });
    return false;
}

function showSubmenuDetails(id, aPos) {
    showLoading("Fetchign Data From Server");
    data = 'task=getUrlIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "global/glb_menu_submenu_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#submenuIdDisplay').html(data[0]);
                $('#sMenuNameDisplay').html(data[1]);
                $('#submenuDescription').html(data[2]);
                $('#lastUpdatedByDisplay').html(data[3]);
                $('#lastUpdateDateDisplay').html(data[4]);
                $('#createdByDisplay').html(data[5]);
                $('#creationDateDisplay').html(data[6]);
                if (data[7] == 'y') {
                    $('#activeDisplay').html('<font class="green">Active</font>');
                    $('#dropMenuUrl_d').show();
                    $('#dropMenuUrl_d').attr('onclick', 'dropSubmenu(\'' + data[0] + '\', \'' + aPos + '\')');
                    $('#activateMenuUrl_d').hide();
                }
                else {
                    $('#activeDisplay').html('<font class="red">Inactive</font>');
                    $('#activateMenuUrl_d').show();
                    $('#activateMenuUrl_d').attr('onclick', 'activateSubmenu(\'' + data[0] + '\', \'' + aPos + '\')');
                    $('#dropMenuUrl_d').hide();
                }
                $('#update_menu_button').attr('onclick', 'editSubmenuDetails(\'' + data[0] + '\', \'' + aPos + '\')');
                $('#menu_id_d').val(data[0]);
                $('#position_d').val(aPos);
                hideUpdateForm();
                showDisplayPortion();
                hideInsertForm();
                endLoading();
            }
            else {
                handleNotification('Error While Processing Data, Please Try Again', 'error');
                endLoading();
            }
        }
    });
    return false;
}

function editSubmenuDetails(id, aPos) {
    showLoading("Processing Data");
    data = 'task=getUrlIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "global/glb_menu_submenu_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0]) {
                $('#sMenuName_u').val(data[1]);
                $('#sMenuName_ui').val(data[1]);
                $('#sMenuDescription_u').val(data[2]);

                if (data[7] == 'y') {
                    $('#dropMenuUrl_u').show();
                    $('#dropMenuUrl_u').attr('onclick', 'dropSubmenu(\'' + data[0] + '\', \'' + aPos + '\')');
                    $('#activateMenuUrl_u').hide();
                }
                else {
                    $('#activateMenuUrl_u').show();
                    $('#activateMenuUrl_u').attr('onclick', 'activateSubmenu(\'' + data[0] + '\', \'' + aPos + '\')');
                    $('#dropMenuUrl_u').hide();
                }
                $('#menuId_u').val(data[0]);
                $('#position_u').val(aPos);
                hideDisplayPortion();
                hideInsertForm();
                showUpdateForm();
                endLoading();
            }
            else {
                handleNotification('Error While Processing Data, Please Try Again', 'error');
                endLoading();
            }
        }
    });
    return false;
}

function processSubmenuUpdate() {
    //validation process
    var id = $('#menuId_u').val();
    if (id) {
        var position = $('#position_u').val();
        position = parseInt(position);
        //preparing for ajax call
        var data = $('#updateForm').serialize();
        data = 'task=updateMenuUrl&' + data;
        showLoading("Uploading Data To Server");
        $.ajax({
            url:formGlobalPath + "global/glb_menu_submenu_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    oTable.fnUpdate(
                        [
                            output[1],
                            output[2],
                            "<button type=\"button\" id=\"details\" class=\"positive browse\" onclick=\"showSubmenuUrl('"
                                + output[0] + "', '" + position + "')\">" + browseImageLink + "URL Associated</button>",
                            "<button type=\"button\" id=\"details\" class=\"positive view\" onclick=\"showSubmenuDetails('"
                                + output[0] + "', '" + position + "')\">" + viewImageLink + "Show Details</button>",
                            "<button type=\"button\" id=\"edit\" class=\"positive edit\" onclick=\"editSubmenuDetails('"
                                + output[0] + "', '" + position + "')\">" + editImageLink + "Edit Details</button>"
                        ], position);
                    hideUpdateForm();
                    showSubmenuDetails(output[0], position);
                    handleNotification('The submenu details has been successfully updated', 'success');
                    endLoading();
                } else {
                    handleNotification('Error While Inserting Data, Please Try Again', 'error');
                    endLoading();
                }
            }
        });
    }
    return false;
}

function dropSubmenu(id, aPos) {
    if (confirm('Do you really want to delete ?')) {
        showLoading("Processing Data");
        data = 'task=dropId&id=' + id;
        $.ajax({
            url:formGlobalPath + "global/glb_menu_submenu_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    showSubmenuDetails(id, aPos);
                    hideUpdateForm();
                    handleNotification('The submenu has been successfully dropped', 'success');
                    endLoading();
                } else {
                    handleNotification('Error In Dropping Submenu Details ... Try After Sometime', 'error');
                    endLoading();
                }

            }
        });
    }
    return false;
}

function activateSubmenu(id, aPos) {
    if (confirm('Do you really want to activate ?')) {
        showLoading("Processing Data");
        data = 'task=activateId&id=' + id;
        $.ajax({
            url:formGlobalPath + "global/glb_menu_submenu_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    showSubmenuDetails(id, aPos);
                    hideUpdateForm();
                    handleNotification('The submenu has been successfully activated', 'success');
                    endLoading();
                } else {
                    handleNotification('Error In Dropping Menu URL ... Try After Sometime', 'error');
                    endLoading();
                }

            }
        });
    }
    return false;
}



