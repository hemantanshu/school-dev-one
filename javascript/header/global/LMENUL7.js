var valid = new validate();
var oTable, formGlobalPath;
$(document).ready(function () {
    oTable = $('#groupMenus').dataTable({
        "bJQueryUI":true,
        "sPaginationType":"full_numbers"
    });
    $(".date").dateinput({
        format: 'yyyy-mm-dd'
    });
    formGlobalPath = getFormGlobalPath();

    //top menu in the insert form
    $("#topMenu").autocomplete(formGlobalPath + "global/glb_menu_form.php?type=menu_top", {
        width:260,
        matchContains:true,
        mustMatch:true,
        selectFirst:false
    });
    $("#topMenu").result(function (event, data, formatted) {
        $("#topMenu_val").val(data[1]);
    });

    $("#menuUrl").autocomplete(formGlobalPath + "global/glb_menu_form.php?type=menu_url", {
        width:260,
        matchContains:true,
        mustMatch:true,
        selectFirst:false
    });
    $("#menuUrl").result(function (event, data, formatted) {
        $("#menuUrl_val").val(data[1]);
    });


    $("#user")
        .autocomplete(
        formGlobalPath
            + "utility/utl_user_autocomplete.php?option=employeeTeacher",
        {
            width:260,
            matchContains:true,
            mustMatch:false,
            selectFirst:false
        });
    $("#user").result(
        function (event, data, formatted) {
            $("#user_val").val(data[1]);
        });

    $("#userHint")
        .autocomplete(
        formGlobalPath
            + "utility/utl_user_autocomplete.php?option=employeeTeacher",
        {
            width:260,
            matchContains:true,
            mustMatch:false,
            selectFirst:false
        });
    $("#userHint").result(
        function (event, data, formatted) {
            $("#userHint_val").val(data[1]);
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

function getTopMenuDescription() {
    var topMenu = $('#topMenu_val').val();
    if (topMenu) {
        showLoading('Fetching Data From Server');
        data = 'topMenu=' + topMenu + '&task=insertTabOut';
        $.ajax({
            url:formGlobalPath + "global/glb_menu_group_assignment_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    $('#topMenuDescription').html(output[0]);

                }
            }
        });
        endLoading();
    }

    return false;
}
function getTopMenuDescription1() {
    var topMenu = $('#topMenu_u_val').val();
    if (topMenu) {
        showLoading('Fetching Data From Server');
        data = 'topMenu=' + topMenu + '&task=insertTabOut';
        $.ajax({
            url:formGlobalPath + "global/glb_menu_group_assignment_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    $('#topMenuDescription_u').html(output[0]);

                }
            }
        });
        endLoading();
    }

    return false;
}

