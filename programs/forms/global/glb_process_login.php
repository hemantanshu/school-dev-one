<?php
require_once 'config.php';
require_once BASE_PATH.'include/global/class.loggedInfo.php';

$loggedInfo = new loggedInfo();

if($_GET['task'] == 'logout'){
    if($loggedInfo->logOutUser()){
        $outputArray[0] = 1;
    }else
        $outputArray[1] = 0;

    echo json_encode($outputArray);
}elseif($_GET['task'] == 'checkSessionValidity'){
    if($loggedInfo->isUserLogged(false)){
        $outputArray[0] = time () - $_SESSION['lastActivityTime'];
    }
    else
        $outputArray[0] = 'ERR401';
    echo json_encode($outputArray);
}elseif($_GET['task'] == 'checkLogin'){
    $userId = $_GET['username'];
    $password = $_GET['password'];
    echo json_encode($loggedInfo->authenticateUser($userId, $password, false));
}elseif($_GET['task'] == 'mainCheckLogin'){
    $userId = $_GET['username'];
    $password = $_GET['password'];	
    echo json_encode($loggedInfo->authenticateUser($userId, $password, true));
}else{
    $outputArray[0] = 0;
    echo json_encode($outputArray);
}
?>

