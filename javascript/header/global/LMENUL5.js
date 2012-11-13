var valid = new validate();

$(document).ready(function () {
    oTable = $('#groupMenus').dataTable({
        "bJQueryUI":true,
        "sPaginationType":"full_numbers",
        "aaSorting":[
            [4, 'asc']
        ]
    });
    hideUpdateForm();
    hideDisplayPortion();
    showHideDatatable();
    formGlobalPath = getFormGlobalPath();
    var submenuId_glb = $('#submenuId_glb').val();

    $("#menuUrl").autocomplete(formGlobalPath + "global/glb_menu_form.php?type=menu_url", {
        width:260,
        matchContains:true,
        mustMatch:true,
        selectFirst:false
    });
    $("#menuUrl").result(function (event, data, formatted) {
        $("#menuUrl_i").val(data[1]);
    });

    $("#cSubmenu").autocomplete(formGlobalPath + "global/glb_menu_form.php?type=menu_submenu&submenuId=" + submenuId_glb, {
        width:260,
        matchContains:true,
        mustMatch:true,
        selectFirst:false
    });
    $("#cSubmenu").result(function (event, data, formatted) {
        $("#cSubmenu_i").val(data[1]);
    });

    $("#menuUrl_u").autocomplete(formGlobalPath + "global/glb_menu_form.php?type=menu_url", {
        width:260,
        matchContains:true,
        mustMatch:true,
        selectFirst:false
    });
    $("#menuUrl_u").result(function (event, data, formatted) {
        $("#menuUrl_ui").val(data[1]);
    });

    $("#cSubmenu_u").autocomplete(formGlobalPath + "global/glb_menu_form.php?type=menu_submenu&submenuId=" + submenuId_glb, {
        width:260,
        matchContains:true,
        mustMatch:true,
        selectFirst:false
    });
    $("#cSubmenu_u").result(function (event, data, formatted) {
        $("#cSubmenu_ui").val(data[1]);
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
function showTheDatatable() {
    showTheDiv('displayDatatable');
}
function showHideSearchForm() {
    toggleTheDiv('searchForm');
}
function showHideDatatable() {
    toggleTheDiv('displayDatatable');
}

function checkChildMenuAssignmentInsert() {
    var submenuId = $('#cSubmenu_i').val();
    if (submenuId != "") {
        var menuId = $('#menuUrl_i').val();
        var data = $('#insertForm').serialize();
        data = 'menuId=' + menuId + '&submenuId=' + submenuId + '&task=checkMenuSubmenu&';
        $.ajax({
            url:formGlobalPath + "global/glb_menu_submenu_url_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    alert('This Submenu Cannot Be Assigned To The Menu URL As It Creates Loop');
                    $('#cSubmenu_i').val('');
                    var cSubmenu = $('#cSubmenu');
                    cSubmenu.val('');
                    cSubmenu.focus();
                }
            }
        });
        return false;
    }
}

function checkChildMenuAssignmentUpdate() {
    var submenuId = $('#cSubmenu_ui').val();
    if (submenuId != "") {
        var menuId = $('#menuUrl_ui').val();
        var data = $('#insertForm').serialize();
        data = 'menuId=' + menuId + '&submenuId=' + submenuId + '&task=checkMenuSubmenu&';
        $.ajax({
            url:formGlobalPath + "global/glb_menu_submenu_url_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    alert('This Submenu Cannot Be Assigned To The Menu URL As It Creates Loop');
                    $('#cSubmenu_ui').val('');
                    $('#cSubmenu_u').val('');
                    $('#cSubmenu_u').focus();
                }
            }
        });
        return false;
    }
}

