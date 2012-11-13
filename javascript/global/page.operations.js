var valid = new validate();
var formGlobalPath = getFormGlobalPath();

function restorePreviousPage(){
    var url = serverUrl+previousPageArray.pop();
    loadPage(url);
    return false;
}

function confirmLogoutClick(){
    jConfirm('Do you really want to logout ?', 'Support Gurukul Prompt', function(r){
        if(r == true)
            logOutUserLoggedUser();
    });
}

function processBookmarkInsert(){
    //preparing for ajax call
    var url = currentPage.replace('&', '#*');
    var data = 'pageName='+$('#pageNameD').val()+'&priority='+$('#priorityD').val()+'redirect=n&newurl='+url;
    data = 'task=insertRecord&' + data;
    $
        .ajax({
            url : formGlobalPath + "utility/utl_personal_bookmark_form.php",
            type : "POST",
            data : data,
            cache : false,
            dataType : 'json',
            success : function(output) {
                if (output[0] != 0) {
                    $.colorbox.close();
                    handleNotification('The bookmark has been successfully added', 'success');
                    loadBookmarkedMenus();
                } else {
                    handleNotification(
                        'Error While Inserting Data, Please Try Again',
                        'error');

                }
            }
        });
    return false;
}

function checkUserSessionStatus(){
    $.ajax({
        url : formGlobalPath+'global/glb_process_login.php?task=checkSessionValidity',
        type : 'GET',
        cache : true,
        dataType : 'json',
        success : function(output) {
            if(output[0] == 'ERR401'){
                showLoginForm();
            }
            else{
                var timer = 60000;
                setTimeout(checkUserSessionStatus, timer);
            }
        }
    });
    return false;
}

function loadExtraMenuListing(){
    var extraMenus = $('#extraMenuListingPage').html();
    extraMenuListing.html(extraMenus);
    additionalMenuDisplay.show();
}

function lockTheScreen(){
    jConfirm('Confirm If You Want To Lock The Screen ?', 'Support Gurukul Prompt', function(r){
        if(r == true)
            showLoginForm();
    });

}

function showLoginForm(){
    $.colorbox({
        href: serverUrl+"pages/global/glb_login.php",
        overlayClose: false,
        escKey: false,
        close: 'close',
        onLoad:function (){
            $('#cboxClose').hide();
        }
    });
}
function showBookmarkPage(){
    $.colorbox({
        href: serverUrl+"pages/global/glb_bookmark_page.php",
        overlayClose: true,
        escKey: true,
        scrolling: false,
        width: 600
    });
}

function loadColorboxPage(link, width){
    $.colorbox({
        href: link,
        overlayClose: true,
        escKey: true,
        width: width,
        scrolling: false
    });
}

function logOutUserLoggedUser(){
    var formGlobalPath = getFormGlobalPath();
    $.ajax({
        url : formGlobalPath+'global/glb_process_login.php?task=logout',
        type : "GET",
        cache : false,
        dataType : 'json',
        success : function(output) {
            if (output == 0) {
                jAlert("The system is facing some problem, please close the browser");
            }else{
                window.location='./';
            }
        }
    });
}

function loadPageIntoDisplay(link){
    additionalMenuDisplay.hide();
    if(link == serverUrl)
        link = getDenialOfServicePageUrl();
    if(link == '#')
        return false;
    loadPage(link);
    if(currentPage != '')
        previousPageArray.push(currentPage.replace(serverUrl, ''));
    return false;
}

function loadPage(link){
    showLoading('Loading Page Content');
    $.ajax({
        url : link,
        type : "GET",
        cache : false,
        dataType : 'html',
        success : function(htmlOutput) {
            checkValidityOfOutput(htmlOutput);
            if (htmlOutput == 0) {
                processFailurePageDisplayOperations(link);
            }else{
                $('#displayRegion').html(htmlOutput);
                processSuccessPageDisplayOperations(link);
                initiateToolTipText();
            }
        }
    });
}

