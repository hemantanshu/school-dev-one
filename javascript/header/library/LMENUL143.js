var valid = new validate();
var formGlobalPath = getFormGlobalPath();
var oTable;

$(document)
    .ready(
    function () {
        oTable = $('#groupRecords').dataTable({
            "bJQueryUI":true,
            "sPaginationType":"full_numbers",
            "sSwfPath": "/media/swf/copy_csv_xls_pdf.swf",
            "sDom": '<"H"Tfr>t<"F"ip>'
        });
        getSearchResults();
    });

function showDatatable() {
    showTheDiv('displayDatatable');
}

function getSearchResults() {
    var data = $('#searchForm').serialize();
    data = 'task=search&' + data;
    showLoading("Fetching Data From Server");
    $
        .ajax({
            url:formGlobalPath + "library/library_vendor_liste_form.php",
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
                    if(data[0][0] == 1){
                        handleNotification('No Data Exists For The Given Combination', 'info');
                        endLoading();
                    }else{
                        if (data[0][0] != 0) {
                            oTable.fnClearTable();
                            for (var i = 0; i < data.length; i++) {
                                oTable
                                    .fnAddData([
                                    "<a href=\"#\" onclick=\"editVendorDetails('"+data[i][0]+"')\">"+data[i][1]+"</a>",
                                    data[i][2],
                                    data[i][3],
                                    data[i][4]
                                ]);
                            }
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

function editVendorDetails(id){
    var url = serverUrl+'pages/library/library_vendor_details.php?vendorId='+id;
    loadPageIntoDisplay(id);
    return false;
}