$(document).ready(function() {
    $('#photoimg').live('change', function()			{
        $("#preview").html('');
        $("#preview").html('<img src="'+serverUrl+'images/global/loader.gif" alt="Uploading...."/>');
        $("#imageform").ajaxForm({
            dataType: 'json',
            success: showResponse
        }).submit();
    });
});

function showResponse(responseText, statusText, xhr, $form){
    var data  = responseText;
    if(data[0] == 1){
        var imageLink = serverUrl+'programs/uploads/user/'+data[1];
        $('#imageLink').attr('src', imageLink);
        $('#preview').html("<font class='green'>Image uploaded successfully</font>");
    }else{
        $('#preview').html("<font class='red'>"+data[1]+"</font> ");
    }
    return false;
}