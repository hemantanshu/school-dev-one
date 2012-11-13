var childMenuUrl = '';
var childMenuName = '';

$(document).ready(function () {
    oTable = $('#groupValues').dataTable({
        "bJQueryUI":true,
        "sPaginationType":"full_numbers",
        "iDisplayLength":20
    });
    hideTheDiv('completePageListing');
    hideInsertFormDetails();
    formGlobalPath = getFormGlobalPath();

    $("#childMenu").autocomplete(formGlobalPath + "global/glb_menu_form.php?type=menu_url", {
        width:260,
        matchContains:true,
        mustMatch:true,
        selectFirst:false
    });
    $("#childMenu").result(function (event, data, formatted) {
        $("#childMenu_val").val(data[1]);
    });

    $("#parentMenu").autocomplete(formGlobalPath + "global/glb_menu_form.php?type=menu_url", {
        width:260,
        matchContains:true,
        mustMatch:true,
        selectFirst:false
    });
    $("#parentMenu").result(function (event, data, formatted) {
        $("#parentMenu_val").val(data[1]);
    });
});
function showInsertForm() {
    showTheDiv('insertForm');
}
function hideInsertForm() {
    hideTheDiv('insertForm');
}

function showDisplayForm() {
    showTheDiv('displayRecord');
    hideInsertForm();
}
function hideDisplayForm() {
    hideTheDiv('displayRecord');
}
function toggleInsertForm() {
    hideDisplayForm();
    toggleTheDiv('insertForm');
}
function showInsertFormDetails() {
    showTheDiv('insertMenuDetails');
}
function hideInsertFormDetails() {
    hideTheDiv('insertMenuDetails');
}

function showChoiceListing() {
    showTheDiv('choiceListing');
    hideInsertForm();
    hideDisplayForm();
    hideTheDiv('completePageListing');
}
function hideChoiceListing() {
    hideTheDiv('choiceListing');
    showTheDiv('completePageListing');
}

function getChildMenuData() {
    childMenuUrl = $('#childMenu_val').val();
    childMenuName = $('#childMenu').val();
    if (childMenuUrl == '') {
        hideTheDiv('completePageListing');
    } else {
        hideChoiceListing();
        getChildMenuDataDetails();
        getSearchDetails();
        hideDisplayForm();
    }
}

function getChildMenuDataDetails() {
    var data = 'task=fetchDetails&menuId=' + childMenuUrl;
    showLoading("Fetching Data From Server");
    $.ajax({
        url:formGlobalPath + "global/glb_menu_url_details_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (output) {
            checkValidityOfOutput(output);
            if (output[0] != 0) {
                if (output[14] == 'y')
                    var active = "<font class=\"green\">Active</font>";
                else
                    var active = "<font class=\"red\">In-Active</font>";


                $('#menuIdDisplay_2d').html(output[0]);
                $('#menuNameDisplay_2d').html(output[1]);
                $('#menuUrlDisplay_2d').html(output[2]);
                $('#tagline_2d').html(output[4]);
                $('#description_2d').html(output[5]);
                $('#active_2d').html(active);
                endLoading();
            } else {
                endLoading();
            }
        }
    });
    return false;
}

function getInsertMenuDetails() {
    var parentMenu = $('#parentMenu_val').val();
    if (parentMenu != "") {
        var data = 'task=fetchDetails&menuId=' + parentMenu;
        showLoading("Fetching Data From Server");
        $.ajax({
            url:formGlobalPath + "global/glb_menu_url_details_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    if (output[14] == 'y')
                        var active = "<font class=\"green\">Active</font>";
                    else
                        var active = "<font class=\"red\">In-Active</font>";

                    if (output[6] != 'y')
                        var edit = "<font class=\"green\">View Only</font>";
                    else
                        var edit = "<font class=\"red\">Edit Enabled</font>";

                    if (output[7] == 'y')
                        var auth = "<font class=\"green\">Login Authenticable</font>";
                    else
                        var auth = "<font class=\"red\">No Authentication</font>";


                    $('#menuIdDisplay').html(output[0]);
                    $('#menuNameDisplay').html(output[1]);
                    $('#menuUrlDisplay').html(output[2]);
                    $('#tagline').html(output[4]);
                    $('#description').html(output[5]);
                    $('#menuEdit').html(edit);
                    $('#menuAuth').html(auth);
                    $('#active').html(active);
                    showInsertFormDetails();
                    hideDisplayForm();
                    endLoading();
                } else {
                    endLoading();
                }
            }
        });
    }
}

function processInsertForm() {
    var parentMenu = $('#parentMenu_val').val();
    if (parentMenu != "") {
        var data = 'parentMenu=' + parentMenu + '&childMenu=' + childMenuUrl + '&task=insertRecord';
        showLoading("Fetching Data From Server");
        $.ajax({
            url:formGlobalPath + "global/glb_menu_access_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    var aPos = oTable.fnGetNodes().length;
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    oTable.fnAddData(
                        [
                            output[2],
                            output[1],
                            output[3],
                            "<button type=\"button\" id=\"button\" class=\"negative view\" onclick=\"showMenuIdDetails('" + output[2] + "', '" + aPos + "', '" + output[0] + "')\">" + viewImageLink + "Show Details</button>"
                        ]);
                    hideInsertForm()
                    $('#insertReset').click();
                    hideInsertFormDetails();
                    endLoading();
                } else {
                    endLoading();
                }
            }
        });
        return false;
    }
}

