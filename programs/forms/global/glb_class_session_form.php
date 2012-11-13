<?php
error_reporting(1);
require_once 'config.php';
require_once BASE_PATH . 'include/global/class.session.php';

$session = new Session();
$session->isRequestAuthorised4Form('LMENUL55');

if (isset($_POST['task'])) {
    if ($_POST['task'] == 'insertRecord') {
        $error = 3;
        $errorMessage = '';
        foreach($_POST as $key => $value){
            if($_POST[$key] == ''){
                $outputArray[$error] = $key;
                $errorMessage = "Required form field lacks data, please fill up the form";
                ++$error;
            }
        }
        if($session->getSessionId4Date($_POST['startDate'], 'class')){
            $outputArray[$error] = 'startDate';
            $errorMessage .= 'The session start date falls in another session already defined. please chose another date';
            ++$error;
        }
        if($session->getSessionId4Date($_POST['endDate'], 'class')){
            $outputArray[$error] = 'endDate';
            $errorMessage .= 'The session end date falls in another session already defined. please chose another date';
            ++$error;
        }
        $difference = $session->getDateDifference($_POST['startDate'], $_POST['endDate']);
        if($difference < 0){
            $outputArray[$error] = 'endDate';
            $errorMessage .= 'The end date is smaller than the start date, pls check';
            ++$error;
        }
        if($error == 3 && sizeof($_POST) > 1){
            $sessionId = $session->setNewSession($_POST['sessionName'], 'class', $_POST['startDate'], $_POST['endDate']);
            if ($sessionId) {
                $details = $session->getTableIdDetails($sessionId);
                $outputArray[0] = 1;
                $outputArray[] = $details['id'];
                $outputArray[] = $details['session_name'];
                $outputArray[] = $session->getDisplayDate($details['start_date']);
                $outputArray[] = $session->getDisplayDate($details['end_date']);
                $outputArray[] = 1;
            }else{
                $outputArray[0] = 0;
            }
        }else{
            $outputArray[0] = 2;
            $outputArray[1] = $errorMessage;
            $outputArray[2] = $error;
        }
        echo json_encode($outputArray);
    } else if ($_POST['task'] == 'searchRecord') {
        $hint = htmlentities(trim($_POST['hint']));
        $search_type = htmlentities(trim($_POST['search_type']));
        $data = $session->getSessionIds($hint, $search_type, 'class');
        $i = 0;
        $outputArray[0][0] = 1;
        foreach ($data as $id) {
            $details = $session->getTableIdDetails($id);
            $outputArray[$i][0] = $details['id'];
            $outputArray[$i][] = $details['session_name'];
            $outputArray[$i][] = $session->getDisplayDate($details['start_date']);
            $outputArray[$i][] = $session->getDisplayDate($details['end_date']);

            if($session->getDateDifference($session->getCurrentDate(), $details['end_date']) >= 0)
                $outputArray[$i][] = 1;
            else
                $outputArray[$i][] = 0;

            $i++;
        }
        echo json_encode($outputArray);
    } else if ($_POST['task'] == 'getRecordIdDetails') {
        $id = htmlentities(trim($_POST['id']));

        $details = $session->getTableIdDetails($id);

        $outputArray[0] = $details['id'];
        $outputArray[] = $details['session_name'];
        $outputArray[] = $details['start_date'];
        $outputArray[] = $session->getDisplayDate($details['start_date']);
        $outputArray[] = $details['end_date'];
        $outputArray[] = $session->getDisplayDate($details['end_date']);

        $outputArray[] = $details['last_update_date'];
        $outputArray[] = $session->getOfficerName($details['last_updated_by']);
        $outputArray[] = $details['creation_date'];
        $outputArray[] = $session->getOfficerName($details['created_by']);
        if($session->getDateDifference($session->getCurrentDate(), $details['end_date']) >= 0)
            $outputArray[] = 'y';
        else
            $outputArray[] = 'n';
        echo json_encode($outputArray);
    } elseif ($_POST['task'] == 'updateRecord') {
        $sessionId = $_POST['valueId_u'];
        $details = $session->getTableIdDetails($sessionId);
        $error = 3;
        $errorMessage = '';
        foreach($_POST as $key => $value){
            if($_POST[$key] == ''){
                $outputArray[$error] = $key;
                $errorMessage = "Required form field lacks data, please fill up the form";
                ++$error;
            }
        }
        if($details['start_date'] != $_POST['startDate_u']){
            $startDateSession = $session->getSessionId4Date($_POST['startDate_u'], 'class');
            if($startDateSession != $sessionId && $startDateSession != ''){
                $outputArray[$error] = 'startDate_u';
                $errorMessage .= 'The session start date falls in another session already defined. please chose another date';
                ++$error;
            }
        }
        if($details['end_date'] != $_POST['endDate_u']){
            $endDateSession = $session->getSessionId4Date($_POST['endDate'], 'class');
            if($endDateSession != $sessionId && $endDateSession != ''){
                $outputArray[$error] = 'endDate';
                $errorMessage .= 'The session end date falls in another session already defined. please chose another date';
                ++$error;
            }
        }
        $difference = $session->getDateDifference($_POST['startDate_u'], $_POST['endDate_u']);
        if($difference < 0){
            $outputArray[$error] = 'endDate';
            $errorMessage .= 'The end date is smaller than the start date, pls check';
            ++$error;
        }
        if($error == 3 && sizeof($_POST) > 1){
            if($details['session_name'] != $_POST['sessionName_u']){
            	$session->setUpdateLog('Name From '.$details['session_name']. ' to '.$_POST['sessionName_u']);
            	$session->updateTableParameter('session_name', $_POST['sessionName_u']);
            }
            if($details['start_date'] != $_POST['startDate_u']){
            	$session->setUpdateLog('Start Date from '.$details['start_date']. ' to '. $_POST['startDate_u']);
            	$session->updateTableParameter('start_date', $_POST['startDate_u']);
            }
            if($details['end_date'] != $_POST['endDate_u']){
            	$session->setUpdateLog('End Date From '.$details['end_date']. ' to '.$_POST['endDate_u']);
            	$session->updateTableParameter('end_date', $_POST['endDate_u']);
            }
            
            $updateRecord = $session->commitSessionUpdate($sessionId);            
            if($updateRecord === $sessionId){
                $outputArray[0] = 3;
            }elseif ($updateRecord) {
                $details = $session->getTableIdDetails($sessionId);
                $outputArray[0] = 1;
                $outputArray[] = $details['id'];
                $outputArray[] = $details['session_name'];
                $outputArray[] = $session->getDisplayDate($details['start_date']);
                $outputArray[] = $session->getDisplayDate($details['end_date']);
                $outputArray[] = 1;
            }else{
                $outputArray[0] = 0;
            }
        }else{
            $outputArray[0] = 2;
            $outputArray[1] = $errorMessage;
            $outputArray[2] = $error;
        }        
        echo json_encode($outputArray);
    } elseif ($_POST['task'] == 'checkDateEntry') {
        $outputArray[0] = $session->getSessionId4Date($_POST['date'], 'class');
        echo json_encode($outputArray);
    } elseif ($_POST['task'] == 'getStartDate') {
        $endDate = $session->getSessionLatestEndDate('class');
        if ($endDate){
            $endDate = explode('-', $endDate);
            $outputArray[0] = date('Y-m-d', mktime(0, 0, 0, $endDate[1], $endDate[2] + 1, $endDate[0]));
        }
        else
            $outputArray[0] = date('Y-m-d');
        echo json_encode($outputArray);
    } elseif ($_POST['task'] == 'getCurrentSession') {
        $sessionId = $_SESSION['currentClassSessionId'];
        $details = $session->getTableIdDetails($sessionId);
        if($details['id'] != ''){        
        	$outputArray[0] = $sessionId;
        	$outputArray[] = $details['session_name'];
        }else{
        	$outputArray[0] = 0;
        }
        echo json_encode($outputArray);
    } elseif($_POST['task'] == "isSessionEditable"){
        $sessionId = $_POST['sessionId'];
        if($session->isSessionEditable($sessionId)){
            $outputArray[0] = 1;
        }else
            $outputArray[0] = 0;
        echo json_encode($outputArray);
    }else {
        $outputArray[0] = 0;
        echo json_encode($outputArray);
    }
}elseif(isset($_GET['q'])){
    if (isset ( $_GET ['session'] )) {
        $input = $_GET ['q'];
        $type = $_GET ['type'];

        $sessionIds = $session->getSessionIds('', 'all', $type);
        foreach ( $sessionIds as $sessionId ) {
            $details = $session->getTableIdDetails($sessionId);
            echo $details['session_name'] . "|" . $sessionId . "\n";
        }
    }
    echo "<p>
	<font color=\"#000000\">Your Input</font>
        </p>";
}
?>