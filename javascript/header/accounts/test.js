/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *//* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var valid = new validate();
var oTable, formGlobalPath, classGlobal;
$(document).ready(function(){
    oTable = $('#groupRecords').dataTable({
        'bJQueryUI':true,
        'sPaginationType':'full_numbers'
    });
    formGlobalPath = getFormGlobalPath();
    classGlobal = $('#class_global').val();
    checkFormEditableOption();

    getSearchResult();

    $('employeeName_i').autocomplete(
        formGlobalPath+"global/ramesh_testing_form.php",
        {
            width:260,
            matchContains:true,
            mustMatch:true,
            selectFirst:false
        });
    $('employeeName_i').result(function(event,data,formatted){
        $('employeeName_val').val(data[1]);
    });

    $('searchKey').autocomplete(
        formGlobalPath+"global/ramesh_testing_form.php",
        {
            width:260,
            matchContains:true,
            mustMatch:true,
            selectFirst:false
        });
});             //End of document.read()

function showInsertForm(){
    showTheDiv('insertForm');
}
function hideInsertForm(){
    hideTheDiv('insertForm');
}
function toggleInsertForm(){
    toggleTheDiv('insertForm');
}

function showUpdateForm(){
    showTheDiv('updateForm');
}
function hideUpdateForm(){
    hideTheDiv('updateForm');
}

function showDisplayPortion(){
    showTheDiv('displayRecord');
}
function hideDisplayPortion(){
    hideTheDiv('displayRecord');
}

function showDatatable(){
    showTheDiv('displayDatatable');
}
function showHideDatatable(){
    toggleTheDiv('displayDatatable');
}
function showHideSearchForm(){
    toggleTheDiv('searchForm');
}

function checkFormEditableOption(){                 // Checks whether Form is editable or not 
    var data = 'classId=' + classGlobal + '&task=checkSessionEditable';
    $
        .ajax({
            url:formGlobalPath + "global/ramesh_testing_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] == 0) {
                    $('#insertForm').remove();
                    $('#updateForm').remove();
                    $('#editRecord_d').remove();
                    $('#activateRecord_d').remove();
                    $('#dropRecord_d').remove();
                    $('#toggleInsert').remove();
                }
            }
        });
    return false;
}

function processInsertForm() {
    // preparing for ajax call
    var data = $('#insertForm').serialize();
    data = 'classId=' + classGlobal + '&task=insertRecord&' + data;
    alert(data);
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "global/ramesh_testing_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    aPos = oTable.fnGetNodes().length;
                    var viewImageLink = getButtonViewImage();
                    var browseImageLink = getButtonBrowseImage();
                    var editImageLink = getButtonEditImage();
                    oTable
                        .fnAddData([
                        output[1],
                        output[2],
                        output[3],
                        "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"showCandidateMappings('"
                            + output[0]
                            + "', '"
                            + aPos
                            + "')\">" + browseImageLink + "Show Candidates</button>",
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


function getSearchResults() {
    var data = $('#searchForm').serialize();
    data = 'classId=' + classGlobal + '&task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "global/ramesh_testing_form.php",
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
                                    + "')\">" + viewImageLink + "Show Details</button>" ]);
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



function showRecordDetails(id, aPos) {
    showLoading("Fetching Data From Server");
    data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "global/ramesh_testing_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[1] != '') {
                $('#employeeName_d').html(data[1]);
                $('#employeeCode_d').html(data[2]);
                $('#startDate_d').html(data[3]);
                $('#endDate_d').html(data[4]);
                $('#comments_d').html(data[5]);
                $('#lastUpdateDate_d').html(data[6]);
                $('#createdDate_d').html(data[7]);
                $('#createdBy_d').html(data[8]);
                $('#active_d').html(data[9]);

                if (data[13] == 'y') {
                    $('#active_d').html(
                        '<font class="green">Active</font>');
                    if(data[17] == 1){
                        $('#dropRecord_d').show();
                        $('#dropRecord_d')
                            .attr(
                            'onclick',
                            'dropRecord(\'' + data[0] + '\', \'' + aPos
                                + '\')');
                        $('#activateRecord_d').hide();
                    }
                } else {
                    $('#active_d').html(
                        '<font class="red">Inactive</font>');
                    if(data[17] == 1){
                        $('#activateRecord_d').show();
                        $('#activateRecord_d').attr(
                            'onclick',
                            'activateRecord(\'' + data[0] + '\', \'' + aPos
                                + '\')');
                        $('#dropRecord_d').hide();
                    }
                }
                if(data[17] != 1){
                    $('#dropRecord_d').hide();
                    $('#activateRecord_d').hide();
                }else{
                    $('#editRecord_d').attr('onclick',
                        'editRecord(\'' + data[0] + '\', \'' + aPos + '\')');
                }


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


function editRecord(id, aPos) {
    showLoading("Processing Data Into Update Form");
    data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "global/ramesh_testing_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[17] == 1) {
                $('#employeeName_e').val(data[3]);
                $('#startDate_e').val(data[2]);
                $('#endDate_e').val(data[14]);
                $('#comment_e').val(data[4]);

//                $('#coordinator_uval').val(data[16]);
//                $('#coordinator_u').val(data[15]);

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

//                $('#valueId_u').val(data[0]);
//                $('#rowPosition_u').val(aPos);

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

function processUpdateForm() {
    // validation process
    var id = $('#valueId_u').val();

    if (id) {
        var aPos = $('#rowPosition_u').val();
        aPos = parseInt(aPos);
        // preparing for ajax call
        var data = $('#updateForm').serialize();
        data = 'task=updateRecord&' + data;
        showLoading("Uploading Data To Server");
        $
            .ajax({
                url:formGlobalPath + "global/ramesh_testing_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (output) {
                    checkValidityOfOutput(output);
                    if (output[0] != 0) {
                        var viewImageLink = getButtonViewImage();
                        var editImageLink = getButtonEditImage();
                        var browseImageLink = getButtonBrowseImage();
                        oTable
                            .fnUpdate(
                            [
                                output[1],
                                output[2],
                                output[3],
                                "<button type=\"button\" id=\"details\" class=\"positive details\" onclick=\"showCandidateMappings('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + browseImageLink + "Show Candidates</button>",
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + output[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + viewImageLink + "Show Details</button>",
                                "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                    + output[0] + "', '" + aPos
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

function dropRecord(id, aPos) {
    if (confirm('Do you really want to delete ?')) {
        showLoading("Processing Data");
        data = 'task=dropRecord&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "global/ramesh_testing_form.php",
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

function activateRecord(id, aPos) {
    if (confirm('Do you really want to activate ?')) {
        showLoading("Processing Data");
        data = 'task=activateRecord&id=' + id;
        $
            .ajax({
                url:formGlobalPath + "global/ramesh_testing_form.php",
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


function showCandidateMappings(id, aPos){
    var link = serverUrl+'pages/utility/utl_class_section_candidate.php?sectionId='+id;
    loadPageIntoDisplay(link);
    return false;
}


