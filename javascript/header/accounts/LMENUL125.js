var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var employeeId, oTable;
$(document)
    .ready(
    function () {
        oTable = $('#groupRecords').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers"
        });
        populateBankDetails();

        $("#employee")
            .autocomplete(
            formGlobalPath
                + "utility/utl_user_autocomplete.php?option=employeeTeacher",
            {
                width:260,
                matchContains:true,
                mustMatch:false,
                selectFirst:false
            });
        $("#employee").result(
            function (event, data, formatted) {
                $("#employee_val").val(data[1]);
            });
    });

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

function showChoiceListing(){
    showTheDiv('choiceListing');
}
function hideChoiceListing(){
    hideTheDiv('choiceListing');
}

function showPageDisplay(){
    showTheDiv('completePageDisplay');
}

function hidePageDisplay(){
    hideTheDiv('completePageDisplay');
}

function changeEmployeeName(){
    if(employeeId != undefined){
        var choiceListing = $('#choiceListing');
        if(choiceListing.is(':visible')){
            hideChoiceListing();
            showPageDisplay();
        }
        else{
            showChoiceListing();
            hidePageDisplay();
        }
    }
}

function checkEmployeeChange(){
    employeeId = $('#employee_val').val();
    if(employeeId != ''){
        loadEmployeePersonalDetails();
        hideDisplayPortion();
        hideUpdateForm();
        hideChoiceListing();
        showPageDisplay();
    }
    return false;
}

function loadEmployeePersonalDetails(){
    var data = 'employeeId='+employeeId+'&task=fetchEmployeeDetails';
    showLoading("Uploading Data To Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_employee_master_salary_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (output) {
                checkValidityOfOutput(output);
                if (output[0] != 0) {
                    $('#employeeName').html(output[0]);
                    $('#employeeCode').html(output[1]);
                    getSearchResults();
                    endLoading();
                } else {
                    handleNotification(
                        'Error Fetching Data From Server',
                        'error');
                    endLoading();
                }
            }
        });
    return false;
}

function populateBankDetails(){
    showLoading('Fetching Details From Server');
    var data = 'task=populateBankName';
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_employee_bankaccount_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                checkValidityOfOutput(data);
                if(data[0][0] == 1){
                    handleNotification('No Data Exists For The Given Combination', 'info');
                    endLoading();
                }else{
                    if (data[0][0] != 0) {
                        var options = '';
                        for (var i = 0; i < data.length; i++) {
                            options = options + '<option value="'+data[i][0]+'">'+data[i][1]+'</option>';
                        }
                        $('#bankName').html(options);
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

function getSearchResults() {
    var data = $('#searchForm').serialize();
    data = 'employeeId='+employeeId+'&task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "accounts/accounts_employee_bankaccount_form.php",
            type:"POST",
            data:data,
            cache:false,
            dataType:'json',
            success:function (data) {
                console.log(data);
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
                        var browseImageLink = getButtonBrowseImage();
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
                                    + "')\">" + viewImageLink + " Show Details</button>",
                                "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                    + data[i][0]
                                    + "', '"
                                    + i
                                    + "')\">" + editImageLink + "Edit Details</button>" ]);
                        }
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
    var data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "accounts/accounts_employee_bankaccount_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {

            checkValidityOfOutput(data);
            if (data[0] != '') {
                $('#accountNumber_d').html(data[1]);
                $('#accountType_d').html(data[2]);
                $('#bankName_d').html(data[3]);
                $('#branchName_d').html(data[4]);
                $('#ifscCode_d').html(data[5]);
                $('#micrCode_d').html(data[6]);
                $('#lastUpdateDateDisplay').html(data[7]);
                $('#lastUpdatedByDisplay').html(data[8]);
                $('#creationDateDisplay').html(data[9]);
                $('#createdByDisplay').html(data[10]);
                if (data[11] == 'y') {
                    $('#activeDisplay').html(
                        '<font class="green">Active</font>');
                } else {
                    $('#activeDisplay').html(
                        '<font class="red">Inactive</font>');
                }
                $('#editRecordButton').attr('onclick',
                    'editRecord(\'' + data[0] + '\', \'' + aPos + '\')');

                $('#valueId_d').val(data[0]);
                $('#rowPosition_d').val(aPos);
                hideUpdateForm();
                showDisplayPortion();
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

// code for Edit begins
function editRecord(id, aPos) {
    showLoading("Processing Data Into Update Form");
    var data = 'task=getRecordIdDetails&id=' + id;
    $.ajax({
        url:formGlobalPath + "accounts/accounts_employee_bankaccount_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#bankAccountNumber').val(data[1]);
                $('#bankName').val(data[12]);

                $('#accountType_u').html(data[2]);

                $('#valueId_u').val(data[0]);
                $('#rowPosition_u').val(aPos);

                hideDisplayPortion();
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
                url:formGlobalPath + "accounts/accounts_employee_bankaccount_form.php",
                type:"POST",
                data:data,
                cache:false,
                dataType:'json',
                success:function (data) {
                    checkValidityOfOutput(data);
                    if (data[0] != 0) {
                        var viewImageLink = getButtonViewImage();
                        var editImageLink = getButtonEditImage();
                        oTable
                            .fnUpdate(
                            [
                                data[1],
                                data[2],
                                data[3],
                                "<button type=\"button\" id=\"details\" class=\"regular details\" onclick=\"showRecordDetails('"
                                    + data[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + viewImageLink + "Show Details</button>",
                                "<button type=\"button\" id=\"edit\" class=\"negative edit\" onclick=\"editRecord('"
                                    + data[0]
                                    + "', '"
                                    + aPos
                                    + "')\">" + editImageLink + "Edit Details</button>" ],
                            aPos);
                        hideUpdateForm();
                        showRecordDetails(data[0], aPos);
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