function getSearchDetails() {
    var data = $('#searchForm').serialize();
    data = 'childMenu=' + childMenuUrl + '&task=search&' + data;
    showLoading("Uploading Data To Server");
    $.ajax({
        url:formGlobalPath + "global/glb_menu_access_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);            
            if (data[0][0] == 1) {
                oTable.fnClearTable();
                hideInsertForm();
                hideDisplayForm();
                handleNotification('No Data Fetched With The Given Inputs', 'info');
                endLoading();
            } else {
                if (data[0][0] != 0) {
                    oTable.fnClearTable();
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    for (i = 0; i < data.length; i++) {
                        oTable.fnAddData(
                            [
                                data[i][2],
                                data[i][1],
                                data[i][3],
                                "<button type=\"button\" id=\"button\" class=\"negative\" onclick=\"showMenuIdDetails('" + data[i][2] + "', '" + i + "', '" + data[i][0] + "')\">" + viewImageLink + "Show Details</button>"
                            ]);
                    }
                    hideInsertForm();
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

function showMenuIdDetails(parentMenu, aPos, associationId) {
    var data = 'task=fetchDetails&menuId=' + parentMenu;
    showLoading("Fetching Data From Server");
    $.ajax({
        url:formGlobalPath + "global/glb_menu_url_details_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (output) {
            checkValidityOfOutput(output);
            if (output[0] != 0) {
                if (output[14] == 'y')
                    var active = "<font class=\"green\">Active</font>";
                else
                    var active = "<font class=\"red\">In-Active</font>";

                if (output[6] != 'y')
                    var edit = "<font class=\"green\">View Only</font>";
                else
                    var edit = "<font class=\"red\">Edit Enabled</font>";

                if (output[7] == 'y')
                    var auth = "<font class=\"green\">Login Authenticable</font>";
                else
                    var auth = "<font class=\"red\">No Authentication</font>";


                $('#menuIdDisplay_1d').html(output[0]);
                $('#menuNameDisplay_1d').html(output[1]);
                $('#menuUrlDisplay_1d').html(output[2]);
                $('#tagline_1d').html(output[4]);
                $('#description_1d').html(output[5]);
                $('#menuEdit_1d').html(edit);
                $('#menuAuth_1d').html(auth);
                $('#active_1d').html(active);
                endLoading();
            } else {
                endLoading();
            }
        }
    });
    data = 'task=recordDetails&id=' + associationId;
    showLoading("Fetching Data From Server");
    $.ajax({
        url:formGlobalPath + "global/glb_menu_access_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#lastUpdateDateDisplay').html(data[0]);
                $('#lastUpdatedByDisplay').html(data[1]);
                $('#creationDateDisplay').html(data[2]);
                $('#createdByDisplay').html(data[3]);
                if (data[4] == 'y') {
                    $('#activeDisplay').html('<font class="green">Active</font>');
                    $('#dropMenuUrl_d').show();
                    $('#dropMenuUrl_d').attr('onclick', 'dropMenuUrlDetails(\'' + associationId + '\', \'' + aPos + '\', \'' + parentMenu + '\')');
                    $('#activateMenuUrl_d').hide();
                }
                else {
                    $('#activeDisplay').html('<font class="red">Inactive</font>');
                    $('#activateMenuUrl_d').show();
                    $('#activateMenuUrl_d').attr('onclick', 'activateMenuUrlDetails(\'' + associationId + '\', \'' + aPos + '\', \'' + parentMenu + '\')');
                    $('#dropMenuUrl_d').hide();
                }
                endLoading();
            } else {
                endLoading();
            }
        }
    });
    showDisplayForm();
    return false;
}

function dropMenuUrlDetails(id, position, parentMenu) {
    if (confirm('Do you really want to delete ?')) {
        showLoading("Processing Data");
        var data = 'task=dropId&id=' + id;
        $.ajax({
            url:formGlobalPath + "global/glb_menu_access_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    showMenuIdDetails(parentMenu, position, id);
                    endLoading();
                } else {
                    handleNotification('Error In Dropping Parent URL ... Try After Sometime', 'error');
                    endLoading();
                }
            }
        });
    }

}
function activateMenuUrlDetails(id, position, parentMenu) {
    if (confirm('Do you really want to activate ?')) {
        showLoading("Activating Menu Url Data");
        var data = 'task=activateId&id=' + id;
        $.ajax({
            url:formGlobalPath + "global/glb_menu_access_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    showMenuIdDetails(parentMenu, position, id);
                    endLoading();
                } else {
                    handleNotification('Error In Activating Parent URL ... Try After Sometime', 'error');
                    endLoading();
                }

            }
        });
    }
}