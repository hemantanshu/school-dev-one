var valid = new validate();
var oTable;
$(document).ready(function () {
    oTable = $('#groupMenus').dataTable({
        "bJQueryUI":true,        
        "sPaginationType":"full_numbers",
        "aaSorting":[
            [ 2, 'asc' ]
        ]        
    });

    hideUpdateForm();
    hideDetailsPortion();
    showHideDatatable();
    formGlobalPath = getFormGlobalPath();
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
function hideDetailsPortion() {
    hideTheDiv('menuDetails');
}
function showDetailsPortion() {
    showTheDiv('menuDetails');
}
function showDataTable() {
    showTheDiv('groupMenus_s');
}
function showHideInsertForm() {
    toggleTheDiv('insertForm');
}
function showHideDatatable() {
    toggleTheDiv('groupMenus_s');
}
function showHideSearchForm() {
    toggleTheDiv('searchForm');
}

$(document).ready(
    function () {
        $("#parent_url").autocomplete(
            formGlobalPath + "global/glb_menu_form.php?type=menu_url",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:false
            });
        $("#parent_url").result(function (event, data, formatted) {
            $("#parent_url_id").val(data[1]);
        });
    });

$(document).ready(
    function () {
        $("#parent_url_u").autocomplete(
            formGlobalPath + "global/glb_menu_form.php?type=menu_url",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:false
            });
        $("#parent_url_u").result(function (event, data, formatted) {
            $("#parent_url_id_u").val(data[1]);
        });
    });

function checkMenuName(formField, flag) {
    var formField = $('#' + formField + '');
    var value = formField.val();
    if (flag == 1) {
        var originalValue = $('#display_name_ui').val();
        if (value == originalValue)
            return false;
    } else
        var originalValue = '';
    if (value) {
        showLoading('Validating Data From Server');
        data = 'menu=' + value + '&task=authenticate';
        $
            .ajax({
                url:formGlobalPath + "global/glb_menu_url_form.php",
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

function processInsertMenuUrlForm() {
    //preparing for ajax call
    var data = $('#insertForm').serialize();
    data = 'task=insertMenuUrl&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "global/glb_menu_url_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0]) {
                    var aPos = oTable.fnGetNodes().length;
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    oTable
                        .fnAddData([
                        output[1],
                        output[2],
                        output[3],
                        "<button type=\"button\" id=\"button\" class=\"regular view\" onclick=\"showMenuIdDetails('"
                            + output[1]
                            + "', '"
                            + aPos
                            + "')\">"
                            + viewImageLink
                            + "Show Details</button>",
                        "<button type=\"button\" id=\"button\" class=\"regular browse\" onclick=\"editMenuIdDetails('"
                            + output[1]
                            + "', '"
                            + aPos
                            + "')\">"
                            + editImageLink
                            + "Edit Details</button>" ]);
                    $('#insertReset').click();
                    showDataTable();
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

function getMenuUrlSearchDetails() {
    var data = $('#searchForm').serialize();
    data = 'task=search&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "global/glb_menu_url_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0][0] == 1) {
                    oTable.fnClearTable();
                    hideInsertForm();
                    hideDetailsPortion();
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
                                data[i][0],
                                data[i][1],
                                data[i][2],
                                "<button type=\"button\" id=\"button\" class=\"regular\" onclick=\"showMenuIdDetails('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">"
                                    + viewImageLink
                                    + "Show Details</button>",
                                "<button type=\"button\" id=\"button\" class=\"positive\" onclick=\"editMenuIdDetails('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">"
                                    + editImageLink
                                    + "Edit Details</button>" ]);
                        }
                        hideInsertForm();
                        showDataTable();
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

function showMenuIdDetails(id, aPos) {
    if (id) {
        showLoading("Processing Data");
        data = 'task=getUrlIdDetails&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "global/glb_menu_url_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (data) {
                    checkValidityOfOutput(data);
                    if (data[0] != 0) {
                        $('#menuIdDisplay').html(data[0]);
                        $('#menuNameDisplay').html(data[1]);
                        $('#menuUrlDisplay').html(data[2]);
                        $('#menuImageUrlDisplay').html(data[3]);
                        $('#menuTaglineDisplay').html(data[4]);
                        $('#menuDescription').html(data[5]);
                        if (data[6] == 'y') {
                            $('#menuEdit').html(
                                '<font class="green">Editable</font>');
                        } else {
                            $('#menuEdit')
                                .html(
                                '<font class="red">Not Editable</font>');
                        }
                        if (data[7] == 'y') {
                            $('#menuAuth')
                                .html(
                                '<font class="red">Authentication Required</font>');
                        } else {
                            $('#menuAuth').html(
                                '<font class="green">Public</font>');
                        }
                        $('#urlSourceIdDisplay').html(data[8]);
                        $('#lastUpdateDateDisplay').html(data[10]);
                        $('#lastUpdatedByDisplay').html(data[11]);
                        $('#creationDateDisplay').html(data[12]);
                        $('#createdByDisplay').html(data[13]);
                        if (data[14] == 'y') {
                            $('#activeDisplay').html(
                                '<font class="green">Active</font>');
                            $('#dropMenuUrl_d').show();
                            $('#dropMenuUrl_d').attr(
                                'onclick',
                                'dropMenuUrlDetails(\'' + data[0]
                                    + '\', \'' + aPos + '\')');
                            $('#activateMenuUrl_d').hide();
                        } else {
                            $('#activeDisplay').html(
                                '<font class="red">Inactive</font>');
                            $('#activateMenuUrl_d').show();
                            $('#activateMenuUrl_d').attr(
                                'onclick',
                                'activateMenuUrlDetails(\'' + data[0]
                                    + '\', \'' + aPos + '\')');
                            $('#dropMenuUrl_d').hide();
                        }
                        $('#update_menu_button').attr(
                            'onclick',
                            'editMenuIdDetails(\'' + data[0] + '\', \''
                                + aPos + '\')');
                        showDetailsPortion();
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
    }
}

function editMenuIdDetails(id, aPos) {
    showLoading("Processing Data");
    data = 'task=getUrlIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "global/glb_menu_url_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0]) {
                $('#menu_url_id').val(id);
                $('#display_name_u').val(data[1]);
                $('#display_name_ui').val(data[1]);
                $('#menu_url_u').val(data[2]);
                $('#image_url_u').val(data[3]);
                $('#menu_tagline_u').val(data[4]);
                $('#menu_description_u').val(data[5]);
                if (data[6] != 'y')
                    $('#menu_edit_u').removeAttr('checked', 'checked');
                else
                    $('#menu_edit_u').attr('checked', 'checked');
                if (data[7] != 'y')
                    $('#menu_auth_u').removeAttr('checked', 'checked');
                else
                    $('#menu_auth_u').attr('checked', 'checked');

                if (data[14] == 'y') {
                    $('#dropMenuUrl_u').show();
                    $('#dropMenuUrl_u').attr(
                        'onclick',
                        'dropMenuUrlDetails(\'' + data[0] + '\', \'' + aPos
                            + '\')');
                    $('#activateMenuUrl_u').hide();
                } else {
                    $('#activateMenuUrl_u').show();
                    $('#activateMenuUrl_u').attr(
                        'onclick',
                        'activateMenuUrlDetails(\'' + data[0] + '\', \''
                            + aPos + '\')');
                    $('#dropMenuUrl_u').hide();
                }

                $('#menuId_u').val(data[0]);
                $('#rowPosition_u').val(aPos);
                $('#updateForm').show();
                endLoading();
            } else {
                handleNotification(
                    'Error While Processing Data, Please Try Again',
                    'error');
                endLoading();
            }
        }
    });
}

