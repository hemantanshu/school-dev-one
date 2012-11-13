var valid = new validate();
$(document)
    .ready(
    function () {
        oTable = $('#groupBooks').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });


        hideUpdateForm();
        hideDisplayPortion();
        showHideDatatable();
        formGlobalPath = getFormGlobalPath();

        // Building Id in the insert form
        $("#authorName")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?option=yes&type=ATHOR",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:false
            });
        $("#authorName").result(function (event, data, formatted) {
            $("#authorName_val").val(data[1]);
        });

        // Room Type in the insert form
        $("#publication")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?option=yes&type=PBLCT",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:false
            });
        $("#publication").result(function (event, data, formatted) {
            $("#publication_val").val(data[1]);
        });

        // Building Id in the insert form
        $("#authorName_u")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?option=yes&type=ATHOR",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:false
            });
        $("#authorName_u").result(function (event, data, formatted) {
            $("#authorName_uval").val(data[1]);
        });

        // Room Type in the insert form
        $("#publication_u")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?option=yes&type=PBLCT",
            {
                width:260,
                matchContains:true,
                mustMatch:true,
                selectFirst:false
            });
        $("#publication_u").result(
            function (event, data, formatted) {
                $("#publication_uval").val(data[1]);
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
    showTheDiv('bookdisplay');
}
function hideDisplayPortion() {
    hideTheDiv('bookdisplay');
}
function showTheDatatable() {
    showTheDiv('bookDatatable');
}
function showHideSearchForm() {
    toggleTheDiv('searchForm');
}
function showHideDatatable() {
    toggleTheDiv('bookDatatable');
}


//Code For Check Of Input Errors
function processInsertForm() {
    //preparing for ajax call
    var data = $('#insertForm').serialize();
    data = 'task=insertRecord&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_book_details_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    aPos = oTable.fnGetNodes().length;
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
                    showTheDatatable();
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

function getSearchResults() {
    var data = $('#searchForm').serialize();
    data = 'task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "utility/utl_book_details_form.php",
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
                    var viewImageLink = getButtonViewImage();
                    var editImageLink = getButtonEditImage();
                    if (data[0][0] != 0) {
                        oTable.fnClearTable();
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
                                "<button type=\"button\" id=\"negative edit\" class=\"negative edit\" onclick=\"editRecord('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + editImageLink + "Edit Record</button>" ]);
                        }
                        hideInsertForm();
                        hideDisplayPortion();
                        showTheDatatable();
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
        url:formGlobalPath + "utility/utl_book_details_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[1] != '') {
                $('#bookId_dDisplay').html(data[0]);
                $('#bookName_dDisplay').html(data[1]);
                $('#authorName_dDisplay').html(data[2]);
                $('#publication_dDisplay').html(data[3]);
                $('#lastUpdateDateDisplay').html(data[4]);
                $('#lastUpdatedByDisplay').html(data[5]);
                $('#creationDateDisplay').html(data[6]);
                $('#createdByDisplay').html(data[7]);
                $('#activeDisplay').html(data[8]);
                if (data[8] == 'y') {
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
        url:formGlobalPath + "utility/utl_book_details_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#bookName_u').val(data[1]);
                $('#authorName_uval').val(data[9]);
                $('#authorName_u').val(data[2]);
                $('#publication_uval').val(data[10]);
                $('#publication_u').val(data[3]);
                if (data[8] == 'y') {
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
                url:formGlobalPath + "utility/utl_book_details_form.php",
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
                                "<button type=\"button\" id=\"negative edit\" class=\"negative edit\" onclick=\"editRecord('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + editImageLink + "Edit Details</button>" ],
                            aPos);
                        hideUpdateForm();
                        showRecordDetails(output[0], aPos);
                        showTheDatatable();
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
                url:formGlobalPath + "utility/utl_book_details_form.php",
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
                url:formGlobalPath + "utility/utl_book_details_form.php",
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

