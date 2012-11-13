/**
 * Created with JetBrains PhpStorm.
 * User: hsah
 * Date: 5/2/12
 * Time: 10:58 PM
 * To change this template use File | Settings | File Templates.
 */
var valid = new validate();
function processLoginProcess() {
    var formGlobalPath = getFormGlobalPath();
    var userName = $('#username').val();
    var password = $('#password').val();

    var data = 'username=' + userName + '&password=' + password;
    $.ajax({
        url:formGlobalPath + "global/glb_process_login.php?task=checkLogin",
        type:"GET",
        data:data,
        dataType:'html',
        cache:false,
        success:function (html) {
            if (html == 0) {
                $.colorbox.close();
                checkUserSessionStatus();
            }
            else {
                $('#loginMessage').html("<b>Error</b> : Wrong User / Password Combination .. !!");
            }
        }
    });
    return false;
}

function processMainLoginProcess() {	
    var userName = $('#username').val();
    var password = $('#password').val();

    var data = 'username=' + userName + '&password=' + password;
    $.ajax({
        url:"programs/forms/global/glb_process_login.php?task=mainCheckLogin",
        type:"GET",
        data:data,
        dataType:'json',
        cache:false,
        success:function (output) {
            if (output == 0) {
                window.location = "./";
            }
            else {
                $('#loginMessage').html("<b>Error</b> : Wrong User / Password Combination .. !!");
            }
        }
    });
    return false;
}

function logoffUser() {
    $.colorbox.close();
    logOutUserLoggedUser();
    return false;
}