function processUpdateForm() {
    //validation process
    var id = $('#menuId_u').val();
    if (id) {
        var position = $('#rowPosition_u').val();
        position = parseInt(position);
        //preparing for ajax call
        var data = $('#updateForm').serialize();
        data = 'task=updateMenuUrl&' + data;
        showLoading("Uploading Data To Server");
        $
            .ajax({
                url:formGlobalPath + "global/glb_menu_url_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] == 0) {
                        handleNotification(
                            'Error While Inserting Data, Please Try Again',
                            'error');
                        endLoading();
                    } else {
                        var viewImageLink = getButtonViewImage();
                        var editImageLink = getButtonEditImage();
                        oTable
                            .fnUpdate(
                            [
                                output[1],
                                output[2],
                                output[3],
                                "<button type=\"button\" id=\"details\" class=\"regular\" onclick=\"showMenuUrlDetails('"
                                    + output[1]
                                    + "', '"
                                    + position
                                    + "')\">"
                                    + viewImageLink
                                    + "Show Details</button>",
                                "<button type=\"button\" id=\"positive\" class=\"edit\" onclick=\"editMenuIdDetails('"
                                    + output[1]
                                    + "', '"
                                    + position
                                    + "')\">"
                                    + editImageLink
                                    + "Edit Details</button>" ],
                            position);
                        hideUpdateForm();
                        showMenuIdDetails(output[1], position);
                        showDataTable();
                        endLoading();
                    }
                }
            });
    }
    return false;
}

function dropMenuUrlDetails(id, position) {
    if (confirm('Do you really want to delete ?')) {
        showLoading("Processing Data");
        data = 'task=dropId&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "global/glb_menu_url_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] != 0) {
                        showMenuIdDetails(id, position);
                        hideUpdateForm();
                        endLoading();
                    } else {
                        handleNotification(
                            'Error In Dropping Menu URL ... Try After Sometime',
                            'error');
                        endLoading();
                    }
                }
            });
    }

}
function activateMenuUrlDetails(id, position) {
    if (confirm('Do you really want to activate ?')) {
        showLoading("Activating Menu Url Data");
        data = 'task=activateId&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "global/glb_menu_url_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] != 0) {
                        showMenuIdDetails(id, position);
                        hideUpdateForm();
                        endLoading();
                    } else {
                        handleNotification(
                            'Error In Activating Menu URL ... Try After Sometime',
                            'error');
                        endLoading();
                    }

                }
            });
    }
}
