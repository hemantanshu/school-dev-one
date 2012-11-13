var valid = new validate();
$(document)
    .ready(
    function () {
        oTable = $('#groupRooms').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });
        hideUpdateForm();
        hideDisplayPortion();
        showHideDatatable();
        formGlobalPath = getFormGlobalPath();

        //Building Id in the insert form
        $("#buildingName")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?option=yes&type=BUILD",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:false
            });
        $("#buildingName").result(function (event, data, formatted) {
            $("#buildingName_val").val(data[1]);
        });

        //Room Type in the insert form
        $("#roomType")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?option=yes&type=ROOMT",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:false
            });
        $("#roomType").result(function (event, data, formatted) {
            $("#roomType_val").val(data[1]);
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
    showTheDiv('roomDisplay');
}
function hideDisplayPortion() {
    hideTheDiv('roomDisplay');
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
//Code For Check Of Input Errors
function processInsertForm() {
    //preparing for ajax call
    var data = $('#insertForm').serialize();
    data = 'task=insertRecord&' + data;    
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_room_list_form.php",
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
//code For Inserting New value Name Ends Here	 

//Code for Search Begins Here
function getRoomSearchDetails() {
    var data = $('#searchForm').serialize();
    data = 'task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_room_list_form.php",
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
//Code For Search Ends Here

//Code For Show Details Begins Here
function showRecordDetails(id, aPos) {
    showLoading("Fetchign Data From Server");
    data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "utility/utl_room_list_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[13] != 0) {
                $('#roomName_dDisplay').html(data[1]);
                $('#roomNo_dDisplay').html(data[2]);
                $('#floorNo_dDisplay').html(data[3]);
                $('#buildingName_dDisplay').html(data[4]);
                $('#seatingCapacityN_dDisplay').html(data[5]);
                $('#seatingCapacityE_dDisplay').html(data[6]);
                $('#roomType_dDisplay').html(data[7]);
                $('#descriptionRoom_dDisplay').html(data[8]);
                $('#lastUpdateDateDisplay').html(data[9]);
                $('#lastUpdatedByDisplay').html(data[10]);
                $('#creationDateDisplay').html(data[11]);
                $('#createdByDisplay').html(data[12]);
                $('#activeDisplay').html(data[13]);
                if (data[13] == 'y') {
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
                $('#update_menu_button').attr('onclick',
                    'editRecord(\'' + data[0] + '\', \'' + aPos + '\')');
                $('#valueId_d').val(data[0]);
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
//code for show details ends here

//code for edit detaisl begins
function editRecord(id, aPos) {
    showLoading("Processing Data Into Update Form");
    data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "utility/utl_room_list_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#roomName_u').val(data[1]);
                $('#roomNo_u').val(data[2]);
                $('#floorNo_u').val(data[3]);
                $('#buildingName_u').val(data[4]);
                $('#seatingCapN_u').val(data[5]);
                $('#seatingCapE_u').val(data[6]);
                $('#roomType_u').val(data[7]);
                $('#descriptionRoom_u').val(data[8]);
                $('#buildingName_uval').val(data[14]);
                $('#roomType_uval').val(data[15]);
                if (data[13] == 'y') {
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
                $('#valueId_u').val(data[0]);
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
//code for edit details ends here

//Code For Update Form Begins Here
function processUpdateForm() {
    //validation process
    var id = $('#valueId_u').val();
    if (id) {
        var aPos = $('#rowPosition_u').val();
        aPos = parseInt(aPos);
        //preparing for ajax call
        var data = $('#updateForm').serialize();
        data = 'task=updateRecord&' + data;
        showLoading("Uploading Data To Server");
        $
            .ajax({
                url:formGlobalPath + "utility/utl_room_list_form.php",
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
                        showRecordDetails(output[0], aPos);
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
//Code For Update Form Ends here

//Code For Drop Value Begins here
function dropRecord(id, aPos) {
    if (confirm('Do you really want to delete ?')) {
        showLoading("Processing Data");
        data = 'task=dropRecord&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "utility/utl_room_list_form.php",
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
//Code for Drop value Ends Here

//Code For Activate Value Begins Here
function activateRecord(id, aPos) {
    if (confirm('Do you really want to activate ?')) {
        showLoading("Processing Data");
        data = 'task=activateRecord&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "utility/utl_room_list_form.php",
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
//Code For Activate Value Ends Here

