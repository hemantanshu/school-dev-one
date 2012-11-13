$(document)
    .ready(
    function () {
        seminarId = $('#seminarId').val();
        getSeminarIdDetails();
    });

function getSeminarIdDetails() {
    var data = 'task=getRecordIdDetails&id=' + seminarId;
    $.ajax({
        url:formGlobalPath + "utility/utl_user_seminar_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != '') {
                $('#seminarTitle_d').html(data[1]);
                $('#organizedBy_d').html(data[2]);
                $('#startDate_d').html(data[3]);
                $('#duration_d').html(data[4]);
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