function getMenuDescription(){
    var menuUrl = $('#menuUrl_val').val();
    if(menuUrl){
        var data = 'task=fetchDetails&menuId=' + menuUrl;
        showLoading("Fetching Data From Server");
        $.ajax({
            url:formGlobalPath + "global/glb_menu_url_details_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if(output[0] != 0){
                    $('#menuUrlDescription').html(output[4]);
                    endLoading();
                }else{
                    handleNotification('Error While Inserting Data, Please Try Again', 'error');
                    endLoading();
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
    $.ajax({
        url:formGlobalPath + "global/glb_menu_user_assignment_form.php",
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
                for(var i = 0; i < output.length; ++i){
                    var position = aPos + i;
                    oTable.fnAddData(
                        [
                            output[i][1],
                            output[i][2],
                            output[i][3],
                            output[i][4],
                            output[i][5],
                            "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showAssignmentDetails('" + output[i][0] + "', '" + position + "')\">" + viewImageLink + "Show Details</button>",
                            "<button type=\"button\" id=\"edit\" class=\"positive edit\" onclick=\"editAssignment('" + output[i][0] + "', '" + position + "')\">" + editImageLink + "Edit Details</button>"
                        ]);
                }
                hideInsertForm();
                hideUpdateForm();
                $('#menu_hint_val').val(output[6]);
                $('#menu_hint').val(output[7]);
                $('#resetInsert').click();
                getUserAssignedMenus();
                showDatatable();
                endLoading();
            } else {
                handleNotification('Error While Inserting Data, Please Try Again', 'error');
                endLoading();
            }
        }
    });
    return false;
}

function getUserAssignedMenus() {
    var data = $('#searchForm').serialize();
    data = 'task=search&' + data;
    showLoading("Fetching Data From Server");
    $.ajax({
        url:formGlobalPath + "global/glb_menu_user_assignment_form.php",
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
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    for (var i = 0; i < data.length; i++) {
                        oTable.fnAddData(
                            [
                                data[i][1],
                                data[i][2],
                                data[i][3],
                                data[i][4],
                                data[i][5],
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showAssignmentDetails('" + data[i][0] + "', '" + i + "')\">" + viewImageLink + "Show Details</button>",
                                "<button type=\"button\" id=\"edit\" class=\"positive edit\" onclick=\"editAssignment('" + data[i][0] + "', '" + i + "')\">" + editImageLink + "Edit Details</button>"
                            ]);
                    }
                    hideInsertForm();
                    hideDisplayPortion();
                    showDatatable();
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

function showAssignmentDetails(id, aPos) {
    showLoading("Fetching Data From Server");
    data = 'task=getUrlIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "global/glb_menu_user_assignment_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                if (data[3] == 'y')
                    edit = "<font class=\"red\">Edit Enabled</font>";
                else
                    edit = "<font class=\"green\">Read Only</font>";
                $('#assignmentIdDisplay').html(data[0]);
                $('#userDisplay').html(data[1]);
                $('#menuNameDisplay').html(data[2]);
                $('#menuEditDisplay').html(edit);
                $('#startDateDisplay').html(data[4]);
                $('#endDateDisplay').html(data[5]);
                $('#lastUpdateDateDisplay').html(data[6]);
                $('#lastUpdatedByDisplay').html(data[7]);
                $('#creationDateDisplay').html(data[8]);
                $('#createdByDisplay').html(data[9]);
                if (data[10] == 'y') {
                    $('#activeDisplay').html('<font class="green">Active</font>');
                    $('#dropMenuUrl_d').show();
                    $('#dropMenuUrl_d').attr('onclick', 'dropMenuAssignment(\'' + data[0] + '\', \'' + aPos + '\')');
                    $('#activateMenuUrl_d').hide();
                }
                else {
                    $('#activeDisplay').html('<font class="red">Inactive</font>');
                    $('#activateMenuUrl_d').show();
                    $('#activateMenuUrl_d').attr('onclick', 'activateMenuAssignment(\'' + data[0] + '\', \'' + aPos + '\')');
                    $('#dropMenuUrl_d').hide();
                }
                $('#update_menu_button').attr('onclick', 'editAssignment(\'' + data[0] + '\', \'' + aPos + '\')');
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

function editAssignment(id, aPos) {
    showLoading("Processing Data Into Update Form");
    data = 'task=getUrlIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "global/glb_menu_user_assignment_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#topMenu_u').val(data[13]);
                $('#menuUrl_u').val(data[13]);
                $('#user_u').val(data[1]);

                $('#sDate_u').val(data[11]);
                $('#eDate_u').val(data[12]);

                if (data[3] == 'y')
                    $('#edit_u').attr('checked', 'checked');
                else
                    $('#edit_u').removeAttr('checked', 'checked');

                if (data[10] == 'y') {
                    $('#dropMenuUrl_u').show();
                    $('#dropMenuUrl_u').attr('onclick', 'dropMenuAssignment(\'' + data[0] + '\', \'' + aPos + '\')');
                    $('#activateMenuUrl_u').hide();
                }
                else {
                    $('#activateMenuUrl_u').show();
                    $('#activateMenuUrl_u').attr('onclick', 'activateMenuAssignment(\'' + data[0] + '\', \'' + aPos + '\')');
                    $('#dropMenuUrl_u').hide();
                }
                $('#assignmentId_u').val(data[0]);
                $('#rowPosition_u').val(aPos);
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

function processUpdateForm() {
    //validation process
    
    var id = $('#assignmentId_u').val();
    if (id) {
        var aPos = $('#rowPosition_u').val();
        aPos = parseInt(aPos);
        //preparing for ajax call
        var data = $('#updateForm').serialize();
        data = 'task=updateMenuUrl&' + data;
        showLoading("Uploading Data To Server");
        $.ajax({
            url:formGlobalPath + "global/glb_menu_user_assignment_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                var viewImageLink = getButtonViewImage();
                var editImageLink = getButtonEditImage();
                if (output[0] != 0) {
                    oTable.fnUpdate([
                        output[1],
                        output[2],
                        output[3],
                        output[4],
                        output[5],
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showAssignmentDetails('" + output[0] + "', '" + aPos + "')\">" + viewImageLink + "Show Details</button>",
                        "<button type=\"button\" id=\"edit\" class=\"positive edit\" onclick=\"editAssignment('" + output[0] + "', '" + aPos + "')\">" + editImageLink + "Edit Details</button>"
                    ], aPos);
                    hideUpdateForm();
                    $('#menu_hint_val').val(output[4]);
                    $('#menu_hint').val(output[5]);
                    showDatatable();
                    showAssignmentDetails(output[0], aPos);
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

function dropMenuAssignment(id, aPos) {
    if (confirm('Do you really want to delete ?')) {
        showLoading("Processing Data");
        data = 'task=dropId&id=' + id;
        $.ajax({
            url:formGlobalPath + "global/glb_menu_user_assignment_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    showAssignmentDetails(id, aPos);
                    hideUpdateForm();
                    endLoading();
                } else {
                    handleNotification('Error In Dropping Assignment ... Try After Sometime', 'error');
                    endLoading();
                }

            }
        });
    }
    return false;
}

function activateMenuAssignment(id, aPos) {
    if (confirm('Do you really want to activate ?')) {
        showLoading("Processing Data");
        data = 'task=activateId&id=' + id;
        $.ajax({
            url:formGlobalPath + "global/glb_menu_user_assignment_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    showAssignmentDetails(id, aPos);
                    hideUpdateForm();
                    endLoading();
                } else {
                    handleNotification('Error In Activating Assignment ... Try After Sometime', 'error');
                    endLoading();
                }

            }
        });
    }
    return false;
}





