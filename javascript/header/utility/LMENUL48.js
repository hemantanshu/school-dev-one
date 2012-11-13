$(document)
    .ready(
    function () {
        employmentId = $('#employmentId').val();
        getEmploymentIdDetails();
    });

function getEmploymentIdDetails() {
    var data = 'task=getRecordIdDetails&id=' + employmentId;
    $.ajax({
        url:formGlobalPath + "utility/utl_user_employment_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != '') {
                $('#organization_d').html(data[1]);
                $('#position_d').html(data[2]);
                $('#startDate_d').html(data[3]);
                $('#endDate_d').html(data[4]);
                $('#lastUpdateDateDisplay').html(data[5]);
                $('#lastUpdatedByDisplay').html(data[6]);
                $('#creationDateDisplay').html(data[7]);
                $('#createdByDisplay').html(data[8]);
                $('#comments_d').html(data[10]);
                if (data[9] == 'y') {
                    $('#activeDisplay').html(
                        '<font class="green">Active</font>');
                } else {
                    $('#activeDisplay').html(
                        '<font class="red">Inactive</font>');
                }
            } else {
                $.colorbox.close();
            }
        }
    });
    return false;
}
