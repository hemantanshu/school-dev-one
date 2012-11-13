var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var oTable, vendorId, vendorName;

$(document)
    .ready(
    function () {
        oTable = $('#groupRecords').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });

        vendorId = $('#vendorId_glb').val();
        vendorName = $('#vendorName_glb').val();

        $("#category")
            .autocomplete(
            formGlobalPath
                + "global/glb_option_search.php?type=LICAT&option=yes",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#category").result(function (event, data, formatted) {
            $("#category_val").val(data[1]);
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
    showTheDiv('displayRecord');
}
function hideDisplayPortion() {
    hideTheDiv('displayRecord');
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

function processInsertForm() {
    // preparing for ajax call
    var data = $('#insertForm').serialize();
    data = 'vendorId='+vendorId+'&task=insertRecord&' + data;
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "library/library_vendor_category_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if (data[0] != 0) {
                    var aPos = oTable.fnGetNodes().length;
                    var viewImageLink = getButtonViewImage();
                    oTable
                        .fnAddData([
                        vendorName,
                        data[1],
                        "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                            + data[0]
                            + "', '"
                            + aPos
                            + "')\">" + viewImageLink + "Show Details</button>" ]);
                    hideDisplayPortion();
                    showDatatable();
                    endLoading();
                    $('#insertReset').click();
                    $('#category').focus();
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

function getSearchResults() {
    var data = $('#searchForm').serialize();
    data = 'vendorId='+vendorId+'&task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "library/library_vendor_category_form.php",
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
                    if(data[0][0] == 1){
                        handleNotification('No Data Exists For The Given Combination', 'info');
                        endLoading();
                    }else{
                        if (data[0][0] != 0) {
                            oTable.fnClearTable();
                            for (var i = 0; i < data.length; i++) {
                                oTable
                                    .fnAddData([
                                    vendorName,
                                    data[i][1],
                                    "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                        + data[i][0]
                                        + "', '"
                                        + i
                                        + "')\">" + viewImageLink + " Show Details</button>"
                                    ]);
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
            }
        });
    return false;
}

function showRecordDetails(id, aPos) {
    showLoading("Fetching Data From Server");
    var data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "library/library_vendor_category_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#categoryDisplay').html(data[1]);
                $('#lastUpdateDateDisplay').html(data[2]);
                $('#lastUpdatedByDisplay').html(data[3]);
                $('#creationDateDisplay').html(data[4]);
                $('#createdByDisplay').html(data[5]);
                if (data[6] == 'y') {
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

// Code For Drop Value Begins here
function dropRecord(id, aPos) {
    if (confirm('Do you really want to drop record ?')) {
        showLoading("Processing Data");
        var data = 'task=dropRecord&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "library/library_vendor_category_form.php",
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
                            'Error In Dropping Record ... Try After Sometime',
                            'error');
                        endLoading();
                    }

                }
            });
    }
    return false;
}
// Code for Drop value Ends Here

// Code For Activate Value Begins Here
function activateRecord(id, aPos) {
    if (confirm('Do you really want to activate record ?')) {
        showLoading("Processing Data");
        var data = 'task=activateRecord&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "library/library_vendor_category_form.php",
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
                            'Error In Activating Record ... Try After Sometime',
                            'error');
                        endLoading();
                    }
                }
            });
    }
    return false;
}