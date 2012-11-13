$(document)
    .ready(
    function () {
        educationId = $('#educationId').val();
        getEducationIdDetails();
    });

function getEducationIdDetails() {
    var formGlobalPath = getFormGlobalPath();
    var data = 'task=getRecordIdDetails&id=' + educationId;
    $.ajax({
        url:formGlobalPath + "utility/utl_user_education_form.php",
        type:"POST",
        data:data,
        cache:false,
        dataType:'json',
        success:function (data) {
            checkValidityOfOutput(data);
            if (data[0] != 0) {
                $('#institute_d').html(data[1]);
                $('#university_d').html(data[3]);
                $('#level_d').html(data[4]);
                $('#year_d').html(data[5]);
                $('#score_d').html(data[6]);
                $('#markType_d').html(data[7]);
                $('#lastUpdateDateDisplay').html(data[8]);
                $('#lastUpdatedByDisplay').html(data[9]);
                $('#creationDateDisplay').html(data[10]);
                $('#createdByDisplay').html(data[11]);
                $('#comments_d').html(data[14]);

                if (data[12] == 'y') {
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