function processSuccessPageDisplayOperations(link){
    var newUrl = link.replace(serverUrl, '');
    var messageText = newUrl +' successfully loaded';
    notificationCounter =  notificationCounter + 1;
    var notificationId = "notificationIdNo"+notificationCounter;
    var notification = '<li id="'+notificationId+'"><a href="#" class="delete" onclick="removeAlertNotification(\''+notificationId+'\')">X</a><p>'+messageText+'</p></li>';
    $('#notificationMessages').append(notification);    
    getImageOnButton();
    currentPage = link;
    endLoading();
    return false;
}

function processFailurePageDisplayOperations(link){
    previousPageArray.pop();
    handleNotification('The page '+link+' cannot be loaded. Check your system admin', 'error');
    endLoading();
    return false;
}

function getFormGlobalPath(){
    return "/school/programs/forms/";
}
function schoolGlobalPath(){
    return "/school/pages/";
}
function schoolImageGlobalPath(){
    return "/school/images/";
}

function refreshThePage(){
    if(currentPage != "")
        loadPage(currentPage);
    return false;
}

function loadBookmarkedMenus(){
    var formGlobalPath = getFormGlobalPath();
    var bookmark = $('#bookmarkedMenuListing');
    bookmark.html('');
    var data = 'task=getBookmarkMenus';
    $.ajax({
        url : formGlobalPath+'utility/utl_personal_bookmark_form.php',
        type : "POST",
        cache : false,
        data: data,
        dataType : 'json',
        success : function(data) {
            if (data[0][0] != 0) {
                var newurl;
                for(var i=0;i<data.length;i++)
                {
                    newurl = data[i][1].replace('#*', '&');                    
                    var link =  '<li><a href="#" class="bookmarkedMenuListing" target="'+data[i][2]+'" onclick="handleBookmarkMenuClick(\''+newurl+'\', \''+data[i][2]+'\')"><img src="'+serverUrl+'images/global/b_usredit.png" alt="" />'+data[i][0]+'</a></li>';
                    bookmark.append(link);
                }
            }
        }
    });
}

function loadTaskMenus(){
    //loading the menu assigned
    var data = 'task=getTaskMenus';
    var taskId;
    var pendingTaskPanel = $('#extraPendingTasks');
    $.ajax({
        url : formGlobalPath+'utility/utl_personal_bookmark_form.php',
        type : "POST",
        cache : false,
        data: data,
        dataType : 'json',
        success : function(data) {
            if (data[0][0] != 0) {
                var newurl;
                for(var i=0;i<data.length;i++)
                {
                    newurl = data[i][1].replace('#*', '&');
                    taskId = 'taskNotification'+data[i][3];
                    var link =  '<li><a href="#" class="bookmarkedMenuListing" id="'+taskId+'" target="'+data[i][2]+'" onclick="handleBookmarkMenuClick(\''+newurl+'\', \''+data[i][2]+'\')"><img src="'+serverUrl+'images/global/b_usredit.png" alt="" />'+data[i][0]+'</a></li>';

                    pendingTaskPanel.prepend(link);
                }
            }
        }
    });
}

function checkValidityOfOutput(output){
    if(output == 'ERR404'){
        processFormDenialActions();
    }
    if(output == 'ERR406')
        processFormDenialActions();
    if(output == 'ERR401')
    	showLoginForm();

    if(output == 'ERRORPAGE'){
        alert('this page is not authorized');
    }
    return false;
}

function handleBookmarkMenuClick(link, type){
    if(type == '_blank'){
        window.open('link', '_blank');
    }else{
        loadPageIntoDisplay(link);
    }
    return false;
}


function processFormDenialActions(){
    var link = getDenialOfServicePageUrl();
    loadPageIntoDisplay(link);
    return false;
}

function getDenialOfServicePageUrl(){
    var link = serverUrl+'pages/global/denial_of_service.php';
    return link;
}

function initiateToolTipText(){
    $(".formelements :input").tooltip({
        // place tooltip on the right edge
        position: "center right",
        // a little tweaking of the position
        offset: [-2, 10],
        // use the built-in fadeIn/fadeOut effect
        effect: "fade",
        // custom opacity setting
        opacity: 0.7

    });
}