//this is a alteration made just to check if everything is going fine

var notificationCounter = 0;
var previousPage = '';
var currentPage = '';
var serverUrl, additionalMenuDisplay;
var loadingDiv;
var previousPageArray;

function showLoading(message){
    var imagePath = schoolImageGlobalPath() + 'global/';
    var image = imagePath+'ajax-loader.gif';
    var loaderImageTag = '<img src="'+image+'" alt="" /><span>'+message+'</span>';
    loadingDiv.html(loaderImageTag);
    loadingDiv.show();
}

function endLoading(){
    loadingDiv.hide();
}

function handleNotification(messageText, messageType){
    var opts = {};
    opts.classes = [messageType];
    opts.autoHide = true;
    $('#notification').freeow('', messageText, opts);


    notificationCounter =  notificationCounter + 1;
    var notificationId = "notificationIdNo"+notificationCounter;
    var notification = '<li id="'+notificationId+'"><a href="#" class="delete" onclick="removeAlertNotification(\''+notificationId+'\')">X</a><p>'+messageText+'</p></li>';
    $('#notificationMessages').append(notification);
}
function removeAlertNotification(notificationId){
    $('#'+notificationId+'').remove();
}

function resetForm(id){    
    $('#'+id).each(function(){
        this.reset();
    });
}

$(document).ready(function(){
    loadingDiv = $('#loading');
    loadingDiv.hide();
    checkUserSessionStatus();

    serverUrl = $('#globalServerUrl').val();
    additionalMenuDisplay = $('#extraMenuPanel');
    extraMenuListing = $('#extraMenuListing');
    previousPageArray = new Array();

    loadBookmarkedMenus();
    loadTaskMenus();

    $('a').click(function(event) {
        event.preventDefault();
        var pageRedirect =  $(this).attr('target');
        var link = $(this).attr('href');
        if(link == '#')
            return false;
        if(pageRedirect == '_blank'){
            window.open(link, '_blank');
        }
        else{
            loadPageIntoDisplay(link);
        }
        return false;
    });
});

ddsmoothmenu.init({
    mainmenuid: "sgmenudiv", //menu DIV id
    orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
    classname: 'sgmenu', //class added to menu's outer DIV
    customtheme: ["#606060", "#a7a7a7"],
    contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
});

function getButtonViewImage(){
    var imagePath = schoolImageGlobalPath() + 'global/icons/';
    var viewLink = '<img src="'+imagePath+'browse.png" alt="" /> ';
    return viewLink;
}
function getButtonEditImage(){
    var imagePath = schoolImageGlobalPath() + 'global/icons/';
    var updateLink = '<img src="'+imagePath+'edit.png" alt="" /> ';
    return updateLink;
}

function getButtonBrowseImage(){
    var imagePath = schoolImageGlobalPath() + 'global/icons/';
    var viewLink = '<img src="'+imagePath+'b_insrow.png" alt="" /> ';
    return viewLink;
}

function getButtonAddImage(){
    var imagePath = schoolImageGlobalPath() + 'global/icons/';
    var viewLink = '<img src="'+imagePath+'add.png" alt="" /> ';
    return viewLink;
}


function showTheDiv(id){
    $('#'+id+'').slideDown('slow');
}

function hideTheDiv(id){
    $('#'+id+'').slideUp('slow');
}

function toggleTheDiv(id){
    $('#'+id+'').slideToggle('slow');
}



function getImageOnButton(){
    var imagePath = schoolImageGlobalPath() + 'global/icons/';

    var hideLink = '<img src="'+imagePath+'up.png" alt="" /> ';
    var resetLink = '<img src="'+imagePath+'Redo.png" alt="" /> ';
    var dropLink = '<img src="'+imagePath+'Delete.png" alt="" /> ';
    var insertLink = '<img src="'+imagePath+'Create.png" alt="" /> ';
    var updateLink = '<img src="'+imagePath+'Upload.png" alt="" /> ';
    var activateLink = '<img src="'+imagePath+'Apply.png" alt="" /> ';
    var viewLink = '<img src="'+imagePath+'Browse.png" alt="" /> ';
    var searchLink = '<img src="'+imagePath+'View.png" alt="" /> ';
    var toggleLink = '<img src="'+imagePath+'toggle.png" alt="" /> ';
    var editLink = '<img src="'+imagePath+'edit.png" alt="" /> ';
    var browseLink = '<img src="'+imagePath+'b_insrow.png" alt="" /> ';

    $('button.hide').prepend(hideLink);
    $('button.reset').prepend(resetLink);
    $('button.drop').prepend(dropLink);
    $('button.insert').prepend(insertLink);
    $('button.update').prepend(updateLink);
    $('button.activate').prepend(activateLink);
    $('button.view').prepend(viewLink);
    $('button.search').prepend(searchLink);
    $('button.toggle').prepend(toggleLink);
    $('button.edit').prepend(editLink);
    $('button.browse').prepend(browseLink);


    return false;
}