function processInsertForm() {
    //validation process
    var submenuId_glb = $('#submenuId_glb').val();
    var data = $('#insertForm').serialize();
    data = 'submenuId=' + submenuId_glb + '&task=insertMenuUrl&' + data;
    showLoading("Uploading Data To Server");
    $.ajax({
        url:formGlobalPath + "global/glb_menu_submenu_url_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (output) {
            checkValidityOfOutput(output);
            if (output[0] != 0) {
                var viewImageLink = getButtonViewImage();
                var editImageLink = getButtonEditImage();

                var aPos = oTable.fnGetNodes().length;
                oTable.fnAddData(
                    [
                        output[1],
                        output[2],
                        output[3],
                        output[4],
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showMenuUrlDetails('" + output[0] + "', '" + aPos + "')\">" + viewImageLink + "Show Details</button>",
                        "<button type=\"button\" id=\"edit\" class=\"positive edit\" onclick=\"editMenuUrl('"
                            + output[0] + "', '" + aPos + "')\">" + editImageLink + "Edit Details</button>"
                    ]);
                hideInsertForm();
                hideUpdateForm();
                showTheDatatable();
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
    var submenuId = $('#submenuId_glb').val();
    var data = $('#searchForm').serialize();
    data = 'submenuId_glb=' + submenuId + '&task=search&' + data;
    showLoading("Fetching Data From Server");
    $.ajax({
        url:formGlobalPath + "global/glb_menu_submenu_url_form.php",
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
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    oTable.fnClearTable();
                    for (var i = 0; i < data.length; i++) {
                        oTable.fnAddData(
                            [
                                data[i][1],
                                data[i][2],
                                data[i][3],
                                data[i][4],
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showMenuUrlDetails('" + data[i][0] + "', '" + i + "')\">" + viewImageLink + "Show Details</button>",
                                "<button type=\"button\" id=\"edit\" class=\"positive edit\" onclick=\"editMenuUrl('" + data[i][0] + "', '" + i + "')\">" + editImageLink + "Edit Details</button>"
                            ]);
                    }
                    hideInsertForm();
                    hideDisplayPortion();
                    showTheDatatable();
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

function showMenuUrlDetails(id, aPos) {
    showLoading("Fetchign Data From Server");
    data = 'task=getUrlIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "global/glb_menu_submenu_url_form.php",
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
                $('#menuIdDisplay').html(data[0]);
                $('#menuNameDisplay').html(data[1]);
                $('#menuUrlNameDisplay').html(data[2]);
                $('#menuUrlDisplay').html(data[3]);
                $('#parentSubmenuDisplay').html(data[4]);
                $('#childSubmenuDisplay').html(data[5]);
                $('#redirectDisplay').html(redirect);
                $('#priorityDisplay').html(data[7]);
                $('#lastUpdateDateDisplay').html(data[8]);
                $('#lastUpdatedByDisplay').html(data[9]);
                $('#creationDateDisplay').html(data[10]);
                $('#createdByDisplay').html(data[11]);
                if (data[12] == 'y') {
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
                $('#update_menu_button').attr('onclick', 'editMenuUrl(\'' + data[0] + '\', \'' + aPos + '\')');
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

function editMenuUrl(id, aPos) {
    showLoading("Processing Data Into Update Form");
    data = 'task=getUrlIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "global/glb_menu_submenu_url_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#menuName_u').val(data[1]);
                $('#menuUrl_ui').val(data[13]);
                $('#menuUrl_u').val(data[2]);
                $('#redirect_u').val(data[6]);
                $('#menuPriority_u').val(data[7]);
                $('#cSubmenu_ui').val(data[14]);
                $('#cSubmenu_u').val(data[5]);

                if (data[12] == 'y') {
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

function processSubmenuURLUpdate() {
    //validation process
    var id = $('#menuId_u').val();
    if (id) {
        //validation process
        var submenuId_glb = $('#submenuId_glb').val();
        var aPos = $('#position_u').val();
        aPos = parseInt(aPos);
        //preparing for ajax call
        var data = $('#updateForm').serialize();
        data = 'submenuId=' + submenuId_glb + '&task=updateMenuUrl&' + data;
        showLoading("Uploading Data To Server");
        $.ajax({
            url:formGlobalPath + "global/glb_menu_submenu_url_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    if (output[3] == '')
                        var submenu = "N/A";
                    else
                        var submenu = output[3];
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    oTable.fnUpdate([
                        output[1],
                        output[2],
                        submenu,
                        output[4],
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showMenuUrlDetails('" + output[0] + "', '" + aPos + "')\">" + viewImageLink + "Show Details</button>",
                        "<button type=\"button\" id=\"edit\" class=\"positive edit\" onclick=\"editMenuUrl('"
                            + output[0] + "', '" + aPos + "')\">" + editImageLink + "Edit Details</button>"
                    ], aPos);
                    hideUpdateForm();
                    showMenuUrlDetails(output[0], aPos);
                    showTheDatatable();
                    endLoading();
                } else {
                    handleNotification('Error While Updating Data, Please Try Again', 'error');
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
            url:formGlobalPath + "global/glb_menu_submenu_url_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    showMenuUrlDetails(id, aPos);
                    hideUpdateForm();
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

function activateSubmenu(id, aPos) {
    if (confirm('Do you really want to activate ?')) {
        showLoading("Processing Data");
        data = 'task=activateId&id=' + id;
        $.ajax({
            url:formGlobalPath + "global/glb_menu_submenu_url_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    showMenuUrlDetails(id, aPos);
                    hideUpdateForm();
                    endLoading();
                } else {
                    handleNotification('Error In Activating Menu URL ... Try After Sometime', 'error');
                    endLoading();
                }

            }
        });
    }
    return false;
}
