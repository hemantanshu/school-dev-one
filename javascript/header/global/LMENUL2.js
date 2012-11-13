$(document).ready(function () {
    oTable = $('#groupMenus').dataTable({
        "bJQueryUI":true,
        "sPaginationType":"full_numbers",
        "iDisplayLength":20
    });
    hideDetailsForm();
    hideLog();
    $('#hideLog').hide();
    formGlobalPath = getFormGlobalPath();
});

$(document).ready(function () {
    $("#menuName").autocomplete(formGlobalPath + "global/glb_menu_form.php?type=menu_url", {
        width:260,
        matchContains:true,
        mustMatch:true,
        selectFirst:false
    });
    $("#menuName").result(function (event, data, formatted) {
        $("#menuId").val(data[1]);
    });
});


function hideDetailsForm() {
    $('#display').fadeOut();
}
function showDetailsForm() {
    $('#display').fadeIn();
}
function hideLog() {
    $('#showLogs').fadeOut();
    $('#hideLog').hide();
}
function showLog() {
    $('#showLogs').fadeIn();
    $('#hideLog').show();
}

function fetchDetails() {
    var menuId = $('#menuId').val();
    if (menuId == '')
        return false;
    //fetching data from server
    var data = 'task=fetchDetails&menuId=' + menuId;
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
                $('#topMenu').html(output[16]);
                $('#subMenu').html(output[15]);
                $('#lastUpdateDate').html(output[10]);
                $('#lastUpdatedBy').html(output[11]);
                $('#creationDate').html(output[12]);
                $('#createdBy').html(output[13]);
                $('#parentMenu').html(output[9]);
                $('#sourceId').html(output[8]);
                $('#menu_id_d').val(output[0]);
                showDetailsForm();
                hideLog();
                endLoading();
            } else {
                endLoading();
            }
        }
    });
    return false;
}

function showLogDetails() {
    var menuId = $('#menu_id_d').val();

    var data = 'task=fetchLogDetails&menuId=' + menuId;
    showLoading("Fetching Data From Server");
    $.ajax({
        url:formGlobalPath + "global/glb_menu_url_details_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0][0] != 0) {
                oTable.fnClearTable();
                for (i = 0; i < data.length; i++) {
                    oTable.fnAddData(
                        [
                            data[i][0],
                            data[i][1],
                            data[i][2]
                        ]);
                }
                showLog();
                endLoading();
            } else {
                handleNotification('Error In Getting The Data From The Server ... Try After Some Time', 'error')
                endLoading();
            }
        }
    });
}